<?php

namespace App\Events\Jockey\Account;

use App\Models\Jockey;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NewJockeyRegistered
{
    use Dispatchable, SerializesModels;

    public $jockey;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Jockey $jockey)
    {
        $this->jockey = $jockey;
    }
}
