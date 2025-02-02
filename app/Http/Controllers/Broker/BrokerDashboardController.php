<?php

namespace App\Http\Controllers\Broker;

use App\Models\Broker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Broker\BrokerPropertiesResource;

class BrokerDashboardController extends Controller
{

    public function editProperties($brokerId)
    {
        $broker = Broker::with([
        'flats', 'villas', 'shops', 'lands', 'offices',
         'chalets', 'clinics','houses'
         ])
                        ->findOrFail($brokerId);
                        $properties = collect([])
        ->merge($broker->flats)
        ->merge($broker->villas)
        ->merge($broker->shops)
        ->merge($broker->lands)
        ->merge($broker->offices)
        ->merge($broker->chalets)
        ->merge($broker->clinics)
        ->merge($broker->houses);

    $broker->propertiesCount = $properties->count();
                        $broker->save();

                        return response()->json(new BrokerPropertiesResource($broker));
    }
}
