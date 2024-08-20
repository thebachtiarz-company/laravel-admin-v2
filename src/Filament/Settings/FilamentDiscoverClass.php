<?php

namespace TheBachtiarz\Admin\Filament\Settings;

abstract class FilamentDiscoverClass
{
    /**
     * Get directory name
     */
    abstract public static function dirname(): string;

    /**
     * Get class directory
     */
    public static function dirClass(): string
    {
        $class = explode(separator: '\\', string: static::class);
        array_pop($class);
        return implode(separator: '\\', array: $class);
    }
}
