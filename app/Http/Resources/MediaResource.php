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


            $mediaItems = $this->media ?? collect();  // إذا كانت media null، استخدم مجموعة فارغة

            return [
                'image' => $mediaItems->where('type', 'image')->isNotEmpty() ? $mediaItems->where('type', 'image')->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'path' => $item->path,
                        'type' => $item->type,
                    ];
                }) : [],
                'video' => $mediaItems->where('type', 'video')->isNotEmpty() ? $mediaItems->where('type', 'video')->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'path' => $item->path,
                        'type' => $item->type,
                    ];
                }) : [],
                'audio' => $mediaItems->where('type', 'audio')->isNotEmpty() ? $mediaItems->where('type', 'audio')->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'path' => $item->path,
                        'type' => $item->type,
                    ];
                }) : [],
            ];
    }
}
