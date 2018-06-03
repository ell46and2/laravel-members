<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadAvatarImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $fileId;

    /**
     * Create a new job instance.
     * Using User so we can reuse the avatar upload for jockeys and coaches.
     *
     * @return void
     */
    public function __construct(User $user, $fileId)
    {
        $this->user = $user;
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get the image
        $path = storage_path() . '/uploads/' . $this->fileId;
        $fileName = $this->fileId . '.jpg';

        // dd($path);

        // resize
        Image::make($path)->encode('jpg')->fit(100, 100, function($c) {
            $c->upsize();
        })->save();

        // upload to S3
        if(Storage::disk('s3_images')->put('avatars/' . $fileName, fopen($path, 'r+'))) { 
            File::delete(storage_path('/uploads/' . $this->fileId)); // delete local temp file
        }

        // update user with avatar_path
        $this->user->avatar_filename = $fileName;
        $this->user->save();   
    }
}
