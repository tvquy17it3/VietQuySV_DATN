<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Timesheet;
use App\Models\Employee;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class AddnewTimesheet extends Component
{
    public $input_search = '';
    public $employee_id, $shift_id, $check_in, $check_out, $email, $note;
    protected $listeners = ['postAdded'];

    public function __construct()
    {
        $this->date = date('Y-m-d');
        $this->date_from = date('Y-m-d');
        $this->shifts= Shift::All();
    }

    protected $rules = [
        'employee_id' => 'required',
        'shift_id' => 'required',
        'check_in' => 'required|date',
        'check_out' => 'required|date|after:check_in',
    ];


    public function render()
    {
        $data_search = [];
        if($this->input_search != null){
            $data_search =  Employee::with(['user'])->search(trim($this->input_search))->limit(5)->get();
        }

        return view('livewire.addnew-timesheet', ['data_search'=>$data_search]);
    }

    public function postAdded($email = null)
    {
        $this->employee_id = Employee::with(['user'])
            ->whereHas('user', function($query) use($email) {
                $query->where('email', 'like', $email);
            })->pluck('id')->first();
    }

    public function save()
    {
        $this->validate();
        $start = new Carbon($this->check_in);
        $end  = new Carbon($this->check_out);
        $hour =  $start->diffInHours($end);
        if($hour <= 12){
            $shift_select= Shift::find($this->shift_id);
            $shift_checkout = new Carbon($shift_select->check_out);
            $shift_checkin = new Carbon($shift_select->check_in);
            if($start < $shift_checkout){
                $check_exist = Timesheet::whereDate('check_in', '=', $start->toDateString())
                ->where('employee_id', $this->employee_id)->where('shift_id', $this->shift_id)->first();
                if(!$check_exist){
                    $note = "";
                    $time_late = 0;
                    if($start > $shift_checkin){
                        $note_late = $this->check_later($start, $shift_checkin);
                        $time_late = $note_late['late'];
                        $note = $note_late['note'];
                    }
                    $ip = $this->getUserIpAddr();
                    $location = $this->check_location($ip);
                    $note = $note."&Add(by: ".Auth::user()->email." | Time: ".now()." | ".$location['note']. ").";

                    $result = Timesheet::create([
                        'employee_id' => $this->employee_id,
                        'shift_id' => $this->shift_id,
                        'check_in' => $this->check_in,
                        'check_out' => $this->check_out,
                        'hour' => $hour,
                        'late' => $time_late,
                        'location' => $location['location'],
                        'note' => $this->note."".$note,
                        'status' => 1
                    ]);

                    if ( $result == true ) {
                        $this->dispatchBrowserEvent('noti',['message'=> 'Đã thêm thành công']);
                    }else{
                        $this->dispatchBrowserEvent('noti-error',['message'=> 'Đã có lỗi xảy ra!']);
                    }
                }else{
                    $this->dispatchBrowserEvent('noti-error',['message'=> 'Đã có dữ liệu!']);
                }
            }else{
                $this->dispatchBrowserEvent('noti-error',['message'=> 'Ca làm việc và thời gian check in không hợp lệ']);
            }
        }else{
            $this->dispatchBrowserEvent('noti-error',['message'=> 'Tối đa 12 tiếng!']);
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

    public function check_later($start, $shift_checkin)
    {
        $note = "";
        $hour = 0;
        $minutes = 0;
        $hour =  $start->diffInHours($shift_checkin);
        $minutes =  $start->diffInMinutes($shift_checkin);
        if($hour>0){
            $note = "Đi muộn sau ". $hour. "giờ ". $minutes. " phút. ";
            $minutes = $minutes + ($hour*60);
        }elseif($minutes>=1){
            $note = "Đi muộn sau ". $minutes. " phút. ";
        }

        return array('note' => $note, 'late' => $minutes);
    }
}
