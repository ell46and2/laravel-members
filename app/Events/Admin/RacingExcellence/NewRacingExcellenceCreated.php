<?php

namespace App\Events\Admin\RacingExcellence;

use App\Models\RacingExcellence;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRacingExcellenceCreated
{
    use Dispatchable, SerializesModels;

    public $racingExcellence;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RacingExcellence $racingExcellence)
    {
        $this->racingExcellence = $racingExcellence;
    }

}
