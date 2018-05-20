<?php

namespace App\Listeners\Admin\Coach;

use App\Events\Admin\Coach\NewCoachCreated;
use App\Mail\Coach\Account\CoachCreatedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendToCoachAccountCreatedEmail
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
     * @param  NewCoachCreated  $event
     * @return void
     */
    public function handle(NewCoachCreated $event)
    {
        Mail::to($event->coach->email)->queue(new CoachCreatedEmail($event->coach));
    }
}
