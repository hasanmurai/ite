<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

protected $guard='api';
    protected $fillable = [
        'username',
        'email',
        'password',
        'phone_number',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

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
    public function invites(){
        return $this->hasMany(Invite::class,'user_id');
    }    public function favorites(){
        return $this->hasMany(Favorite::class,'user_id');
    }
   public function productlikes(){
        return $this->hasMany(ProductLike::class,'user_id');}
}
