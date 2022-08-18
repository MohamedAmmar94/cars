<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSuppliersBrandsCategoriesProductSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('code')->nullable();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->string('code')->nullable();
        });

        Schema::table('product_systems', function (Blueprint $table) {
            $table->string('code')->nullable();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('code')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->drop('code');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->drop('code');
        });

        Schema::table('product_systems', function (Blueprint $table) {
            $table->drop('code');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->drop('code');
        });

    }
}
