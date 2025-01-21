<?php

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;
use App\Traits\AuthGuardTrait;
use App\Http\Requests\LandRequest;
use App\Traits\ManagesModelsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\LandResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesAuthorizationTrait;

class LandController extends Controller
{
    use HandlesAuthorizationTrait;
    use ManagesModelsTrait;
    use AuthGuardTrait;
    public function showAll()
    {
        $Lands = Land::get();
        return response()->json([
            'data' => LandResource::collection($Lands),
            'message' => "Show All Lands Successfully."
        ]);
    }


    public function create(LandRequest $request)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Land = Land::create([
        'user_id' => $ids['user_id'],
        'broker_id' => $ids['broker_id'],
        'admin_id' => $ids['admin_id'],
        'installment_id' => $request->installment_id,
        'transaction_id' => $request->transaction_id,
        'property_id' => $request->property_id,
        'water_id' => $request->water_id,
        'electricty_id' => $request->electricty_id,
        'sale' => 'notSold',
        'status' => 'notActive',
        'governorate' => $request->governorate,
        'city' => $request->city,
        'district' => $request->district,
        'street' => $request->street,
        'locationGPS' => $request->locationGPS,
        'propertyNum' => $request->propertyNum,
        'description' => $request->description,
        'area' => $request->area,
        'length' => $request->length,
        'width' => $request->width,
        'ownerType' => $request->ownerType,
        'creationDate' => now()->timezone('Africa/Cairo')->format('Y-m-d H:i:s'),
        'totalPrice' => $request->totalPrice,
        'installmentPrice' => $request->installmentPrice,
        'downPrice' => $request->downPrice,
        'rentPrice' => $request->rentPrice,
    ]);

    $Land->handleFileCreateMedia($request);

    $Land->save();

    return response()->json([
        'data' => new LandResource($Land),
        'message' => 'Land Created Successfully.',
    ]);
}


    public function edit(string $id)
    {
        $Land = Land::find($id);

        if (!$Land) {
            return response()->json([
                'message' => "Land not found."
            ], 404);
        }

        return response()->json([
            'data' =>new LandResource($Land),
            'message' => "Edit Land By ID Successfully."
        ]);
    }


public function update(LandRequest $request, string $id)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Land = Land::findOrFail($id);

    if (!$Land) {
        return response()->json([
            'message' => "Land not found."
        ], 404);
    }

    $Land->update([
        'user_id' => $ids['user_id'],
        'broker_id' => $ids['broker_id'],
        'admin_id' => $ids['admin_id'],
        "installment_id" => $request->installment_id,
        "transaction_id" => $request->transaction_id,
        "property_id" => $request->property_id,
        "water_id" => $request->water_id,
        "electricty_id" => $request->electricty_id,
        "governorate" => $request->governorate,
        "city" => $request->city,
        "district" => $request->district,
        "street" => $request->street,
        "locationGPS" => $request->locationGPS,
        "propertyNum" => $request->propertyNum,
        "description" => $request->description,
        "area" => $request->area,
        'length' => $request->length,
        'width' => $request->width,
        "ownerType" => $request->ownerType,
        "creationDate" => now()->timezone('Africa/Cairo')->format('Y-m-d h:i:s'),
        "totalPrice" => $request->totalPrice,
        "installmentPrice" => $request->installmentPrice,
        "downPrice" => $request->downPrice,
        "rentPrice" => $request->rentPrice
    ]);

    $Land->handleFileUpdateMedia($request);

    $Land->save();

    return response()->json([
        'data' => new LandResource($Land),
        'message' => "Land updated successfully."
    ]);
}


public function notActive(string $id)
{
    $this->authorize('manage_users');
    $Land =Land::findOrFail($id);

    if (!$Land) {
     return response()->json([
         'message' => "Land not found."
     ], 404);
 }

    $Land->update(['status' => 'notActive']);

    return response()->json([
        'data' => new LandResource($Land),
        'message' => 'Land has been Not Active.'
    ]);
}

public function active(string $id)
{
    $this->authorize('manage_users');
    $Land =Land::findOrFail($id);

    if (!$Land) {
     return response()->json([
         'message' => "Land not found."
     ], 404);
 }

    $Land->update(['status' => 'active']);

    return response()->json([
        'data' => new LandResource($Land),
        'message' => 'Land has been Active.'
    ]);
}


public function notSold(string $id)
{
    $Land =Land::findOrFail($id);

    $authorization = $this->authorizeSale($Land);
    if ($authorization !== true) {
        return $authorization;
    }


    // $this->authorize('updateSale',$Land);

    $Land->update(['sale' => 'notSold']);

    return response()->json([
        'data' => new LandResource($Land),
        'message' => 'Land has been Not Active.'
    ]);
}

public function sold(string $id)
{
    $Land =Land::findOrFail($id);

    if (!$Land) {
     return response()->json([
         'message' => "Land not found."
     ], 404);
 }

 $authorization = $this->authorizeSale($Land);
 if ($authorization !== true) {
     return $authorization;
 }


// $this->authorize('updateSale',$Land);

    $Land->update(['sale' => 'sold']);

    return response()->json([
        'data' => new LandResource($Land),
        'message' => 'Land has been Sold.'
    ]);
}

  public function destroy(string $id)
  {
      return $this->destroyModel(Land::class, LandResource::class, $id);
  }

  public function showDeleted()
  {
    $this->authorize('manage_users');
  $Lands=Land::onlyTrashed()->get();
  return response()->json([
      'data' =>LandResource::collection($Lands),
      'message' => "Show Deleted Land Successfully."
  ]);
  }

  public function restore(string $id)
  {
    $this->authorize('manage_users');
  $Land = Land::withTrashed()->where('id', $id)->first();
  if (!$Land) {
      return response()->json([
          'message' => "Land not found."
      ]);
  }

  $Land->restore();
  return response()->json([
      'data' =>new LandResource($Land),
      'message' => "Restore Land By Id Successfully."
  ]);
  }

  public function forceDelete(string $id)
  {
      return $this->forceDeleteModel(Land::class, $id);
  }
}
