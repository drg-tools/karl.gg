<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('comments.table_names.votes'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('comment_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->enum('type', ['up', 'down'])->default('up');
            $table->timestamps();

            $table->foreign('comment_id')
                    ->references('id')
                    ->on(config('comments.table_names.comments'))
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('comments.table_names.votes'));
    }
}
