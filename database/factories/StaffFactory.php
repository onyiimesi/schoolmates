<?php

namespace Database\Factories;

use App\Models\Designation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'designation_id' => Designation::all()->random()->id,
            'department' => $this->faker->unique(true)->word(),
            'surname' => $this->faker->unique(true)->word(),
            'firstname' => $this->faker->unique(true)->word(),
            'middlename' => $this->faker->unique(true)->word(),
            'email' => $this->faker->unique(true)->safeEmail,
            'phoneno' => $this->faker->unique(true)->numerify('081########'),
            'address' => $this->faker->text(),
            'image' => 'vendor/public/image.jpg',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ];
    }
}
