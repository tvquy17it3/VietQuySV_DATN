<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'img',
        'timesheet_id',
    ];
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class, 'timesheet_id');
    }
}
