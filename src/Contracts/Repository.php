<?php

namespace Camc\LaraSettings\Contracts;

interface Repository
{
    public function has($key): bool;

    public function get($key, $default = null);

    public function set($key, $value);
}
