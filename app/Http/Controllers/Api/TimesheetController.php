<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Timesheet;
use App\Models\Shift;
use Carbon\Carbon;
use App\Models\Image;
use Illuminate\Support\Facades\DB;

class TimesheetController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'shift_id' => 'required|integer',
                'ip_address' => 'required|string',
                'image' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Đã có lỗi xác thực!',
                ]);
            }

            $user = $request->user();
            $emp = $user->employee;
            $ip = $request->ip_address;

            if($emp){
                $dt = Carbon::now('Asia/Ho_Chi_Minh');
                // $dt = Carbon::create(2021, 12, 19, 12, 40, 00);
                $check_exist = Timesheet::whereDate('check_in', '=', $dt->toDateString())
                ->where('employee_id', $emp->id)->where('shift_id', $request->shift_id)->first();
                if($check_exist){
                    $check_in  = Carbon::create($check_exist->check_in);
                    $check_out  = Carbon::create($dt->toDateTimeString());
                    $hours =  $check_out->diffInHours($check_in);
                    $note = $check_exist->note;

                    $note_check_ip = $this->check_location($ip, $dt);
                    DB::beginTransaction();
                    try {
                        $check_exist->update([
                            'status' => 1,
                            'check_out' => $dt->toDateTimeString(),
                            'hour' => $hours,
                            'note' => $note.$note_check_ip,
                        ]);
                        $check_exist->save();
                        $employee = Image::create([
                            'img' => $request->image,
                            'timesheet_id' => $check_exist->id,
                        ]);

                        DB::commit();
                        return response()->json([
                            'status' => true,
                            'message' => 'Thành công!',
                            'results' =>  $check_exist,
                        ]);
                    } catch (Exception $e) {
                        DB::rollBack();

                        throw new Exception($e->getMessage());
                    }
                    return response()->json([
                        'status' => false,
                        'message' => 'Đã có lỗi xảy ra!',
                    ]);

                }else{
                    $shifts= Shift::find($request->shift_id);
                    $check_in  = Carbon::create($dt->toDateString()." ".$shifts->check_in);
                    $check_in_sub = Carbon::create($dt->toDateString()." ".$shifts->check_in);
                    $check_in_add = Carbon::create($dt->toDateString()." ".$shifts->check_in);

                    $check_in_sub = $this->cal_time('sub', $check_in_sub);
                    $check_in_add = $this->cal_time('add', $check_in_add);
                    $check = $dt->between($check_in_sub,$check_in_add);

                    if($check){
                        $note = $this->check_later($dt, $check_in, $check_in_add);
                        DB::beginTransaction();
                        try {
                            $result = Timesheet::create([
                                'employee_id' => $emp->id,
                                'shift_id' => $request->shift_id,
                                'check_in' => $dt->toDateTimeString(),
                                'check_out' => $dt->toDateTimeString(),
                                'hour' => 0,
                                'location' =>  $this->check_only_location($ip),
                                'ip_address' => $ip,
                                'note' => $note,
                            ]);

                            $employee = Image::create([
                                'img' => $request->image,
                                'timesheet_id' => $result->id,
                            ]);


                            DB::commit();
                            return response()->json([
                                'status' => true,
                                'message' => 'Thành công!',
                                'results' => $result,
                            ]);
                        } catch (Exception $e) {
                            DB::rollBack();

                            throw new Exception($e->getMessage());
                        }
                        return response()->json([
                            'status' => false,
                            'message' => 'Đã có lỗi xảy ra!',
                        ]);
                    }else{
                        return response()->json([
                            'status' => false,
                            'message' => 'Không thể chấm công, Vì không nằm trong khoảng thời gian cho phép!',
                        ]);
                    }
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Chưa tạo có hồ sơ!',
                ]);
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Đã có lỗi xảy ra!',
                'errors' => $error->getMessage(),
            ]);
        }
    }

    public function cal_time($status, $date_time)
    {
        $newDateTime = null;
        if($status== 'sub'){
           $newDateTime = $date_time->subHour();
        }else{
            $newDateTime = $date_time->addHour();
        }
        return $newDateTime;
    }

    public function check_later($now, $check_in, $check_in_add)
    {
        $check = $now->between($check_in,$check_in_add);
        $note = "";

        if($check){
            $hour =  $now->diffInHours($check_in);
            $minutes =  $now->diffInMinutes($check_in);
            if($hour>0){
                $note = "Đi muộn sau ". $hour. "giờ ". $minutes. " phút. ";
            }elseif($minutes>=1){
                $note = "Đi muộn sau ". $minutes. " phút. ";
            }
        }
        return $note;
    }

    public function check_location($ip, $dt)
    {
        $note = "";
        try {
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
            $note = "&Check Out(". $dt. ", ip:".$ip. ", location: [".$details->loc."]). ";
        } catch(\Exception $error) {
            $note = "&Check Out(". $dt. ", Không tìm thấy tọa độ của ip: ".$ip. ". ";
            return $note;
        }
        return $note;
    }

    public function check_only_location($ip)
    {
        $location = "[]";
        try {
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
            $location = "[".$details->loc."]";
        } catch(\Exception $error) {
            return $location;
        }
        return $location;
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
