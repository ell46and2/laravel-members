<?php

namespace Tests\Unit;

use App\Models\SeriesType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeriesTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function can_pass_the_place_to_the_series_and_will_return_the_points_awarded_for_that_place()
	{
	    $series = SeriesType::find(1);

	    $this->assertEquals(5, $series->pointsForPlace(1));
	    $this->assertEquals(3, $series->pointsForPlace(2));
	    $this->assertEquals(2, $series->pointsForPlace(3));
	    $this->assertEquals(1, $series->pointsForPlace(4));
	    $this->assertEquals(0, $series->pointsForPlace(5));
	    $this->assertEquals(0, $series->pointsForPlace(6));
	}

	/** @test */
	public function points_awarded_different_for_salisbury()
	{
	    $series = SeriesType::where('name', 'Salisbury series')->first();

	    $this->assertEquals(10, $series->pointsForPlace(1));
	    $this->assertEquals(6, $series->pointsForPlace(2));
	    $this->assertEquals(4, $series->pointsForPlace(3));
	    $this->assertEquals(3, $series->pointsForPlace(4));
	    $this->assertEquals(2, $series->pointsForPlace(5));
	    $this->assertEquals(1, $series->pointsForPlace(6));
	    $this->assertEquals(0, $series->pointsForPlace(7));
	    $this->assertEquals(0, $series->pointsForPlace(8));
	}
}