<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector\Facades;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Facade;
use Mpyw\LaravelUniqueViolationDetector\Contracts;
use Mpyw\LaravelUniqueViolationDetector\DetectorDiscoverer;
use Mpyw\UniqueViolationDetector\UniqueViolationDetector as DetectorInterface;

/**
 * @method static bool violated(\PDOException $e)
 * @method static \Mpyw\LaravelUniqueViolationDetector\Contracts\UniqueViolationDetector forConnection(\Illuminate\Database\ConnectionInterface $connection)
 */
class Unique extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return Contracts\UniqueViolationDetector::class;
    }

    /**
     * @param string|callable $factory
     * @phpstan-param class-string<ConnectionInterface> $connectionClassName
     * @phpstan-param class-string<DetectorInterface>|callable(): DetectorInterface $factory
     * @psalm-param class-string<ConnectionInterface> $connectionClassName
     * @psalm-param class-string<DetectorInterface>|callable(): DetectorInterface $factory
     */
    public static function resolverFor(string $connectionClassName, $factory): void
    {
        DetectorDiscoverer::resolverFor($connectionClassName, $factory);
    }
}
