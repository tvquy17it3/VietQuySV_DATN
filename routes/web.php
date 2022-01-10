<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\TimesheetController;
// sudo /etc/init.d/apache2 start
// sudo /etc/init.d/apache2 stop
// sudo /opt/lampp/manager-linux-x64.run
// sudo /opt/lampp/lampp start
// php artisan make:livewire EditEmployee
// php artisan serve --host 0.0.0.0


Route::get('/', function () {
    return redirect('/user/profile');
    //return view('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::group(['prefix'=>'admin','middleware'=> ['auth','admin']], function()
{
    //employee
    Route::get('/employee', [EmployeeController::class,'index'])->name('admin.employee');
    Route::get('/employee/{id}', [EmployeeController::class,'edit'])->name('admin.edit-employee');
    Route::POST('/employee/{employee}/{user}', [EmployeeController::class,'update'])->name('admin.update-employee');
    Route::get('/new-employee', [EmployeeController::class,'create'])->name('admin.create-employee');
    Route::POST('/new-employee', [EmployeeController::class,'store'])->name('admin.store-employee');

    Route::get('/employee_profile/{employee}', [EmployeeController::class,'show_employee'])->name('admin.employee-profile'); // employee view

    Route::get('/emp', [EmployeeController::class,'index'])->name('admin.index');
    Route::get('/accounts', [UserController::class,'index'])->name('admin.accounts');
    Route::get('/blocked', [UserController::class,'blocked'])->name('admin.blocked');

    //timesheets
    Route::get('/timesheet', [TimesheetController::class,'index'])->name('admin.timesheets');
    Route::get('/timesheet/{id}', [TimesheetController::class,'show'])->name('admin.view-timesheets-detail');

    Route::get('/change_shifts', [TimesheetController::class,'change_shifts'])->name('admin.change-shifts');
    Route::POST('/change_shifts/{shift}', [TimesheetController::class,'update_shifts'])->name('admin.update_shifts');

    //Training
    Route::get('/training', [EmployeeController::class,'training'])->name('admin.training');
});
