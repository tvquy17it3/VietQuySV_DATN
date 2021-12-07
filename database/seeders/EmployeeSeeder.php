<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => "admin",
            'email' => "admin@gmail.com",
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => "",
            'role_id' => 1,
        ]);
        //$admin = User::where('name', 'admin')->first();

        $emp = Employee::create([
            'phone' => '0999898989',
            'address' => 'Da Nang',
            'gender' => 'M',
            'birth_date' => '1998-11-22',
            'salary' => '10000000',
            'user_id' => $admin->id,
            'position_id' => 1,
            'department_id' => 1,
            'from_date' => '2021-08-01'
        ]);
    }
}
