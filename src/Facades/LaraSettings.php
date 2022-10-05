<?php

namespace Camc\LaraSettings\Facades;

use Camc\LaraSettings\Support\Testing\LaraSettingsFake;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool has($key)
 * @method static mixed get($key, $default = null)
 * @method static void set($key, $value = null)
 */
class LaraSettings extends Facade
{
    public static function fake($settingsToFake = [])
    {
        static::swap($fake = new LaraSettingsFake(static::getFacadeRoot(), $settingsToFake));

        return $fake;
    }

    protected static function getFacadeAccessor()
    {
        return 'lara-settings';
    }
}
