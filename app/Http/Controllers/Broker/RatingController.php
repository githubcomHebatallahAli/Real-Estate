<?php

namespace App\Http\Controllers\Broker;

use App\Models\Broker;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Broker\RatingRequest;
use App\Http\Resources\Broker\RatingResource;
use App\Http\Resources\Broker\BrokerRatingResource;
use App\Http\Resources\Broker\BrokerProfileResource;

class RatingController extends Controller
{
    public function create(RatingRequest $request)
    {
        // $this->authorize('manage_users');

           $Rating =Rating::create ([
                "user_id" => $request-> user_id,
                "broker_id" => $request-> broker_id,
                "rating" => $request-> rating,
                "comment" => $request->comment,
                // "transactionNum" => $request-> transactionNum,
                "completeRate" => $request-> completeRate,
            ]);

           $Rating->save();
           return response()->json([
            'data' =>new RatingResource($Rating),
            'message' => "Rating Created Successfully."
        ]);
        }

    public function edit(string $id)
    {
        $Broker = Broker::find($id);

        if (!$Broker) {
            return response()->json([
                'message' => "Broker not found."
            ], 404);
        }

        return response()->json([
            'data' =>new BrokerProfileResource($Broker),
            'message' => "Edit Broker With His Rating & Comments By ID Successfully."
        ]);
    }

}
