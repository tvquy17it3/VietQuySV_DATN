<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\Image;
use App\Models\Shift;
use App\Models\Geolocation;
use App\Http\Requests\UpdateShiftsRequest;
use App\Http\Requests\UpdateGeolocationRequest;
use Validator;

class TimesheetController extends Controller
{
    public function index()
    {
        return View('admin.timesheets.index');
    }
    public function show($id)
    {
        $timesheets = Timesheet::where('id', $id)->with(['employee','employee.user','timesheet_details'])->withTrashed()->first();
        if($timesheets ==null){
            return redirect('/admin');
        }
        return View('admin.timesheets.show', ['timesheets' => $timesheets]);
    }

    public function change_shifts()
    {
        $shifts = Shift::orderBy('check_in', 'ASC')->get();
        return View('admin.timesheets.change-shifts', ['shifts' =>$shifts]);
    }

    public function update_shifts(UpdateShiftsRequest $request, Shift $shift)
    {
        $result = $shift->update([
            'name' => $request->name,
            'check_in' => $request->check_in,
            'check_out'=> $request->check_out,
        ]);
        return $result ? back()->with('success', 'Cập nhật thành công!') : back()->withErrors('Đã có lỗi xãy ra!');
    }

    public function change_location()
    {
        $geolocation = Geolocation::orderBy('id', 'DESC')->first();
        return View('admin.timesheets.change-location', ['geolocation' =>$geolocation]);
    }

    public function update_location(UpdateGeolocationRequest $request, Geolocation $location)
    {
        $result = $location->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'max_distance'=> $request->max_distance,
        ]);
        return $result ? back()->with('success', 'Cập nhật thành công!') : back()->withErrors('Đã có lỗi xãy ra!');
    }

    public function timesheet_today()
    {
        return View('admin.timesheets.today');
    }

    public function timesheet_month()
    {
        return View('admin.timesheets.month');
    }

    public function history()
    {
        $timesheets =  Timesheet::with(['employee','employee.user'])
        ->onlyTrashed()
        ->orderBy('created_at', 'ASC')
        ->simplePaginate(10);

        return view('admin.history',['timesheets' => $timesheets]);
    }

    public function add_new()
    {
        return View('admin.timesheets.addnew');
    }
}
