<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Timesheet;
use Livewire\WithPagination;
use DB;
class TimesheetsEmployee extends Component
{
    use WithPagination;
    public $employee_id;
    public $paginate= 10;
    public function render()
    {
        $timesheets = Timesheet::with(['employee', 'shifts'])->where('employee_id', $this->employee_id)->orderBy('check_in', 'DESC')->simplePaginate($this->paginate);

        // $statistical = Timesheet::with(['employee', 'shifts'])->where('employee_id', $this->employee_id)->selectRaw("year(check_in) year, month(check_in) month, count(*) count_checkin, SUM(hour) as hour")
        // ->groupBy('year', 'month')
        // ->orderBy('year', 'desc')
        // ->orderBy('month', 'desc')
        // ->get();

        $statistical = Timesheet::with(['employee', 'shifts'])
        ->where('employee_id', $this->employee_id)
        ->selectRaw("extract(year from check_in) as year, extract(month from check_in) as month, count(*) as count_checkin, SUM(hour) as hour, SUM(late) as late")
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();
        $months = [];
        $counts = [];
        $sum_hours = [];
        $late = [];
        foreach ($statistical as $month) {
            $months[] = $month->month."/".$month->year;
            $counts[] = $month->count_checkin;
            $sum_hours[] = $month->hour;
            if($month->late > 60){
                $late[] = (int)($month->late / 60);
            }else{
                $late[] = 1;
            }

        }

        return view('livewire.timesheets-employee',
        [
            'timesheets'=> $timesheets,
            'months' => $months,
            'counts' => $counts,
            'sum_hours' => $sum_hours,
            'late' => $late
        ]);
    }
}
