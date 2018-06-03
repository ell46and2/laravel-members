<?php

namespace App\Http\Controllers\Jockey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Jockey\UpdateJockeyFormRequest;
use App\Jobs\UploadAvatarImage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
    	$jockey = auth()->user();

    	return view('jockey.profile.index', compact('jockey'));
    }

    public function edit()
    {
    	$jockey = auth()->user();

    	return view('jockey.profile.edit', compact('jockey'));
    }

    public function update(UpdateJockeyFormRequest $request)
    {
    	$jockey = auth()->user();

        // dd($request->file('avatar_image'));

    	$jockey->update($request->only([
            'middle_name',
            'alias',
            'address_1',
            'address_2',
            'county',
            'country',
            'postcode',
            'telephone',
            'twitter_handle',
            'email',
    	]));

        if($request->file('avatar_image')) {
            // move to temp location and give the filename a unique id 
            $request->file('avatar_image')->move(storage_path() . '/uploads', $fileId = uniqid(true));

            // dispatch job 
            $this->dispatch(new UploadAvatarImage($jockey, $fileId));
        }

    	return redirect()->route('jockey.profile.index');
    }
}
