<?php

namespace App\Models;

use Closure;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Company
 * @package App\Models
 * @method static whereHas(string $string, Closure $param)
 */
class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['title', 'phone', 'description'];
    protected $table = 'company';

    /**
     * @return CompanyFactory
     */
    protected static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }

    /**
     * @return HasMany
     */
    public function userCompanyLinks(): HasMany
    {
        return $this->hasMany(UserCompanyLink::class, 'company_id');
    }
}
