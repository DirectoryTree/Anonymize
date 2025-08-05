<?php

namespace DirectoryTree\Anonymize\Benchmarks;

use DirectoryTree\Anonymize\AnonymizeServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Orchestra\Testbench\Concerns\CreatesApplication;

abstract class Benchmark
{
    use CreatesApplication;

    protected Application $app;

    public function __construct()
    {
        $this->app = $this->createApplication();

        $this->app->register(AnonymizeServiceProvider::class);
    }
}
