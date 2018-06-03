<?php

namespace Tests\Feature\Jockey;

use App\Jobs\UploadAvatarImage;
use App\Models\Jockey;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'middle_name' => 'New middle name',
            'alias' => 'New alias',
            'address_1' => 'New address',
            'address_2' => 'New city',
            'county' => 'New county',
            'country' => 'New country',
            'postcode' => 'New post',
            'telephone' => '0000 111111',
            'twitter_handle' => 'newtwitterhandle',
            'email' => 'newemail@example.com',
        ], $overrides);
    }

    private function oldAttributes($overrides = [])
    {
        return array_merge([
            'middle_name' => 'Lee',
            'alias' => 'Janey',
            'address_1' => '123 street',
            'address_2' => 'Cheltenham',
            'county' => 'Gloucestershire',
            'country' => 'UK',
            'postcode' => 'GL50 1ST',
            'telephone' => '01242 222333',
            'twitter_handle' => 'jdoe',
            'email' => 'jane@example.com',
        ], $overrides);
    }

    /** @test */
    public function a_jockey_can_view_their_account_profile()
    {
    	factory(User::class, 10)->create();
    	
        $jockey = factory(Jockey::class)->states('approved')->create([
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        	'telephone' => '01242 222333',	
        	'address_1' => '123 street',
        	'address_2' => 'Cheltenham',
        	'postcode' => 'GL50 1ST',
        	'email' => 'jane@example.com',
        ]);

        $response = $this->actingAs($jockey)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('jockey.profile.index');
        $this->assertTrue($response->data('jockey')->is($jockey));
    }

    /** @test */
    public function a_jockey_can_view_the_edit_form_for_their_profile()
    {
        $jockey = factory(Jockey::class)->states('approved')->create([
        	'first_name' => 'Jane',
        	'last_name' => 'Doe',
        	'telephone' => '01242 222333',	
        	'address_1' => '123 street',
        	'address_2' => 'Cheltenham',
        	'postcode' => 'GL50 1ST',
        	'email' => 'jane@example.com',
        ]);

        $response = $this->actingAs($jockey)->get('/profile/edit');

        $response->assertStatus(200);
        $response->assertViewIs('jockey.profile.edit');
        $this->assertTrue($response->data('jockey')->is($jockey));
    }

    /** @test */
    public function a_jockey_can_edit_their_own_profile()
    {
        $jockey = factory(Jockey::class)->states('approved')->create([
        	'middle_name' => 'Lee',
        	'alias' => 'Janey',
        	'address_1' => '123 street',
        	'address_2' => 'Cheltenham',
            'county' => 'Gloucestershire',
            'country' => 'UK',
        	'postcode' => 'GL50 1ST',
            'telephone' => '01242 222333',
            'twitter_handle' => 'jdoe',
        	'email' => 'jane@example.com',
        ]);

        $response = $this->actingAs($jockey)->put("/profile/edit", [
        	'middle_name' => 'New middle name',
            'alias' => 'New alias',
        	'address_1' => 'New address',
        	'address_2' => 'New city',
            'county' => 'New county',
            'country' => 'New country',
        	'postcode' => 'New post',
            'telephone' => '0000 111111',
            'twitter_handle' => 'newtwitterhandle',
        	'email' => 'newemail@example.com',
        ]);


        $response->assertStatus(302);
        $response->assertRedirect('/profile');

        tap($jockey->fresh(), function($jockey) {
        	$this->assertEquals('New middle name', $jockey->middle_name);
		    $this->assertEquals('New alias', $jockey->alias);	    
		    $this->assertEquals('New address', $jockey->address_1);
		    $this->assertEquals('New city', $jockey->address_2);
            $this->assertEquals('New county', $jockey->county);
            $this->assertEquals('New country', $jockey->country);
		    $this->assertEquals('New post', $jockey->postcode);
            $this->assertEquals('0000 111111', $jockey->telephone);
            $this->assertEquals('newtwitterhandle', $jockey->twitter_handle);
		    $this->assertEquals('newemail@example.com', $jockey->email);
        });
    }

    /** @test */
    public function middle_name_is_optional()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'middle_name' => ''
        ]));

        tap($jockey->fresh(), function($jockey) use ($response) {
            $response->assertRedirect('/profile');
            // $response->assertSessionHas('registered', true);

            $this->assertNull($jockey->middle_name);
        });
    }

    /** @test */
    public function alias_is_optional()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'alias' => ''
        ]));

        tap($jockey->fresh(), function($jockey) use ($response) {
            $response->assertRedirect('/profile');
            // $response->assertSessionHas('registered', true);

            $this->assertNull($jockey->alias);
        });
    }

    /** @test */
    public function address_1_is_required()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'address_1' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/profile/edit');
        $response->assertSessionHasErrors('address_1');
        $this->assertArraySubset($this->oldAttributes(), $jockey->fresh()->getAttributes());
    }

    /** @test */
    public function address_2_is_optional()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'address_2' => ''
        ]));

        tap($jockey->fresh(), function($jockey) use ($response) {
            $response->assertRedirect('/profile');
            // $response->assertSessionHas('registered', true);

            $this->assertNull($jockey->address_2);
        });
    }

    /** @test */
    public function county_is_required()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'county' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/profile/edit');
        $response->assertSessionHasErrors('county');
        $this->assertArraySubset($this->oldAttributes(), $jockey->fresh()->getAttributes());
    }

    /** @test */
    public function country_is_required()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'country' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/profile/edit');
        $response->assertSessionHasErrors('country');
        $this->assertArraySubset($this->oldAttributes(), $jockey->fresh()->getAttributes());
    }

    /** @test */
    public function postcode_is_required()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'postcode' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/profile/edit');
        $response->assertSessionHasErrors('postcode');
        $this->assertArraySubset($this->oldAttributes(), $jockey->fresh()->getAttributes());
    }

    /** @test */
    public function telephone_is_required()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'telephone' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/profile/edit');
        $response->assertSessionHasErrors('telephone');
        $this->assertArraySubset($this->oldAttributes(), $jockey->fresh()->getAttributes());
    }

    /** @test */
    public function twitter_handle_is_optional()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'twitter_handle' => ''
        ]));

        tap($jockey->fresh(), function($jockey) use ($response) {
            $response->assertRedirect('/profile');
            // $response->assertSessionHas('registered', true);

            $this->assertNull($jockey->twitter_handle);
        });
    }

    /** @test */
    public function email_is_required()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'email' => ''
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/profile/edit');
        $response->assertSessionHasErrors('email');
        $this->assertArraySubset($this->oldAttributes(), $jockey->fresh()->getAttributes());
    }

    /** @test */
    public function email_must_be_a_valid_email_address()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'email' => 'not-an_email_address'
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/profile/edit');
        $response->assertSessionHasErrors('email');
        $this->assertArraySubset($this->oldAttributes(), $jockey->fresh()->getAttributes());
    }

    /** @test */
    public function email_must_be_unique()
    {
        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $jockey2 = factory(Jockey::class)->create([
            'email' => 'janenew@example.com'
        ]);

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'email' => 'janenew@example.com'
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/profile/edit');
        $response->assertSessionHasErrors('email');
        $this->assertArraySubset($this->oldAttributes(), $jockey->fresh()->getAttributes());
    }

    // add test for uploading avatar
    // should be optional
    // should be queued - then resized and saved to S3
    /** @test */
    public function avatar_image_is_uploaded_if_included()
    {
        // Storage::fake('s3');
        Queue::fake();

        $jockey = factory(Jockey::class)->states('approved')->create($this->oldAttributes());

        $file = File::image('concert-poster.png', 800, 800);

        $response = $this->actingAs($jockey)->from('/profile/edit')->put('/profile/edit', $this->validParams([
            'avatar_image' => $file
        ]));


        tap($jockey->fresh(), function($jockey) use ($response, $file) {
            // $this->expectsJobs(UploadAvatarImage::class);
            Queue::assertPushed(UploadAvatarImage::class, function($job) use ($response, $file, $jockey) {
                return $job->user->id == $jockey->id;
            });
        });
    }
    
    /** @test */
    public function uploaded_avatar_must_be_an_image()
    {

    }

    /** @test */
    public function avatar_image_is_optional()
    {

    }
}