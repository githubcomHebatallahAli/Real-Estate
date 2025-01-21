<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use App\Traits\AuthGuardTrait;
use App\Traits\ManagesModelsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\OfficeRequest;
use App\Http\Resources\OfficeResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesAuthorizationTrait;

class OfficeController extends Controller
{
    use HandlesAuthorizationTrait;
    use ManagesModelsTrait;
    use AuthGuardTrait;
    public function showAll()
    {
        $Offices = Office::get();
        return response()->json([
            'data' => OfficeResource::collection($Offices),
            'message' => "Show All Offices Successfully."
        ]);
    }


    public function create(OfficeRequest $request)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Office = Office::create([
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
        'ownerType' => $request->ownerType,
        'creationDate' => now()->timezone('Africa/Cairo')->format('Y-m-d H:i:s'),
        'totalPrice' => $request->totalPrice,
        'installmentPrice' => $request->installmentPrice,
        'downPrice' => $request->downPrice,
        'rentPrice' => $request->rentPrice,
    ]);

$Office->handleFileCreateMedia($request);

    $Office->save();

    return response()->json([
        'data' => new OfficeResource($Office),
        'message' => 'Office Created Successfully.',
    ]);
}


    public function edit(string $id)
    {
        $Office = Office::find($id);

        if (!$Office) {
            return response()->json([
                'message' => "Office not found."
            ], 404);
        }

        return response()->json([
            'data' =>new OfficeResource($Office),
            'message' => "Edit Office By ID Successfully."
        ]);
    }


public function update(OfficeRequest $request, string $id)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Office = Office::findOrFail($id);

    if (!$Office) {
        return response()->json([
            'message' => "Office not found."
        ], 404);
    }

    $Office->update([
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
    //     if ($Office->mainImage) {
    //         Storage::disk('public')->delete($Office->mainImage);
    //     }
    //     $mainImagePath = $request->file('mainImage')->store('Office/mainImages', 'public');
    //     $Office->mainImage = $mainImagePath;
    // }

    // if ($request->hasFile('image')) {
    //     $imagePaths = [];
    //     foreach ($request->file('image') as $image) {
    //         $imagePath = $image->store('Office/images', 'public');
    //         $imagePaths[] = $imagePath;
    //     }

    //     $Office->image = $imagePaths;
    // }

    // if ($request->hasFile('video')) {
    //     if ($Office->video) {
    //         Storage::disk('public')->delete($Office->video);
    //     }
    //     $videoPath = $request->file('video')->store('Office/videos', 'public');
    //     $Office->video = $videoPath;
    // }

    // if ($request->hasFile('audio')) {
    //     if ($Office->audio) {
    //         Storage::disk('public')->delete($Office->audio);
    //     }
    //     $audioPath = $request->file('audio')->store('Office/audios', 'public');
    //     $Office->audio = $audioPath;
    // }
    $Office->handleFileUpdateMedia($request);

    $Office->save();

    return response()->json([
        'data' => new OfficeResource($Office),
        'message' => "Office updated successfully."
    ]);
}


public function notActive(string $id)
{
    $this->authorize('manage_users');
    $Office =Office::findOrFail($id);

    if (!$Office) {
     return response()->json([
         'message' => "Office not found."
     ], 404);
 }

    $Office->update(['status' => 'notActive']);

    return response()->json([
        'data' => new OfficeResource($Office),
        'message' => 'Office has been Not Active.'
    ]);
}
public function active(string $id)
{
    $this->authorize('manage_users');
    $Office =Office::findOrFail($id);

    if (!$Office) {
     return response()->json([
         'message' => "Office not found."
     ], 404);
 }

    $Office->update(['status' => 'active']);

    return response()->json([
        'data' => new OfficeResource($Office),
        'message' => 'Office has been Active.'
    ]);
}


public function notSold(string $id)
{
    $Office =Office::findOrFail($id);

    $authorization = $this->authorizeSale($Office);
    if ($authorization !== true) {
        return $authorization;
    }


    // $this->authorize('updateSale',$Office);

    $Office->update(['sale' => 'notSold']);

    return response()->json([
        'data' => new OfficeResource($Office),
        'message' => 'Office has been Not Active.'
    ]);
}

public function sold(string $id)
{
    $Office =Office::findOrFail($id);

    if (!$Office) {
     return response()->json([
         'message' => "Office not found."
     ], 404);
 }

 $authorization = $this->authorizeSale($Office);
 if ($authorization !== true) {
     return $authorization;
 }


// $this->authorize('updateSale',$Office);

    $Office->update(['sale' => 'sold']);

    return response()->json([
        'data' => new OfficeResource($Office),
        'message' => 'Office has been Sold.'
    ]);
}

  public function destroy(string $id)
  {
      return $this->destroyModel(Office::class, OfficeResource::class, $id);
  }

  public function showDeleted()
  {
    $this->authorize('manage_users');
  $Offices=Office::onlyTrashed()->get();
  return response()->json([
      'data' =>OfficeResource::collection($Offices),
      'message' => "Show Deleted Office Successfully."
  ]);
  }

  public function restore(string $id)
  {
    $this->authorize('manage_users');
  $Office = Office::withTrashed()->where('id', $id)->first();
  if (!$Office) {
      return response()->json([
          'message' => "Office not found."
      ]);
  }

  $Office->restore();
  return response()->json([
      'data' =>new OfficeResource($Office),
      'message' => "Restore Office By Id Successfully."
  ]);
  }

  public function forceDelete(string $id)
  {
      return $this->forceDeleteModel(Office::class, $id);
  }
}
