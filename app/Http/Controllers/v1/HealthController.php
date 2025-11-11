<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\JsonResponse;

class HealthController
{
    /**
     * Health check endpoint
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $ok = true;

        // Check database
        try {
            DB::connection()->getPdo();
            $db = 'ok';
        } catch (\Exception $e) {
            $db = 'fail';
            $ok = false;
        }

        // Check Redis
        try {
            Redis::ping();
            $redis = 'ok';
        } catch (\Exception $e) {
            $redis = 'fail';
            $ok = false;
        }

        return response()->json([
            'status' => $ok ? 'ok' : 'degraded',
            'checks' => [
                'database' => $db,
                'redis' => $redis,
            ],
        ]);
    }
}
