<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageRecipient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_recipient', function (Blueprint $table) {
            $table->integer('message_id')->unsigned();
            $table->integer('recipient_id')->unsigned()->index();
            $table->boolean('read')->default(false);
            $table->boolean('deleted')->default(false);

            // $table->foreign('recipient_id')->references('id')->on('users');
            // $table->foreign('message_id')->references('id')->on('messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
