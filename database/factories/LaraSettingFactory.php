<?php

namespace Camc\LaraSettings\Database\Factories;

use Camc\LaraSettings\Models\LaraSetting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class LaraSettingFactory extends Factory
{
    protected $model = LaraSetting::class;

    public function definition()
    {
        return [
            'key' => $this->faker->word(),
            'value' => rand(0, 1) === 0 ? $this->generateArrayValue(rand(1, 3)) : $this->faker->words(rand(1, 3), true),
        ];
    }

    protected function generateArrayValue($depth = 1)
    {
        return Arr::undot([implode('.', $this->faker->words($depth)) ?? 0 => $this->faker->word()]);
    }
}
