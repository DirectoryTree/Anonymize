<?php

namespace DirectoryTree\Anonymize;

use Faker\Generator;

interface Anonymizable
{
    public function getAnonymizableSeed(): string;

    public function getAnonymizedAttributes(Generator $faker): array;
}
