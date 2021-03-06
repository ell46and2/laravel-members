<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->full_name,
            'avatar' => $this->getAvatar(),
            'coach' => $this->isCoach(),
            'role' => $this->role_name,
            'status' => $this->status,
        ];
    }
}
