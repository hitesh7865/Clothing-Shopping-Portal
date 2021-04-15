<?php

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
        //
        DB::table('users')->insert(
            [
              'fullname' => 'Akash Saikia',
              'email' => 'akash@sodainmind.com',
              'password' => bcrypt('123')
            ]
            
        );
        
        DB::table('organizations')->insert(
            [
              'user_id' => '1',
              'name' => 'Default'
            ]
            
        );
        
        
        DB::table('users')->insert(
            [
            'fullname' => 'Piyush',
            'email' => 'piyush@sodainmind.com',
            'password' => bcrypt('123')
          ]
            
        );
        DB::table('users')->insert(
            [
            'fullname' => 'Sodainmind',
            'email' => 'dev@sodainmind.com',
            'password' => bcrypt('123')
          ]
            
        );
    }
}
