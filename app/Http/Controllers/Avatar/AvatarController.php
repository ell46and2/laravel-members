<?php

namespace App\Http\Controllers\Avatar;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AvatarController extends Controller
{
    public function store(Request $request, User $user) // add validation
    {
    	$uid = uniqid(true);
    	$filename = uniqid(true) . '.' . $request->fileExtension;
    	$path = storage_path() . '/uploads/'. $filename;
    	$request->file('avatar')->move(storage_path() . '/uploads', $filename);

    	Image::make($path)->encode('jpg')->fit(200, 200, function($c) {
            $c->upsize();
        })->save();

        $newFilename = $uid . '.jpg';

        if(Storage::disk('s3_images')->put('avatars/' . $newFilename, fopen($path, 'r+'))) { 
            File::delete($path); // delete local temp file
        }

        $user->avatar_filename = $newFilename;
        $user->save();

        return response()->json($user->fresh()->getAvatar(), 200);

    }

    public function destroy(User $user)
    {
    	$user->avatar_filename = null;
        $user->save();

        return response()->json($user->fresh()->getAvatar(), 200);
    }
}
