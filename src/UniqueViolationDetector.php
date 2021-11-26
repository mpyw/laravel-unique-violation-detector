<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector;

use Illuminate\Database\ConnectionInterface;
use Mpyw\UniqueViolationDetector\UniqueViolationDetector as DetectorInterface;
use PDOException;

class UniqueViolationDetector implements Contracts\UniqueViolationDetector
{
    /**
     * @var DetectorInterface
     */
    private $detector;

    public function __construct(ConnectionInterface $connection)
    {
        $this->detector = (new DetectorDiscoverer())->discover($connection);
    }

    public function violated(PDOException $e): bool
    {
        return $this->detector->uniqueConstraintViolated($e);
    }

    /**
     * @return static
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function forConnection(ConnectionInterface $connection)
    {
        return new static($connection);
    }
}
