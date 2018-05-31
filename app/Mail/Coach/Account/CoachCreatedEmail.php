<?php

namespace App\Mail\Coach\Account;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoachCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $coach;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $coach)
    {
        $this->coach = $coach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.coach.account.created');
    }
}
