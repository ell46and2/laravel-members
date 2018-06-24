<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            'filetype' => $this->filetype,
            'uid' => $this->uid,
            'processed' => $this->processed,
            'thumbnail' => $this->getThumb(),
            'video_url' => $this->getStreamUrl(),
            // 'image_url' => $this->getImageUrl()
        ];
    }
}
