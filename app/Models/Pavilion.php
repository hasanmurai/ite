<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pavilion extends Model
{
    use HasFactory;

    protected $table = "pavilions";

    protected $fillable = [
        'exhibition_id',
        'name',
        'start',
        'end',
        'price'
    ];
    protected $primaryKey = "id";
    public $timestamps = true;

    protected $hidden = [
        'remember_token',
    ];

    public $with = ['exhibition'];

    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class, 'exhibition_id');
    }
    public function tables(){
        return $this->hasMany(Table::class,'pavilion_id');}
    public function register_requests(){
    return $this->hasMany(RegisterRequest::class,'admin_id');}

}
