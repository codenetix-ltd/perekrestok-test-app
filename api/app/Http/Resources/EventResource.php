<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'is_viewed' => $this->resource->is_viewed,
            'is_hidden' => $this->resource->is_hidden,
            'message' => $this->resource->message,
            'link' => $this->resource->link,
            'user' => $this->resource->user ? (new UserResource($this->resource->user))->toArray($request) : null,
            'fired_at' => $this->resource->fired_at->format('d.m.Y H:s')
        ];
    }
}
