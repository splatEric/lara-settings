<?php

namespace Camc\LaraSettings\Tests\Unit\Models;

use Camc\LaraSettings\Models\LaraSetting;
use Camc\LaraSettings\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\Test;

class LaraSettingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_store_a_string_value()
    {
        $instance = LaraSetting::factory()->create(['value' => 'foobar']);

        $this->assertEquals('foobar', LaraSetting::find($instance->id)->value);
    }

    #[Test]
    public function can_store_a_nested_array()
    {
        $array = Arr::undot(['foo.bar' => 'baz']);
        $instance = LaraSetting::factory()->create(['value' => $array]);

        $this->assertEquals($array, LaraSetting::find($instance->id)->value);
    }
}
