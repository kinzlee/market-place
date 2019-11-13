<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class ProductBuyerTransactionController extends APiController
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {

        $validation = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request, $validation);
        if($buyer->id == $product->seller_id) {
            return $this->errorResponse('the buyer must be different from the seller', 409);
        } 
        
        if (!$buyer->isVerified()) {
            return $this->errorResponse('The buyer must be a verified user', 409);
        }

        if (!$product->seller->isVerified()) {
            return $this->errorResponse('The seller must be verified user', 409);
        }

        if (!$product->isAvailable()) {

            return $this->errorResponse('The porduct is not available', 409);
        }

        if($product->quantity < $request->quantity) {
            return $this->errorResponse('the product does not have enough units for this transaction', 409);
        }

        return DB::transaction(function () use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
                    $product->save();
            
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' =>  $buyer->id,
                'product_id' => $product->id                                                                                                                                           
            ]);

            return $this->showOne($transaction, 201);
        });
    }
}
