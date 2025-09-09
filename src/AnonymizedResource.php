<?php

namespace DirectoryTree\Anonymize;

trait AnonymizedResource
{
    use AnonymizesAttributes;

    /**
     * Anonymize the given attributes.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function toAnonymized(array $attributes): array
    {
        if (! $this->anonymizeEnabled || ! static::getAnonymizeManager()->isEnabled()) {
            return $attributes;
        }

        return $this->addAnonymizedAttributesToArray($attributes);
    }
}
