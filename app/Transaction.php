<?php

namespace App;
use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id',
    ];
    //protected $table = 'users';
    public function buyer()
    {
        dd('i am herre');
        return $this->belongsToMany(Buyer::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
