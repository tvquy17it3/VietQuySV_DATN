<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        return View('admin.employee.index');
    }

    public function create()
    {
        $departments = Department::All();
        $positions = Position::All();
        return View('admin.employee.new', ['departments' => $departments, 'positions' =>$positions]);
    }

    public function store(StoreEmployeeRequest $request)
    {
        DB::beginTransaction();
        try {
            $data_user = $request->only('name','email', 'password');
            $data_user['password'] = Hash::make($data_user['password']);
            $user = User::create($data_user);

            $data_employee = $request->except('name','email', 'password');
            $data_employee['user_id'] = $user->id;
            $employee = Employee::create($data_employee);

            DB::commit();
            return redirect()->route('admin.edit-employee', $employee->id)->with('success', 'Đã thêm thành công!');
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }

        return back()->withErrors('Đã có lỗi xãy ra!');
    }


    public function edit($id)
    {
        $departments = Department::All();
        $positions = Position::All();
        $employee = Employee::with(['user'])->find($id);
        if($employee ==null){
            return redirect('/admin');
        }
        return View('admin.employee.edit', ['employee' => $employee, 'departments' => $departments, 'positions' =>$positions]);
    }

    public function update(Employee $employee, User $user, UpdateEmployeeRequest $request)
    {
        $name = $request->only('name');
        $input = $request->except('name','user_id');
        $user->fill($name)->save();
        $rs =  $employee->fill($input)->save();
        return $rs ? back()->with('success', 'Cập nhật thành công!') : back()->withErrors('Đã có lỗi xãy ra!');
    }

    public function show_employee($employee)
    {
        $employee = Employee::with(['user', 'department', 'position'])->find($employee);
        if($employee ==null){
            return redirect('/admin');
        }
        return View('admin.employee.show', ['employee' => $employee]);
    }
}
