<?php

namespace App\Models;

use Database\Factories\UserCompanyLinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserCompanyLink
 * @package App\Models
 */
class UserCompanyLink extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['user_id', 'company_id'];
    protected $table = 'user_company_link';
    protected $primaryKey = ['user_id', 'company_id'];

    /**
     * @return UserCompanyLinkFactory
     */
    protected static function newFactory(): UserCompanyLinkFactory
    {
        return UserCompanyLinkFactory::new();
    }
}
