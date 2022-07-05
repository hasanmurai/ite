<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterRequest extends Model
{
    use HasFactory;
    protected $table = "register_requests";
    protected $fillable = [
        'company_id',
        'table_id',
        'table_number',
        'company_name',
        'company_email',
        'phone_number',
        'commercial_record'
    ];
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $hidden = [
        'remember_token',
    ];
    public $with = ['company','table'];
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
