<?php

use DirectoryTree\Anonymize\Facades\Anonymize;
use DirectoryTree\Anonymize\Tests\AnonymizedJsonResource;
use DirectoryTree\Anonymize\Tests\NestedAnonymizedResource;

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

it('anonymizes nested arrays and resources', function () {
    Anonymize::enable();

    $resource = new AnonymizedJsonResource([
        'name' => 'Foo Bar',
        'address' => '1600 Pennsylvania Avenue',
        'role' => [
            'name' => 'Admin',
            'permissions' => [
                ['name' => 'View Users'],
                ['name' => 'View Roles'],
            ],
            'created_at' => '2023-01-01 00:00:00',
        ],
        'nested' => new NestedAnonymizedResource([
            'name' => 'Foo Bar',
        ]),
    ]);

    $attributes = $resource->resolve();

    expect($attributes['role'])->toHaveKey('created_at');
    expect($attributes['role']['name'])->not->toBe('Admin');

    expect($attributes['role']['permissions'])->toHaveCount(1);
    expect($attributes['role']['permissions'][0])->toHaveKey('name');
    expect($attributes['role']['permissions'][0]['name'])->not->toBe('View Users');

    expect($attributes['nested'])->toHaveKey('name');
    expect($attributes['nested']['name'])->not->toBe('Foo Bar');
});
