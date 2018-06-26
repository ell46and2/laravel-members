<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\RacingExcellence;
use App\Models\User;
use App\Policies\ActivityPolicy;
use App\Policies\AttachmentPolicy;
use App\Policies\CommentPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\RacingExcellencePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Comment::class => CommentPolicy::class,
        Activity::class => ActivityPolicy::class,
        RacingExcellence::class => RacingExcellencePolicy::class,
        Notification::class => NotificationPolicy::class,
        User::class => UserPolicy::class,
        Attachment::class => AttachmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
