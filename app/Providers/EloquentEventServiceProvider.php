<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\RacingExcellenceParticipant;
use App\Observers\ActivityObserver;
use App\Observers\RacingExcellenceParticipantObserver;
use Illuminate\Support\ServiceProvider;

class EloquentEventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Activity::observe(ActivityObserver::class);
        RacingExcellenceParticipant::observe(RacingExcellenceParticipantObserver::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
