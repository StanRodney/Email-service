<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericTemplateMail;
use App\Services\CircuitBreakerService;
use App\Services\TemplateClientService;
use Illuminate\Support\Facades\Redis;

class EmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public function __construct(
        public readonly array $payload,
        public readonly TemplateClientService $templateClient,
        public readonly CircuitBreakerService $cb
    ){}

    public function handle()
    {
        $reqId = $this->payload['request_id'];
        if (Cache::has("notification:processed:{$reqId}")) return; // idempotency

        $user = $this->fetchUser($this->payload['user_id']);
        if (!$user || empty($user['email'])) return $this->notifyStatus('failed', 'invalid_user');

        if (($user['preference']['email'] ?? true) === false) {
            Cache::put("notification:processed:{$reqId}", true, 86400);
            return $this->notifyStatus('skipped', 'user_opt_out');
        }

        $template = $this->templateClient->get($this->payload['template_code']);
        $body = $this->render($template['body'], $this->payload['variables'] ?? []);
        $subject = $this->render($template['subject'], $this->payload['variables'] ?? []);

        try {
            Mail::to($user['email'])->send(new GenericTemplateMail($subject, $body));
            Cache::put("notification:processed:{$reqId}", true, 86400);
            $this->notifyStatus('delivered');
        } catch (\Exception $e) {
            Log::error('Mail send failed', ['err' => $e->getMessage()]);
            $this->notifyStatus('failed', 'send_error');
        }
    }

    private function fetchUser(string $userId)
    {
        return Cache::remember("user:{$userId}", 300, function() use ($userId) {
            $resp = Http::withHeaders(['X-Service-Token' => config('services.user_service.token')])
                ->get(config('services.user_service.url')."/api/v1/users/{$userId}");
            return $resp->ok() ? $resp->json()['user'] : null;
        });
    }

    private function notifyStatus(string $status, ?string $error = null)
    {
        try {
            Http::withHeaders(['X-Service-Token' => config('services.api_gateway.token')])
                ->post(config('services.api_gateway.url').'/api/v1/notifications/status', [
                    'request_id' => $this->payload['request_id'],
                    'status' => $status,
                    'error' => $error
                ]);
        } catch (\Exception $e) {
            Log::warning('Failed to forward status', ['err' => $e->getMessage()]);
        }
    }

    private function render(string $template, array $vars): string
    {
        foreach ($vars as $k => $v) $template = str_replace("{{{$k}}}", (string)$v, $template);
        return $template;
    }
}
