<?php

namespace App\Http\Controllers\Coach;

use App\Events\Coach\Activity\NewActivityCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Activity\OneToOneUpdatePutFormRequest;
use App\Http\Requests\Coach\Activity\StorePostFormRequest;
use App\Http\Resources\AttachmentResource;
use App\Http\Resources\UserSelectResource;
use App\Jobs\Activity\NotifyJockeyAddedToActivity;
use App\Jobs\Activity\NotifyJockeyAmendedActivity;
use App\Jobs\Activity\NotifyJockeyRemovedFromActivity;
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

        $events = (new Collection($coach->events($request)))->paginate(15);

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

    // public function show(Activity $activity)
    // {   
    //     // $video = $activity->attachments->first(); // NOTE: Remove, for testing purposes

    //     $attachmentsResource = AttachmentResource::collection($activity->attachments);

    //     return view('coach.activity.show', compact('activity', 'attachmentsResource'));
    // }

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

    public function edit(Activity $activity)
    {
        $this->authorize('edit', $activity);

        request()->request->add(['selectedIds' => $activity->jockeys->pluck('id')]); // append to the request so we can access in the Resource.

        $activityTypes = ActivityType::all();

        $locations = ActivityLocation::all();

        $jockeysResource = UserSelectResource::collection($activity->coach->jockeys()->with('role')->get());

        return view('coach.activity.edit', compact('activity', 'jockeysResource', 'activityTypes', 'locations'));
    }

    public function singleUpdate(OneToOneUpdatePutFormRequest $request, Activity $activity)
    {
        $this->authorize('update', $activity);

        $previous = clone $activity;

        $activity->update([
            'activity_type_id' => $request->activity_type_id,
            'start' => Carbon::createFromFormat('d/m/Y H:i',"{$request->start_date} {$request->start_time}"),
            'duration' => $request->duration ? $request->duration : null,
            'location_id' => $request->location_id,
            'location_name' => $request->location_name,
            'information' => $request->information,
        ]);

        if($activity->start > Carbon::now() && $this->typeOrStartOrLocationChanged($previous, $activity)) {
            $this->dispatch(new NotifyJockeyAmendedActivity($activity));
        }
        

        return redirect()->route('activity.show', $activity);
    }

    public function groupUpdate(Request $request, Activity $activity) // Add form request
    {
        $this->authorize('update', $activity);

        $previous = clone $activity;

        $activity->update([
            'activity_type_id' => $request->activity_type_id,
            'start' => Carbon::createFromFormat('d/m/Y H:i',"{$request->start_date} {$request->start_time}"),
            'duration' => $request->duration ? $request->duration : null,
            'location_id' => $request->location_id,
            'location_name' => $request->location_name,
            'information' => $request->information,
        ]);

        $this->updateActivitysJockeys($activity);

        if($activity->start > Carbon::now() && $this->typeOrStartOrLocationChanged($previous, $activity)) {
            $this->dispatch(new NotifyJockeyAmendedActivity($activity));
        } 

        return redirect()->route('activity.show', $activity);
    }

    // Move to trait
    private function updateActivitysJockeys(Activity $activity)
    {
        $currentJockeys = $activity->jockeys;
        $requestJockeys = collect(array_keys(request()->jockeys));
        
        // remove
        foreach ($currentJockeys as $jockey) {
            if(!$requestJockeys->contains($jockey->id)) {
                $activity->removeJockey($jockey);

                // notify jockey removed
                $this->dispatch(new NotifyJockeyRemovedFromActivity($activity, $jockey));
            }
        }

        // add
        $currentJockeyIds = $currentJockeys->pluck('id');
        foreach ($requestJockeys as $id) {
            if(!$currentJockeyIds->contains($id)) {
                $activity->addJockeyById($id);

                // notify jockey added
               $this->dispatch(new NotifyJockeyAddedToActivity($activity, $id));
            }
        }
    }

    // Move to trait
    private function typeOrStartOrLocationChanged(Activity $previous, Activity $activity)
    {
        // dd($previous->activity_type_id != $activity->activity_type_id);
        return $previous->start != $activity->start ||
            $previous->activity_type_id != $activity->activity_type_id ||
            $previous->location_id != $activity->location_id ||
            $previous->location_name != $activity->location_name; 
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
