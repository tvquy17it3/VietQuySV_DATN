<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Timesheet;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class ManagerTimesheets extends Component
{
    use WithPagination;
    public $search = '';
    public $paginate= 10;
    public $select_shifts = 0;
    public $shifts = [];
    public $key_shifts = [];
    public $date= '';
    public $timesheets_id, $employee_id, $shift_id, $check_in, $check_out, $email, $note;
    public $input_search = '';
    protected $listeners = ['postAdded'];

    public function __construct()
    {
        $this->date = date('Y-m-d');
        $this->shifts= Shift::All();
        foreach ($this->shifts as $item) {
            $this->key_shifts[] = $item->id;
        }
    }

    protected $rules = [
        'employee_id' => 'required',
        'shift_id' => 'required',
        'check_in' => 'required|date',
        'check_out' => 'required|date',
    ];

    public function render()
    {
        $data_search = [];
        if($this->input_search != null){
            $data_search =  Employee::with(['user'])->search(trim($this->input_search))->limit(5)->get();
        }

        if ($this->search !=null) {
            $timesheets = Timesheet::with(['employee', 'employee.user'])->search(trim($this->search))->orderBy('check_in', 'DESC')->simplePaginate($this->paginate);
        }else{
            $timesheets = Timesheet::with(['employee', 'employee.user'])->whereDate('check_in', '=', $this->date)
            ->where(function ($query){
                if($this->select_shifts == 0){
                    $query->whereIn('shift_id', $this->key_shifts);
                }else{
                    $query->where('shift_id','=', $this->select_shifts);
                }
            })->orderBy('check_in', 'ASC')->simplePaginate($this->paginate);
        }
        return view('livewire.manager-timesheets', ['timesheets'=> $timesheets, 'data_search'=>$data_search]);
    }

    public function save()
    {
        $this->validate();
        $ip = $this->getUserIpAddr();
        $location = $this->check_location($ip);
        $note = "&Add(by: ".Auth::user()->email." | Time: ".now()." | ".$location['note']. ").";
        $start = new Carbon($this->check_in);
        $end  = new Carbon($this->check_out);
        $hour =  $start->diffInHours($end);
        $result = Timesheet::create([
            'employee_id' => $this->employee_id,
            'shift_id' => $this->shift_id,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'hour' => $hour,
            'location' => $location['location'],
            'ip_address' => $ip,
            'note' => $this->note."".$note,
            'status' => 1
        ]);

        if ( $result == true ) {
            $this->dispatchBrowserEvent('hide-modal',['message'=> 'Đã thêm thành công']);
        }else{
            $this->dispatchBrowserEvent('noti-error',['message'=> 'Đã có lỗi xảy ra!']);
        }
    }

    public function postAdded($email = null)
    {
        $this->employee_id = Employee::with(['user'])
            ->whereHas('user', function($query) use($email) {
                $query->where('email', 'like', $email);
            })->pluck('id')->first();
    }

    public function show_edit($email, $timesheets_id, $shift_id, $check_in, $check_out)
    {
        $this->timesheets_id = $timesheets_id;
        $this->shift_id = $shift_id;
        $this->check_in = date("Y-m-d\TH:i", strtotime($check_in));
        $this->check_out = date("Y-m-d\TH:i", strtotime($check_out));
        $this->email = $email;
        $this->dispatchBrowserEvent('show_editTimeSheetsModal');
    }

    public function edit()
    {
        $find_timesheets = Timesheet::findOrFail($this->timesheets_id);
        $start = new Carbon($this->check_in);
        $end  = new Carbon($this->check_out);
        $hour =  $start->diffInHours($end);
        $ip = $this->getUserIpAddr();
        $location = $this->check_location($ip);
        $note = $find_timesheets->note. "&Edit(by: ".Auth::user()->email." | Time: ".now()." | ".$location['note']. ").";
        $result = $find_timesheets->update([
            'shift_id' => $this->shift_id,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'hour' => $hour,
            'status' => 1,
            'note' => $note,
        ]);

        if ( $result == true ) {
            $this->reset_att();
            $this->dispatchBrowserEvent('hide_editTimeSheetsModal',['message'=> 'Đã sửa thành công']);
        }else{
            $this->dispatchBrowserEvent('noti-error',['message'=> 'Đã có lỗi xảy ra!']);
        }
    }

    public function reset_att()
    {
        $this->timesheets_id = null;
        $this->shift_id = null;
        $this->check_in = null;
        $this->check_out = null;
        $this->email = null;
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

    public function check_location($ip)
    {
        //$ip = "14.236.109.87";
        $note = "";
        $location = "[]";
        try {
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}?token=88cb82c6b0ea8d"));
            $location = $details->loc;
            $note = "IP: ".$details->ip." | City: ".$details->city.", ".$details->region.", loc: [".$details->loc."]";
        } catch(\Exception $error) {
            $note = "IP: ".$ip." | location not found";
        }
        return array('note' => $note, 'location' => $location);
    }

    public function getUserIpAddr(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
     }
}
