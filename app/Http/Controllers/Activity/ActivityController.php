<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Http\Requests\Activity\OneToOneUpdatePutFormRequest;
use App\Http\Resources\AttachmentResource;
use App\Http\Resources\UserSelectResource;
use App\Jobs\Activity\NotifyCoachDeletedActivity;
use App\Jobs\Activity\NotifyJockeyAddedToActivity;
use App\Jobs\Activity\NotifyJockeyAmendedActivity;
use App\Jobs\Activity\NotifyJockeyRemovedFromActivity;
use App\Jobs\Activity\NotifyJockeysDeletedActivity;
use App\Models\Activity;
use App\Models\ActivityLocation;
use App\Models\ActivityType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function destroy(Activity $activity)
    {
    	$this->authorize('delete', $activity);

    	$usersRole = $this->currentUser->roleName;

    	$this->dispatch(new NotifyJockeysDeletedActivity(
    		$activity->jockeys->pluck('id'),
            $activity->formattedType,
            $activity->formattedStart
    	));

    	if($usersRole === 'admin') {
    		$this->dispatch(new NotifyCoachDeletedActivity(
	    		$activity->coach,
	            $activity->formattedType,
	            $activity->formattedStart
	    	));
    	}
    	

    	$activity->delete();

    	return redirect()->route($usersRole . '.dashboard.index');
    }

    public function show(Activity $activity)
    {
        $this->authorize('show', $activity);

        $isAdmin = $this->currentUser->isAdmin();
        $isCoach = $this->currentUser->isCoach();
        $isAssignedCoach = $this->currentUser->id === $activity->coach_id;
        $isAssignedJockey = $activity->isAssignedToJockey($this->currentUser);

        $attachmentsResource = AttachmentResource::collection($activity->attachments);

        return view('activity.show', compact(
            'activity', 
            'attachmentsResource',
            'isAdmin',
            'isCoach',
            'isAssignedCoach',
            'isAssignedJockey'
        ));
    }

    public function edit(Activity $activity)
    {
        $this->authorize('edit', $activity);

        request()->request->add(['selectedIds' => $activity->jockeys->pluck('id')]); // append to the request so we can access in the Resource.

        $activityTypes = ActivityType::all();

        $locations = ActivityLocation::all();

        $jockeysResource = UserSelectResource::collection($activity->coach->jockeys()->with('role')->get());

        return view('activity.edit', compact('activity', 'jockeysResource', 'activityTypes', 'locations'));
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

        // NOTE: Check if Jockey changed here - NEED TO ADD

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
}
