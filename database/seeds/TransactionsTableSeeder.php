<?php

use App\Transaction;
use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     Transaction::truncate();
     
     $transactionsQunatity = 1000; 
     
     factory(Transaction::class, $transactionsQunatity)->create();
    }
}
