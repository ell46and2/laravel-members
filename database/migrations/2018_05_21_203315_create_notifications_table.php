<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('body');
            $table->boolean('read')->default(false);
            $table->integer('notifiable_id')->unsigned();
            $table->string('notifiable_type');
            $table->timestamps();

            // Add polymorphic notifiable_type notifiable_id
            // So we can link from notification to the activity, message, comment, document etc
            // Can be nullable as for example Activity deleted can't be linked to

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
