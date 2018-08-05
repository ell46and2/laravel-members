<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
        $userId = $request->user()->id;

        return [
            'id' => $this->id,
            'body' => $this->body,
            'owner' => $userId === $this->author_id,
            'created_at' => $this->created_at->diffForHumans(),
            'author' => new UserResource($this->author),
            'private' => $this->private,
            'read' => $this->read || $this->author_id === $userId,
            'attachment' => new AttachmentResource($this->attachment),
            'isCoach' => $request->user()->isCoach(),
        ];
    }
}
