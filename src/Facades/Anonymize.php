<?php

namespace DirectoryTree\Anonymize\Facades;

use DirectoryTree\Anonymize\AnonymizeManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void enable()
 * @method static void disable()
 * @method static bool isEnabled()
 * @method static \Faker\Generator faker(string|int|null $seed = null)
 *
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
