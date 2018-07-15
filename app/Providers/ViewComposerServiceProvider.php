<?php

namespace App\Providers;

use App\Http\Resources\NotificationsResource;
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
            $view->with('numUnreadMessages', auth()->user()->unreadMessagesCount());
            $view->with('notificationsResource', new NotificationsResource(null));
            $view->with('notificationCount', auth()->user()->unreadNotifications()->count());
        });

        view()->composer('coach.dashboard.index', function($view) {
            $view->with('numUnreadMessages', auth()->user()->unreadMessagesCount());
        });
        view()->composer('jockey.dashboard.index', function($view) {
            $view->with('numUnreadMessages', auth()->user()->unreadMessagesCount());
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
