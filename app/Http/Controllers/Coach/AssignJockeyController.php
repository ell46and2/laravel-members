<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Jobs\Assignment\CoachAssigned;
use App\Jobs\Assignment\CoachUnassigned;
use App\Models\Coach;
use App\Models\Jockey;
use Illuminate\Http\Request;

class AssignJockeyController extends Controller
{
    public function create(Request $request, Coach $coach)
    {
    	$jockey = Jockey::findOrFail($request->jockey_id);

    	$coach->assignJockey($jockey);

    	$this->dispatch(new CoachAssigned($coach, $jockey));

    	return response()->json('success', 200);
    }

    public function destroy(Request $request, Coach $coach)
    {
    	$jockey = Jockey::findOrFail($request->jockey_id);

    	$coach->unassignJockey($jockey);

    	$this->dispatch(new CoachUnassigned($coach, $jockey));

    	return response()->json('success', 200);
    }
}
