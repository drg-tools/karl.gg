<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->increments('id');

                $table->morphs('model');
                $table->morphs('rateable');

                $table->decimal('value', 2, 1);

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('ratings')) {
            Schema::drop('ratings');
        }
    }
}
