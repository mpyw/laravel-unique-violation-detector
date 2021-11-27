<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector;

use Carbon\Laravel\ServiceProvider;
use Mpyw\LaravelUniqueViolationDetector\Contracts;

class UniqueViolationDetectorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UniqueViolationDetector::class);
        $this->app->alias(UniqueViolationDetector::class, Contracts\UniqueViolationDetector::class);
    }
}
