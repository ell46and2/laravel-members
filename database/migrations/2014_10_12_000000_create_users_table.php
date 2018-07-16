<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned()->default(1);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('alias')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->integer('county_id')->unsigned();
            $table->integer('country_id')->unsigned();
            $table->integer('nationality_id')->unsigned()->nullable();
            $table->string('postcode');
            $table->string('telephone');
            $table->string('twitter_handle')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('approved')->default(false); // May need to change to status (Awaiting approval, Active, Suspended, Deleted, Gone away)

            $table->integer('api_id')->unsigned()->nullable();
            $table->string('licence_type')->nullable();
            $table->date('licence_date')->nullable();
            $table->integer('wins')->nullable();
            $table->integer('rides')->nullable();
            $table->integer('lowest_riding_weight')->nullable();
            $table->float('price_money')->nullable();
            $table->string('associated_content')->nullable(); //stewards enquiries/reports

            $table->string('avatar_filename')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('access_token')->nullable();

            // Coach only
            // $table->integer('mileage')->default(0);
            $table->string('vat_number')->nullable();

            $table->rememberToken();
            $table->timestamps();

            // Need to add fields: 
            // Jockey status ? - for jockeys that vacate from the system for a period of time.
            // profile_completion % - when profile is updated (and jockey registers) update. Probably use an observer to calculate.
            // terms_acceptance?
            // privacy_policy_acceptance?
            
            // license_date - for first 3 month jockeys can have 6hrs of training
            
            // COACH - coach_details table ? or add to this table as nullable fields
            // bio
            // Current mileage -- needs to be updated when an invoice-line is added/removed/edited.
            // vat_number 
             
            // JOCKEY
            // classification - enum ('apprentice', 'conditional')
            // Racing Post API id - nullable() as only on RP when already raced or have a race booked.
            // Number of wins
            // Number of races
            // Current claim (7lb/5lb/3lb/none) - enum
            // Start date of their licence
            /* Jockey leaving the system
                A jockey will eventually leave the system. The admin will then be able to mark the jockey as ‘left the system’ and add a comment to their profile stating the reason.
            */


            // $table->foreign('role_id')->references('id')->on('roles');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
