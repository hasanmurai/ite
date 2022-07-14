<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $table = "tables";
    protected $fillable = [
        'company_id',
        'pavilion_id',
        'table_number',
        'company_name',
        'company_email',
        'phone_number',
        'commercial_record',
        'photo'
    ];
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $hidden = [
        'remember_token',
    ];
    public $with = ['pavilion','company'];

    public function products(){
        return $this->hasMany(Product::class,'table_id');}

    public function registerrequests(){
        return $this->hasMany(RegisterRequest::class,'table_id');}

    public function invites(){
        return $this->hasMany(Invite::class,'table_id');}
    public function favorites(){
        return $this->hasMany(Favorite::class,'table_id');}

    public function pavilion()
    {
        return $this->belongsTo(Pavilion::class, 'pavilion_id');
    }public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

