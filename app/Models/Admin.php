<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table="admins";


    protected $fillable = [
        'username',
        'email',
        'password',
        'phone_number',
        'photo',
    ];
    protected $primaryKey="id";
    public $timestamps=true;
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

    public function companyrequests()
    {
        return $this->hasMany(CompanyRequest::class, 'admin_id');
    }
    public function exhibitions(){
        return $this->hasMany(Exhibition::class,'admin_id');}

}
