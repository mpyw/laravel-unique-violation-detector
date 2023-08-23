<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\UniqueConstraintViolationException;
use Mpyw\UniqueViolationDetector\UniqueViolationDetector as DetectorInterface;
use PDOException;

class UniqueViolationDetector implements Contracts\UniqueViolationDetector
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var null|DetectorInterface
     */
    protected $detector = null;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function violated(PDOException $e): bool
    {
        if ($e instanceof UniqueConstraintViolationException) {
            return true;
        }

        return (
            $this->detector
            ?: ($this->detector = (new DetectorDiscoverer())->discover($this->connection))
        )->uniqueConstraintViolated($e);
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
