<?php

namespace App\Http\Resources\Broker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrokerProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            // 'phoNum' => $this -> phoNum ,
            // 'governorate' => $this -> governorate,
            'targetPlace' => $this -> targetPlace,
            'userType' => $this -> userType,
            'commission' => $this -> commission,
            'brief' => $this -> brief,
            'realEstateType' => $this -> realEstateType,
            'image' => $this -> image



        ];
    }
}
