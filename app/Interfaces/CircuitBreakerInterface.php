<?php

declare(strict_types=1);

namespace App\Interfaces;


interface CircuitBreakerInterface
{
    public function failure(string $provider): void;

    public function success(string $provider): void;

    public function isOpen(string $provider): bool;

}