<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('effect');
            $table->text('description')->nullable(); // TODO: Remove nullable
            $table->unsignedBigInteger('row');
            $table->unsignedBigInteger('column');
            $table->unsignedBigInteger('gun_id');
            $table->timestamps();

            $table->unique(['row', 'column', 'gun_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mods');
    }
}
