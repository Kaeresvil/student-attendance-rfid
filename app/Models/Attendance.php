<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    
    protected $table = 'attendance';
    protected $fillable = [
        'stud_lrn',
        'section_id',
        'event_id',
        'am_time_in',
        'am_time_out',
        'pm_time_in',
        'pm_time_out',
      ];
}
