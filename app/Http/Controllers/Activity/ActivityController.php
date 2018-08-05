<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttachmentResource;
use App\Jobs\Activity\NotifyCoachDeletedActivity;
use App\Jobs\Activity\NotifyJockeysDeletedActivity;
use App\Models\Activity;
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
}
