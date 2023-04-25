<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'lrn',
        'name',
        'address',
        'grade_section_id'
    ];
    protected $appends = ['extra'];
    public function section()
    {
      return $this->hasOne(Section::class, 'id', 'grade_section_id');
    }

    public function prsentToday()
    {
      $now = Carbon::now()->format('Y-m-d');
      $attendance = Attendance::whereDate('created_at',$now)->where('stud_lrn', $this->lrn)->get();

      return $attendance->count() != 0 ? true:false;
    }


    public function getExtraAttribute(){
      return $this->attributes['extra'] = [
        'isPresent' => $this->prsentToday()
      ];
    }
}
