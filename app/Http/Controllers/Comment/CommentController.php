<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Activity $activity)
    {
    	$activity->comments()->create([
    		'body' => request()->body,
    		'author_id' => request()->user()->id,
    		'recipient_id' => request()->recipient_id
    	]);
    }
}
