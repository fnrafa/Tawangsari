<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property mixed|string $uuid
 * @property mixed $name
 * @property mixed $description
 * @property mixed $contact_person
 * @property false|mixed|string $image_path
 * @property mixed $google_map_link
 * @property mixed $title
 * @property mixed $owner
 * @method static where(string $string, $uuid)
 */
class Umkm extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
