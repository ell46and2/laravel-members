<?php

namespace App\Providers;

use App\Http\Resources\NotificationsResource;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.base', function($view) {
            $view->with('notificationsResource', new NotificationsResource(null));
            $view->with('notificationCount', auth()->user()->unreadNotifications()->count());
        });
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
