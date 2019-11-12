<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\APiController;

class ProductBuyerController extends APiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $buyers = $product->transactions()->with('buyer')
        ->get()
        ->pluck('buyer')
        ->unique('id')
        ->values();

        return $this->showAll($buyers);
    }
}
