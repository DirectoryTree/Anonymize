<?php

use DirectoryTree\Anonymize\AnonymizeManager;
use DirectoryTree\Anonymize\AnonymizeServiceProvider;
use Faker\Generator;
use Illuminate\Contracts\Container\ContextualBindingBuilder;

it('provides a faker generator to the anonymize manager', function () {
    $builder = mock(ContextualBindingBuilder::class);
    $builder->shouldReceive('needs')
        ->once()
        ->with(Generator::class)
        ->andReturnSelf();
    $builder->shouldReceive('give')->once();

    $app = mock($this->app);
    $app->shouldReceive('when')
        ->once()
        ->with(AnonymizeManager::class)
        ->andReturn($builder);

    (new AnonymizeServiceProvider($app))->register();
});

it('registers the anonymize manager service as a singleton', function () {
    $app = mock($this->app);
    $app->shouldReceive('singleton')
        ->once()
        ->with(AnonymizeManager::class);

    (new AnonymizeServiceProvider($app))->register();
});

it('provides the anonymize manager service', function () {
    expect((new AnonymizeServiceProvider($this->app))->provides())->toContain(AnonymizeManager::class);
});
