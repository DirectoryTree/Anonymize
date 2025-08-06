<?php

namespace DirectoryTree\Anonymize\Tests;

use DirectoryTree\Anonymize\Anonymizable;
use DirectoryTree\Anonymize\Anonymized;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;

class AnonymizedModel extends Model implements Anonymizable
{
    use Anonymized;

    protected $guarded = false;

    public function getAnonymizedAttributes(Generator $faker): array
    {
        return [
            'name' => $faker->name(),
            'address' => $faker->address(),
        ];
    }
}
