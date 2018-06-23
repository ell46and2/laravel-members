<?php

namespace App\Http\Controllers\Attachment;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EncodingWebhookController extends Controller
{
    public function handle(Request $request)
    {

    	$payload = json_decode($request->getContent(), true);

    	$event = camel_case($payload['Type']);

    	// Log::debug($event);

    	Log::debug($payload);

    	if(method_exists($this, $event)) {
			$this->{$event}($payload);
		}	
    }

    protected function subscriptionConfirmation($payload)
    {
    	$confirmation_url = curl_init($payload['SubscribeURL']);
        curl_exec($confirmation_url);

		return response(null, 204);
    }

    protected function notification($payload)
    {
    	Log::debug(gettype($payload['Message']));
    	Log::debug(json_decode($payload['Message'])->state);

    	$message = json_decode($payload['Message']);
    	if($message->state === 'COMPLETED') {
    		Log::debug($message->input->key);
    		$video = Attachment::where('filename', $message->input->key)->firstOrFail();
    		$video->update(['processed' => true]);
    	}
    }
}
