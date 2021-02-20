<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TEST USER
        User::create([
            'name' => "Argevim",
            'email' => "root",
            'password' => bcrypt('?2018argevim?'),
            'userType' => "admin",
        ]);
    }
}
