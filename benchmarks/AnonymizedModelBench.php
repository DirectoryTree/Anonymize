<?php

namespace DirectoryTree\Anonymize\Benchmarks;

use DirectoryTree\Anonymize\Facades\Anonymize;
use DirectoryTree\Anonymize\Tests\AnonymizedModel;
use Illuminate\Database\Eloquent\Model;

class AnonymizedModelBench extends ModelBench
{
    public function __construct()
    {
        parent::__construct();

        Anonymize::enable();
    }

    protected function newModel(array $attributes): Model
    {
        return new AnonymizedModel($attributes);
    }
}
