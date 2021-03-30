<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderitem', function (Blueprint $table) {
            $table->bigIncrements('order_item_id');
            $table->integer('qty');
            $table->unsignedbigInteger('product_id');
            $table->unsignedbigInteger('order_id');

            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('order_id')->references('orderid')->on('orderdata')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('orderitem');
    }
}
