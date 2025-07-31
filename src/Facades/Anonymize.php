<?php

namespace DirectoryTree\Anonymize\Facades;

use DirectoryTree\Anonymize\AnonymizeManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \DirectoryTree\Anonymize\AnonymizeManager
 */
class Anonymize extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return AnonymizeManager::class;
    }
}
