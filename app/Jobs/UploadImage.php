<?php

namespace App\Jobs;

use App\Models\Attachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $attachment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = storage_path() . '/uploads/' . $this->attachment->filename;
        $fileName = $this->attachment->uid . '.jpg';
        $thumbnail = 'thumb_' . $fileName;
        $thumbnailPath = storage_path() . '/uploads/' . $thumbnail;
        // resize
        
        // Create thumbnail
        Image::make($path)->encode('jpg')->fit(200, 200, function($c) {
            $c->upsize();
        })->save($thumbnailPath);

        // Resize Original image
        Image::make($path)->encode('jpg')->resize(1000, 800, function($c) {
            $c->aspectRatio();
        })->save();

        // upload to S3
        if(Storage::disk('s3_images')->put('images/' . $fileName, fopen($path, 'r+')) &&
           Storage::disk('s3_images')->put('images/' . $thumbnail, fopen($thumbnailPath, 'r+')) ) { 
            File::delete($path); // delete local temp file
            File::delete($thumbnailPath);
        }

        $this->attachment->filename = $fileName;
        $this->attachment->processed = true;
        $this->attachment->save();

    }
}
