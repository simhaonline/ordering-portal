<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('customers', static function (Blueprint $table) {
            $table->string('code')->unique();
            $table->string('name');
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('post_code')->nullable();
            $table->string('invoice_name')->nullable();
            $table->string('invoice_address_line_1')->nullable();
            $table->string('invoice_address_line_2')->nullable();
            $table->string('invoice_city')->nullable();
            $table->string('invoice_country')->nullable();
            $table->string('invoice_postcode')->nullable();
            $table->string('vat_flag')->default('SS');
            $table->string('currency')->default('GBP');
            $table->string('buying_group')->nullable();
            $table->string('price_list')->nullable();
            $table->string('discount_code')->nullable();

            $table->primary('code');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
}
