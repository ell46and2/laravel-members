<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $currentUser;

    public function __construct()
    {
    	$this->middleware(function ($request, $next) {
            if($this->currentUser = auth()->user()) {
            	view()->share('currentUser', $this->currentUser);
            	view()->share('numUnreadMessages', $this->currentUser->unreadMessagesCount());
            }

            return $next($request);
        });
    }
}
