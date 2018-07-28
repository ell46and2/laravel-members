<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('commentable_id')->unsigned()->index();
            $table->string('commentable_type');
            $table->integer('author_id')->unsigned();
            $table->integer('recipient_id')->unsigned()->index();
            $table->boolean('read')->default(false);
            $table->boolean('private')->default(false);
            $table->text('body');
            $table->timestamps();

            // $table->foreign('author_id')->references('id')->on('users');
            // $table->foreign('recipient_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
