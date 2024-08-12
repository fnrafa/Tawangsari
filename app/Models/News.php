<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property mixed|string $uuid
 * @property mixed $title
 * @property mixed $content
 * @property mixed $uploaded_by
 * @property false|mixed|string $image_path
 * @property mixed $category
 * @method static where(string $column, $value, $value2 = 0)
 */
class News extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'content',
        'image_path',
        'uploaded_by',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
