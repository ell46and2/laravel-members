<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Jockey;
use Illuminate\Http\Request;

class ActivityJockeyFeedbackController extends Controller
{
    public function create(Request $request,Activity $activity, Jockey $jockey)
    {
    	$jockey->activities()
        ->updateExistingPivot($activity->id, [
            'feedback' => $request->feedback
        ]);
    }
}
