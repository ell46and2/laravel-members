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
            $table->integer('activity_id')->unsigned()->index();
            $table->integer('author_id')->unsigned();
            $table->integer('recipient_id')->unsigned()->index();
            $table->string('body');
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('recipient_id')->references('id')->on('users');
            $table->foreign('activity_id')->references('id')->on('activities');

            // Needs a private (boolean) field
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
