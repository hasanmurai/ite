<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibition extends Model
{
    use HasFactory;


    protected $table = "exhibitions";
    protected $fillable = [
        'name',
        'admin_id',
        'exhibition_start',
        'exhibition_end',
        'preparation_duration',
        'district',
        'city',
        'status',
        'photo'
    ];
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $hidden = [
        'remember_token',
    ];

    public $with = ['admin'];


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
    public function pavilions()
    {
        return $this->hasMany(Pavilion::class, 'exhibition_id');
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'exhibition_id');
    }

}
