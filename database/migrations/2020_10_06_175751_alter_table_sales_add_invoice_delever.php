<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSalesAddInvoiceDelever extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->boolean('is_invoice_deliver')->default(0)->nullable();
            $table->date('invoice_deliver_date')->nullable();        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIfExists('is_invoice_deliver');
            $table->dropIfExists('invoice_deliver_date');
        });
    }

}
