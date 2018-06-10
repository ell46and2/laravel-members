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

class DocumentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_admin_can_upload_a_new_document()
    {
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$response = $this->actingAs($admin)->post("/admin/documents", [
    		'title' => 'Document title',
    		'document' => UploadedFile::fake()->create('file to upload.pdf', 100) // 100 is size in kb
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