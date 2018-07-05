<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Jobs\Activity\NotifyCoachDeletedActivity;
use App\Jobs\Activity\NotifyJockeysDeletedActivity;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function destroy(Activity $activity)
    {
    	$this->authorize('delete', $activity);

    	$usersRole = auth()->user()->roleName;

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
}
