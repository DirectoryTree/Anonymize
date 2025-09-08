<?php

namespace DirectoryTree\Anonymize;

use Faker\Generator;

/**
 * @mixin Anonymizable
 */
trait Anonymized
{
    use AnonymizesAttributes;

    /**
     * Get the anonymized attributes.
     */
    abstract public function getAnonymizedAttributes(Generator $faker): array;

    /**
     * Get all of the current attributes on the model.
     *
     * @return array<string, mixed>
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();

        if (static::getAnonymizeManager()->isEnabled()) {
            $attributes = $this->addAnonymizedAttributesToArray($attributes);
        }

        return $attributes;
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string  $key
     */
    public function getAttributeValue($key): mixed
    {
        if (! static::getAnonymizeManager()->isEnabled()) {
            return parent::getAttributeValue($key);
        }

        return $this->getCachedAnonymizedAttributes()[$key] ?? parent::getAttributeValue($key);
    }
}
