<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function dismiss(Notification $notification)
    {
    	$this->authorize('dismiss', $notification);

    	$notification->markAsRead();

    	return response()->json(null, 200);
    }

    /*
    	Pass time the page was loaded in request so we only mark as read those
    	notifications that are older then then.
    	In case user receives a notification(s) whilst on the page.
     */
    public function dismissAll(User $user)
    {
    	$this->authorize('markAllNotificationsAsRead', $user);

    	Notification::markAllAsRead($user->unreadNotifications
    		->where('created_at', '<=', Carbon::parse(request()->from)));

    	return response()->json(null, 200);
    }
}
