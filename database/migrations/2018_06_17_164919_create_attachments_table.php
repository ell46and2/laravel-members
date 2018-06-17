<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid');
            $table->integer('attachable_id')->unsigned()->nullable();
            $table->string('attachable_type')->nullable();
            $table->boolean('processed')->default(false);
            $table->string('video_id')->nullable(); // used by telestream
            $table->string('filename')->nullable();
            $table->enum('filetype', ['image', 'video']);
            $table->integer('processed_percentage')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
