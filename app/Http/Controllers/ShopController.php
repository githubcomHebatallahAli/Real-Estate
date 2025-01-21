<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Traits\AuthGuardTrait;
use App\Http\Requests\ShopRequest;
use App\Traits\ManagesModelsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ShopResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesAuthorizationTrait;

class ShopController extends Controller
{
    use HandlesAuthorizationTrait;
    use ManagesModelsTrait;
    use AuthGuardTrait;
    public function showAll()
    {
        $Shops = Shop::get();
        return response()->json([
            'data' => ShopResource::collection($Shops),
            'message' => "Show All Shops Successfully."
        ]);
    }


    public function create(ShopRequest $request)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Shop = Shop::create([
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

    $Shop->handleFileCreateMedia($request);

    $Shop->save();
    return response()->json([
        'data' => new ShopResource($Shop),
        'message' => 'Shop Created Successfully.',
    ]);
}


    public function edit(string $id)
    {
        $Shop = Shop::find($id);

        if (!$Shop) {
            return response()->json([
                'message' => "Shop not found."
            ], 404);
        }

        return response()->json([
            'data' =>new ShopResource($Shop),
            'message' => "Edit Shop By ID Successfully."
        ]);
    }


public function update(ShopRequest $request, string $id)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Shop = Shop::findOrFail($id);

    if (!$Shop) {
        return response()->json([
            'message' => "Shop not found."
        ], 404);
    }

    $Shop->update([
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

    // if ($request->hasFile('mainImage')) {
    //     if ($Shop->mainImage) {
    //         Storage::disk('public')->delete($Shop->mainImage);
    //     }
    //     $mainImagePath = $request->file('mainImage')->store('Shop/mainImages', 'public');
    //     $Shop->mainImage = $mainImagePath;
    // }

    // if ($request->hasFile('image')) {
    //     $imagePaths = [];
    //     foreach ($request->file('image') as $image) {
    //         $imagePath = $image->store('Shop/images', 'public');
    //         $imagePaths[] = $imagePath;
    //     }

    //     $Shop->image = $imagePaths;
    // }

    // if ($request->hasFile('video')) {
    //     if ($Shop->video) {
    //         Storage::disk('public')->delete($Shop->video);
    //     }
    //     $videoPath = $request->file('video')->store('Shop/videos', 'public');
    //     $Shop->video = $videoPath;
    // }

    // if ($request->hasFile('audio')) {
    //     if ($Shop->audio) {
    //         Storage::disk('public')->delete($Shop->audio);
    //     }
    //     $audioPath = $request->file('audio')->store('Shop/audios', 'public');
    //     $Shop->audio = $audioPath;
    // }

    $Shop->handleFileUpdateMedia($request);

    $Shop->save();


    return response()->json([
        'data' => new ShopResource($Shop),
        'message' => "Shop updated successfully."
    ]);
}


public function notActive(string $id)
{
    $this->authorize('manage_users');
    $Shop =Shop::findOrFail($id);

    if (!$Shop) {
     return response()->json([
         'message' => "Shop not found."
     ], 404);
 }

    $Shop->update(['status' => 'notActive']);

    return response()->json([
        'data' => new ShopResource($Shop),
        'message' => 'Shop has been Not Active.'
    ]);
}
public function active(string $id)
{
    $this->authorize('manage_users');
    $Shop =Shop::findOrFail($id);

    if (!$Shop) {
     return response()->json([
         'message' => "Shop not found."
     ], 404);
 }

    $Shop->update(['status' => 'active']);

    return response()->json([
        'data' => new ShopResource($Shop),
        'message' => 'Shop has been Active.'
    ]);
}


public function notSold(string $id)
{
    $Shop =Shop::findOrFail($id);

    $authorization = $this->authorizeSale($Shop);
    if ($authorization !== true) {
        return $authorization;
    }


    // $this->authorize('updateSale',$Shop);

    $Shop->update(['sale' => 'notSold']);

    return response()->json([
        'data' => new ShopResource($Shop),
        'message' => 'Shop has been Not Active.'
    ]);
}

public function sold(string $id)
{
    $Shop =Shop::findOrFail($id);

    if (!$Shop) {
     return response()->json([
         'message' => "Shop not found."
     ], 404);
 }

 $authorization = $this->authorizeSale($Shop);
 if ($authorization !== true) {
     return $authorization;
 }


// $this->authorize('updateSale',$Shop);

    $Shop->update(['sale' => 'sold']);

    return response()->json([
        'data' => new ShopResource($Shop),
        'message' => 'Shop has been Sold.'
    ]);
}

  public function destroy(string $id)
  {
      return $this->destroyModel(Shop::class, ShopResource::class, $id);
  }

  public function showDeleted()
  {
    $this->authorize('manage_users');
  $Shops=Shop::onlyTrashed()->get();
  return response()->json([
      'data' =>ShopResource::collection($Shops),
      'message' => "Show Deleted Shop Successfully."
  ]);
  }

  public function restore(string $id)
  {
    $this->authorize('manage_users');
  $Shop = Shop::withTrashed()->where('id', $id)->first();
  if (!$Shop) {
      return response()->json([
          'message' => "Shop not found."
      ]);
  }

  $Shop->restore();
  return response()->json([
      'data' =>new ShopResource($Shop),
      'message' => "Restore Shop By Id Successfully."
  ]);
  }

  public function forceDelete(string $id)
  {
      return $this->forceDeleteModel(Shop::class, $id);
  }
}
