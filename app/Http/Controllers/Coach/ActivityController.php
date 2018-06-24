<?php

namespace App\Http\Controllers\Coach;

use App\Events\Coach\Activity\NewActivityCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\Activity\StorePostFormRequest;
use App\Models\Activity;
use App\Models\ActivityType;
use App\Models\Coach;
use App\Models\Jockey;
use App\Utilities\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $coach = Coach::find(auth()->user()->id);

        $activityTypes = ActivityType::get();

        $events = (new Collection($coach->events($request)))->paginate(15);

        $jockeys = $this->getAllJockeysWorkedWithCoach($coach);

        return view('coach.activitylist.index', compact('events', 'jockeys', 'activityTypes'));
    }

    public function store(StorePostFormRequest $request)
    {
        DB::beginTransaction();

        try {
        	$activity = auth()->user()->activities()->create([
                'activity_type_id' => $request->activity_type_id,
    			'start' => Carbon::parse("{$request->start_date} {$request->start_time}"),
            	'duration' => $request->duration,
            	'location_name' => $request->location_name,
                'location_id' => $request->location_id,
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
                Jockey::findOrFail($jockeyId)
                    ->activities()
                    ->updateExistingPivot($activity->id, [
                        'feedback' => $feedback
                    ]);
            });
        });
    }

    public function show(Activity $activity)
    {   
        $video = $activity->attachments->first(); // NOTE: Remove, for testing purposes

        return view('coach.activity.show', compact('activity', 'video'));
    }

    private function getAllJockeysWorkedWithCoach(Coach $coach)
    {
        $ownJockeys = $coach->jockeys->pluck('id');

        // incase they have been reassigned
        // $activityJockeyIds = $coach->activities->first()->jockeys;
        
        $racingExcels = $coach->racingExcellences;



        $racingExcelJockeyIds = $racingExcels->map(function($racingExcel) {
            return $racingExcel->jockeys->pluck('jockey_id')->unique();
        });

        return Jockey::find($ownJockeys->merge($racingExcelJockeyIds->flatten())->unique())->sortBy('first_name');
    }
}
