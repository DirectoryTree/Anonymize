<?php

namespace DirectoryTree\Anonymize;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AnonymizeServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AnonymizeManager::class, static fn (Application $app): AnonymizeManager => (
            new AnonymizeManager($app->bound(Generator::class) ? $app->make(Generator::class) : Factory::create())
        ));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [AnonymizeManager::class];
    }
}
