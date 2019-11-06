<?php

namespace App;
use App\Transaction;
use App\scopes\BuyerScope;

class Buyer extends User        
{
    protected $table = 'users';
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
