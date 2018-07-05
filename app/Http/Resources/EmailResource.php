<?php

namespace App\Http\Resources;

use App\Models\Coach;
use App\Models\Jockey;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{

    public function toArray($request)
    {
        $user = auth()->user();

        $role = $user->roleName;

        if($role === 'coach') {
            $coach = Coach::find($user->id);
            return [
                'jockeys' => UserSelectResource::collection($coach->jockeys)
            ];
        }

        // will need one for jets with all jockeys only
        if($role === 'jets') {
            return [
                'jockeys' => UserSelectResource::collection(Jockey::all())
            ];
        }

        return [
            'jockeys' => UserSelectResource::collection(Jockey::all()),
            'coaches' => UserSelectResource::collection(Coach::all()),
            // 'jets' => UserSelectResource::collection(Jet::all()),
        ];
    }
}
