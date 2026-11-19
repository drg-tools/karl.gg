<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Comment settings now live in config/comments.php.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('comment_settings');
    }

    public function down(): void
    {
        Schema::create('comment_settings', function (Blueprint $table) {
            $table->string('key')->unique();
            $table->text('value');
        });
    }
};
