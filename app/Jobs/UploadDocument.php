<?php

namespace App\Jobs;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $document;
    public $fileName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Document $document, $fileName)
    {
        $this->document = $document;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         // get the image
        $path = storage_path() . '/uploads/' . $this->fileName;
        // $fileName = $this->fileId . '.jpg';

        // dd($path);

        // resize
        // Image::make($path)->encode('jpg')->fit(100, 100, function($c) {
        //     $c->upsize();
        // })->save();

        // upload to S3
        if(Storage::disk('s3_documents')->put($this->fileName, fopen($path, 'r+'))) { 
            File::delete(storage_path('/uploads/' . $this->fileName)); // delete local temp file
        }

        // update user with avatar_path
        $this->document->document_filename = $this->fileName;
        $this->document->save();   
    }
}
