<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSection extends Model
{
    use HasFactory;
    protected $table = 'teacher_sections';
    protected $fillable = [
        'user_id', 'section_id'
      ];
}
