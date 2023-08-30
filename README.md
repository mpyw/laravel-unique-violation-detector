# Laravel Unique Violation Detector [![Build Status](https://github.com/mpyw/laravel-unique-violation-detector/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/mpyw/laravel-unique-violation-detector/actions) [![Coverage Status](https://coveralls.io/repos/github/mpyw/laravel-unique-violation-detector/badge.svg?branch=master)](https://coveralls.io/github/mpyw/laravel-unique-violation-detector?branch=master)

**ABANDONED: The functionalities of this library have been integrated into the Laravel core due to changes in Laravel [v10.20.0](https://github.com/laravel/framework/releases/tag/v10.20.0). <ins>From now on, the `Illuminate\Database\UniqueConstraintViolationException` exception class will be thrown when there is a unique constraint violation, so there is no need to use this library for judgment</ins>. Simply checking with `catch` or `instanceof` should be sufficient. Although the internal judgment logic is strictly different in some parts, it should be replaceable without any problems in most cases.**

Detect **primary/unique key or constraint violation** errors from `PDOException`.

## Requirements

| Package                                                                             | Version                              |
|:------------------------------------------------------------------------------------|:-------------------------------------|
| PHP                                                                                 | <code>^8.0</code>                    |
| Laravel                                                                             | <code>^9.0 &#124;&#124; ^10.0</code> |
| [mpyw/unique-violation-detector](https://github.com/mpyw/unique-violation-detector) | <code>^1.0</code>                    |

## Supported Connections

| Database | Connection Class |
|:---|:---|
| MySQL | `Illuminate\Database\MySqlConnection` |
| PostgreSQL | `Illuminate\Database\PostgresConnection` |
| SQLite | `Illuminate\Database\SQLiteConnection` |
| SQLServer | `Illuminate\Database\SqlServerConnection` |

You can also add custom resolvers by one of the following:

- `Mpyw\LaravelUniqueViolationDetector\Facades\Unique::resolverFor()`
- `Mpyw\LaravelUniqueViolationDetector\DetectorDiscoverer::resolverFor()`

## Installing

```
composer require mpyw/laravel-unique-violation-detector
```

## Usage

You can detect unique violations in various ways.

```php
use Mpyw\LaravelUniqueViolationDetector\UniqueViolationDetector;
use Mpyw\LaravelUniqueViolationDetector\Facades\Unique;

// Detect on a specific connection via explicitly created detector instance
// (Recommended usage in libraries)
$violated = (new UniqueViolationDetector($connection))->violated($e);

// Detect on the default connection
$violated = Unique::violated($exception);

// Detect on a specific connection via Facade
$violated = Unique::forConnection($connection)->violated($e);
```
