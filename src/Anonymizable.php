<?php

namespace DirectoryTree\Anonymize;

use Faker\Generator;

interface Anonymizable
{
    /**
     * Get the key value used to ensure consistent fake data generation.
     */
    public function getAnonymizableKey(): ?string;

    /**
     * Get the seed value used to ensure consistent fake data generation.
     */
    public function getAnonymizableSeed(): string;

    /**
     * Get the anonymized attributes.
     */
    public function getAnonymizedAttributes(Generator $faker): array;
}
