<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     Category::truncate();
    
     $categoriesQunatity = 30;

     factory(Category::class, $categoriesQunatity)->create();

    }
}
