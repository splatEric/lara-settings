<?php

namespace Camc\LaraSettings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool has($key)
 * @method static mixed get($key, $default = null)
 * @method static void set($key, $value = null)
 */
class LaraSettings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lara-settings';
    }
}
