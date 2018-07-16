<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMileagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mileages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_mileage_id')->unsigned()->index();
            $table->string('description');
            $table->date('mileage_date');
            $table->float('miles');
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
        Schema::dropIfExists('mileages');
    }
}
