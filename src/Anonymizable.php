<?php

namespace DirectoryTree\Anonymize;

use Faker\Generator;

interface Anonymizable
{
    /**
     * Get the seed to use for faker.
     */
    public function getAnonymizableSeed(): string;

    /**
     * Get the anonymized attributes.
     */
    public function getAnonymizedAttributes(Generator $faker): array;
}
