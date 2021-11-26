<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector\Contracts;

use Illuminate\Database\ConnectionInterface;
use PDOException;

interface UniqueViolationDetector
{
    public function violated(PDOException $e): bool;

    /**
     * @return static
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function forConnection(ConnectionInterface $connection);
}
