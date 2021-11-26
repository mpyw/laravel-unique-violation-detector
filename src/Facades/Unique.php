<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector\Facades;

use Illuminate\Support\Facades\Facade;
use Mpyw\LaravelUniqueViolationDetector\Contracts;

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
}
