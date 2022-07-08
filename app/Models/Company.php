<?php

namespace App\Models;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Company extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table="companies";


    protected $fillable = [
        'username',
        'email',
        'password',
        'company_name',
        'company_email',
        'company_address',
        'phone_number',
        'photo',
        'commercial_record',
    ];

    protected $primaryKey="id";
    public $timestamps=true;
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
    public function tables()
    {
        return $this->hasMany(Table::class,'company_id');
    } public function productlikes()
    {
        return $this->hasMany(ProductLike::class,'company_id');
    }
    public function register_requests()
    {
        return $this->hasMany(RegisterRequest::class,'company_id');
    }
    public function invites(){
        return $this->hasMany(Invite::class,'company_id');}
}
