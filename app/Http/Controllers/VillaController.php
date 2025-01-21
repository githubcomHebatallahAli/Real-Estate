<?php

namespace App\Http\Controllers;

use App\Models\Villa;
use Illuminate\Http\Request;
use App\Traits\AuthGuardTrait;
use App\Traits\ManagesModelsTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VillaRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\VillaResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesAuthorizationTrait;

class VillaController extends Controller
{
    use HandlesAuthorizationTrait;
    use ManagesModelsTrait;
    use AuthGuardTrait;
    public function showAll()
    {
        $Villas = Villa::get();
        return response()->json([
            'data' => VillaResource::collection($Villas),
            'message' => "Show All Villas Successfully."
        ]);
    }


    public function create(VillaRequest $request)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Villa = Villa::create([
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
        'reciptionNum' => $request->reciptionNum,
        'bedRoomNum' => $request->bedRoomNum,
        'kitchenNum' => $request->kitchenNum,
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

    $Villa->handleFileCreateMedia($request);

    $Villa->save();

    return response()->json([
        'data' => new VillaResource($Villa),
        'message' => 'Villa Created Successfully.',
    ]);
}


    public function edit(string $id)
    {
        $Villa = Villa::find($id);

        if (!$Villa) {
            return response()->json([
                'message' => "Villa not found."
            ], 404);
        }

        return response()->json([
            'data' =>new VillaResource($Villa),
            'message' => "Edit Villa By ID Successfully."
        ]);
    }


public function update(VillaRequest $request, string $id)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Villa = Villa::findOrFail($id);

    if (!$Villa) {
        return response()->json([
            'message' => "Villa not found."
        ], 404);
    }

    $Villa->update([
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
        'reciptionNum' => $request->reciptionNum,
        'bedRoomNum' => $request->bedRoomNum,
        'kitchenNum' => $request->kitchenNum,
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
    //     if ($Villa->mainImage) {
    //         Storage::disk('public')->delete($Villa->mainImage);
    //     }
    //     $mainImagePath = $request->file('mainImage')->store('Villa/mainImages', 'public');
    //     $Villa->mainImage = $mainImagePath;
    // }

    // if ($request->hasFile('image')) {
    //     $imagePaths = [];
    //     foreach ($request->file('image') as $image) {
    //         $imagePath = $image->store('Villa/images', 'public');
    //         $imagePaths[] = $imagePath;
    //     }

    //     $Villa->image = $imagePaths;
    // }

    // if ($request->hasFile('video')) {
    //     if ($Villa->video) {
    //         Storage::disk('public')->delete($Villa->video);
    //     }
    //     $videoPath = $request->file('video')->store('Villa/videos', 'public');
    //     $Villa->video = $videoPath;
    // }

    // if ($request->hasFile('audio')) {
    //     if ($Villa->audio) {
    //         Storage::disk('public')->delete($Villa->audio);
    //     }
    //     $audioPath = $request->file('audio')->store('Villa/audios', 'public');
    //     $Villa->audio = $audioPath;
    // }

    $Villa->handleFileUpdateMedia($request);

    $Villa->save();
    return response()->json([
        'data' => new VillaResource($Villa),
        'message' => "Villa updated successfully."
    ]);
}


public function notActive(string $id)
{
    $this->authorize('manage_users');
    $Villa =Villa::findOrFail($id);

    if (!$Villa) {
     return response()->json([
         'message' => "Villa not found."
     ], 404);
 }

    $Villa->update(['status' => 'notActive']);

    return response()->json([
        'data' => new VillaResource($Villa),
        'message' => 'Villa has been Not Active.'
    ]);
}

public function active(string $id)
{
    $this->authorize('manage_users');
    $Villa =Villa::findOrFail($id);

    if (!$Villa) {
     return response()->json([
         'message' => "Villa not found."
     ], 404);
 }

    $Villa->update(['status' => 'active']);

    return response()->json([
        'data' => new VillaResource($Villa),
        'message' => 'Villa has been Active.'
    ]);
}


public function notSold(string $id)
{
    $Villa =Villa::findOrFail($id);

    $authorization = $this->authorizeSale($Villa);
    if ($authorization !== true) {
        return $authorization;
    }


    // $this->authorize('updateSale',$Villa);

    $Villa->update(['sale' => 'notSold']);

    return response()->json([
        'data' => new VillaResource($Villa),
        'message' => 'Villa has been Not Active.'
    ]);
}

public function sold(string $id)
{
    $Villa =Villa::findOrFail($id);

    if (!$Villa) {
     return response()->json([
         'message' => "Villa not found."
     ], 404);
 }

 $authorization = $this->authorizeSale($Villa);
 if ($authorization !== true) {
     return $authorization;
 }


// $this->authorize('updateSale',$Villa);

    $Villa->update(['sale' => 'sold']);

    return response()->json([
        'data' => new VillaResource($Villa),
        'message' => 'Villa has been Sold.'
    ]);
}

  public function destroy(string $id)
  {
      return $this->destroyModel(Villa::class, VillaResource::class, $id);
  }

  public function showDeleted()
  {
    $this->authorize('manage_users');
  $Villas=Villa::onlyTrashed()->get();
  return response()->json([
      'data' =>VillaResource::collection($Villas),
      'message' => "Show Deleted Villa Successfully."
  ]);
  }

  public function restore(string $id)
  {
    $this->authorize('manage_users');
  $Villa = Villa::withTrashed()->where('id', $id)->first();
  if (!$Villa) {
      return response()->json([
          'message' => "Villa not found."
      ]);
  }

  $Villa->restore();
  return response()->json([
      'data' =>new VillaResource($Villa),
      'message' => "Restore Villa By Id Successfully."
  ]);
  }

  public function forceDelete(string $id)
  {
      return $this->forceDeleteModel(Villa::class, $id);
  }
}
