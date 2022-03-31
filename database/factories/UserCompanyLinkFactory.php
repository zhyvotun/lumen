<?php

namespace Database\Factories;

use App\Models\UserCompanyLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class UserCompanyLinkFactory
 * @package Database\Factories
 */
class UserCompanyLinkFactory extends Factory
{
    protected $model = UserCompanyLink::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomDigit(),
            'company_id' => $this->faker->randomDigit(),
        ];
    }
}
