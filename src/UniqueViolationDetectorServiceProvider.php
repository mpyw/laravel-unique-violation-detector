<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector;

use Carbon\Laravel\ServiceProvider;
use Mpyw\LaravelUniqueViolationDetector\Contracts;

class UniqueViolationDetectorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(Contracts\UniqueViolationDetector::class, UniqueViolationDetector::class);
    }
}
