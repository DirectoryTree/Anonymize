<?php

namespace DirectoryTree\Anonymize\Tests;

use DirectoryTree\Anonymize\Anonymizable;
use DirectoryTree\Anonymize\AnonymizedResource;
use Faker\Generator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NestedAnonymizedResource extends JsonResource implements Anonymizable
{
    use AnonymizedResource;

    public function toArray(Request $request): array
    {
        return $this->toAnonymized($this->resource);
    }

    public function getAnonymizedAttributes(Generator $faker): array
    {
        return [
            'name' => $faker->name(),
        ];
    }

    public function getAnonymizableKey(): string
    {
        return 1;
    }
}
