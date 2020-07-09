<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadoutEquipmentModTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loadout_equipment_mod', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loadout_id');
            $table->unsignedBigInteger('equipment_mod_id');
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
        Schema::dropIfExists('loadout_equipment_mod');
    }
}
