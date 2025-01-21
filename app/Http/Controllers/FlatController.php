<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use Illuminate\Http\Request;
use App\Traits\AuthGuardTrait;
use App\Http\Requests\FlatRequest;
use App\Traits\ManagesModelsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\FlatResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesAuthorizationTrait;

class FlatController extends Controller
{
    use HandlesAuthorizationTrait;
    use ManagesModelsTrait;
    use AuthGuardTrait;
    public function showAll()
    {
        $Flats = Flat::get();
        return response()->json([
            'data' => FlatResource::collection($Flats),
            'message' => "Show All Flats Successfully."
        ]);
    }


    public function create(FlatRequest $request)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Flat = Flat::create([
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
        'flatNum' => $request->flatNum,
        'roomNum' => $request->roomNum,
        'pathRoomNum' => $request->pathRoomNum,
        'description' => $request->description,
        'area' => $request->area,
        'gardenArea' => $request->gardenArea,
        'roofArea' => $request->roofArea,
        'ownerType' => $request->ownerType,
        'creationDate' => now()->timezone('Africa/Cairo')->format('Y-m-d H:i:s'),
        'totalPrice' => $request->totalPrice,
        'installmentPrice' => $request->installmentPrice,
        'downPrice' => $request->downPrice,
        'rentPrice' => $request->rentPrice,
    ]);

    $Flat->handleFileCreateMedia($request);

    $Flat->save();

    return response()->json([
        'data' => new FlatResource($Flat),
        'message' => 'Flat Created Successfully.',
    ]);
}


    public function edit(string $id)
    {
        $Flat = Flat::find($id);

        if (!$Flat) {
            return response()->json([
                'message' => "Flat not found."
            ], 404);
        }

        return response()->json([
            'data' =>new FlatResource($Flat),
            'message' => "Edit Flat By ID Successfully."
        ]);
    }


public function update(FlatRequest $request, string $id)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Flat = Flat::findOrFail($id);

    if (!$Flat) {
        return response()->json([
            'message' => "Flat not found."
        ], 404);
    }

    $Flat->update([
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
        'flatNum' => $request->flatNum,
        "roomNum" => $request->roomNum,
        "pathRoomNum" => $request->pathRoomNum,
        "description" => $request->description,
        "area" => $request->area,
        "gaedenArea" => $request->gardenArea,
        "roofArea" => $request->roofArea,
        "ownerType" => $request->ownerType,
        "creationDate" => now()->timezone('Africa/Cairo')->format('Y-m-d h:i:s'),
        "totalPrice" => $request->totalPrice,
        "installmentPrice" => $request->installmentPrice,
        "downPrice" => $request->downPrice,
        "rentPrice" => $request->rentPrice
    ]);

    // if ($request->hasFile('mainImage')) {
    //     if ($Flat->mainImage) {
    //         Storage::disk('public')->delete($Flat->mainImage);
    //     }
    //     $mainImagePath = $request->file('mainImage')->store('Flat/mainImages', 'public');
    //     $Flat->mainImage = $mainImagePath;
    // }

    // if ($request->hasFile('image')) {
    //     $imagePaths = [];
    //     foreach ($request->file('image') as $image) {
    //         $imagePath = $image->store('Flat/images', 'public');
    //         $imagePaths[] = $imagePath;
    //     }

    //     $Flat->image = $imagePaths;
    // }

    // if ($request->hasFile('video')) {
    //     if ($Flat->video) {
    //         Storage::disk('public')->delete($Flat->video);
    //     }
    //     $videoPath = $request->file('video')->store('Flat/videos', 'public');
    //     $Flat->video = $videoPath;
    // }

    // if ($request->hasFile('audio')) {
    //     if ($Flat->audio) {
    //         Storage::disk('public')->delete($Flat->audio);
    //     }
    //     $audioPath = $request->file('audio')->store('Flat/audios', 'public');
    //     $Flat->audio = $audioPath;
    // }

    $Flat->handleFileUpdateMedia($request);

    $Flat->save();








    return response()->json([
        'data' => new FlatResource($Flat),
        'message' => "Flat updated successfully."
    ]);
}


public function notActive(string $id)
{
    $this->authorize('manage_users');
    $Flat =Flat::findOrFail($id);

    if (!$Flat) {
     return response()->json([
         'message' => "Flat not found."
     ], 404);
 }

    $Flat->update(['status' => 'notActive']);

    return response()->json([
        'data' => new FlatResource($Flat),
        'message' => 'Flat has been Not Active.'
    ]);
}
public function active(string $id)
{
    $this->authorize('manage_users');
    $Flat =Flat::findOrFail($id);

    if (!$Flat) {
     return response()->json([
         'message' => "Flat not found."
     ], 404);
 }

    $Flat->update(['status' => 'active']);

    return response()->json([
        'data' => new FlatResource($Flat),
        'message' => 'Flat has been Active.'
    ]);
}


public function notSold(string $id)
{
    $Flat =Flat::findOrFail($id);

    $authorization = $this->authorizeSale($Flat);
    if ($authorization !== true) {
        return $authorization;
    }


    // $this->authorize('updateSale',$Flat);

    $Flat->update(['sale' => 'notSold']);

    return response()->json([
        'data' => new FlatResource($Flat),
        'message' => 'Flat has been Not Active.'
    ]);
}

public function sold(string $id)
{
    $Flat =Flat::findOrFail($id);

    if (!$Flat) {
     return response()->json([
         'message' => "Flat not found."
     ], 404);
 }

 $authorization = $this->authorizeSale($Flat);
 if ($authorization !== true) {
     return $authorization;
 }


// $this->authorize('updateSale',$Flat);

    $Flat->update(['sale' => 'sold']);

    return response()->json([
        'data' => new FlatResource($Flat),
        'message' => 'Flat has been Sold.'
    ]);
}

  public function destroy(string $id)
  {
      return $this->destroyModel(Flat::class, FlatResource::class, $id);
  }

  public function showDeleted()
  {
    $this->authorize('manage_users');
  $Flats=Flat::onlyTrashed()->get();
  return response()->json([
      'data' =>FlatResource::collection($Flats),
      'message' => "Show Deleted Flat Successfully."
  ]);
  }

  public function restore(string $id)
  {
    $this->authorize('manage_users');
  $Flat = Flat::withTrashed()->where('id', $id)->first();
  if (!$Flat) {
      return response()->json([
          'message' => "Flat not found."
      ]);
  }

  $Flat->restore();
  return response()->json([
      'data' =>new FlatResource($Flat),
      'message' => "Restore Flat By Id Successfully."
  ]);
  }

  public function forceDelete(string $id)
  {
      return $this->forceDeleteModel(Flat::class, $id);
  }
}
