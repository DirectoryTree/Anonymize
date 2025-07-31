<?php

namespace DirectoryTree\Anonymize;

use DirectoryTree\Anonymize\Facades\Anonymize;
use Faker\Generator;

trait AnonymizesResource
{
    /**
     * Resolve the resource to an array.
     *
     * @param  \Illuminate\Http\Request|null  $request
     */
    public function resolve($request = null): array
    {
        if (! Anonymize::isEnabled()) {
            return parent::resolve($request);
        }

        $faker = Anonymize::faker($this->getAnonymizableSeed());

        return array_merge(parent::resolve($request), $this->toAnonymized($faker));
    }

    /**
     * Get the seed for the anonymizable resource.
     */
    public function getAnonymizableSeed(): string
    {
        return get_class($this->resource).':'.$this->resource->id;
    }

    /**
     * Convert the resource to its anonymized array representation.
     */
    abstract public function toAnonymized(Generator $faker): array;
}
