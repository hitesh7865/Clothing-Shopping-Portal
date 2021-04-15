<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailboxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->unsigned();
            $table->foreign('org_id')->references('id')->on('organizations');
            $table->string('imap_name', 80)->nullable()->comment('A nice name used to display');
            $table->string('imap_host', 80)->nullable();
            $table->string('imap_folder', 40)->nullable()->default('INBOX');
            $table->string('imap_user', 40)->nullable();
            $table->string('imap_password', 300)->nullable();
            $table->string('imap_connection_type', 20)->nullable();
            $table->tinyInteger('imap_connection_status')->default(0)->comment('True if test passes, else false');
            $table->tinyInteger('status')->default(0)->comment('Active or Paused');
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
        Schema::dropIfExists('mailboxes');
    }
}
