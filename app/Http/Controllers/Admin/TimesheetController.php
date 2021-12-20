<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use App\Models\Image;
use App\Models\Shift;
use App\Http\Requests\UpdateShiftsRequest;
use Validator;

class TimesheetController extends Controller
{
    public function index()
    {
        return View('admin.timesheets.index');
    }
    public function show($id)
    {
        $timesheets = Timesheet::where('id', $id)->with(['employee','employee.user','images'])->withTrashed()->first();
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
}
