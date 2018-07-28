<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
                'approved'
            ])->default('pending submission');
            $table->string('label')->nullable(); // May 2018 - the previous month from when it was submitted.
            $table->float('total')->nullable();
            $table->timestamps();

            // $table->foreign('coach_id')->references('id')->on('users');
        });

        /*
            Change to whatever id number the Invoices need to start from.
         */
        // DB::statement("ALTER TABLE invoices AUTO_INCREMENT = 2000;");
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
