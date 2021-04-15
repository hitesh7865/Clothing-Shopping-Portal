<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('questions')->insert(
            [
              [
                'question' => 'What is your current CTC ?',
                'org_id' => 1
              ],
              [
                'question' => 'What is your expected CTC ?',
                'org_id' => 1
              ],
              [
                'question' => 'What is your relevant experience ?',
                'org_id' => 1
              ],
              [
                'question' => 'What is your notice period ?',
                'org_id' => 1
              ]
          ]
            
        );
    }
}
