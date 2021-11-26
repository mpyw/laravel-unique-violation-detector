# Laravel Unique Violation Detector [![Build Status](https://github.com/mpyw/laravel-unique-violation-detector/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/mpyw/laravel-unique-violation-detector/actions) [![Coverage Status](https://coveralls.io/repos/github/mpyw/laravel-unique-violation-detector/badge.svg?branch=master)](https://coveralls.io/github/mpyw/laravel-unique-violation-detector?branch=master)

Detect **primary/unique key or constraint violation** errors from `PDOException`.

## Requirements

| Package | Version |
|:---|:---|
| PHP | <code>^7.3 &#124;&#124; ^8.0</code> |
| Laravel | <code>^6.0 &#124;&#124; ^7.0 &#124;&#124; ^8.0 &#124;&#124; ^9.0</code> |
| [mpyw/unique-violation-detector](https://github.com/mpyw/unique-violation-detector) | <code>^1.0</code> |

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
