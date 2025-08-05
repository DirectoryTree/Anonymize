<?php

use DirectoryTree\Anonymize\AnonymizeManager;
use Faker\Factory;
use Faker\Generator;

it('seeds and returns faker instance', function () {
    $seed = fake()->randomNumber();

    $faker = mock(Generator::class);
    $faker->shouldReceive('seed')
        ->once()
        ->with($seed);

    $result = (new AnonymizeManager($faker))->faker($seed);

    expect($result)->toBe($faker);
});

it('can be enabled', function () {
    $manager = new AnonymizeManager(Factory::create());

    $manager->enable();

    expect($manager->isEnabled())->toBeTrue();
});

it('can be disabled', function () {
    $manager = new AnonymizeManager(Factory::create());

    $manager->disable();

    expect($manager->isEnabled())->toBeFalse();
});
