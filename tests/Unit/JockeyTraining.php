<?php

namespace Tests\Unit;

use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JockeyTrainingTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function if_jockey_licence_date_is_before_15_of_the_month_they_get_3_months_of_6hr_training()
	{
		Carbon::setTestNow(Carbon::parse('2018-03-25'));

	    $jockeyA = factory(Jockey::class)->create([
	    	'licence_date' => Carbon::parse('2018-02-02')
	    ]);
	    $this->assertEquals($jockeyA->trainingAllowanceThisMonth(), 6);

	    $jockeyB = factory(Jockey::class)->create([
	    	'licence_date' => Carbon::parse('2017-12-02')
	    ]);
	    $this->assertEquals($jockeyB->trainingAllowanceThisMonth(), 4);
	}

	/** @test */
	public function if_jockey_licence_date_is_after_15_of_the_month_they_get_4_months_of_6hr_training()
	{
	    Carbon::setTestNow(Carbon::parse('2018-03-25'));

	    $jockeyA = factory(Jockey::class)->create([
	    	'licence_date' => Carbon::parse('2018-02-25')
	    ]);
	    $this->assertEquals($jockeyA->trainingAllowanceThisMonth(), 6);	

	    $jockeyB = factory(Jockey::class)->create([
	    	'licence_date' => Carbon::parse('2017-12-25')
	    ]);
	    $this->assertEquals($jockeyB->trainingAllowanceThisMonth(), 6);	

	    $jockeyC = factory(Jockey::class)->create([
	    	'licence_date' => Carbon::parse('2017-11-25')
	    ]);
	    $this->assertEquals($jockeyC->trainingAllowanceThisMonth(), 4);	
	}

	/** @test */
	public function if_no_licence_date_get_4hr_training()
	{
	    Carbon::setTestNow(Carbon::parse('2018-03-25'));

	    $jockey = factory(Jockey::class)->create([
	    	'licence_date' => null
	    ]);

	    $this->assertEquals($jockey->trainingAllowanceThisMonth(), 4);
	}
}
