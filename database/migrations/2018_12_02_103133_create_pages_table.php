<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('pages', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        DB::table('pages')->insert([
            'name'        => 'terms-and-conditions',
            'description' => 'Terms & Conditions will go here',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        DB::table('pages')->insert([
            'name'        => 'data-protection',
            'description' => 'Data Protection will go here',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        DB::table('pages')->insert([
            'name'        => 'accessibility-policy',
            'description' => 'Accessibility Policy will go here',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
}
