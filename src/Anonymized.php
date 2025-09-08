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
     * Whether to enable anonymization for the current model instance.
     */
    protected bool $anonymizeEnabled = true;

    /**
     * Get the anonymized attributes.
     */
    abstract public function getAnonymizedAttributes(Generator $faker): array;

    /**
     * Execute a callback without anonymization.
     *
     * @template TReturn
     *
     * @param  callable($this): TReturn  $callback
     * @return TReturn
     */
    public function withoutAnonymization(callable $callback): mixed
    {
        $previous = $this->anonymizeEnabled;

        $this->anonymizeEnabled = false;

        try {
            return $callback($this);
        } finally {
            $this->anonymizeEnabled = $previous;
        }
    }

    /**
     * Get all of the current attributes on the model.
     *
     * @return array<string, mixed>
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();

        if ($this->anonymizeEnabled && static::getAnonymizeManager()->isEnabled()) {
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
        if (! $this->anonymizeEnabled || ! static::getAnonymizeManager()->isEnabled()) {
            return parent::getAttributeValue($key);
        }

        return $this->getCachedAnonymizedAttributes()[$key] ?? parent::getAttributeValue($key);
    }
}
