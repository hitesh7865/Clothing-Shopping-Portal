<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('placeorder', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('Bill_no');
            $table->unsignedbigInteger('user_id');
            $table->unsignedbigInteger('Product_id');
            $table->string('Product_name');
            $table->Integer('Qty');
            $table->float('Price');
            $table->float('Grand_total');
            $table->string('Address');
            $table->string('Zip_code');
            $table->string('Phone_no');
            $table->enum('Payment_method',['Case_on_delivery']);

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('Product_id')->references('id')->on('product')->onDelete('cascade');
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
        Schema::dropIfExists('placeorder');
    }
}
