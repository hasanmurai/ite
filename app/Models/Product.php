<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Product extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table="products";
    protected $fillable = [
        'name',
        'admin_id'
    ];

    protected $primaryKey="id";
    public $timestamps=true;
    public function owner(){
        return $this->belongsTo(Admin::class,'owner_id');
    }
}
