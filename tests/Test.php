<?php

declare(strict_types=1);

namespace Mpyw\LaravelUniqueViolationDetector\Tests;

use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mpyw\LaravelUniqueViolationDetector\DetectorDiscoverer;
use Mpyw\LaravelUniqueViolationDetector\Facades\Unique;
use Mpyw\LaravelUniqueViolationDetector\Tests\Models\Post;
use Mpyw\LaravelUniqueViolationDetector\Tests\Models\User;
use Mpyw\LaravelUniqueViolationDetector\UniqueViolationDetector;
use Mpyw\LaravelUniqueViolationDetector\UniqueViolationDetectorServiceProvider;
use Mpyw\UniqueViolationDetector\MySQLDetector;
use Mpyw\UniqueViolationDetector\SQLiteDetector;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PDO;

class Test extends BaseTestCase
{
    /**
     * @var string[]
     */
    protected $queries = [];

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [UniqueViolationDetectorServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.connections' => require __DIR__ . '/config/database.php']);
        config(['database.default' => getenv('DB') ?: 'sqlite']);

        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=true');
        }

        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('email')->unique();
            $table->enum('type', ['consumer', 'provider']);
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        $user = new User();
        $user->fill(['id' => 1, 'email' => 'example@example.com', 'type' => 'consumer'])->save();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function testDuplicatePrimaryKeyViolated(): void
    {
        try {
            $user = new User();
            $user->fill(['id' => 1, 'email' => 'example-another@example.com', 'type' => 'consumer'])->save();
            $this->fail();
        } catch (QueryException $e) {
            var_dump($e->errorInfo);
            $this->assertTrue(Unique::violated($e));
            $this->assertTrue(Unique::forConnection(DB::connection())->violated($e));
            $this->assertTrue((new UniqueViolationDetector(DB::connection()))->violated($e));
        }
    }

    public function testDuplicateUniqueKeyViolated(): void
    {
        try {
            $user = new User();
            $user->fill(['id' => 2, 'email' => 'example@example.com', 'type' => 'consumer'])->save();
            $this->fail();
        } catch (QueryException $e) {
            var_dump($e->errorInfo);
            $this->assertTrue(Unique::violated($e));
            $this->assertTrue(Unique::forConnection(DB::connection())->violated($e));
            $this->assertTrue((new UniqueViolationDetector(DB::connection()))->violated($e));
        }
    }

    public function testForeignKeyConstraintNotViolated(): void
    {
        try {
            $post = new Post();
            $post->fill(['user_id' => 9999])->save();
            $this->fail();
        } catch (QueryException $e) {
            var_dump($e->errorInfo);
            $this->assertFalse(Unique::violated($e));
            $this->assertFalse(Unique::forConnection(DB::connection())->violated($e));
            $this->assertFalse((new UniqueViolationDetector(DB::connection()))->violated($e));
        }
    }

    public function testEnumConstraintNotViolated(): void
    {
        try {
            $user = new User();
            $user->fill(['id' => 2, 'email' => 'example-another@example.com', 'type' => 'foo'])->save();
            $this->fail();
        } catch (QueryException $e) {
            var_dump($e->errorInfo);
            $this->assertFalse(Unique::violated($e));
            $this->assertFalse(Unique::forConnection(DB::connection())->violated($e));
            $this->assertFalse((new UniqueViolationDetector(DB::connection()))->violated($e));
        }
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCustomClosureResolver(): void
    {
        $originalDetector = new class() extends SQLiteDetector {
        };

        Unique::resolverFor(SQLiteConnection::class, function () use ($originalDetector) {
            return $originalDetector;
        });

        $discoveredDetector = (new DetectorDiscoverer())
            ->discover(new SQLiteConnection(new PDO('sqlite::memory:')));

        $this->assertTrue($discoveredDetector instanceof $originalDetector);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCustomStringResolver(): void
    {
        // Just for testing
        Unique::resolverFor(SQLiteConnection::class, MySQLDetector::class);

        $discoveredDetector = (new DetectorDiscoverer())
            ->discover(new SQLiteConnection(new PDO('sqlite::memory:')));

        $this->assertInstanceOf(MySQLDetector::class, $discoveredDetector);
    }
}
