<?php

namespace Tests\Feature\Coach;

use App\Models\Activity;
use App\Models\Coach;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Jockey;
use App\Models\Mileage;
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
	public function can_create_an_invoice()
	{
	    $coach = factory(Coach::class)->create();

	    $this->assertTrue($coach->canCreateInvoice());
	}

	/** @test */
	public function cannot_create_an_invoice_if_they_currently_have_one_open()
	{
	    $coach = factory(Coach::class)->create();

	    $invoice = factory(Invoice::class)->create([
	    	'coach_id' => $coach->id
	    ]);

	    $this->assertFalse($coach->canCreateInvoice());
	}

	/** @test */
	public function cannot_create_an_invoice_if_they_have_already_submitted_for_the_month()
	{
	    Carbon::setTestNow(Carbon::parse('2018-03-5'));

	    $coach = factory(Coach::class)->create();

	    $invoice = factory(Invoice::class)->create([
	    	'coach_id' => $coach->id,
	    	'submitted' => Carbon::parse('2018-03-2'),
	    	'status' => 'pending review'
	    ]);

	    $this->assertFalse($coach->canCreateInvoice());
	}

	/** @test */
	public function a_coach_can_add_activities_and_racing_excellence_to_their_invoice()
	{
	    $coach = factory(Coach::class)->create();
	    $jockey = factory(Jockey::class)->create();

	    $activity = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(2),
	    	'duration' => 30,
        	'end' => Carbon::now()->subDays(2),
	    ]);
	    $activity->addJockey($jockey);

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

	/*
		invoiceable list contains activities and RE from last months
		Except if the current date is between the 1st and 10th, do not show for the current month.
	 */


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

	    $response = $this->actingAs($coach)->post("/invoices/invoice/{$invoice->id}/misc", [
	    	'misc_name' => 'Misc name',
	    	'misc_date' => '26/11/2018',
	    	'value' => 100
	    ]);

	    tap(InvoiceLine::first(), function($invoiceLine) use ($response, $invoice) {
	    	$response->assertStatus(302);
         	$response->assertRedirect("/invoices/invoice/{$invoice->id}");

         	$this->assertEquals($invoiceLine->misc_name, 'Misc name');
         	$this->assertEquals($invoiceLine->misc_date->format('d/m/Y'), '26/11/2018');
         	$this->assertEquals($invoiceLine->value, 100);
         	$this->assertEquals($invoiceLine->invoiceable_id, null);
         	$this->assertEquals($invoiceLine->invoiceable_type, null);

         	$this->assertTrue($invoice->fresh()->lines->first()->is($invoiceLine));
	    });
	}

	/** @test */
	public function mileage_can_be_added()
	{
	    $coach = factory(Coach::class)->create();

	    $coach->mileages()->create([
            'year' => now()->year,
            'miles' => 1000
        ]);

	    $invoice = factory(Invoice::class)->create([
	    	'coach_id' => $coach->id,
	    ]);

	    $invoice->invoiceMileage()->create();

	    $response = $this->actingAs($coach)->post("/invoices/invoice/{$invoice->id}/mileage", [
	    	'description' => 'Mileage description',
	    	'mileage_date' => now()->subMonth()->format('d/m/Y'),
	    	'miles' => 104.5
	    ]);

	    tap(Mileage::first(), function($mileage) use ($coach, $invoice, $response) {
	    	$response->assertStatus(302);
         	$response->assertRedirect("/invoices/invoice/{$invoice->id}");

         	$this->assertEquals($mileage->description, 'Mileage description');
         	$this->assertEquals($mileage->mileage_date->format('d/m/Y'), now()->subMonth()->format('d/m/Y'));
         	$this->assertEquals($mileage->miles, 104.5);

         	$this->assertEquals($invoice->invoiceMileage->value, number_format(104.5 * config('jcp.mileage.rate_below_threshold'), 2)); // 47.03
	    });
	}

	/** @test */
	public function mileage_can_be_added_where_already_above_threshold()
	{
	    $coach = factory(Coach::class)->create();

	    $coach->mileages()->create([
            'year' => now()->year,
            'miles' => (config('jcp.mileage.threshold') + 100)
        ]);

	    $invoice = factory(Invoice::class)->create([
	    	'coach_id' => $coach->id,
	    ]);

	    $invoice->invoiceMileage()->create();

	    $response = $this->actingAs($coach)->post("/invoices/invoice/{$invoice->id}/mileage", [
	    	'description' => 'Mileage description',
	    	'mileage_date' => now()->subMonth()->format('d/m/Y'),
	    	'miles' => 104.5
	    ]);

	    tap(Mileage::first(), function($mileage) use ($coach, $invoice, $response) {
	    	$response->assertStatus(302);
         	$response->assertRedirect("/invoices/invoice/{$invoice->id}");

         	$this->assertEquals($mileage->description, 'Mileage description');
         	$this->assertEquals($mileage->mileage_date->format('d/m/Y'), now()->subMonth()->format('d/m/Y'));
         	$this->assertEquals($mileage->miles, 104.5);

         	$this->assertEquals($invoice->invoiceMileage->value, number_format(104.5 * config('jcp.mileage.rate_above_threshold'), 2)); // 26.13
	    });
	}

	/** @test */
	public function mileage_can_be_added_where_new_miles_pushes_over_threshold()
	{
	    $coach = factory(Coach::class)->create();

	    $coach->mileages()->create([
            'year' => now()->year,
            'miles' => (config('jcp.mileage.threshold') - 50)
        ]);

	    $invoice = factory(Invoice::class)->create([
	    	'coach_id' => $coach->id,
	    ]);

	    $invoice->invoiceMileage()->create();

	    $response = $this->actingAs($coach)->post("/invoices/invoice/{$invoice->id}/mileage", [
	    	'description' => 'Mileage description',
	    	'mileage_date' => now()->subMonth()->format('d/m/Y'),
	    	'miles' => 104.5
	    ]);

	    tap(Mileage::first(), function($mileage) use ($coach, $invoice, $response) {
	    	$response->assertStatus(302);
         	$response->assertRedirect("/invoices/invoice/{$invoice->id}");

         	$this->assertEquals($mileage->description, 'Mileage description');
         	$this->assertEquals($mileage->mileage_date->format('d/m/Y'), now()->subMonth()->format('d/m/Y'));
         	$this->assertEquals($mileage->miles, 104.5);

         	$this->assertEquals($invoice->invoiceMileage->value, number_format((54.5 * config('jcp.mileage.rate_above_threshold')) + (50 * config('jcp.mileage.rate_below_threshold')), 2)); // 36.13
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
	    $activity2->addJockey($jockey);

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
	Potential issue when invoice is submitted and we add the invoice miles to the coach current Mileage, what happens if the
	invoice has mileage from last year also.

	So, need to work out if all mileage from current year, and if not split between this yea and last year by mileage_date.

 */


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
	-date (maybe only need this if free text (not assigned to activity or RE))
	-miles

	On adding Mileage to invoice
	 - if not already exists an invoiceMileage is created for that invoice
	 - else the invoiceMileage is retrieved
	 - the Mileage row is inserted into the Mileage table and linked to the invoiceMileage.
	 - on creating Mileage the InvoiceMileage value is calculated, this is the accumalated sum of all the Mileage attached and will work using the correct rates using the calculateMileageValue function below.
	 - this way the rate is worked out on all the attached mileage and not indivdually. So if mileage is added, amended or removed, we just recalculate the total value again using the calculateMileageValue function.
	 - when Admin marks an invoice as paid, the mileage for that invoice is added to the coach->mileage (coach's total mileage for year)

	 - the invoice Total is then the sum of all the InvoiceLines (activities, RE, misc) plus the invoiceMileage value.

	function calculateMileageValue() {
		$currentCoachMileage = $coach->mileage;
		$numOfInvoiceMiles = $invoiceMileage->mileage->sum('miles');
		$total = $currentCoachMileage + $numOfInvoiceMiles;
		
		$milesAbove = $total - 10000;
		$milesUnder = $numOfInvoiceMiles - $milesAbove;

		return ($milesAbove * 10000Rate) + ($milesUnder * below10000Rate);
	}

	TESTS
	- an activity that has an invoice line cannot be edited by a coach

	- an RE that has an invoice line cannot be edited (location, time, divisions) by a coach - can still add/amends results

	- invoice shows jockeys overall training time and shows/highlights when a Jockey has gone over their training allowance -
	    - Jockeys in first 3 months from start of license have 6 hours per invoicing period
	    - After 3 months reduces to 4 hours

	- can submit an invoice between 1st and 10th
	- cannot submit at other times

	- can only submit one invoice per month
	- once submitted new items cannot be added to a new invoice until the 11th

	- the mileage date must be within last 2 months - add validation

	**Queries
		--if Jockey doesn't submit for a month then submits a combined two month invoice, what happens with the allocated time? As jockey is allowed 4 hours, but we're showing 2 months/invoice periods.
		Do we need to show Jockeys training time per month for previous two months on each invoice?

		--how does a Admin edit an activity thats on the invoice?
		-- At what point does editing the activity become locked - for Coach and for Admin?
		-- Do we make it that when an activity is edited, we check if their is an invoice line for it and update the value/price if needed? That way an Admin can edit the activity when its on the invoice.
		--Would an Admin need to edit a Racing Excellence thats on an invoice?
		--Do we not have an edit button, but have it so an Admin can click through on the invoice to the Activity's edit page. The only issue is that they can't get back to the invoice easily from the edit.

		--when does a Coachs mileage reset? As if it resets at the start of the year the December invoice
		 hasn't been submitted yet, so the mileage rates won't work.

		-- Can we have a date by which the admin will have processed the invoices? I.e. the 14th

		--In the admin invoice email is says 'The admin is unable to view/edit/delete (if a coach has created it) the current invoice (May)'. 
			- We're not currently storing who created the invoice.
			- Why can't an admin do these things for an unsubmitted invoice, could there not be a case where a coach is away, and they have contacted the admin via phone to say they want to submit the invoice?

		--The way we are doing it now Mileage is added to the invoice, when its removed it is deleted. There isn't a list of unattached (to invoice) Mileage that can be added (says this in the email).


		function calculateMileageValue() {

			$currentMileageForCurrentYear = optional($coach->currentMileage)->miles;

			if no $coach->currentMileage then create new CoachMileage and set the miles to 0.

			// Get all mileage claims that are for the current year
				$currentYearInvoiceMiles = $invoiceMileage->mileage->whereYear('date', '=', $currentYear);
				$totalForCurrentYear = $currentMileageForCurrentYear + $currentYearInvoiceMiles;
				
				$valueForCurrentYear = getMileageValue($totalForCurrentYear, $currentYearInvoiceMiles);


			// if $currentYearInvoiceMiles->count() === the count of all invoice mileage - then all mileage is for the current year
			//	so just return $valueForCurrentYear
			

			// else we have some mileage from last year
				$lastYear = $currentYear - 1;
				$mileageForLastYear = $coach->mileageForYear($mileageForLastYear);
				$previousYearInvoiceMiles = $invoiceMileage->mileage->whereYear('date', '=', $lastYear);
				$totalForPreviousYear = $mileageForLastYear + $previousYearInvoiceMiles;

				$valueForLastYear = getMileageValue($totalForPreviousYear, $previousYearInvoiceMiles);

				return $valueForCurrentYear + $valueForLastYear;
		}

		function getMileageValue($total, $newMiles) {
			$milesAbove = $total - 10000; // get 10000 from jcp.mileage.threshold
			$milesUnder = $newMiles - $milesAbove;

			return ($milesAbove * jcp.mileage.rate_above_threshold) + ($milesUnder * jcp.mileage.rate_below_threshold);
		}

 */