<?php

namespace DirectoryTree\Anonymize;

use Faker\Factory;
use Faker\Generator;

class AnonymizeManager
{
    /**
     * The Faker instance.
     */
    protected Generator $faker;

    /**
     * Whether anonymization is enabled.
     */
    protected bool $enabled = false;

    /**
     * Create a new Faker instance with the given seed.
     */
    public function faker(string|int|null $seed = null): Generator
    {
        $this->faker ??= Factory::create();

        $this->faker->seed($seed);

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
