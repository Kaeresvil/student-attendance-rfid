<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable 
{
    use HasApiTokens, HasFactory, Notifiable;

 


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['sections','role'];
    public function role()
    {
        return $this->hasOne(Role::class,'id','role_type_id');
      
    }

    public function sections()
    {
        return $this->hasManyThrough(Section::class, UserSection::class, 'user_id', 'id', 'id', 'section_id');
    }

        /**
     * Get the indexable data array for the model.
     *
     * @return array
     */

     public static function allSectionsId($id = 0)
     {
 
         // HANDLE IF DIFF USER IS BEING USE
         if ($id != 0) {
             $id = Auth::user()->id;
         }
         $user = User::find(Auth::user()->id);
         $locations = $user->sections->pluck('id');
         return $locations;
     }
}
