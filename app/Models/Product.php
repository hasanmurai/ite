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
        'table_id',
        'photo',
        'price'
    ];

    protected $primaryKey="id";
    public $timestamps=true;
    public $with=['table'];
    public function table(){
        return $this->belongsTo(Table::class,'table_id');
    }
    public function productlikes()
    {
        return $this->hasMany(ProductLike::class,'product_id');
    }
}
