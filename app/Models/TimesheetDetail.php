<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'longitude',
        'latitude',
        'distance',
        'accuracy',
        'ip_address',
        'img',
        'timesheet_id',
        'confidence',
        'note',
        'status'
    ];
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class, 'timesheet_id');
    }
}
