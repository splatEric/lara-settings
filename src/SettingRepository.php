<?php

namespace Camc\LaraSettings;

use Camc\LaraSettings\Contracts\Repository;
use Camc\LaraSettings\Models\LaraSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Access and set LaraSetting values by key
 */
class SettingRepository implements Repository
{
    public function __construct(protected string $cacheKey)
    {
    }

    public function has($key): bool
    {
        $setting = $this->settings()
            ->where('key', Str::before($key, '.'))
            ->first();

        if (! $setting) {
            return false;
        }

        return ! Str::contains($key, '.') || Arr::has($setting->value, Str::after($key, '.'));
    }

    public function get($key, $default = null)
    {
        $setting = $this->settings()
            ->where('key', Str::before($key, '.'))
            ->first();

        if (! Str::contains($key, '.')) {
            return $setting?->value ?? $default;
        }

        return Arr::get($setting?->value, Str::after($key, '.')) ?? $default;
    }

    public function set($key, $value)
    {
        $settingKey = Str::before($key, '.');
        $currentValue = $this->get($settingKey);
        $subKey = Str::after($key, '.');

        if ($subKey && $subKey !== $settingKey) {
            // dot notation setting key, so store subkeys in value array
            $currentDot = Arr::dot(is_array($currentValue) ? $currentValue : []);
            Arr::set($currentDot, $subKey, $value);
            $value = Arr::undot($currentDot);
        }

        LaraSetting::updateOrCreate(['key' => $settingKey], ['value' => $value]);
        Cache::forget($this->cacheKey);
    }

    protected function settings()
    {
        return Cache::rememberForever($this->cacheKey, function () {
            return LaraSetting::all();
        });
    }
}
