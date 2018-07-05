<?php

namespace Tests\Feature\Coach;

use App\Models\Activity;
use App\Models\Coach;
use App\Models\Invoice;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_coach_can_add_activities_and_racing_excellence_to_their_invoice()
	{
	    $coach = factory(Coach::class)->create();

	    $activity = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(2),
	    	'duration' => 30,
        	'end' => Carbon::now()->subDays(2),
	    ]);

	    $racingExcellence = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id
	    ]);

	    $division1 = factory(RacingExcellenceDivision::class)->create([
	    	'racing_excellence_id' => $racingExcellence->id
	    ]);

	    $invoice = factory(Invoice::class)->create([
	    	'coach_id' => $coach->id,
	    ]);

	    $this->assertTrue($activity->invoicable());
	    $this->assertTrue($racingExcellence->invoicable());

	    $response = $this->actingAs($coach)->post("/invoice/$coach->id/invoice-lines", [
            'activities' => [$activity->id], // array of selected activities - will need one for RE too.
            'racingexcellences' => [$racingExcellence->id], // array of selected activities - will need one for RE too.
        ]);

        tap($invoice->fresh(), function($invoice) use ($response, $activity, $racingExcellence) {
        	// $response->assertStatus(302);
         //    $response->assertRedirect("/coach/activity/{$activity->id}");

        	$this->assertEquals(2, $invoice->lines->count());

        	$this->assertEquals($activity->id, $invoice->lines->first()->invoiceable_id);
        	$this->assertEquals('activity', $invoice->lines->first()->invoiceable_type);
        	$this->assertEquals(17.5, $invoice->lines->first()->value);

        	$this->assertEquals($racingExcellence->id, $invoice->lines->last()->invoiceable_id);
        	$this->assertEquals('racing-excellence', $invoice->lines->last()->invoiceable_type);
        	$this->assertEquals(120, $invoice->lines->last()->value);

        	// dd($activity->invoiceLine);
        	$this->assertFalse($activity->fresh()->invoicable());
        	$this->assertFalse($racingExcellence->fresh()->invoicable());
        });
	}


	/** @test */
	public function an_invoiceable_can_only_be_added_to_an_invoice_once()
	{
	    // use validation to check that activity, RE, mileage aren't already invoicelines
	}

}


/*
	Views: create_invoice, add_activities/Re, add_mileage, submitted_view

	an invoiceable activity/RE:
		- must be in the past
		- start_date must be > than 2 months ago date
		- coach_id === Coaches id
		- not already have an invoice line


	mileageable acitivity list:
		- activity is on the current invoice
		- no mileage already for that activity

	Adding an activity/RE
	 - invoice-line is created and the cost/value field calculated

	Adding Mileage: seperate view from main invoice
		- link to activity/RE or free text
		- enter mileage (float)
		- on submit mileage is created and invoice line is created


	Viewing invoice:
		- activities broken down per each jockey
		- RE shown (seperate - not per jockey)
		- Misc shown
		- mileage shown
		- total shown
		- each line has a remove btn, mileage has an edit btn also
		- removing activity or RE will put them back in the invoiceable list
		- removing mileage or an misc will delete them.
		- The total cost is shown, but not saved to the invoice until it is submitted.
		- can only be submitted between 1st and 10th - invoice label is set as the previous month plus the year - so if submitted 2nd June 2018 - the label will be May 2018
 */