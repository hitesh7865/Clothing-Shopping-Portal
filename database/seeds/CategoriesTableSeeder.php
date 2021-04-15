<?php

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
        //
        
        DB::table('categories')->insert(
            [
              [
              'org_id' => '1',
              'title' => 'Champ Frontend Engineer',
              'email_filter' => 'akash.saikia89@gmail.com',
              'subject_filter' => 'Champ Frontend Engineer',
            ],
            [
              'org_id' => '1',
              'title' => 'Sr. Backend Engineer',
              'email_filter' => 'akash.saikia89@gmail.com',
              'subject_filter' => 'Sr. Backend Engineer'
            ],
            [
              'org_id' => '1',
              'title' => 'Mid Level Backend Developer',
              'email_filter' => 'akash.saikia89@gmail.com',
              'subject_filter' => 'Mid Level Backend Developer'
            ]
          ]
            
        );
    }
}
