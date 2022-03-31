<?php

namespace App\Models;

use Closure;
use Database\Factories\UserFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $email
 * @property mixed $password
 * @property mixed $phone
 * @method static find(int $int)
 * @method static where(string $string, mixed $input)
 * @method static whereHas(string $string, Closure $param)
 */
class User extends Model
{
    use Authenticatable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'phone', 'companies'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    protected $table = 'user';

    /**
     * @return HasOne
     */
    public function accessToken(): HasOne
    {
        return $this->hasOne(UserAccessToken::class);
    }

    /**
     * @return UserFactory
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
