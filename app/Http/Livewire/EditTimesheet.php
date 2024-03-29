<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Shift;
use App\Models\Timesheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EditTimesheet extends Component
{
    public $timesheets_id, $employee_id, $shift_id, $check_in, $check_out, $email, $note;
    public $shifts = [];
    public $key_shifts = [];
    public Timesheet $timesheet;

    public function __construct()
    {
        $this->shifts= Shift::All();
        foreach ($this->shifts as $item) {
            $this->key_shifts[] = $item->id;
        }
    }

    public function mount($timesheet)
    {
        $this->timesheets_id = $timesheet->id;
        $this->check_in = date("Y-m-d\TH:i", strtotime($timesheet->check_in));
        $this->check_out = date("Y-m-d\TH:i", strtotime($timesheet->check_out));
        $this->email = $timesheet->employee->user->email;
        $this->shift_id = $timesheet->shift_id;
    }

    protected $rules = [
        'employee_id' => 'required',
        'shift_id' => 'required',
        'check_in' => 'required|date',
        'check_out' => 'required|date',
    ];

    public function render()
    {
        return view('livewire.edit-timesheet');
    }

    public function edit()
    {
        $find_timesheets = Timesheet::findOrFail($this->timesheets_id);
        $start = new Carbon($this->check_in);
        $end  = new Carbon($this->check_out);
        if($end < $start){
            $this->dispatchBrowserEvent('noti-error',['message'=> 'Thời gian check out phải lớn hơn check in']);
        }else{
            $hour =  $start->diffInHours($end);
            if($hour <= 12){
                $shift_select = Shift::find($this->shift_id);
                $shift_checkin = Carbon::create($start->toDateString()." ".$shift_select->check_in);
                $shift_checkout = Carbon::create($start->toDateString()." ".$shift_select->check_out);

                if($start < $shift_checkout){
                    $note = "";
                    $time_late = 0;
                    if($start > $shift_checkin){
                        $note_late = $this->check_later($start, $shift_checkin);
                        $time_late = $note_late['late'];
                        $note = $note_late['note'];
                    }
                    $ip = $this->getUserIpAddr();
                    $location = $this->check_location($ip);
                    $note = $find_timesheets->note. "&Edit(by: ".Auth::user()->email." | Time: ".now()." | ".$location['note']. "), ".$note.".";
                    $result = $find_timesheets->update([
                        'shift_id' => $this->shift_id,
                        'check_in' => $this->check_in,
                        'check_out' => $this->check_out,
                        'hour' => $hour,
                        'status' => 1,
                        'note' => $note,
                        'late' => $time_late
                    ]);

                    if ($result == true) {
                        $this->dispatchBrowserEvent('hide_editTimeSheetsModal',['message'=> 'Đã sửa thành công']);
                    }else{
                        $this->dispatchBrowserEvent('noti-error',['message'=> 'Đã có lỗi xảy ra!']);
                    }
                }else{
                    $this->dispatchBrowserEvent('noti-error',['message'=> 'Ca làm việc và thời gian check in không hợp lệ']);
                }
            }else{
                $this->dispatchBrowserEvent('noti-error',['message'=> 'Tối đa 12 tiếng!']);
            }
        }
    }

    public function check_location($ip)
    {
        //$ip = "14.236.109.87";
        $note = "";
        $location = "[]";
        try {
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}?token=".config('app.token_ip')));
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

    public function check_later($start, $shift_checkin){
        $note = "";
        $hour = 0;
        $minutes = 0;
        $hour =  $start->diffInHours($shift_checkin);
        $minutes =  $start->diffInMinutes($shift_checkin);
        if($hour>0){
            $note = "Đi muộn sau ". $hour. " giờ ". $minutes. " phút. ";
            $minutes = $minutes + ($hour*60);
        }elseif($minutes>=1){
            $note = "Đi muộn sau ". $minutes. " phút. ";
        }

        return array('note' => $note, 'late' => $minutes);
    }
}
