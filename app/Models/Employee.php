<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'address',
        'gender',
        'birth_date',
        'from_date',
        'salary',
        'department_id',
        'position_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->whereHas('user', function($query) use($term) {
            $query->where('name', 'like', $term)
                  ->orWhere('email', 'like', $term);
        })->orwhere(function ($query) use ($term) {
            $query->where('phone', 'like', $term);
        });
    }

    public function scopeTimesheet_search($query, $date)
    {
        $query->whereDoesntHave('timesheets', function ($query) use($date){
            $query->whereDate('check_in', $date);
        });
    }
}
