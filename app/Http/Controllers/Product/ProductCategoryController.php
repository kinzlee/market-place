<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductCategoryController extends APiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    public function update(Request $request, Product $product, Category $category)
    {


        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
        // $validation = [
        //     'name'=> 'name',
        //     'description' => 'description'
        // ];

        // $this->validate($request, $validation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        if(!$product->categories()->find($category->id)) {
            return $this->errorResponse('The specified category is not a categoryof this product', 404);
            
        }

        $product->categories()->detach($category->id);
        return $this->showAll($product->categories);   
    }
}
