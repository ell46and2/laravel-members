<?php

namespace App\Http\Controllers\Message;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageFormRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\Message;
use App\Models\User;
use App\Utilities\Collection;
use Illuminate\Http\Request;

class MessageController extends Controller
{
	public function index()
	{
        $messages = auth()->user()
            ->messages()
            ->with('author')
            ->paginate(15);
        
        return view('message.index', compact('messages'));
	}

    public function sentIndex()
    {
        $messages = auth()->user()
            ->sentMessages()
            ->paginate(15);
        
        return view('message.sent-index', compact('messages'));
    }

    public function store(StoreMessageFormRequest $request) // Add form request validation
    {
        $user = auth()->user();

        // $recipientIds = array_keys($request->recipients);
        
       
        $message = $user->sentMessages()->create([
            'subject' => $request->subject,
            'body' => $request->body,
        ]);
        
        $message->addRecipients($request->recipients);

        // dd($request->alljockeys);


        $this->sendToAllJockeys($message, $user->roleName);

        $this->sendToAllCoaches($message, $user->roleName);

        // if allJockeys - if coach sent to all the coaches jockeys
        //  - if admin sent to all jockeys
        
        // if allCoaches - sent to all coaches
        
        // Need add in Jets too.
        // 
        return response()->json('success', 200);

     //    session()->flash('message', "Message sent");

    	// return redirect()->route('messages.index');
    }

    public function show(Message $message)
    {
        auth()->user()->markMessageAsRead($message);
        // $message->markAsRead();

        return view('message.show', compact('message'));
    }

    public function create()
    {
        $messageResource = new MessageResource(request());

        return view('message.create', compact('messageResource'));
    }

    public function getUser(User $user)
    {
       return new UserResource($user); 
    }

    public function destroy(Message $message)
    {
        //validate user is a recipient of the message

        auth()->user()->markMessageAsDeleted($message);

        session()->flash('message', "Message deleted");

        return redirect()->route('messages.index');
    }

    public function destroySentMessage(Message $message)
    {
        $message->markAsDeleted();

        session()->flash('message', "Message deleted");

        return redirect()->route('messages.sent');
    }

    private function sendToAllJockeys(Message $message, $roleName)
    {

       if(request()->allJockeys) {
        // dd(request()->allJockeys);
        if($roleName === 'coach') {
            $message->addRecipients(Coach::find(auth()->user()->id)->jockeys->pluck('id')->toArray());
        } else {
            $message->addRecipients(Jockey::all()->pluck('id')->toArray());
        }
       }
    }

    private function sendToAllCoaches(Message $message, $roleName)
    {

       if(request()->allCoaches && $roleName !== 'coach') {
            $message->addRecipients(Coach::all()->pluck('id')->toArray());    
        }
    }
}
