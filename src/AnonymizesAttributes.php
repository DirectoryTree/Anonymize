<?php

namespace DirectoryTree\Anonymize;

use Illuminate\Support\Facades\App;

trait AnonymizesAttributes
{
    /**
     * Whether anonymization is enabled for the current instance.
     */
    protected bool $anonymizeEnabled = true;

    /**
     * The anonymized attributes for the current instance and seed.
     */
    protected array $anonymizedAttributeCache;

    /**
     * The seed for the cached anonymized attributes.
     */
    protected string $anonymizedAttributeCacheSeed;

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
     * Get the seed for the anonymizable instance.
     */
    public function getAnonymizableSeed(): string
    {
        return get_class($this).':'.($this->getAttributes()[$this->getKeyName()] ?? '');
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
     * Get the anonymize manager instance.
     */
    protected static function getAnonymizeManager(): AnonymizeManager
    {
        return App::make(AnonymizeManager::class);
    }
}
