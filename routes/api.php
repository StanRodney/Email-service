<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\v1\NotificationStatusController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| All routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group.
|
*/

Route::get('/health', [HealthController::class, 'index']);