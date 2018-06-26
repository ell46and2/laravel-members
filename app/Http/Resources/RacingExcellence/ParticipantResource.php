<?php

namespace App\Http\Resources\RacingExcellence;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
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
            'division_id' => $this->division_id,
            'name' => $this->name ?? $this->jockey->full_name,
            'place' => $this->place,
            'place_points' => $this->place_points,
            'completed_race' => $this->completed_race,
            'presentation_points' => $this->presentation_points,
            'professionalism_points' => $this->professionalism_points,
            'coursewalk_points' => $this->coursewalk_points,
            'riding_points' => $this->riding_points,
            'total_points' => $this->total_points,
            'feedback' => $this->feedback,
            'avatar' => $this->getAvatar(),
        ];
    }
}
