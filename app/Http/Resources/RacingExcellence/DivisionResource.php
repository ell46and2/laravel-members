<?php

namespace App\Http\Resources\RacingExcellence;

use App\Http\Resources\RacingExcellence\ParticipantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DivisionResource extends JsonResource
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
            'participants' => ParticipantResource::collection($this->participants),
        ];
    }
}
