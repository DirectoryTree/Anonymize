<?php

use DirectoryTree\Anonymize\AnonymizeManager;
use DirectoryTree\Anonymize\AnonymizeServiceProvider;
use Illuminate\Contracts\Foundation\Application;

it('registers the anonymize manager service as a singleton', function () {
    $app = mock(Application::class);
    $app->shouldReceive('singleton')
        ->once()
        ->with(AnonymizeManager::class, Mockery::type(Closure::class));

    (new AnonymizeServiceProvider($app))->register();
});

it('provides the anonymize manager service', function () {
    expect((new AnonymizeServiceProvider(mock(Application::class)))->provides())->toContain(AnonymizeManager::class);
});
