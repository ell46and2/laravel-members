<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jet_id')->unsigned();
            $table->string('location');
            $table->integer('managable_id')->unsigned()->nullable();
            $table->string('managable_type')->nullable(); // Jockey or CrmJockey
            $table->text('comment');
            $table->date('review_date')->nullable();
            $table->string('document_filename')->nullable();
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
        Schema::dropIfExists('crm_records');
    }
}
