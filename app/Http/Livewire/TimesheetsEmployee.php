<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Timesheet;
use Livewire\WithPagination;

class TimesheetsEmployee extends Component
{
    use WithPagination;
    public $employee_id;
    public $paginate= 10;
    public function render()
    {
        $timesheets = Timesheet::with(['employee', 'shifts'])->where('employee_id', $this->employee_id)->orderBy('check_in', 'DESC')->simplePaginate($this->paginate);
        return view('livewire.timesheets-employee', ['timesheets'=> $timesheets]);
    }
}
