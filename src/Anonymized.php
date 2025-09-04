<?php

namespace DirectoryTree\Anonymize;

use Faker\Generator;
use Illuminate\Support\Facades\App;

/**
 * @mixin Anonymizable
 */
trait Anonymized
{
    /**
     * Whether to enable anonymization for the current model instance.
     */
    protected bool $anonymizeEnabled = true;

    /**
     * The anonymized attributes for the current model instance and seed.
     */
    protected array $anonymizedAttributeCache;

    /**
     * The seed for the cached anonymized attributes.
     */
    protected string $anonymizedAttributeCacheSeed;

    /**
     * Get the anonymize manager instance.
     */
    protected static function getAnonymizeManager(): AnonymizeManager
    {
        return App::make(AnonymizeManager::class);
    }

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

    /**
     * Get the seed for the anonymizable model.
     */
    public function getAnonymizableSeed(): string
    {
        return get_class($this).':'.$this->getAttributeValue('id');
    }

    /**
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
     * Make the anonymized attributes.
     */
    protected function getCachedAnonymizedAttributes(): array
    {
        return $this->withoutAnonymization(function (): array {
            $seed = $this->getAnonymizableSeed();

            if (! isset($this->anonymizedAttributeCache) || $this->anonymizedAttributeCacheSeed !== $seed) {
                $this->anonymizedAttributeCache = $this->getAnonymizedAttributes(
                    static::getAnonymizeManager()->faker($seed)
                );

                $this->anonymizedAttributeCacheSeed = $seed;
            }

            return $this->anonymizedAttributeCache;
        });
    }

    /**
     * Add the anonymized attributes to the attribute array.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    protected function addAnonymizedAttributesToArray(array $attributes): array
    {
        foreach ($this->getCachedAnonymizedAttributes() as $key => $value) {
            if (! array_key_exists($key, $attributes)) {
                continue;
            }

            $attributes[$key] = $value;
        }

        return $attributes;
    }
}
