<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

    protected $table = "invites";

    protected $fillable = [
        'user_id',
        'company_id',
        'table_id',
        'invite_status'
    ];
    protected $primaryKey = "id";
    public $timestamps = true;

    protected $hidden = [
        'remember_token',
    ];

    public $with = ['user','company','table'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

}
