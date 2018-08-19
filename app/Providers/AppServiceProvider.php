<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Comment;
use App\Models\CrmJockey;
use App\Models\CrmRecord;
use App\Models\Document;
use App\Models\Jockey;
use App\Models\Pdp;
use App\Models\RacingExcellence;
use App\Models\SkillProfile;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'activity' => Activity::class,
            'racing-excellence' => RacingExcellence::class,
            'document' => Document::class,
            'skill-profile' => SkillProfile::class,
            'comment' => Comment::class,
            'jockey' => Jockey::class,
            'crm-jockey' => CrmJockey::class,
            'crm-record' => CrmRecord::class,
            'pdp' => Pdp::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
