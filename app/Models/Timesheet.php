<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timesheet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'check_in',
        'check_out',
        'hour',
        'location',
        'late',
        'employee_id',
        'shift_id',
        'note',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function shifts()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function timesheet_details()
    {
        return $this->hasMany(TimesheetDetail::class);
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->whereHas('employee', function($query) use($term) {
            $query->where('phone', 'like', $term);
        })->orWhereHas('employee.user', function($query) use($term) {
            $query->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term);
        })->orwhere(function ($query) use ($term) {
            $query->where('check_in', 'like', $term)
                  ->orWhere('check_out', 'like', $term);
        });
    }

    public function scopeTimesheet_searchs($query, $depart)
    {
        $query->whereHas('employee', function($query) use($depart) {
            $query->where('department_id', $depart);
        })->orderBy('check_in', 'DESC');
    }
}
