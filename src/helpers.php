<?php

if (! function_exists('lara_settings')) {
    /**
     * Return site settings based on the given key
     *
     * @param string $key
     * @param string $default
     */
    function lara_settings($key, $default = null)
    {
        if (is_null($key)) {
            return app('lara-settings');
        }

        return app('lara-settings')->get($key, $default);
    }
}
