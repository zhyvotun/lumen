<?php

namespace Database\Factories;

use App\Models\PasswordReset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class PasswordResetFactory
 * @package Database\Factories
 */
class PasswordResetFactory  extends Factory
{
    protected $model = PasswordReset::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'token' => $this->faker->randomKey,
        ];
    }
}
