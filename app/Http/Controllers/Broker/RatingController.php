<?php

namespace App\Http\Controllers\Broker;

use App\Models\Broker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Broker\BrokerRatingResource;

class BrokerController extends Controller
{
    public function edit(string $id)
    {
        $Broker = Broker::find($id);

        if (!$Broker) {
            return response()->json([
                'message' => "Broker not found."
            ], 404);
        }

        return response()->json([
            'data' =>new BrokerRatingResource($Broker),
            'message' => "Edit Broker With His Rating & Comments By ID Successfully."
        ]);
    }

}
