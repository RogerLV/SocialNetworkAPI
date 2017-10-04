<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $users = [
        	'andy@example.com', 
        	'john@example.com', 
        	'common@example.com',
        	'lisa@example.com',
        	'kate@example.com',
        ];

        foreach ($users as $user) {
        	$userIns = new User();
        	$userIns->email = $user;
        	$userIns->save();
        }

  		echo "Finish Seeding Users";
    }
}
