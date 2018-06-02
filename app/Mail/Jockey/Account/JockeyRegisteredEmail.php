<?php

namespace App\Mail\Jockey\Account;

use App\Models\Jockey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JockeyRegisteredEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $jockey;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Jockey $jockey)
    {
        $this->jockey = $jockey;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.jockey.account.registered');
    }
}
