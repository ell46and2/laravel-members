<?php

namespace App\Http\Controllers\Coach;

use App\Events\Coach\Activity\NewActivityCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activity\OneToOneUpdatePutFormRequest;
use App\Http\Requests\Coach\Activity\StorePostFormRequest;
use App\Http\Resources\UserSelectResource;
use App\Models\Activity;
use App\Models\ActivityLocation;
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
        $coach = Coach::find($this->currentUser->id);

        $activityTypes = ActivityType::get();

        $events = (new Collection($coach->events($request)))->paginate(config('jcp.site.pagination'));

        $jockeys = $this->getAllJockeysWorkedWithCoach($coach);

        return view('coach.activitylist.index', compact('events', 'jockeys', 'activityTypes'));
    }

    public function store(StorePostFormRequest $request) // StorePostFormRequest
    {    
        $coach = Coach::find($this->currentUser->id);

        DB::beginTransaction();

        try {
        	$activity = $coach->activities()->create([
                'activity_type_id' => $request->activity_type_id,
    			'start' => Carbon::createFromFormat('d/m/Y H:i',"{$request->start_date} {$request->start_time}"),
            	'duration' => $request->duration ?? $request->duration,
            	'location_name' => $request->location_name,
                'location_id' => $request->location_id,
                'group' => count(request()->jockeys) > 1,
                'information' => $request->information,
        	]);

        	$activity->addJockeysById(array_keys(request()->jockeys));
            
            $this->addJockeyFeedback(request()->feedback, $activity);

        	// use observable to create 'end' timestamp from 'start' and 'duration'
       	
        	// Need to send notifications to any attached jockeys
        	event(new NewActivityCreated($activity));
            
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            // return back with error message
        }
    	
    	return redirect()->route('activity.show', $activity);
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

    private function create($group = false) // maybe move to OneToOneActivityController and have one for group too.
    {
        // policy coach or admin
        
        $coach = Coach::find($this->currentUser->id);

        $jockeysResource = UserSelectResource::collection($coach->jockeys()->with('role')->get());

        $activityTypes = ActivityType::all();

        $locations = ActivityLocation::all();

        if($group) {
            return view('coach.activity.group.create', compact('jockeysResource', 'activityTypes', 'locations'));
        }
        
        return view('coach.activity.one-to-one.create', compact('jockeysResource', 'activityTypes', 'locations'));
    }

    public function groupCreate()
    {
        return $this->create(true);
    }

    public function singleCreate()
    {
        return $this->create();
    }

    private function getAllJockeysWorkedWithCoach(Coach $coach)
    {
        $ownJockeys = $coach->jockeys->pluck('id');

        // incase they have been reassigned
        // $activityJockeyIds = $coach->activities->first()->jockeys;
        
        $racingExcels = $coach->racingExcellences;



        $racingExcelJockeyIds = $racingExcels->map(function($racingExcel) {
            return $racingExcel->jockeyParticipants->pluck('jockey_id')->unique();
        });

        return Jockey::find($ownJockeys->merge($racingExcelJockeyIds->flatten())->unique())->sortBy('first_name');
    }
}
