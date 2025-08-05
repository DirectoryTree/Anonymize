<?php

use DirectoryTree\Anonymize\AnonymizeManager;
use DirectoryTree\Anonymize\Tests\AnonymizedModel;
use Faker\Factory;

it('does not leak data via serialize', function () {
    setManager()->enable();

    $model = new AnonymizedModel([
        'name' => 'Foo Bar',
    ]);

    expect(serialize($model))->not->toContain($model->name);
});

it('invalidates attribute cache when seed changes', function () {
    setManager()->enable();

    $model = new AnonymizedModel([
        'id' => 1,
        'name' => 'Foo Bar',
    ]);

    $attributes1 = $model->attributesToArray();

    $model->id = 2;

    $attributes2 = $model->attributesToArray();

    expect($attributes1)->not->toBe($attributes2);
});

it('returns unique attributes for models with distinct ids', function () {
    setManager()->enable();

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

it('returns same attributes for models with same id', function () {
    setManager()->enable();

    $model1Original = [
        'id' => 1,
        'name' => 'Foo',
    ];
    $model1Attributes = (new AnonymizedModel($model1Original))->attributesToArray();

    $model2Original = [
        'id' => 1,
        'name' => 'Bar',
    ];
    $model2Attributes = (new AnonymizedModel($model2Original))->attributesToArray();

    expect($model1Attributes)->toBe($model2Attributes);
});

it('only overwrites anonymized attributes', function () {
    setManager()->enable();

    $attributes = (new AnonymizedModel([
        'favourite_color' => 'blue',
    ]))->attributesToArray();

    expect($attributes)->toHaveKey('favourite_color', 'blue');
});

it('only anonymizes existing attributes', function () {
    setManager()->enable();

    $attributes = (new AnonymizedModel([
        'name' => 'Foo Bar',
    ]))->attributesToArray();

    expect($attributes)->toHaveCount(1)->toHaveKey('name');
});

it('attribute array is anonymized when anonymization is enabled', function () {
    setManager()->enable();

    $attributes = (new AnonymizedModel([
        'name' => 'original-title',
        'address' => 'original-address',
    ]))->attributesToArray();

    expect($attributes['name'])->not->toBe('original-name')
        ->and($attributes['address'])->not->toBe('original-address');
});

it('attributes are anonymized when anonymization is enabled', function () {
    setManager()->enable();

    $model = new AnonymizedModel([
        'name' => 'original-name',
        'address' => 'original-address',
    ]);

    expect($model->getAttributeValue('name'))->not->toBe('original-name')
        ->and($model->getAttributeValue('address'))->not->toBe('original-address');
});

test('attribute array is not anonymized when anonymization is disabled', function () {
    setManager()->disable();

    $original = [
        'name' => 'Foo Bar',
        'address' => '1600 Pennsylvania Avenue',
    ];

    $attributes = (new AnonymizedModel($original))->attributesToArray();

    expect($attributes)->toBe($original);
});

it('attributes are not anonymized when anonymization is disabled', function () {
    setManager()->disable();

    $model = new AnonymizedModel([
        'name' => 'Foo Bar',
        'address' => '1600 Pennsylvania Avenue',
    ]);

    expect($model->getAttributeValue('name'))->toBe('Foo Bar')
        ->and($model->getAttributeValue('address'))->toBe('1600 Pennsylvania Avenue');
});

it('withoutAnonymization prevents attributes from being anonymized when anonymization is enabled', function () {
    setManager()->enable();

    $original = [
        'name' => 'Foo Bar',
        'address' => '1600 Pennsylvania Avenue',
    ];

    $attributes = (new AnonymizedModel($original))
        ->withoutAnonymization(fn ($model) => $model->attributesToArray());

    expect($attributes)->toBe($original);
});

it('includes id in seed by default', function () {
    $id = fake()->randomNumber();

    $seed = (new AnonymizedModel(['id' => $id]))->getAnonymizableSeed();

    expect($seed)->toContain($id);
});

function setManager(): AnonymizeManager
{
    AnonymizedModel::setManager($manager = new AnonymizeManager(Factory::create()));

    return $manager;
}
