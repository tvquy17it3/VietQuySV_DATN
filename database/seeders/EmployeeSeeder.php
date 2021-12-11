<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('slug', 'admin')->first();

        $admin = User::create([
            'name' => "admin",
            'email' => "admin@gmail.com",
            'email_verified_at' => now(),
            'password' => '$2y$10$wR9hNe65p46SOSBufVXbTeHeLY00mduZ4Hd9OxUSCv1OJnQ83qJl.',
            'remember_token' => "",
            'role_id' => $role->id,
        ]);

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

        $admin2 = User::create([
            'name' => "Trần Văn Quý",
            'email' => "vanquy.dev@gmail.com",
            'email_verified_at' => now(),
            'password' => '$2y$10$wR9hNe65p46SOSBufVXbTeHeLY00mduZ4Hd9OxUSCv1OJnQ83qJl.',
            'remember_token' => "",
            'role_id' => $role->id,
        ]);
        //$admin = User::where('name', 'admin')->first();

        $emp2 = Employee::create([
            'phone' => '0355317866',
            'address' => 'Da Nang',
            'gender' => 'M',
            'birth_date' => '1998-11-22',
            'salary' => '10000000',
            'user_id' => $admin2->id,
            'position_id' => 3,
            'department_id' => 1,
            'from_date' => '2021-08-01'
        ]);
    }
}
