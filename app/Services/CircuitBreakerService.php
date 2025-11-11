<?php

namespace App\Services;

use App\Interfaces\CircuitBreakerInterface;
use Illuminate\Support\Facades\Redis;

class CircuitBreakerService implements CircuitBreakerInterface
{
    protected int $threshold;
    protected int $window;
    protected int $openTtl;

    public function __construct()
    {
        // Use config values instead of env directly
        $this->threshold = config('services.circuitb.threshold');
        $this->window = config('services.circuitb.window');
        $this->openTtl = config('services.circuitb.open_ttl');
    }

    protected function failureKey(string $provider): string
    {
        return "cb:failures:{$provider}";
    }

    protected function openKey(string $provider): string
    {
        return "cb:open:{$provider}";
    }

    public function failure(string $provider): void
    {
        $key = $this->failureKey($provider);
        $count = Redis::incr($key);
        Redis::expire($key, $this->window);

        if ($count >= $this->threshold) {
            Redis::set($this->openKey($provider), 1, 'EX', $this->openTtl);
        }
    }

    public function success(string $provider): void
    {
        Redis::del($this->failureKey($provider));
        Redis::del($this->openKey($provider));
    }

    public function isOpen(string $provider): bool
    {
        return (bool) Redis::exists($this->openKey($provider));
    }
}
