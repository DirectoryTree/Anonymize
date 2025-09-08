<?php

use DirectoryTree\Anonymize\Facades\Anonymize;
use DirectoryTree\Anonymize\Tests\AnonymizedModel;

it('does not leak data via serialize', function () {
    Anonymize::enable();

    $model = new AnonymizedModel([
        'name' => 'Foo Bar',
    ]);

    expect(serialize($model))->not->toContain($model->name);
});

it('invalidates attribute cache when seed changes', function () {
    Anonymize::enable();

    $model = new AnonymizedModel([
        'id' => 1,
        'name' => 'Foo Bar',
    ]);

    $attributes1 = $model->attributesToArray();

    $model->id = 2;

    $attributes2 = $model->attributesToArray();

    expect($attributes1)->not->toBe($attributes2);
});

it('generates different attributes for models with distinct ids', function () {
    Anonymize::enable();

    $model1Attributes = (new AnonymizedModel([
        'id' => 1,
        'name' => 'Foo',
    ]))->attributesToArray();

    $model2Attributes = (new AnonymizedModel([
        'id' => 2,
        'name' => 'Bar',
    ]))->attributesToArray();

    expect($model1Attributes)->not->toBe($model2Attributes);
});

it('generates the same attributes for models with the same id', function () {
    Anonymize::enable();

    $model1Attributes = (new AnonymizedModel([
        'id' => 1,
        'name' => 'Foo',
    ]))->attributesToArray();

    $model2Attributes = (new AnonymizedModel([
        'id' => 1,
        'name' => 'Bar',
    ]))->attributesToArray();

    expect($model1Attributes)->toBe($model2Attributes);
});

it('overwrites only anonymized attributes', function () {
    Anonymize::enable();

    $attributes = (new AnonymizedModel([
        'favourite_color' => 'blue',
    ]))->attributesToArray();

    expect($attributes)->toHaveKey('favourite_color', 'blue');
});

it('anonymizes only attributes that exist on the model', function () {
    Anonymize::enable();

    $attributes = (new AnonymizedModel([
        'name' => 'Foo Bar',
    ]))->attributesToArray();

    expect($attributes)->toHaveCount(1)->toHaveKey('name');
});

it('anonymizes attributes array when anonymization is enabled', function () {
    Anonymize::enable();

    $attributes = (new AnonymizedModel([
        'name' => 'original-title',
        'address' => 'original-address',
    ]))->attributesToArray();

    expect($attributes['name'])->not->toBe('original-name')
        ->and($attributes['address'])->not->toBe('original-address');
});

it('anonymizes attributes when anonymization is enabled', function () {
    Anonymize::enable();

    $model = new AnonymizedModel([
        'name' => 'original-name',
        'address' => 'original-address',
    ]);

    expect($model->getAttributeValue('name'))->not->toBe('original-name')
        ->and($model->getAttributeValue('address'))->not->toBe('original-address');
});

it('does not anonymize attributes array when anonymization is disabled', function () {
    Anonymize::disable();

    $original = [
        'name' => 'Foo Bar',
        'address' => '1600 Pennsylvania Avenue',
    ];

    $attributes = (new AnonymizedModel($original))->attributesToArray();

    expect($attributes)->toBe($original);
});

it('does not anonymize attributes when anonymization is disabled', function () {
    Anonymize::disable();

    $model = new AnonymizedModel([
        'name' => 'Foo Bar',
        'address' => '1600 Pennsylvania Avenue',
    ]);

    expect($model->getAttributeValue('name'))->toBe('Foo Bar')
        ->and($model->getAttributeValue('address'))->toBe('1600 Pennsylvania Avenue');
});

it('includes id in seed by default', function () {
    $id = fake()->randomNumber();

    $seed = (new AnonymizedModel(['id' => $id]))->getAnonymizableSeed();

    expect($seed)->toContain($id);
});

it('flushes anonymization manager enablement', function (string $attribute, string $value) {
    $model = new AnonymizedModel([$attribute => $value]);

    expect($model->getAttributeValue($attribute))->toBe($value);

    Anonymize::enable();

    expect($model->getAttributeValue($attribute))->not->toBe($value);
})->with([
    ['name', 'Foo Bar'],
    ['email', 'foo.bar@example.com'],
    ['address', '1600 Pennsylvania Avenue'],
]);
