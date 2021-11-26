<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\SqlServerConnection;
use LogicException;
use Mpyw\UniqueViolationDetector\MySQLDetector;
use Mpyw\UniqueViolationDetector\PostgresDetector;
use Mpyw\UniqueViolationDetector\SQLiteDetector;
use Mpyw\UniqueViolationDetector\SQLServerDetector;
use Mpyw\UniqueViolationDetector\UniqueViolationDetector as DetectorInterface;

class UniqueViolationDetectorDiscoverer
{
    public function discover(ConnectionInterface $connection): DetectorInterface
    {
        if ($connection instanceof MySqlConnection) {
            return new MySQLDetector();
        }
        if ($connection instanceof PostgresConnection) {
            return new PostgresDetector();
        }
        if ($connection instanceof SQLiteConnection) {
            return new SQLiteDetector();
        }
        if ($connection instanceof SqlServerConnection) {
            return new SQLServerDetector();
        }

        // @codeCoverageIgnoreStart
        throw new LogicException('Unsupported Driver');
        // @codeCoverageIgnoreEnd
    }
}
