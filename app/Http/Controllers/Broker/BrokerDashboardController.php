<?php

namespace App\Http\Controllers\Broker;

use App\Models\Broker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrokerDashboardController extends Controller
{

    public function edit($brokerId)
    {

        $broker = Broker::with([
        'flats', 'villas', 'shops', 'lands', 'offices',
         'chalets', 'clinics','houses'
         ])
                        ->findOrFail($brokerId);

        return response()->json($broker);
    }
}
