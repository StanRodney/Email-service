<?php

declare(strict_types=1);

namespace App\Interfaces;


interface TemplateClientInterface
{
    public function get(string $code, string $lang = 'en'): array;
}