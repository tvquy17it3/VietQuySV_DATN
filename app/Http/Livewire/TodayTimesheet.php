<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Timesheet;
use App\Models\Employee;
use App\Models\Department;

class TodayTimesheet extends Component
{
    public $select_department = 1;
    public $departments = [];
    public $date= '';

    public function __construct()
    {
        $this->date = date('Y-m-d');
        $this->today_date = date('Y-m-d');
        $this->departments = Department::All();
    }

    public function render()
    {
        // $all = Employee::where("department_id", $this->select_department)->count();

        $employees_notcheckin =  Employee::with(['user', 'timesheets'])
        ->where('department_id', $this->select_department)
        ->timesheet_search( $this->date)
        ->get();

        $employees_checkin = Timesheet::with(['employee', 'employee.user'])
        ->whereDate('check_in', $this->date)
        ->timesheet_searchs($this->select_department)
        ->orderBy('check_in', 'DESC')
        ->get();

        return view('livewire.today-timesheet',['employees_checkin' => $employees_checkin, 'employees_notcheckin' => $employees_notcheckin]);
    }

    public function confirmRemoved($id)
    {
        $result = Timesheet::WhereKey((int)$id)->delete();
        if ( $result == true ) {
            $this->dispatchBrowserEvent('noti',['message'=> 'Đã xóa!']);
        }else{
            $this->dispatchBrowserEvent('noti-error',['message'=> 'Đã có lỗi xảy ra!']);
        }
    }

}
