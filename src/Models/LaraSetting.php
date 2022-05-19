<?php

namespace Camc\LaraSettings\Models;

use Camc\LaraSettings\Casts\MightBeArray;
use Camc\LaraSettings\Database\Factories\LaraSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaraSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    protected $casts = [
        'value' => MightBeArray::class,
    ];

    public function getTable()
    {
        return config('lara_settings.model_table', 'lara_settings');
    }

    protected static function newFactory()
    {
        return new LaraSettingFactory();
    }
}
