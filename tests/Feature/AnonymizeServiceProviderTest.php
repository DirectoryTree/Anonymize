<?php

use DirectoryTree\Anonymize\AnonymizeManager;
use Faker\Factory;
use Faker\Generator;

it('creates faker generator when not bound', function () {
    unset($this->app[Generator::class]);

    $manager = $this->app->make(AnonymizeManager::class);

    expect($this->app->bound(Generator::class))->toBeFalse()
        ->and($manager->faker())->toBeInstanceOf(Generator::class);
});

it('injects faker generator when bound', function () {
    $generator = $this->app->instance(Generator::class, Factory::create());

    $manager = $this->app->make(AnonymizeManager::class);

    expect($manager->faker())->toBe($generator);
});
