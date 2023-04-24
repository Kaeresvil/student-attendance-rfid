<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_level',
        'description',
    ];

    public $searchable = ['grade_level', 'description'];

    public function sections()
    {
      return $this->hasMany(Section::class, 'grade_level_id', 'id');
    }

    public static function searchable()
    {
        return ['grade_level', 'description'];
    }
}
