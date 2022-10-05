<?php

namespace Camc\LaraSettings\Support\Testing;

use Camc\LaraSettings\Contracts\Repository;
use Illuminate\Support\Arr;

class LaraSettingsFake implements Repository
{
    protected Repository $originalRepository;

    protected array $settingsToFake;

    public function __construct(Repository $repository, array $settingsToFake = [])
    {
        $this->originalRepository = $repository;
        $this->settingsToFake = $settingsToFake;
    }

    public function has($key): bool
    {
        return Arr::has($this->settingsToFake, $key);
    }

    public function get($key, $default = null)
    {
        return Arr::get($this->settingsToFake, $key, $default);
    }

    public function set($key, $value)
    {
        Arr::set($this->settingsToFake, $key, $value);
    }
}
