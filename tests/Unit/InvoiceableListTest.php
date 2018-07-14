<?php

namespace Tests\Unit;


use App\Models\Activity;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceableListTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function inSubmitInvoicePeriod_returns_true_if_between_1st_and_10th()
	{
	    Carbon::setTestNow(Carbon::parse('first day of March 2018'));

	    $this->assertTrue(Invoice::inSubmitInvoicePeriod());

	    Carbon::setTestNow(Carbon::parse('2018-03-10'));

	    $this->assertTrue(Invoice::inSubmitInvoicePeriod());

	    Carbon::setTestNow(Carbon::parse('2018-03-11'));

	    $this->assertFalse(Invoice::inSubmitInvoicePeriod());

	    Carbon::setTestNow(Carbon::parse('2018-02-28'));

	    $this->assertFalse(Invoice::inSubmitInvoicePeriod());
	}

	/** @test */
	public function shows_activities_and_racing_excellence_that_belong_to_the_coach()
	{
	    $coach = factory(Coach::class)->create();
	    $this->be($coach);

	    $activity = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(10),
			'duration' => 30,
	        'end' => Carbon::now()->subDays(10),
	    ]);

	    $racingExcellence = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(30),
	    ]);

	    $this->assertEquals(2, $coach->invoiceableList()->count());
	    $this->assertTrue($coach->invoiceableList()->first()->is($racingExcellence));
	    $this->assertTrue($coach->invoiceableList()->last()->is($activity));
	}

	/** @test */
	public function invoiceables_must_be_within_last_two_months()
	{
	    $coach = factory(Coach::class)->create();
	    $this->be($coach);

	    $activity = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(10),
			'duration' => 30,
	        'end' => Carbon::now()->subDays(10),
	    ]);

	    $activityOld = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(70),
			'duration' => 30,
	        'end' => Carbon::now()->subDays(70),
	    ]);

	    $activityFuture = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->addMinutes(1),
			'duration' => 30,
	        'end' => Carbon::now()->addMinutes(1),
	    ]);

	    $racingExcellence = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(30),
	    ]);

	    $racingExcellenceOld = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(70),
	    ]);

	    $racingExcellenceFuture = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->addMinutes(1),
	    ]);

	    $this->assertEquals(2, $coach->invoiceableList()->count());
	    $this->assertTrue($coach->invoiceableList()->first()->is($racingExcellence));
	    $this->assertTrue($coach->invoiceableList()->last()->is($activity));
	}

	/** @test */
	public function invoiceables_must_be_within_last_four_months_if_admin_user()
	{
		$coach = factory(Coach::class)->create();
	    $admin = factory(Admin::class)->create();

	    $this->be($admin);

	    $activity = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subMonths(3),
			'duration' => 30,
	        'end' => Carbon::now()->subMonths(3),
	    ]);

	    $activityOld = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(200),
			'duration' => 30,
	        'end' => Carbon::now()->subDays(200),
	    ]);

	    $activityFuture = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->addMinutes(1),
			'duration' => 30,
	        'end' => Carbon::now()->addMinutes(1),
	    ]);

	    $racingExcellence = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subMonths(3),
	    ]);

	    $racingExcellenceOld = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(170),
	    ]);

	    $racingExcellenceFuture = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->addMinutes(1),
	    ]);

	    $this->assertEquals(2, $coach->invoiceableList()->count());
	    $this->assertTrue($coach->invoiceableList()->last()->is($racingExcellence));
	    $this->assertTrue($coach->invoiceableList()->first()->is($activity));
	}

	/** @test */
	public function invoiceables_must_belong_to_the_coach()
	{
	    $coach = factory(Coach::class)->create();
	    $this->be($coach);
	    $otherCoach = factory(Coach::class)->create();

	    $activity = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(10),
			'duration' => 30,
	        'end' => Carbon::now()->subDays(10),
	    ]);

	    $activityForOther = factory(Activity::class)->create([
	    	'coach_id' => $otherCoach->id,
	    	'start' => Carbon::now()->subDays(10),
			'duration' => 30,
	        'end' => Carbon::now()->subDays(10),
	    ]);

	    $racingExcellence = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(30),
	    ]);

	    $racingExcellenceForOther = factory(RacingExcellence::class)->create([
	    	'coach_id' => $otherCoach->id,
	    	'start' => Carbon::now()->subDays(30),
	    ]);

	    $this->assertEquals(2, $coach->invoiceableList()->count());
	    $this->assertTrue($coach->invoiceableList()->first()->is($racingExcellence));
	    $this->assertTrue($coach->invoiceableList()->last()->is($activity));
	}

	/** @test */
	public function invoiceable_must_not_already_have_an_invoice_line()
	{
	    $coach = factory(Coach::class)->create();
	    $jockey = factory(Jockey::class)->create();

	    $this->be($coach);

	    $activity = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(10),
			'duration' => 30,
	        'end' => Carbon::now()->subDays(10),
	    ]);
	    $activity->addJockey($jockey);

	    $activityAlreadyInvoiced = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(10),
			'duration' => 30,
	        'end' => Carbon::now()->subDays(10),
	    ]);
	    $activityAlreadyInvoiced->addJockey($jockey);

	    $activityInvoiceLine = factory(InvoiceLine::class)->create([
	    	'invoiceable_id' => $activityAlreadyInvoiced->id,
	    	'invoiceable_type' => 'activity'
	    ]);

	    $racingExcellence = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(30),
	    ]);

	    $racingAlreadyInvoiced = factory(RacingExcellence::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(30),
	    ]);

	    $racingExcellenceInvoiceLine = factory(InvoiceLine::class)->create([
	    	'invoiceable_id' => $racingAlreadyInvoiced->id,
	    	'invoiceable_type' => 'racing-excellence'
	    ]);

	    $this->assertEquals(2, $coach->invoiceableList()->count());
	    $this->assertTrue($coach->invoiceableList()->first()->is($racingExcellence));
	    $this->assertTrue($coach->invoiceableList()->last()->is($activity));
	}

	/** @test */
	public function activity_must_have_a_duration()
	{
	    $coach = factory(Coach::class)->create();
	    $this->be($coach);

	    $activity = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(10),
			'duration' => 30,
	        'end' => Carbon::now()->subDays(10),
	    ]);


	    $activityWithoutDuration = factory(Activity::class)->create([
	    	'coach_id' => $coach->id,
	    	'start' => Carbon::now()->subDays(10),
			'duration' => null,
	        'end' => null,
	    ]);

	    $this->assertEquals(1, $coach->invoiceableList()->count());
	    $this->assertTrue($coach->invoiceableList()->first()->is($activity));
	}

}