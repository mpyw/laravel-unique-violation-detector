<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector\Tests\Models;

use Illuminate\Database\SqlServerConnection;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string[]
     */
    protected $guarded = [];

    public function getDateFormat(): string
    {
        // https://github.com/laravel/nova-issues/issues/1796
        if ($this->getConnection() instanceof SqlServerConnection) {
            return 'Y-m-d H:i:s';
        }

        return parent::getDateFormat();
    }
}
