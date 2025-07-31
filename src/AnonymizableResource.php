<?php

namespace DirectoryTree\Anonymize;

use Faker\Generator;

interface AnonymizableResource
{
    /**
     * Convert the resource to its anonymized array representation.
     */
    public function toAnonymized(Generator $faker): array;
}
