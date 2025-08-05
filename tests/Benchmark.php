<?php

namespace DirectoryTree\Anonymize\Tests;

use DirectoryTree\Anonymize\AnonymizeServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Orchestra\Testbench\Concerns\CreatesApplication;
use PhpBench\Attributes\BeforeClassMethods;

#[BeforeClassMethods('setUpBeforeClass')]
abstract class Benchmark
{
    use CreatesApplication;

    protected Application $app;

    public function __construct()
    {
        ini_set('memory_limit', '1G');

        $this->app = $this->createApplication();

        $this->app->register(AnonymizeServiceProvider::class);

        $this->setUp();
    }

    public static function setUpBeforeClass(): void
    {
        static::setUpBeforeClassUsingWorkbench();
    }

    /**
     * Set up the benchmark.
     */
    protected function setUp(): void {}
}
