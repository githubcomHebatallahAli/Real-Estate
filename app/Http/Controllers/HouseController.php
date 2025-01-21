<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use App\Traits\AuthGuardTrait;
use App\Traits\ManagesModelsTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\HouseRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\HouseResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesAuthorizationTrait;

class HouseController extends Controller
{
    use HandlesAuthorizationTrait;
    use ManagesModelsTrait;
    use AuthGuardTrait;
    public function showAll()
    {
        $Houses = House::get();
        return response()->json([
            'data' => HouseResource::collection($Houses),
            'message' => "Show All Houses Successfully."
        ]);
    }


    public function create(HouseRequest $request)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $House = House::create([
        'user_id' => $ids['user_id'],
        'broker_id' => $ids['broker_id'],
        'admin_id' => $ids['admin_id'],
        'installment_id' => $request->installment_id,
        'finishe_id' => $request->finishe_id,
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
        'floorNum' => $request->floorNum,
        'floorFlatNum' => $request->floorFlatNum,
        'roomNum' => $request->roomNum,
        'pathRoomNum' => $request->pathRoomNum,
        'description' => $request->description,
        'area' => $request->area,
        'gardenArea' => $request->gardenArea,
        'ownerType' => $request->ownerType,
        'creationDate' => now()->timezone('Africa/Cairo')->format('Y-m-d H:i:s'),
        'totalPrice' => $request->totalPrice,
        'installmentPrice' => $request->installmentPrice,
        'downPrice' => $request->downPrice,
        'rentPrice' => $request->rentPrice,
    ]);

    $House->handleFileCreateMedia($request);

    $House->save();

    return response()->json([
        'data' => new HouseResource($House),
        'message' => 'House Created Successfully.',
    ]);
}


    public function edit(string $id)
    {
        $House = House::find($id);

        if (!$House) {
            return response()->json([
                'message' => "House not found."
            ], 404);
        }

        return response()->json([
            'data' =>new HouseResource($House),
            'message' => "Edit House By ID Successfully."
        ]);
    }


public function update(HouseRequest $request, string $id)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $House = House::findOrFail($id);

    if (!$House) {
        return response()->json([
            'message' => "House not found."
        ], 404);
    }

    $House->update([
        'user_id' => $ids['user_id'],
        'broker_id' => $ids['broker_id'],
        'admin_id' => $ids['admin_id'],
        "installment_id" => $request->installment_id,
        "finishe_id" => $request->finishe_id,
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
        "floorNum" => $request->floorNum,
        'floorFlatNum' => $request->floorFlatNum,
        "roomNum" => $request->roomNum,
        "pathRoomNum" => $request->pathRoomNum,
        "description" => $request->description,
        "area" => $request->area,
        "gaedenArea" => $request->gardenArea,
        "ownerType" => $request->ownerType,
        "creationDate" => now()->timezone('Africa/Cairo')->format('Y-m-d h:i:s'),
        "totalPrice" => $request->totalPrice,
        "installmentPrice" => $request->installmentPrice,
        "downPrice" => $request->downPrice,
        "rentPrice" => $request->rentPrice
    ]);

    // if ($request->hasFile('mainImage')) {
    //     if ($House->mainImage) {
    //         Storage::disk('public')->delete($House->mainImage);
    //     }
    //     $mainImagePath = $request->file('mainImage')->store('House/mainImages', 'public');
    //     $House->mainImage = $mainImagePath;
    // }

    // if ($request->hasFile('image')) {
    //     $imagePaths = [];
    //     foreach ($request->file('image') as $image) {
    //         $imagePath = $image->store('House/images', 'public');
    //         $imagePaths[] = $imagePath;
    //     }

    //     $House->image = $imagePaths;
    // }

    // if ($request->hasFile('video')) {
    //     if ($House->video) {
    //         Storage::disk('public')->delete($House->video);
    //     }
    //     $videoPath = $request->file('video')->store('House/videos', 'public');
    //     $House->video = $videoPath;
    // }

    // if ($request->hasFile('audio')) {
    //     if ($House->audio) {
    //         Storage::disk('public')->delete($House->audio);
    //     }
    //     $audioPath = $request->file('audio')->store('House/audios', 'public');
    //     $House->audio = $audioPath;
    // }

    $House->handleFileUpdateMedia($request);

    $House->save();
    return response()->json([
        'data' => new HouseResource($House),
        'message' => "House updated successfully."
    ]);
}


public function notActive(string $id)
{
    $this->authorize('manage_users');
    $House =House::findOrFail($id);

    if (!$House) {
     return response()->json([
         'message' => "House not found."
     ], 404);
 }

    $House->update(['status' => 'notActive']);

    return response()->json([
        'data' => new HouseResource($House),
        'message' => 'House has been Not Active.'
    ]);
}

public function active(string $id)
{
    $this->authorize('manage_users');
    $House =House::findOrFail($id);

    if (!$House) {
     return response()->json([
         'message' => "House not found."
     ], 404);
 }

    $House->update(['status' => 'active']);

    return response()->json([
        'data' => new HouseResource($House),
        'message' => 'House has been Active.'
    ]);
}


public function notSold(string $id)
{
    $House =House::findOrFail($id);

    $authorization = $this->authorizeSale($House);
    if ($authorization !== true) {
        return $authorization;
    }


    // $this->authorize('updateSale',$House);

    $House->update(['sale' => 'notSold']);

    return response()->json([
        'data' => new HouseResource($House),
        'message' => 'House has been Not Active.'
    ]);
}

public function sold(string $id)
{
    $House =House::findOrFail($id);

    if (!$House) {
     return response()->json([
         'message' => "House not found."
     ], 404);
 }

 $authorization = $this->authorizeSale($House);
 if ($authorization !== true) {
     return $authorization;
 }


// $this->authorize('updateSale',$House);

    $House->update(['sale' => 'sold']);

    return response()->json([
        'data' => new HouseResource($House),
        'message' => 'House has been Sold.'
    ]);
}

  public function destroy(string $id)
  {
      return $this->destroyModel(House::class, HouseResource::class, $id);
  }

  public function showDeleted()
  {
    $this->authorize('manage_users');
  $Houses=House::onlyTrashed()->get();
  return response()->json([
      'data' =>HouseResource::collection($Houses),
      'message' => "Show Deleted House Successfully."
  ]);
  }

  public function restore(string $id)
  {
    $this->authorize('manage_users');
  $House = House::withTrashed()->where('id', $id)->first();
  if (!$House) {
      return response()->json([
          'message' => "House not found."
      ]);
  }

  $House->restore();
  return response()->json([
      'data' =>new HouseResource($House),
      'message' => "Restore House By Id Successfully."
  ]);
  }

  public function forceDelete(string $id)
  {
      return $this->forceDeleteModel(House::class, $id);
  }
}
