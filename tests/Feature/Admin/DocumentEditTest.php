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

class DocumentEditTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    public function when_editing_if_a_file_is_uploaded_the_document_is_replaced_with_the_new_file()
    {	
    	Queue::fake();

    	$admin = factory(Admin::class)->create();

    	$document = factory(Document::class)->create([
			'title' => 'Document title',
            'description' => 'The description'
    	]);

		$response = $this->actingAs($admin)->put("/admin/documents/{$document->id}", [
    		'title' => 'New title',
    		'document' => UploadedFile::fake()->create('file to upload.pdf', 100), // 100 is size in kb
            'description' => 'New description'
    	]);

    	tap($document->fresh(), function($document) use ($response) {
    		$response->assertStatus(302);
        	$response->assertRedirect('/documents');

    		$this->assertEquals($document->title, 'New title');
            $this->assertEquals($document->description, 'New description');

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
    public function document_title_is_require()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();

        $document = factory(Document::class)->create([
            'title' => 'Document title',
        ]);

        $response = $this->actingAs($admin)->from('/admin/documents/edit')->put("/admin/documents/{$document->id}", [
            'title' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/documents/edit');
        $response->assertSessionHasErrors('title');
        Queue::assertNotPushed(UploadDocument::class);
        Queue::assertNotPushed(EditedDocumentNotify::class);
    }

    /** @test */
    public function description_is_optional()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();

        $document = factory(Document::class)->create([
            'title' => 'Document title',
            'description' => 'The description'
        ]);

        $response = $this->actingAs($admin)->put("/admin/documents/{$document->id}", [
            'title' => 'New title',
            'document' => UploadedFile::fake()->create('file to upload.pdf', 100), // 100 is size in kb
            'description' => ''
        ]);

        tap($document->fresh(), function($document) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect('/documents');

            $this->assertEquals($document->title, 'New title');
            $this->assertEquals($document->description, '');

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
    public function a_document_must_be_of_type_pdf_or_word_doc()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();

        $document = factory(Document::class)->create([
            'title' => 'Document title',
        ]);

        $response = $this->actingAs($admin)->from('/admin/documents/edit')->put("/admin/documents/{$document->id}", [
            'title' => 'New Document title',
            'document' => UploadedFile::fake()->create('image.jpg', 100) // 100 is size in kb
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/documents/edit');
        $response->assertSessionHasErrors('document');
        $this->assertEquals('Document title', $document->fresh()->title);
        Queue::assertNotPushed(UploadDocument::class);
        Queue::assertNotPushed(UploadDocumentNotify::class);
    }

    /** @test */
    public function a_document_can_be_of_type_word_doc()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();

        $document = factory(Document::class)->create([
            'title' => 'Document title',
        ]);

        $response = $this->actingAs($admin)->from('/admin/documents/edit')->put("/admin/documents/{$document->id}", [
            'title' => 'New title',
            'document' => UploadedFile::fake()->create('worddoc.docx', 100) // 100 is size in kb
        ]);

        tap($document->fresh(), function($document) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect('/documents');

            $this->assertEquals($document->title, 'New title');

            Queue::assertPushed(UploadDocument::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id &&
                    $job->fileName == 'worddoc.docx';
            });

            Queue::assertPushed(EditedDocumentNotify::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id;
            });
        });
    }

    /** @test */
    public function document_size_must_be_a_max_of_5mb()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();

        $document = factory(Document::class)->create([
            'title' => 'Document title',
        ]);

        $response = $this->actingAs($admin)->from('/admin/documents/edit')->put("/admin/documents/{$document->id}", [
            'title' => 'New title',
            'document' => UploadedFile::fake()->create('worddoc.docx', 4500) // 4.5mb
        ]);

        tap($document->fresh(), function($document) use ($response) {
            $response->assertStatus(302);
            $response->assertRedirect('/documents');

            $this->assertEquals($document->title, 'New title');

            Queue::assertPushed(UploadDocument::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id &&
                    $job->fileName == 'worddoc.docx';
            });

            Queue::assertPushed(EditedDocumentNotify::class, function($job) use ($response, $document) {
                return $job->document->id == $document->id;
            });
        });
    }

    /** @test */
    public function document_cannot_be_over_5mb()
    {
        Queue::fake();

        $admin = factory(Admin::class)->create();

        $document = factory(Document::class)->create([
            'title' => 'Document title',
        ]);

        $response = $this->actingAs($admin)->from('/admin/documents/edit')->put("/admin/documents/{$document->id}", [
            'title' => 'New title',
            'document' => UploadedFile::fake()->create('worddoc.docx', 5001) // 5.001 mb
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin/documents/edit');
        $response->assertSessionHasErrors('document');
        Queue::assertNotPushed(UploadDocument::class);
        Queue::assertNotPushed(EditedDocumentNotify::class);
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
    public function an_admin_can_delete_a_document()
    {
    	$admin = factory(Admin::class)->create();

    	$document = factory(Document::class)->create();

    	$this->assertEquals(Document::all()->count(), 1);

    	$response = $this->actingAs($admin)->delete("/admin/documents/{$document->id}");

    	$this->assertEquals(Document::all()->count(), 0);
    }
}