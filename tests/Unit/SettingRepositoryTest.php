<?php

namespace Camc\LaraSettings\Tests\Unit;

use Camc\LaraSettings\Facades\LaraSettings;
use Camc\LaraSettings\Models\LaraSetting;
use Camc\LaraSettings\SettingRepository;
use Camc\LaraSettings\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;

class SettingRepositoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected SettingRepository $repo;

    protected string $cacheKey = 'foo';

    public function setUp(): void
    {
        parent::setUp();
        $this->repo = new SettingRepository($this->cacheKey);
    }

    /** @test */
    public function cache_key_is_set_by_repository()
    {
        $repo = new SettingRepository('foobar');
        $this->assertFalse(Cache::has('foobar'));

        $repo->get('foo');

        $this->assertTrue(Cache::has('foobar'));
    }

    /** @test */
    public function no_setting_stored_returns_false_from_has()
    {
        Cache::shouldReceive('rememberForever')
            ->withArgs(function (...$args) {
                return $args[0] === $this->cacheKey;
            })
            ->andReturn(collect([LaraSetting::factory()->create(['key' => 'foo'])]));

        $this->assertFalse($this->repo->has('bar'));
    }

    /** @test */
    public function empty_setting_stored_returns_true_from_has()
    {
        Cache::shouldReceive('rememberForever')
            ->withArgs(function (...$args) {
                return $args[0] === $this->cacheKey;
            })
            ->andReturn(collect([LaraSetting::factory()->create(['key' => 'foo', 'value' => null])]));

        $this->assertTrue($this->repo->has('foo'));
    }

    /** @test */
    public function nested_null_value_returns_true_from_has()
    {
        Cache::shouldReceive('rememberForever')
            ->withArgs(function (...$args) {
                return $args[0] === $this->cacheKey;
            })
            ->andReturn(collect([LaraSetting::factory()->create(['key' => 'foo', 'value' => ['bar' => null]])]));

        $this->assertTrue($this->repo->has('foo.bar'));
    }

    /** @test */
    public function nested_false_value_returns_true_from_has()
    {
        Cache::shouldReceive('rememberForever')
            ->withArgs(function (...$args) {
                return $args[0] === $this->cacheKey;
            })
            ->andReturn(collect([LaraSetting::factory()->create(['key' => 'foo', 'value' => ['bar' => false]])]));

        $this->assertTrue($this->repo->has('foo.bar'));
    }

    /** @test */
    public function get_returns_nested_value()
    {
        Cache::shouldReceive('rememberForever')
            ->withArgs(function (...$args) {
                return $args[0] === $this->cacheKey;
            })
            ->andReturn(collect([LaraSetting::factory()->create(['key' => 'foo', 'value' => ['bar' => 'baz']])]));

        $this->assertEquals('baz', $this->repo->get('foo.bar'));
    }

    /** @test */
    public function default_returned_for_null_value()
    {
        Cache::shouldReceive('rememberForever')
            ->withArgs(function (...$args) {
                return $args[0] === $this->cacheKey;
            })
            ->andReturn(collect([LaraSetting::factory()->create(['key' => 'foo', 'value' => ['bar' => null]])]));

        $this->assertEquals('foobar', $this->repo->get('foo.bar', 'foobar'));
    }

    /** @test */
    public function setting_updated_in_db_and_cache_is_forgotten()
    {
        $setting = LaraSetting::factory()->create(['value' => 'foobar']);
        $newValue = $this->faker->words(3, true);

        Cache::shouldReceive('rememberForever')
            ->withArgs(function (...$args) {
                return $args[0] === $this->cacheKey;
            })
            ->andReturn(collect($setting));

        Cache::shouldReceive('forget')
            ->with($this->cacheKey)
            ->once();

        $this->repo->set($setting->key, $newValue);

        $this->assertEquals($newValue, $setting->refresh()->value);
    }
}
