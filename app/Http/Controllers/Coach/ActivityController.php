<?php

namespace App\Http\Controllers\Coach;

use App\Events\Coach\Activity\NewActivityCreated;
use App\Http\Controllers\Controller;
use App\Models\Jockey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function store(Request $request) // add form request validation
    {
        DB::beginTransaction();

        try {
        	$activity = auth()->user()->activities()->create([
                'activity_type_id' => $request->activity_type_id,
    			'start' => Carbon::parse("{$request->start_date} {$request->start_time}"),
            	'duration' => $request->duration,
            	'location' => $request->location
        	]);

        	$activity->addJockeysById(request()->jockeys);
            
            $this->addJockeyFeedback(request()->feedback, $activity);

        	// use observable to create 'end' timestamp from 'start' and 'duration'
       	
        	// Need to send notifications to any attached jockeys
        	event(new NewActivityCreated($activity));

        } catch(\Exception $e) {
            DB::rollback();
            // return back with error message
        }
    	
    	return redirect()->route('coach.activity.show', $activity);
    }

    /*
        Adds feedback to the activity_jockey pivot table.
     */
    private function addJockeyFeedback($requestFeedback, $activity) 
    {
        collect($requestFeedback)->each(function($jockeyFeedback) use ($activity) {
            collect($jockeyFeedback)->each(function($feedback, $jockeyId) use ($activity) {
                // dd($jockeyId);
                Jockey::findOrFail($jockeyId)->activities()->updateExistingPivot($activity->id, [
                    'feedback' => $feedback
                ]);
            });
        });
    }
}
