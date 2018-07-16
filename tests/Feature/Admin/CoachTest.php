<?php

namespace Tests\Feature\Admin;

use App\Mail\Coach\Account\CoachCreatedEmail;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CoachTest extends TestCase
{
    use DatabaseMigrations;


    private function validParams($overrides = [])
    {
        return array_merge([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'middle_name' => 'Lee',
            'date_of_birth' => '11/06/1980',
            'gender' => 'female',   
            'address_1' => '123 street',
            'address_2' => 'Cheltenham',
            'county_id' => 1,
            'country_id' => 1,
            'nationality_id' => 1,
            'postcode' => 'GL50 1ST',
            'telephone' => '01242 222333',
            'twitter_handle' => 'jdoe',
            'email' => 'jane@example.com',
            'mileage' => 1000,
            'vat_number' => '12345678A',
        ], $overrides);
    }

    /** @test */
    public function an_admin_can_create_a_coach_user()
    {
    	Mail::fake();

     	$admin = factory(Admin::class)->create();

     	$response = $this->actingAs($admin)->post("/admin/coaches", [
        	'first_name' => 'Jane',
            'last_name' => 'Doe',
            'middle_name' => 'Lee',
            'date_of_birth' => '11/06/1980',
            'gender' => 'female',   
            'address_1' => '123 street',
            'address_2' => 'Cheltenham',
            'county_id' => 1,
            'country_id' => 1,
            'nationality_id' => 1,
            'postcode' => 'GL50 1ST',
            'telephone' => '01242 222333',
            'twitter_handle' => 'jdoe',
            'email' => 'jane@example.com',
            'mileage' => 1000,
            'vat_number' => '12345678A',
        ]);

        tap(Coach::first(), function($coach) use ($response) {
        	$response->assertStatus(302);
        	$response->assertRedirect("/admin/coaches/{$coach->id}");

        	$this->assertEquals('Jane', $coach->first_name);
        	$this->assertEquals('Doe', $coach->last_name);
            $this->assertEquals('Lee', $coach->middle_name);
            $this->assertEquals('11/06/1980', $coach->date_of_birth->format('d/m/Y'));
        	$this->assertEquals('123 street', $coach->address_1);
        	$this->assertEquals('Cheltenham', $coach->address_2);
            $this->assertEquals(1, $coach->county_id);
            $this->assertEquals(1, $coach->country_id);
            $this->assertEquals(1, $coach->nationality_id);
        	$this->assertEquals('GL50 1ST', $coach->postcode);
            $this->assertEquals('01242 222333', $coach->telephone);
            $this->assertEquals('jdoe', $coach->twitter_handle);
        	$this->assertEquals('jane@example.com', $coach->email);
            $this->assertEquals('12345678A', $coach->vat_number);
            $this->assertNotNull($coach->access_token);

            $this->assertEquals(1000, $coach->currentMileage->miles);

        	$this->assertTrue($coach->isCoach());
        	$this->assertTrue($coach->approved);

        	Mail::assertQueued(CoachCreatedEmail::class, 1);
        	Mail::assertQueued(CoachCreatedEmail::class, function($mail) use ($coach) {
        		return $mail->coach->id == $coach->id &&
        		$mail->hasTo($coach->email);
        	});
        });
    }

    /** @test */
    public function first_name_is_required()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'first_name' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('first_name');
        $this->assertEquals(0, Coach::count());
    }

    /** @test */
    public function last_name_is_required()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'last_name' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('last_name');
        $this->assertEquals(0, Coach::count());  
    }

    /** @test */
    public function middle_name_is_optional()
    {
        Mail::fake();

        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'middle_name' => ''
        ]));

        tap(Coach::firstOrFail(), function($coach) use ($response, $admin) {
            $response->assertStatus(302);
            $response->assertRedirect("/admin/coaches/{$coach->id}");

            $this->assertNull($coach->middle_name);
        }); 
    }

    /** @test */
    public function date_of_birth_is_optional()
    {
        Mail::fake();

        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'date_of_birth' => ''
        ]));

        tap(Coach::firstOrFail(), function($coach) use ($response, $admin) {
            $response->assertStatus(302);
            $response->assertRedirect("/admin/coaches/{$coach->id}");

            $this->assertNull($coach->date_of_birth);
        });    
    }

    /** @test */
    public function gender_is_required()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'gender' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('gender');
        $this->assertEquals(0, Coach::count());    
    }

    /** @test */
    public function address_1_is_required()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'address_1' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('address_1');
        $this->assertEquals(0, Coach::count());       
    }

    /** @test */
    public function address_2_is_optional()
    {
        Mail::fake();

        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'address_2' => ''
        ]));

        tap(Coach::firstOrFail(), function($coach) use ($response, $admin) {
            $response->assertStatus(302);
            $response->assertRedirect("/admin/coaches/{$coach->id}");

            $this->assertNull($coach->address_2);
        });      
    }

    /** @test */
    public function county_is_required()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'county_id' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('county_id');
        $this->assertEquals(0, Coach::count());   
    }

    /** @test */
    public function country_is_required()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'country_id' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('country_id');
        $this->assertEquals(0, Coach::count());   
    }

    /** @test */
    public function nationality_is_optional()
    {
        Mail::fake();

        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'nationality_id' => ''
        ]));

        tap(Coach::firstOrFail(), function($coach) use ($response, $admin) {
            $response->assertStatus(302);
            $response->assertRedirect("/admin/coaches/{$coach->id}");

            $this->assertNull($coach->nationality_id);
        });       
    }

    /** @test */
    public function postcode_is_required()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'postcode' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('postcode');
        $this->assertEquals(0, Coach::count());  
    }

    /** @test */
    public function telephone_is_required()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'telephone' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('telephone');
        $this->assertEquals(0, Coach::count());  
    }

    /** @test */
    public function twitter_handle_is_optional()
    {
        Mail::fake();

        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'twitter_handle' => ''
        ]));

        tap(Coach::firstOrFail(), function($coach) use ($response, $admin) {
            $response->assertStatus(302);
            $response->assertRedirect("/admin/coaches/{$coach->id}");

            $this->assertNull($coach->twitter_handle);
        });  
    }

    /** @test */
    public function email_is_required()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'email' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, Coach::count());  
    }

    /** @test */
    public function email_must_be_a_valid_email_address()
    {
        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'email' => 'not_an_email_address'
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/admin/coaches/create');
        $response->assertSessionHasErrors('email');
        $this->assertEquals(0, Coach::count());  
    }

    /** @test */
    public function mileage_is_optional_and_will_default_to_zero()
    {
        Mail::fake();

        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'mileage' => ''
        ]));

        tap(Coach::firstOrFail(), function($coach) use ($response, $admin) {
            $response->assertStatus(302);
            $response->assertRedirect("/admin/coaches/{$coach->id}");

            $this->assertEquals(0, $coach->mileage);
        });     
    }

    /** @test */
    public function vat_number_is_optional()
    {
        Mail::fake();

        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->from('/admin/coaches/create')->post('/admin/coaches', $this->validParams([
            'vat_number' => ''
        ]));

        tap(Coach::firstOrFail(), function($coach) use ($response, $admin) {
            $response->assertStatus(302);
            $response->assertRedirect("/admin/coaches/{$coach->id}");

            $this->assertNull($coach->vat_number);
        });    
    }
}