<?php

namespace App\Http\Controllers\Admin;

use App\Events\Coach\Activity\NewActivityCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Activity\OneToOneUpdatePutFormRequest;
use App\Http\Requests\Admin\Activity\StorePostFormRequest;
use App\Http\Resources\AttachmentResource;
use App\Http\Resources\UserSelectResource;
use App\Jobs\Activity\NotifyCoachAmendedActivity;
use App\Jobs\Activity\NotifyCoachNewActivity;
use App\Jobs\Activity\NotifyJockeyAddedToActivity;
use App\Jobs\Activity\NotifyJockeyAmendedActivity;
use App\Jobs\Activity\NotifyJockeyRemovedFromActivity;
use App\Models\Activity;
use App\Models\ActivityLocation;
use App\Models\ActivityType;
use App\Models\Admin;
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
        $admin = Admin::find($this->currentUser->id);

        $events = (new Collection($admin->events($request)))->paginate(config('jcp.site.pagination'));

        $activityTypes = ActivityType::get();

        $jockeys = Jockey::all();

        $coaches = Coach::all();

        return view('admin.activitylist.index', compact('events', 'jockeys', 'coaches', 'activityTypes'));
    }


    public function singleCreate()
    {
    	return $this->create();
    }

    public function groupCreate()
    {
        return $this->create(true);
    }

    private function create($group = false)
    {
        // admin
        
        $coachesResource = UserSelectResource::collection(Coach::take(10)->get()); // change back to just all()

        $activityTypes = ActivityType::all();

        $locations = ActivityLocation::all();

        if($group) {
            return view('admin.activity.group.create', compact('coachesResource', 'activityTypes', 'locations'));
        }
        
        return view('admin.activity.one-to-one.create', compact('coachesResource', 'activityTypes', 'locations'));
    }

    // public function show(Activity $activity)
    // {   
    //     // $video = $activity->attachments->first(); // NOTE: Remove, for testing purposes

    //     $attachmentsResource = AttachmentResource::collection($activity->attachments);

    //     return view('admin.activity.show', compact('activity', 'attachmentsResource'));
    // }

    public function store(StorePostFormRequest $request) // StorePostFormRequest
    {     
        

        DB::beginTransaction();

        try {
        	$activity = Activity::create([
        		'coach_id' => $request->coach_id,
                'activity_type_id' => $request->activity_type_id,
    			'start' => Carbon::createFromFormat('d/m/Y H:i',"{$request->start_date} {$request->start_time}"),
            	'duration' => $request->duration ?? $request->duration,
            	'location_name' => $request->location_name,
                'location_id' => $request->location_id,
                'group' => count(request()->jockeys) > 1,
        	]);

        	$activity->addJockeysById(array_keys(request()->jockeys));
           
       	
        	// Need to send notifications to any attached jockeys
        	event(new NewActivityCreated($activity));

        	// notify coach
            $this->dispatch(new NotifyCoachNewActivity($activity)); 
            
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            dd($e);
            // return back with error message
        }
    	
    	return redirect()->route('activity.show', $activity);
    }

    public function edit(Activity $activity)
    {
        $this->authorize('edit', $activity);
        
        request()->request->add(['selectedIds' => $activity->jockeys->pluck('id')]); // append to the request so we can access in the Resource.

        $activityTypes = ActivityType::all();

        $locations = ActivityLocation::all();

        $coachesResource = UserSelectResource::collection(Coach::take(10)->get()); // change back to just all()

        return view('admin.activity.edit', compact('activity', 'coachesResource', 'activityTypes', 'locations'));
    }

    public function getCoachesJockeys(Coach $coach)
    {
    	return UserSelectResource::collection($coach->jockeys);
    }
}
