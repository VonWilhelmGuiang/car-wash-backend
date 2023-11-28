<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
    */

    public function definition(): array
    {
    
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'type' => 0, //setType
            'status' => 0, //setStatus
            'created_at' => now(),
            'updated_at' => null,
            'remember_token' => null,
        ];
        
    }

    public function setType():Factory
    {
        return $this->state(function (array $attribute){
            return [
                'type' => 1
            ];
        });
    }

    public function setStatus():Factory
    {
        return $this->state(function (array $attribute){
            return [
                'status' => 1
            ];
        });
    }
}
