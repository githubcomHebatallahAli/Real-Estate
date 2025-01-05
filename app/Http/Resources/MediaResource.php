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
        // return [
            // 'id' => $this->id,
            // 'path' => $this->path,
            // 'type' => $this->type,
            // 'mediaable_type' => $this->mediaable_type,
            // 'mediaable_id' => $this->mediaable_id,


            $images = $this->media->where('type', 'image');
            $videos = $this->media->where('type', 'video');
            $audios = $this->media->where('type', 'audio');

            return [
                'image' => $images->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'path' => $media->path,
                        'type' => $media->type,
                    ];
                }),
                'video' => $videos->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'path' => $media->path,
                        'type' => $media->type,
                    ];
                }),
                'audio' => $audios->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'path' => $media->path,
                        'type' => $media->type,
                    ];
                }),
            ];
    }
}
