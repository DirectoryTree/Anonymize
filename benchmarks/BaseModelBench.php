<?php

namespace DirectoryTree\Anonymize\Benchmarks;

use DirectoryTree\Anonymize\Tests\BaseModel;
use Illuminate\Database\Eloquent\Model;

class BaseModelBench extends ModelBench
{
    protected function newModel(array $attributes): Model
    {
        return new BaseModel($attributes);
    }
}
