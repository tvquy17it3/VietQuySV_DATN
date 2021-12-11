<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Position;
use App\Models\Department;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender = ['M', 'F'];

        return [
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'gender' => $gender = $gender[rand(0,1)],
            'birth_date' => now(),
            'from_date' => now(),
            'salary' =>  10000000,
            'department_id' => Department::pluck('id')->random(),
            'position_id' => Position::pluck('id')->random(),
            'user_id' => null,
        ];
    }
}
