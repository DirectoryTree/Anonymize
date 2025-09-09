<?php

namespace DirectoryTree\Anonymize;

trait AnonymizedResource
{
    use AnonymizesAttributes;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return array<string, mixed>
     */
    public function resolve($request = null): array
    {
        $attributes = parent::resolve($request);

        if (! $this->anonymizeEnabled || ! static::getAnonymizeManager()->isEnabled()) {
            return $attributes;
        }

        return $this->addAnonymizedAttributesToArray($attributes);
    }
}
