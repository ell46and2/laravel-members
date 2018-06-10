<?php

namespace App\Http\Controllers\Jockey;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function show(Activity $activity)
    {

    	return view('jockey.activity.show', compact('activity'));
    }
}
