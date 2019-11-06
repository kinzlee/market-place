<?php
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     User::truncate();
     
     $usersQunatity = 1000;
     
     factory(User::class, $usersQunatity)->create();

    }
}
