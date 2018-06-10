<?php

namespace Tests\Feature\Jockey;

use App\Models\Jockey;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_jockey_can_view_their_next_upcoming_activities_with_a_max_of_10()
    {
        // with id, type, date, time, location
        
    }

    /** @test */
    public function a_jockey_will_see_an_appropriate_message_if_no_upcoming_activities()
    {

    }

    /** @test */
    public function a_jockey_can_view_their_recent_activities_with_a_max_of_10()
    {
        // with id, type, date, time, location
    }

    /** @test */
    public function a_jockey_will_see_an_appropriate_message_if_no_recent_activities()
    {
       
    }

    /** @test */
    public function can_see_total_number_of_hours_training_in_current_month()  // check its current month
    {

    }

    /** @test */
    public function can_see_total_number_of_race_wins() // Data from racing post API
    {
            
    }

    /** @test */
    public function can_see_total_number_of_races() // Data from racing post API
    {
            
    }

    /** @test */
    public function can_see_a_list_of_their_coaches_with_the_total_number_of_hours_of coaching_with_that_coach_in_current_month()
    {
            
    }

    /** @test */
    public function if_not_coaches_currently_assigned_will_see_appropriate_message()
    {
            
    }

    /** @test */
    public function can_view_their_unread_notifications()
    {
            
    }

    /** @test */
    public function can_see_a_badge_with_the_number_of_unread_notifications()
    {
            
    }

    // Need to add Competency Assessment - graph ?
    
    // Need to add PDP - is it just a stock image?
}