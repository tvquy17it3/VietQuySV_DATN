<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolePositionDepartmentShiftSeeder::class);
        $this->call(EmployeeSeeder::class); // create 2 account.

        $roles = Role::where('slug', 'staff')->first();
        User::factory(20)->create();


        User::chunk(50, function ($users) {
            // dd($users);
            foreach ($users as $user) {
                if($user->employee == null){
                    $emp = Employee::factory()->make([
                        'user_id' => $user->id,
                    ]);
                    $emp->save();
                }
            }
        });
    }
}
