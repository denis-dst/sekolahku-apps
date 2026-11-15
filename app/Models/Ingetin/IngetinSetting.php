<?php

namespace App\Models\Ingetin;

use Illuminate\Database\Eloquent\Model;

class IngetinSetting extends Model
{
    protected $connection = 'ingetin';
    protected $table = 'settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function getVal(string $key, $default = '')
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setVal(string $key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
