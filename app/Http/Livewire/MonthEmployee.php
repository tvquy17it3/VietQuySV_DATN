<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;

class MonthEmployee extends Component
{
    public $select_department = 1;
    public $departments = [];
    public $month= '';

    public function __construct()
    {
        $this->month = date('Y-m');
        $this->today_date = date('d-m-Y');
        $this->departments = Department::All();
    }

    public function render()
    {
        $employees = Employee::where('department_id', $this->select_department)
        ->with(['user', 'timesheets'])
        ->with(['timesheets' => function ($query) {
            $query->whereYear('check_in', date("Y", strtotime($this->month)))->whereMonth('check_in', date("m", strtotime($this->month)));
        }])->withCount(['timesheets' => function ($query) {
            $query->whereYear('check_in', date("Y", strtotime($this->month)))->whereMonth('check_in', date("m", strtotime($this->month)));
        }])->get();

        return view('livewire.month-employee',['employees' => $employees]);
    }
}
