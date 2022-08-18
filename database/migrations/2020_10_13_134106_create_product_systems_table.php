<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_systems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->nullable();

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
        Schema::dropIfExists('product_systems');
    }
}
