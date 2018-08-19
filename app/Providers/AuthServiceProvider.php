<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Attachment;
use App\Models\Coach;
use App\Models\Comment;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Jockey;
use App\Models\Notification;
use App\Models\Pdp;
use App\Models\RacingExcellence;
use App\Models\SkillProfile;
use App\Models\User;
use App\Policies\ActivityPolicy;
use App\Policies\AttachmentPolicy;
use App\Policies\CoachPolicy;
use App\Policies\CommentPolicy;
use App\Policies\InvoiceLinePolicy;
use App\Policies\InvoicePolicy;
use App\Policies\JockeyPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PdpPolicy;
use App\Policies\RacingExcellencePolicy;
use App\Policies\SkillProfilePolicy;
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
        Invoice::class => InvoicePolicy::class,
        SkillProfile::class => SkillProfilePolicy::class,
        Pdp::class => PdpPolicy::class,
        Jockey::class => JockeyPolicy::class,
        InvoiceLine::class => InvoiceLinePolicy::class,
        Coach::class => CoachPolicy::class,
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
