<?php

namespace DirectoryTree\Anonymize;

interface AnonymizableResource
{
    /**
     * Get the anonymized resource data.
     */
    public function toAnonymized(): array;
}
