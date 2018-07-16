<?php

namespace App\Mail\Jet\Account;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JetCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $jet;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $jet)
    {
        $this->jet = $jet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.jet.account.created');
    }
}
