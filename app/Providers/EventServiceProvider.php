<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],   
        'App\Events\Jockey\Account\NewJockeyRegistered' => [
            'App\Listeners\Jockey\Account\SendToJockeyRegisteredEmail',
            'App\Listeners\Admin\Account\SendToAdminJockeyRegisteredEmail',
        ],
        'App\Events\Admin\Coach\NewCoachCreated' => [
            'App\Listeners\Admin\Coach\SendToCoachAccountCreatedEmail',
        ],
        'App\Events\Admin\Jet\NewJetCreated' => [
            'App\Listeners\Admin\Jet\SendToJetAccountCreatedEmail',
        ],
        'App\Events\Coach\Activity\NewActivityCreated' => [
            'App\Listeners\Coach\Activity\NotifyJockeysOfNewActivity',
        ],
        'App\Events\Admin\RacingExcellence\NewRacingExcellenceCreated' => [
            'App\Listeners\Admin\RacingExcellence\NotifyCoachOfNewRacingExcellence',
            'App\Listeners\Admin\RacingExcellence\NotifyJockeysOfNewRacingExcellence'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
