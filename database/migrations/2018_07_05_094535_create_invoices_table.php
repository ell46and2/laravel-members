<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coach_id')->unsigned()->index();
            $table->dateTime('submitted')->nullable();
            $table->enum('status', [
                'pending submission',
                'pending review',
                'invoiced'
            ])->default('pending submission');
            $table->string('label')->nullable(); // May 2018 - the previous month from when it was submitted.
            $table->float('total')->nullable();
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
