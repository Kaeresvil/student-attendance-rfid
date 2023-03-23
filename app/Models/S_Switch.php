<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class S_Switch extends Model
{
    use HasFactory;

    protected $table = 'switches';
    protected $fillable = [
        'isSet'
      ];
}
