<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->unsigned();
            $table->foreign('org_id')->references('id')->on('organizations');
            
            
            
            $table->string('question', 500);
            $table->tinyInteger('type')->default(1);
            $table->string('options')->nullable()->comment("Options for the Type of Question, comma or NEW LINE based");
            $table->string('positives')->nullable()->comment("Stores the positive values, seperated by comma or semi column or new line");
            $table->string('negatives')->nullable()->comment("Stores the negatives");
            $table->tinyInteger('is_required')->default(0);
            $table->tinyInteger('status')->default(1)->comment("If the question is active or not");
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
        Schema::dropIfExists('questions');
    }
}
