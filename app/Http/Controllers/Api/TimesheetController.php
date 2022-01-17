<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Timesheet;
use App\Models\Shift;
use Carbon\Carbon;
use App\Models\TimesheetDetail;
use App\Models\Geolocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TimesheetController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'shift_id' => 'required|integer',
                'image' => 'required|string',
                'confidence' => 'required|numeric|between:0,1000',
                'ip_address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'distance' => 'required|integer',
                'accuracy' => 'required|numeric|between:0,1000',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Đã có lỗi xác thực!',
                ]);
            }

            $distance = $request->distance;
            $geolocation = Geolocation::orderBy('id', 'DESC')->first();
            if($geolocation->max_distance < $distance){
                return response()->json([
                    'status' => false,
                    'message' => 'Không nằm trong vị trí cho phép!',
                ]);
            }

            $user = $request->user();
            $emp = $user->employee;
            $ip = $request->ip_address;
            $confidence = $request->confidence;

            $latitude = $request->latitude;
            $longitude = $request->longitude;

            $accuracy = $request->accuracy;

            if($emp){
                $dt = Carbon::now('Asia/Ho_Chi_Minh');
                //$dt = Carbon::create(2022, 01, 15, 9, 40, 00);
                $check_exist = Timesheet::whereDate('check_in', '=', $dt->toDateString())
                ->where('employee_id', $emp->id)->where('shift_id', $request->shift_id)->first();

                if($check_exist){
                    $check_in  = Carbon::create($check_exist->check_in);
                    $check_out  = Carbon::create($dt->toDateTimeString());
                    $hours =  $check_out->diffInHours($check_in);

                    if($hours <= 12){
                        $note = $check_exist->note;
                        $location = $this->check_location($ip);
                        $note = $note. "&check out(by: ".Auth::user()->email." | Time: ".now()." | ".$location['note']. ").";
                        DB::beginTransaction();
                        try {
                            $check_exist->update([
                                'status' => 1,
                                'check_out' => $dt->toDateTimeString(),
                                'hour' => $hours,
                                'note' => $note,
                            ]);
                            $check_exist->save();
                            $employee = TimesheetDetail::create([
                                'img' => $request->image,
                                'timesheet_id' => $check_exist->id,
                                'confidence' => $confidence,
                                "accuracy"=> $accuracy,
                                "latitude" => $latitude,
                                "longitude" => $longitude,
                                "distance" => $distance,
                                "ip_address" => $ip,
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
                        return response()->json([
                            'status' => false,
                            'message' => 'Không thể chấm công, Vì quá thời gian cho phép!',
                        ]);
                    }
                }else{
                    $shifts= Shift::find($request->shift_id);
                    $check_in  = Carbon::create($dt->toDateString()." ".$shifts->check_in);
                    $check_in_sub = Carbon::create($dt->toDateString()." ".$shifts->check_in);
                    $check_in_add = Carbon::create($dt->toDateString()." ".$shifts->check_in);

                    $check_in_sub = $this->cal_time('sub', $check_in_sub);
                    $check_in_add = $this->cal_time('add', $check_in_add);
                    $check = $dt->between($check_in_sub,$check_in_add);

                    if($check){
                        $note_time = $this->check_later($dt, $check_in, $check_in_add);
                        $location = $this->check_location($ip);
                        $note = $note_time['note']. "&check in(by: ".Auth::user()->email." | Time: ".now()." | ".$location['note']. ").";
                        DB::beginTransaction();
                        try {
                            $result = Timesheet::create([
                                'employee_id' => $emp->id,
                                'shift_id' => $request->shift_id,
                                'check_in' => $dt->toDateTimeString(),
                                'check_out' => $dt->toDateTimeString(),
                                'location' =>  $location['location'],
                                'note' => $note,
                                'late' => $note_time['late'],
                            ]);

                            $employee = TimesheetDetail::create([
                                'img' => $request->image,
                                'timesheet_id' => $result->id,
                                'confidence' => $confidence,
                                "accuracy"=> $accuracy,
                                "latitude" => $latitude,
                                "longitude" => $longitude,
                                "distance" => $distance,
                                "ip_address" => $ip,
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
        $hour = 0;
        $minutes = 0;

        if($check){
            $hour =  $now->diffInHours($check_in);
            $minutes =  $now->diffInMinutes($check_in);
            if($hour>0){
                $note = "Đi muộn sau ". $hour. "giờ ". $minutes. " phút. ";
                $minutes = $minutes + ($hour*60);
            }elseif($minutes>=1){
                $note = "Đi muộn sau ". $minutes. " phút. ";
            }
        }
        return array('note' => $note, 'late' => $minutes);
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


    public function show(Request $request)
    {
        $employee_id = $request->user()->employee->id;
        $timesheets = Timesheet::with(['shifts'])->where('employee_id', $employee_id)->orderBy('check_in', 'DESC')->simplePaginate(6);
        if($timesheets){
            return response()->json([
                'status_code' => 200,
                'message' => "Lấy dữ liệu thành công!",
                'data' => $timesheets,
            ]);
        }

        return response()->json([
            'status_code' => 200,
            'message' => "Không có dữ liệu!",
            'data' => [],
        ]);
    }

    public function show_today(Request $request)
    {
        $employee_id = $request->user()->employee->id;
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $now = $dt->toDateString();
        $timesheets = Timesheet::with(['shifts'])->where('employee_id', $employee_id)->whereDate('check_in', $now)->orderBy('check_in', 'ASC')->get();
        if($timesheets != null){
            return response()->json([
                'status_code' => 200,
                'message' => "Lấy dữ liệu thành công!",
                'data' => $timesheets,
            ]);
        }

        return response()->json([
            'status_code' => 401,
            'message' => "Không có dữ liệu!",
            'data' => [],
        ]);
    }

    public function geolocation()
    {
        $geolocation = Geolocation::orderBy('id', 'DESC')->first();
        if($geolocation != null){
            return response()->json([
                'status_code' => 200,
                'message' => "Lấy dữ liệu thành công!",
                'latitude' => $geolocation->latitude,
                'longitude' => $geolocation->longitude,
                'max_distance' =>$geolocation->max_distance
            ]);
        }

        return response()->json([
            'status_code' => 401,
            'message' => "Không có dữ liệu!",
            'data' => "",
        ]);
    }
}
