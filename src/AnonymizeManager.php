<?php

namespace DirectoryTree\Anonymize;

use Faker\Generator;

class AnonymizeManager
{
    /**
     * Whether anonymization is enabled.
     */
    protected bool $enabled = false;

    /**
     * Constructor.
     */
    public function __construct(
        protected readonly Generator $faker
    ) {}

    /**
     * Create a new Faker instance with the given seed.
     */
    public function faker(string|int|null $seed = null): Generator
    {
        $this->faker->seed(is_string($seed) ? crc32($seed) : $seed);

        return $this->faker;
    }

    /**
     * Enable anonymization.
     */
    public function enable(): void
    {
        $this->enabled = true;
    }

    /**
     * Disable anonymization.
     */
    public function disable(): void
    {
        $this->enabled = false;
    }

    /**
     * Determine if anonymization is enabled.
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
