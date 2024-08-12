<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Webpatser\Uuid\Uuid;

/**
 * @property mixed $username
 * @property mixed|string $password
 * @property mixed $name
 * @property mixed $uuid
 * @method static firstOrCreate(string[] $array, array $array1)
 * @method static updateOrCreate(array $array, array $array1)
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $primaryKey = "uuid";

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    protected $casts = [
        'uuid' => 'string',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }

    public function getJWTIdentifier()
    {
        return $this->uuid;
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
