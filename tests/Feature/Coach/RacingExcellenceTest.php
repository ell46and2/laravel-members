<?php

namespace Tests\Feature\Coach;

use App\Jobs\RacingExcellence\Results\NotifyAllRacingResults;
use App\Jobs\RacingExcellence\Results\NotifyRacingResultUpdated;
use App\Models\Coach;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use App\Models\RacingExcellenceParticipant;
use App\Models\SeriesType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RacingExcellenceTest extends TestCase
{
    use DatabaseMigrations;



}