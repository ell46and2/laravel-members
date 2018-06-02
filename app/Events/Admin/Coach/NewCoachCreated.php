<?php

namespace App\Events\Admin\Coach;


use App\Models\Coach;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NewCoachCreated
{
    use Dispatchable, SerializesModels;

    public $coach;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Coach $coach)
    {
        $this->coach = $coach;
    }

   
}
