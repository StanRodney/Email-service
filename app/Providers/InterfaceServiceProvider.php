<?php

namespace App\Providers;

use App\Interfaces\CircuitBreakerInterface;
use App\Interfaces\TemplateClientInterface;
use App\Services\CircuitBreakerService;
use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{

    /**
     * Binds interfaces to their implementations.
     * @var array<string, string>
     */
      public $bindings = [
        TemplateClientInterface::class => TemplateClientInterface::class,
        CircuitBreakerInterface::class => CircuitBreakerService::class
    ];

    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
