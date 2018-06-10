<?php

namespace App\Http\Controllers\Message;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
	public function index()
	{
		$recipient = auth()->user();

		// get all messages for recipient
	}

    public function store() // Add form request validation
    {
    	$author = auth()->user();

    	$author->sentMessages()->create(request()->only([
			'recipient_id',
        	'subject',
        	'body',
    	]));

    	// Do we need to show a flash message saying message sent?
    	return redirect()->route('messages.index');
    }
}
