<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'type' => '1'
        ]);
        
        $user->user_permissions()->attach([1,2,3,4,5]);

        $user->assignRole('administrator');

        $user = User::create([
            'name' => 'Sharon',
            'email' => 'sharon@ledison.shop',
            'password' => bcrypt('admin'),
            'type' => '1'
        ]);

        $user->user_permissions()->attach([1,2,3,4,5]);

        $user->assignRole('administrator');
        

    }
}
