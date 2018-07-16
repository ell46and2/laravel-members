<?php

namespace App\Listeners\Admin\Jet;

use App\Events\Admin\Jet\NewJetCreated;
use App\Mail\Jet\Account\JetCreatedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendToJetAccountCreatedEmail
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
     * @param  object  $event
     * @return void
     */
    public function handle(NewJetCreated $event)
    {
        Mail::to($event->jet->email)->queue(new JetCreatedEmail($event->jet));
    }
}
