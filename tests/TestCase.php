<?php

namespace DirectoryTree\Anonymize\Tests;

use DirectoryTree\Anonymize\AnonymizeServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function getPackageProviders($app): array
    {
        return [
            AnonymizeServiceProvider::class,
        ];
    }
}
