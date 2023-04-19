<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Section extends Model
{
    use HasFactory;

    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'name',
        'description',
        'grade_level_id'
    ];
    protected $with = ['grade_level'];
    protected $appends = ['extra'];

    public function grade_level()
    {
      return $this->hasOne(GradeLevel::class, 'id', 'grade_level_id');
    }
    public function student()
    {
      return $this->hasMany(Student::class, 'grade_section_id', 'id');
    }
    public function total_student()
    {
      $total_students = $this->student()->count();
      return $total_students;
    }

    public function getExtraAttribute(){
      return $this->attributes['extra'] = [
        'total_students' => $this->total_student()
      ];
    }
}
