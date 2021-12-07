<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Position;
use App\Models\Department;
use App\Models\Shift;

class RolePositionDepartmentShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create([
            'name' => 'Admin',
        ]);
        $staff = Role::create([
            'name' => 'Staff',
        ]);

        $executive = Position::create([
            'name' => 'Executive Assistant',
        ]);
        $marketing = Position::create([
            'name' => 'Marketing Manager',
        ]);
        $software = Position::create([
            'name' => 'Software Engineer',
        ]);

        $hr = Department::create([
            'name' => 'Human Resource',
        ]);
        $operations_management = Department::create([
            'name' => 'Operations management',
        ]);

        $morning = Shift::create([
            'name' => 'Morning',
            'check_in' => '07:30:00',
            'check_out' => '11:30:00',
        ]);
        $afternoon = Shift::create([
            'name' => 'Afternoon',
            'check_in' => '13:00:00',
            'check_out' => '17:00:00',
        ]);
    }
}
