<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
	use DatabaseMigrations;


    /** @test */
    public function can_get_the_admin_user()
    {
    	factory(User::class, 10)->create();

       	$adminUser = factory(User::class)->states('admin')->create();

       	$this->assertEquals($adminUser->id, User::admin()->id);
    }

    /** @test */
    public function can_get_users_full_name()
    {
        $user = factory(User::class)->create([
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        ]);

        $this->assertEquals($user->fullName(), 'Jane Doe');	
    }

    /** @test */
    public function can_get_jockeys_coaches()
    {
    	$jockey = factory(User::class)->create([
    		'first_name' => 'Ell'
    	]);

    	$coach1 = factory(User::class)->states('coach')->create();
    	$coach2 = factory(User::class)->states('coach')->create();
    	$coach3 = factory(User::class)->states('coach')->create();
    	$notJockeysCoach = factory(User::class)->states('coach')->create();
    	$adminUser = factory(User::class)->states('admin')->create();

    	$this->assertEquals($jockey->coaches()->count(), 0);

    	$coach1->assignJockey($jockey);
    	$coach2->assignJockey($jockey);
    	$coach3->assignJockey($jockey);

    	$this->assertEquals($jockey->coaches()->count(), 3);

    	$coaches = $jockey->coaches;

    	$coaches->assertContains($coach1);
    	$coaches->assertContains($coach2);
    	$coaches->assertContains($coach3);

    	$coaches->assertNotContains($notJockeysCoach);
    	$coaches->assertNotContains($adminUser);	
    }

    /** @test */
    public function can_get_coaches_jockeys()
    {
        $coach = factory(User::class)->states('coach')->create();

       	$jockey1 = factory(User::class)->create();
       	$jockey2 = factory(User::class)->create();
       	$jockey3 = factory(User::class)->create();
       	$notCoachesJockey = factory(User::class)->create();

       	$this->assertEquals($coach->jockeys()->count(), 0);

       	$coach->assignJockey($jockey1);
       	$coach->assignJockey($jockey2);
       	$coach->assignJockey($jockey3);

       	$this->assertEquals($coach->jockeys()->count(), 3);

       	$jockeys = $coach->jockeys;

       	$jockeys->assertContains($jockey1);
       	$jockeys->assertContains($jockey2);
       	$jockeys->assertContains($jockey3);

       	$jockeys->assertNotContains($notCoachesJockey);
    }
}
