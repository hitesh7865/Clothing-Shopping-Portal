<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable()->comment("If a mail is a reply to another application, can store the Parent App Id");
            $table->integer('org_id')->unsigned();
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->integer('cat_id')->unsigned();
            // $table->foreign('cat_id')->references('id')->on('categories');
            $table->string('subject', 150)->nullable();
            $table->string('name', 50)->nullable();
            $table->text('text')->nullable();
            $table->string('from_email', 50)->nullable();
            $table->string('to_email', 50)->nullable();
            $table->string('message_id', 120)->nullable();
            $table->text('references')->nullable();
            $table->text('in_reply_to')->nullable();
            $table->string('uid', 10)->nullable();
            $table->string('udate', 13)->nullable();
            $table->char('rating', 1)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:Pending Review, 2:Questioned, 3:Responded, 4:Screened 5:Rejected 6:Hired ');
            $table->tinyInteger('type')->default(1)->comment("1:Fresh, 2:Reply Whether the application is fresh email or a reply to another email");
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
        Schema::dropIfExists('applications');
    }
}
