<?php

namespace Tests\Feature\Coach;

use App\Models\Activity;
use App\Models\Coach;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Jockey;
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

	    $response = $this->actingAs($coach)->post("/invoices/$invoice->id/invoice-lines", [
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

	/** @test */
	public function calculate_group_cost()
	{
	    	
	}

	/** @test */
	public function a_miscellaneous_item_can_be_added()
	{
	    $coach = factory(Coach::class)->create();

	    $invoice = factory(Invoice::class)->create([
	    	'coach_id' => $coach->id,
	    ]);

	    $response = $this->actingAs($coach)->post("/invoices/invoice/$invoice->id/misc", [
	    	'misc_name' => 'Misc name',
	    	'misc_date' => '26/11/2018',
	    	'value' => 100
	    ]);

	    tap(InvoiceLine::first(), function($invoiceLine) use ($response, $invoice) {
	    	$response->assertStatus(302);
         	$response->assertRedirect("/invoices/invoice/{$invoice->id}");

         	$this->assertEquals($invoiceLine->misc_name, 'Misc name');
         	$this->assertEquals($invoiceLine->misc_date, Carbon::createFromFormat('d/m/Y', '26/11/2018'));
         	$this->assertEquals($invoiceLine->value, 100);
         	$this->assertEquals($invoiceLine->invoiceable_id, null);
         	$this->assertEquals($invoiceLine->invoiceable_type, null);

         	$this->assertTrue($invoice->fresh()->lines->first()->is($invoiceLine));
	    });
	}

	/** @test */
	public function can_submit_invoice_for_review()
	{
	    $coach = factory(Coach::class)->create();

	    $jockey = factory(Jockey::class)->create();

	    $invoice = factory(Invoice::class)->create([
	    	'coach_id' => $coach->id,
	    ]);

	    $activity1 = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(2),
	    	'duration' => 30,
        	'end' => Carbon::now()->subDays(2),
	    ]);

	    $activity2 = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(2),
	    	'duration' => 60,
        	'end' => Carbon::now()->subDays(2),
	    ]);

	    $activity1->addJockey($jockey);
	    $activity1->addJockey($jockey);

	    $invoiceLine1 = factory(InvoiceLine::class)->create([
	    	'invoice_id' => $invoice->id,
			'invoiceable_id' => $activity1->id,
			'invoiceable_type' => 'activity',
			'value' => 17.5
	    ]);

	    $invoiceLine2 = factory(InvoiceLine::class)->create([
	    	'invoice_id' => $invoice->id,
			'invoiceable_id' => $activity2->id,
			'invoiceable_type' => 'activity',
			'value' => 35
	    ]);

	    $response = $this->actingAs($coach)->post("/invoices/invoice/$invoice->id/submit");

	    tap($invoice->fresh(), function($invoice) use ($response) {
        	$response->assertStatus(302);
         	$response->assertRedirect("/invoices/invoice/{$invoice->id}");

         	$this->assertEquals($invoice->status, 'pending review');
         	$this->assertEquals($invoice->total, 52.5);
         	$this->assertEquals($invoice->label, Carbon::now()->subMonths(1)->format('F Y'));
        });
	}

}


/*
	Views: create_invoice, add_activities/Re, add_mileage, submitted_view, invoice_index

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


/*
	MILEAGE

	Invoice (hasOne InvoiceMileage)

	InvoiceMileage (belongs to Invoice, hasMany Mileage)
	-id
	-invoice_id
	-value

	Mileage (belongs to InvoiceMileage)
	-id
	-invoice_mileage_id
	-mileagable_id
	-mileagable_type
	-name/desc
	-miles

	On adding Mileage to invoice
	 - if not already exists an invoiceMileage is created for that invoice
	 - else the invoiceMileage is retrieved
	 - the Mileage row is inserted into the Mileage table and linked to the invoiceMileage.
	 - on creating Mileage the InvoiceMileage value is calculated, this is the accumalated sum of all the Mileage attached and will work using the correct rates using the calculateMileageValue function below.
	 - this way the rate is worked out on all the attached mileage and not indivdually. So if mileage is added, amended or removed, we just recalculate the total value again using the calculateMileageValue function.
	 - when Admin marks an invoice as paid the mileage for that invoice is added to the coach->mileage (coach's total mileage for year)

	 - the invoice Total is then the sum of all the InvoiceLines (activities, RE, misc) plus the invoiceMileage value.

	function calculateMileageValue() {
		$currentCoachMileage = $coach->mileage;
		$numOfInvoiceMiles = $invoiceMileage->mileage->sum('miles');
		$total = $currentCoachMileage + $numOfInvoiceMiles;
		
		$milesAbove = $total - 10000;
		$milesUnder = $numOfInvoiceMiles - $milesAbove;

		return ($milesAbove * 10000Rate) + ($milesUnder * below10000Rate);
	}
 */