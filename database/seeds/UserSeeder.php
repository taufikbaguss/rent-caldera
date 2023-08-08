<?php

use Illuminate\Database\Seeder;
use App\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$data = [
	        'name' => 'administrator',
	        'username' => 'administrator',
	        'email' => 'admin@gmail.com',
	        'password' => bcrypt('admin@gmail.com'),
	        'role' => 'admin',
	        'address' => 'Wongiri',
	        'status' => 1
    	];
    	$create = User::create($data);

    }
}
