<?php

namespace App\Listeners\Admin\Account;

use App\Events\Jockey\Account\NewJockeyRegistered;
use App\Mail\Admin\Account\ToAdminJockeyRegisteredEmail;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendToAdminJockeyRegisteredEmail
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
        $admin = User::admin();

        Mail::to($admin->email)->queue(new ToAdminJockeyRegisteredEmail($event->user));
    }
}
