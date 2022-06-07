<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CompanyRequest extends Model
{
    use  HasFactory, Notifiable;

    protected $table="company_requests";
    protected $fillable = [
        'admin_id',
        'status_id',
        'username',
        'email',
        'password',
        'company_name',
        'company_email',
        'company_address',
        'phone_number',
        'photo',
        'commercial_record',
        'social_media_account'
    ];

    protected $primaryKey="id";
    public $timestamps=true;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

