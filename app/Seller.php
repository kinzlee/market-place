<?php

namespace App;
use App\Product;
use App\scopes\SellerScope;


class Seller extends User
{
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }
    public function products()
    {
        // dd('i am here');
        return $this->hasMany(Product::class);
    }
    protected $table = 'users';
    


}
