<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedbigInteger('category_id');
            $table->unsignedbigInteger('subcategory_id');
            $table->string('Product_name');
            $table->string('Product_brand');
            $table->Integer('Price');
            $table->string('Description');
            $table->string('Color');
            $table->string('Size');
            $table->Integer('Stock');
            $table->date('Posting_date');
            $table->binary('photo');
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('subcategory')->onDelete('cascade');
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
        Schema::dropIfExists('product');
    }
}
