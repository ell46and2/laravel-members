<?php

namespace App\Http\Resources\RacingExcellence;

use App\Http\Resources\RacingExcellence\DivisionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->load([
            'divisions',
            'divisions.participants',
            'divisions.participants.jockey',
            'divisions.participants.jockey.role',
            'series',
        ]);

        return [
            'id' => $this->id,
            'divisions' => DivisionResource::collection($this->divisions),
            'totalJustFromPlace' => false,
            // 'totalJustFromPlace' => $this->series->total_just_from_place
        ];
    }
}
