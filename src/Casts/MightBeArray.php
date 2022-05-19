<?php

namespace Camc\LaraSettings\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class MightBeArray implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }
}
