<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
    public function toArray($request)
    {   
        $user = $request->user();
        $unreadNotifications = $user->unreadNotifications()->with(['notifiable'])->get();

        return [
            'from' => Carbon::now()->toDateTimeString(),
            'user_id' => $user->id,
            'notifications' => NotificationResource::collection($unreadNotifications),
            'count' => $unreadNotifications->count(),
        ];
    }
}
