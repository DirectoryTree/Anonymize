<?php

namespace DirectoryTree\Anonymize\Tests;

use DirectoryTree\Anonymize\Anonymizable;
use DirectoryTree\Anonymize\AnonymizedResource;
use Faker\Generator;
use Illuminate\Http\Resources\Json\JsonResource;

class AnonymizedJsonResource extends JsonResource implements Anonymizable
{
    use AnonymizedResource;

    public function getAnonymizedAttributes(Generator $faker): array
    {
        return [
            'name' => $faker->name(),
            'address' => $faker->address(),
        ];
    }

    public function getAnonymizableSeed(): string
    {
        return 1;
    }
}
