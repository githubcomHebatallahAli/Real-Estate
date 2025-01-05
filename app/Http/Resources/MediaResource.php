<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            // 'path' => $this->path,
            // 'type' => $this->type,
            // 'mediaable_type' => $this->mediaable_type,
            // 'mediaable_id' => $this->mediaable_id,


         'id' => $this->id,
            'image' => $this->media->where('type', 'image')->map(function ($mediaItem) {
                return [
                    'id' => $mediaItem->id,
                    'path' => $mediaItem->path,  // المسار كما هو
                    'type' => $mediaItem->type,
                ];
            }),

            'video' => $this->media->where('type', 'video')->map(function ($mediaItem) {
                return [
                    'id' => $mediaItem->id,
                    'path' => $mediaItem->path,  // المسار كما هو
                    'type' => $mediaItem->type,
                ];
            }),

            'audio' => $this->media->where('type', 'audio')->map(function ($mediaItem) {
                return [
                    'id' => $mediaItem->id,
                    'path' => $mediaItem->path,  // المسار كما هو
                    'type' => $mediaItem->type,
                ];
            }),
        ];
    }
}
