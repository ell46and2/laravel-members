<?php

namespace App\Http\Resources;

use App\Models\Coach;
use App\Models\Jet;
use App\Models\Jockey;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{

    public function toArray($request)
    {
        $user = auth()->user();

        $role = $user->roleName;

        if($role === 'coach') {
            $coach = Coach::find($user->id);
            return [
                'jockeys' => UserSelectResource::collection($coach->jockeys()->with('role')->get()->sortBy('full_name'))
            ];
        }

        // will need one for jets with all jockeys only
        if($role === 'jets') {
            return [
                'jockeys' => UserSelectResource::collection(Jockey::with('role')->get()->sortBy('full_name'))
            ];
        }

        return [
            'jockeys' => UserSelectResource::collection(Jockey::with('role')->get()->sortBy('full_name')),
            'coaches' => UserSelectResource::collection(Coach::with('role')->get()->sortBy('full_name')),
            'jets' => UserSelectResource::collection(Jet::with('role')->get()->sortBy('full_name')),
        ];
    }
}
