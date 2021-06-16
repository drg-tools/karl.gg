<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnToRatingsTable extends Migration
{
    public function up()
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->string('type')->nullable();
        });

        \Nagy\LaravelRating\Models\Rating::query()->update(['type' => 'vote']);
    }

    public function down()
    {
        Schema::dropColumns('ratings', ['type']);
    }
}
