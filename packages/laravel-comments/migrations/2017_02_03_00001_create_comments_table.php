<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('comments.table_names.comments');

        Schema::create($tableName, function (Blueprint $table) use ($tableName) {
            $table->bigIncrements('id');
            $table->nullableMorphs('commentable');
            $table->string('page_id')->nullable()->index();
            $table->bigInteger('user_id')->unsigned()->nullable()->index();
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->string('author_url')->nullable();
            $table->string('author_ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('content');
            $table->string('permalink')->nullable();
            $table->integer('upvotes')->default(0);
            $table->integer('downvotes')->default(0);
            $table->enum('status', ['approved', 'pending', 'spam', 'trash'])->default('approved');
            $table->bigInteger('root_id')->unsigned()->nullable()->index();
            $table->bigInteger('parent_id')->unsigned()->nullable()->index();
            $table->timestamps();

            $table->foreign('root_id')
                    ->references('id')
                    ->on($tableName)
                    ->onDelete('cascade');

            $table->foreign('parent_id')
                    ->references('id')
                    ->on($tableName)
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
        Schema::dropIfExists(config('comments.table_names.comments'));
    }
}
