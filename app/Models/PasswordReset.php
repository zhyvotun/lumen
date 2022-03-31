<?php

namespace App\Models;

use Database\Factories\PasswordResetFactory;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CreatePasswordResetTable
 * @package App\Models
 * @method static where(string $string, mixed $input)
 */
class PasswordReset extends Model
{
    use CanResetPassword;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['email', 'token'];
    protected $table = 'password_reset';
    public $timestamps = false;

    /**
     * @return PasswordResetFactory
     */
    protected static function newFactory(): PasswordResetFactory
    {
        return PasswordResetFactory::new();
    }
}
