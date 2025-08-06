<?php

namespace DirectoryTree\Anonymize\Tests;

use DirectoryTree\Anonymize\Anonymizable;
use DirectoryTree\Anonymize\Anonymized;
use Faker\Generator;

class AnonymizedModel extends BaseModel implements Anonymizable
{
    use Anonymized;

    public function getAnonymizedAttributes(Generator $faker): array
    {
        return [
            'name' => $faker->name(),
            'address' => $faker->address(),
        ];
    }
}
