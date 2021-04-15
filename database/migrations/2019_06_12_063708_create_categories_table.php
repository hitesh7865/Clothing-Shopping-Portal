<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->unsigned();
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->string('title', 150)->comment('Title for the Job/Category');
            $table->string('subject_filter', 150)->comment('Filter on Subject to scrap emails')->nullable();
            
            $table->string('email_filter', 150)->comment('Filter on Email From Address to scrap emails')->nullable();
            $table->dateTime('start_date_time')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            
            $table->dateTime('end_date_time')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
  
            $table->tinyInteger('status')->default(1)->comment('Active, Paused, Deleted');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
