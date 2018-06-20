<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Comment;
use App\Models\CompetencyAssessment;
use App\Models\Document;
use App\Models\RacingExcellence;
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
            'competency-assessment' => CompetencyAssessment::class,
            'comment' => Comment::class,
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
