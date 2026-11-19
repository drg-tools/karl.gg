<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Profile pages look loadouts up by creator, which previously scanned the whole table.
     */
    public function up(): void
    {
        Schema::table('loadouts', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('loadouts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }
};
