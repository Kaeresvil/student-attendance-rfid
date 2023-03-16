<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function grade_level()
    {
      return $this->hasOne(GradeLevel::class, 'id', 'grade_level_id');
    }
}
