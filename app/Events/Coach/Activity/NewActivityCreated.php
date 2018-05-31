<?php

namespace App\Events\Coach\Activity;

use App\Models\Activity;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewActivityCreated
{
    use Dispatchable, SerializesModels;

    public $activity;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }
}
