<?php

use DirectoryTree\Anonymize\Facades\Anonymize;
use DirectoryTree\Anonymize\Tests\AnonymizedJsonResource;

it('anonymizes json resource when anonymization is enabled', function () {
    Anonymize::enable();

    $resource = new AnonymizedJsonResource([
        'name' => 'Foo Bar',
        'address' => '1600 Pennsylvania Avenue',
    ]);

    expect($resource->resolve())->not->toHaveKey('name', 'Foo Bar')
        ->and($resource->resolve())->not->toHaveKey('address', '1600 Pennsylvania Avenue');
});

it('does not anonymize json resource when anonymization is disabled', function () {
    Anonymize::disable();

    $resource = new AnonymizedJsonResource([
        'name' => 'Foo Bar',
        'address' => '1600 Pennsylvania Avenue',
    ]);

    expect($resource->resolve())->toHaveKey('name', 'Foo Bar')
        ->and($resource->resolve())->toHaveKey('address', '1600 Pennsylvania Avenue');
});
