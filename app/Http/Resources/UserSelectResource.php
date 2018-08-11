<?php

namespace App\Http\Resources;

// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSelectResource extends JsonResource
{
    

    /**
     * Transform the resource into an array.
     *
     *  Append a request()->request->add(['selectedIds' => $ids]);
     *  before calling the resource, so we can set which users should be selected. 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $selectedIds = optional($request->selectedIds);

        return [
            'id' => $this->id,
            'name' => $this->full_name,
            'avatar' => $this->getAvatar(),
            'selected' => $selectedIds->contains($this->id),
            'role' => $this->role->name,
            'status' => $this->status,
        ];
    }
}
