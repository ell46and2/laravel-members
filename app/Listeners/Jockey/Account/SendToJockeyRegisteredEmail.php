<?php

namespace App\Listeners\Jockey\Account;

use App\Events\Jockey\Account\NewJockeyRegistered;
use App\Mail\Jockey\Account\JockeyRegisteredEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendToJockeyRegisteredEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewJockeyRegistered  $event
     * @return void
     */
    public function handle(NewJockeyRegistered $event)
    {
        Mail::to($event->user->email)->queue(new JockeyRegisteredEmail($event->user));
    }
}
