<?php

namespace DirectoryTree\Anonymize\Benchmarks;

use DirectoryTree\Anonymize\Tests\Benchmark;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;

abstract class ModelBench extends Benchmark
{
    protected Model $model;
    protected Collection $collection;

    #[Revs(100000), Iterations(10), BeforeMethods('setUpModel')]
    public function benchAttributesToArray(): void
    {
        (clone $this->model)->attributesToArray();
    }

    #[Revs(1), Iterations(100), BeforeMethods('setUpCollection')]
    public function benchCollectionToArray(): void
    {
        $this->collection->toArray();
    }

    public function setUpModel(): void
    {
        $this->model = $this->newModel([
            'name' => 'Foo Bar',
            'address' => '1600 Pennsylvania Avenue',
            'favourite_color' => 'blue'
        ]);
    }

    public function setUpCollection(): void
    {
        $this->collection = Collection::make(array_fill(0, 1000, $this->newModel([
            'name' => 'Foo Bar',
            'address' => '1600 Pennsylvania Avenue',
            'favourite_color' => 'blue'
        ])))->map(fn (Model $model, int $i) => (clone $model)->fill(['id' => $i]));
    }

    abstract protected function newModel(array $attributes): Model;
}