<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLike extends Model
{
    use HasFactory;

    protected $table = "product_likes";
    protected $fillable = [
        'product_id',
        'user_id',
        'company_id'
    ];
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $hidden = [
        'remember_token',
    ];
    public $with = ['product','company','user'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
        public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
