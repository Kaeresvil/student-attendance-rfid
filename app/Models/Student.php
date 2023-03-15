<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'lrn',
        'name',
        'address',
        'grade_section_id'
    ];
    public function section()
    {
      return $this->hasOne(Section::class, 'id', 'grade_section_id');
    }
}
