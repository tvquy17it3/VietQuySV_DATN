<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','check_in', 'check_out',
    ];

    public function getTimeCheck()
    {
        return "{$this['name']} {$this['check_in']} -> {$this['check_out']}";
    }
}
