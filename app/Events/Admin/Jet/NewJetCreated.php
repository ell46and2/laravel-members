<?php

namespace App\Events\Admin\Jet;

use App\Models\Jet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewJetCreated
{
    use Dispatchable, SerializesModels;

    public $jet;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Jet $jet)
    {
        $this->jet = $jet;
    }
}
