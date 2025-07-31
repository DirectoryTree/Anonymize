<?php

namespace DirectoryTree\Anonymize;

use DirectoryTree\Anonymize\Facades\Anonymize;
use Faker\Generator;

trait Anonymized
{
    /**
     * Get an attribute from the model.
     */
    public function getAttribute($key): mixed
    {
        if (! Anonymize::isEnabled()) {
            return parent::getAttribute($key);
        }

        return $this->getAnonymizedAttribute($key, fn () => parent::getAttribute($key));
    }

    /**
     * Get the seed for the anonymizable model.
     */
    public function getAnonymizableSeed(): string
    {
        return get_class($this).':'.$this->getAttributeFromArray('id');
    }

    /**
     * Get the anonymized attribute.
     */
    public function getAnonymizedAttribute(string $key, mixed $default): mixed
    {
        $faker = Anonymize::faker($this->getAnonymizableSeed());

        $attributes = $this->getAnonymizedAttributes($faker);

        if (! array_key_exists($key, $attributes)) {
            return value($default);
        }

        return $attributes[$key];
    }

    /**
     * Get the anonymized attributes.
     */
    abstract public function getAnonymizedAttributes(Generator $faker): array;
}
