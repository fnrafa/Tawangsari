<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property mixed|string $uuid
 * @property mixed $name
 * @property mixed $level
 * @property mixed $upper_level_uuid
 * @property false|mixed|string $image_path
 * @property mixed $nip
 * @method static where(string $string, $uuid)
 */
class Structure extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'level',
        'upper_level_uuid',
        'image_path',
        'nip'
    ];

    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'level' => 'integer',
        'upper_level_uuid' => 'string',
        'image_path' => 'string',
        'nip' => 'string',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string)Str::uuid();
        });
    }
}
