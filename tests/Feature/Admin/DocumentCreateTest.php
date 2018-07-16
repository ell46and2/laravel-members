<?php

namespace Tests\Feature\Admin;

use App\Jobs\EditedDocumentNotify;
use App\Jobs\UploadDocument;
use App\Jobs\UploadDocumentNotify;
use App\Models\Admin;
use App\Models\Document;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DocumentCreateTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_admin_can_upload_a_new_document()
    {
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$response = $this->actingAs($admin)->post("/admin/documents", [
    		'title' => 'Document title',
    		'document' => UploadedFile::fake()->create('file to upload.pdf', 100), // 100 is size in kb
            'description' => 'The description'
    	]);

    	tap(Document::first(), function($document) use ($response) {
    		$response->assertStatus(302);
        	$response->assertRedirect('/documents');

    		$this->assertEquals($document->title, 'Document title');
            $this->assertEquals($document->description, 'The description');

            Queue::assertPushed(UploadDocument::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id &&
                	$job->fileName == 'filetoupload.pdf';
            });

            // Do we need to notify all coaches and jockeys
            // Best to queue a job and use chunk to create all those notifications.
            Queue::assertPushed(UploadDocumentNotify::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id;
            });
        });

    }

    /** @test */
    public function document_title_is_require()
    {
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$response = $this->actingAs($admin)->from('/admin/documents/create')->post("/admin/documents", [
    		'title' => '',
    		'document' => UploadedFile::fake()->create('file to upload.pdf', 100) // 100 is size in kb
    	]);

    	$response->assertStatus(302);
    	$response->assertRedirect('/admin/documents/create');
        $response->assertSessionHasErrors('title');
        $this->assertEquals(0, Document::count());
        Queue::assertNotPushed(UploadDocument::class);
        Queue::assertNotPushed(UploadDocumentNotify::class);
    }

    /** @test */
    public function document_description_is_optional()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();

        $response = $this->actingAs($admin)->post("/admin/documents", [
            'title' => 'Document title',
            'document' => UploadedFile::fake()->create('file to upload.pdf', 100), // 100 is size in kb
            'description' => ''
        ]);

        tap(Document::first(), function($document) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect('/documents');

            $this->assertEquals($document->title, 'Document title');
            $this->assertEquals($document->description, null);

            Queue::assertPushed(UploadDocument::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id &&
                    $job->fileName == 'filetoupload.pdf';
            });

            // Do we need to notify all coaches and jockeys
            // Best to queue a job and use chunk to create all those notifications.
            Queue::assertPushed(UploadDocumentNotify::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id;
            });
        });  
    }

    /** @test */
    public function a_document_must_be_uploaded()
    {
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$response = $this->actingAs($admin)->from('/admin/documents/create')->post("/admin/documents", [
    		'title' => 'Document title',
    		'document' => ''
    	]);

    	$response->assertStatus(302);
    	$response->assertRedirect('/admin/documents/create');
        $response->assertSessionHasErrors('document');
        $this->assertEquals(0, Document::count());
        Queue::assertNotPushed(UploadDocument::class);
        Queue::assertNotPushed(UploadDocumentNotify::class);
    }

    /** @test */
    public function a_document_must_be_of_type_pdf_or_word_doc()
    {
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$response = $this->actingAs($admin)->from('/admin/documents/create')->post("/admin/documents", [
    		'title' => 'Document title',
    		'document' => UploadedFile::fake()->create('image.jpg', 100) // 100 is size in kb
    	]);

		$response->assertStatus(302);
    	$response->assertRedirect('/admin/documents/create');
        $response->assertSessionHasErrors('document');
        $this->assertEquals(0, Document::count());
        Queue::assertNotPushed(UploadDocument::class);
        Queue::assertNotPushed(UploadDocumentNotify::class);
    }

    /** @test */
    public function a_document_can_be_of_type_word_doc()
    {
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$response = $this->actingAs($admin)->post("/admin/documents", [
    		'title' => 'Document title',
    		'document' => UploadedFile::fake()->create('worddoc.docx', 100) // 100 is size in kb
    	]);

    	tap(Document::first(), function($document) use ($response) {
    		$response->assertStatus(302);
        	$response->assertRedirect('/documents');

    		$this->assertEquals($document->title, 'Document title');

            Queue::assertPushed(UploadDocument::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id &&
                	$job->fileName == 'worddoc.docx';
            });

            // Do we need to notify all coaches and jockeys
            // Best to queue a job and use chunk to create all those notifications.
            Queue::assertPushed(UploadDocumentNotify::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id;
            });
        });
    }

    /** @test */
    public function document_size_must_be_a_max_of_5mb()
    {
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$response = $this->actingAs($admin)->post("/admin/documents", [
    		'title' => 'Document title',
    		'document' => UploadedFile::fake()->create('file to upload.pdf', 4500) // 4.5mb
    	]);

    	tap(Document::first(), function($document) use ($response) {
    		$response->assertStatus(302);
        	$response->assertRedirect('/documents');

    		$this->assertEquals($document->title, 'Document title');

            Queue::assertPushed(UploadDocument::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id &&
                	$job->fileName == 'filetoupload.pdf';
            });

            // Do we need to notify all coaches and jockeys
            // Best to queue a job and use chunk to create all those notifications.
            Queue::assertPushed(UploadDocumentNotify::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id;
            });
        });
    }

    /** @test */
    public function document_cannot_be_over_5mb()
    {
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$response = $this->actingAs($admin)->from('/admin/documents/create')->post("/admin/documents", [
    		'title' => 'Document title',
    		UploadedFile::fake()->create('file to upload.pdf', 6000) // 6mb
    	]);

		$response->assertStatus(302);
    	$response->assertRedirect('/admin/documents/create');
        $response->assertSessionHasErrors('document');
        $this->assertEquals(0, Document::count());
        Queue::assertNotPushed(UploadDocument::class);
        Queue::assertNotPushed(UploadDocumentNotify::class);
    }

    /** @test */
    public function an_admin_can_edit_a_documents_title()
    {
    	Queue::fake();
    	
    	$admin = factory(Admin::class)->create();

    	$document = factory(Document::class)->create([
			'title' => 'Document title',
    	]);

		$response = $this->actingAs($admin)->put("/admin/documents/{$document->id}", [
    		'title' => 'New title',
    	]);

    	tap($document->fresh(), function($document) use ($response) {
    		$response->assertStatus(302);
        	$response->assertRedirect('/documents');

    		$this->assertEquals($document->title, 'New title');

    		Queue::assertPushed(EditedDocumentNotify::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id;
            });
    	});   	
    }

    /** @test */
    public function when_editing_if_a_file_is_uploaded_the_document_is_replaced_with_the_new_file()
    {	
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$document = factory(Document::class)->create([
			'title' => 'Document title',
    	]);

		$response = $this->actingAs($admin)->put("/admin/documents/{$document->id}", [
    		'title' => 'New title',
    		'document' => UploadedFile::fake()->create('file to upload.pdf', 100) // 100 is size in kb
    	]);

    	tap($document->fresh(), function($document) use ($response) {
    		$response->assertStatus(302);
        	$response->assertRedirect('/documents');

    		$this->assertEquals($document->title, 'New title');

    		Queue::assertPushed(UploadDocument::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id &&
                	$job->fileName == 'filetoupload.pdf';
            });

            Queue::assertPushed(EditedDocumentNotify::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id;
            });
    	});   	
    }

    /** @test */
    public function an_admin_can_delete_a_document()
    {
    	$admin = factory(Admin::class)->create();

    	$document = factory(Document::class)->create();

    	$this->assertEquals(Document::all()->count(), 1);

    	$response = $this->actingAs($admin)->delete("/admin/documents/{$document->id}");

    	$this->assertEquals(Document::all()->count(), 0);
    }
}