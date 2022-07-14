<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;


    protected $table = "favorites";
    protected $fillable = [
        'exhibition_id',
        'table_id',
        'user_id',
        'company_id',
    ];
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $hidden = [
        'remember_token',
    ];

    public $with = ['exhibition','table','user','company',];

    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class, 'exhibition_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
