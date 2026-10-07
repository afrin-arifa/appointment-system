<?php

namespace Database\Factories;

use App\Models\DoctorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorProfileFactory extends Factory
{
    protected $model = DoctorProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'specialization' => 'General Medicine',
            'phone' => fake()->numerify('01#########'),
            'experience' => fake()->numberBetween(1, 25),
            'consultation_fee' => fake()->numberBetween(500, 3000),
            'chamber_address' => fake()->address(),
        ];
    }
}
