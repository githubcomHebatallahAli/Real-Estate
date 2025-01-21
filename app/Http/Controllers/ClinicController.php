<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use App\Traits\AuthGuardTrait;
use App\Traits\ManagesModelsTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ClinicRequest;
use App\Http\Resources\ClinicResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesAuthorizationTrait;

class ClinicController extends Controller
{
    use HandlesAuthorizationTrait;
    use ManagesModelsTrait;
    use AuthGuardTrait;
    public function showAll()
    {
        $Clinics = Clinic::get();
        return response()->json([
            'data' => ClinicResource::collection($Clinics),
            'message' => "Show All Clinics Successfully."
        ]);
    }


    public function create(ClinicRequest $request)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Clinic = Clinic::create([
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
        'ownerType' => $request->ownerType,
        'creationDate' => now()->timezone('Africa/Cairo')->format('Y-m-d H:i:s'),
        'totalPrice' => $request->totalPrice,
        'installmentPrice' => $request->installmentPrice,
        'downPrice' => $request->downPrice,
        'rentPrice' => $request->rentPrice,
    ]);

    $Clinic->handleFileCreateMedia($request);

    $Clinic->save();




    return response()->json([
        'data' => new ClinicResource($Clinic),
        'message' => 'Clinic Created Successfully.',
    ]);
}






    public function edit(string $id)
    {
        $Clinic = Clinic::find($id);

        if (!$Clinic) {
            return response()->json([
                'message' => "Clinic not found."
            ], 404);
        }

        return response()->json([
            'data' =>new ClinicResource($Clinic),
            'message' => "Edit Clinic By ID Successfully."
        ]);
    }


public function update(ClinicRequest $request, string $id)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Clinic = Clinic::findOrFail($id);

    if (!$Clinic) {
        return response()->json([
            'message' => "Clinic not found."
        ], 404);
    }

    $Clinic->update([
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
        "ownerType" => $request->ownerType,
        "creationDate" => now()->timezone('Africa/Cairo')->format('Y-m-d h:i:s'),
        "totalPrice" => $request->totalPrice,
        "installmentPrice" => $request->installmentPrice,
        "downPrice" => $request->downPrice,
        "rentPrice" => $request->rentPrice
    ]);

    // if ($request->hasFile('mainImage')) {
    //     if ($Clinic->mainImage) {
    //         Storage::disk('public')->delete($Clinic->mainImage);
    //     }
    //     $mainImagePath = $request->file('mainImage')->store('Clinic/mainImages', 'public');
    //     $Clinic->mainImage = $mainImagePath;
    // }

    // if ($request->hasFile('image')) {
    //     $imagePaths = [];
    //     foreach ($request->file('image') as $image) {
    //         $imagePath = $image->store('Clinic/images', 'public');
    //         $imagePaths[] = $imagePath;
    //     }

    //     $Clinic->image = $imagePaths;
    // }

    // if ($request->hasFile('video')) {
    //     if ($Clinic->video) {
    //         Storage::disk('public')->delete($Clinic->video);
    //     }
    //     $videoPath = $request->file('video')->store('Clinic/videos', 'public');
    //     $Clinic->video = $videoPath;
    // }

    // if ($request->hasFile('audio')) {
    //     if ($Clinic->audio) {
    //         Storage::disk('public')->delete($Clinic->audio);
    //     }
    //     $audioPath = $request->file('audio')->store('Clinic/audios', 'public');
    //     $Clinic->audio = $audioPath;
    // }

    $Clinic->handleFileUpdateMedia($request);

    $Clinic->save();





    return response()->json([
        'data' => new ClinicResource($Clinic),
        'message' => "Clinic updated successfully."
    ]);
}


public function notActive(string $id)
{
    $this->authorize('manage_users');
    $Clinic =Clinic::findOrFail($id);

    if (!$Clinic) {
     return response()->json([
         'message' => "Clinic not found."
     ], 404);
 }

    $Clinic->update(['status' => 'notActive']);

    return response()->json([
        'data' => new ClinicResource($Clinic),
        'message' => 'Clinic has been Not Active.'
    ]);
}
public function active(string $id)
{
    $this->authorize('manage_users');
    $Clinic =Clinic::findOrFail($id);

    if (!$Clinic) {
     return response()->json([
         'message' => "Clinic not found."
     ], 404);
 }

    $Clinic->update(['status' => 'active']);

    return response()->json([
        'data' => new ClinicResource($Clinic),
        'message' => 'Clinic has been Active.'
    ]);
}


public function notSold(string $id)
{

    $Clinic =Clinic::findOrFail($id);

    $authorization = $this->authorizeSale($Clinic);
    if ($authorization !== true) {
        return $authorization;
    }


    // $this->authorize('updateSale',$Clinic);

    $Clinic->update(['sale' => 'notSold']);

    return response()->json([
        'data' => new ClinicResource($Clinic),
        'message' => 'Clinic has been Not Active.'
    ]);
}

public function sold(string $id)
{
    $Clinic =Clinic::findOrFail($id);

    if (!$Clinic) {
     return response()->json([
         'message' => "Clinic not found."
     ], 404);
 }

 $authorization = $this->authorizeSale($Clinic);
 if ($authorization !== true) {
     return $authorization;
 }


// $this->authorize('updateSale',$Clinic);

    $Clinic->update(['sale' => 'sold']);

    return response()->json([
        'data' => new ClinicResource($Clinic),
        'message' => 'Clinic has been Sold.'
    ]);
}

  public function destroy(string $id)
  {
      return $this->destroyModel(Clinic::class, ClinicResource::class, $id);
  }

  public function showDeleted()
  {
    $this->authorize('manage_users');
  $Clinics=Clinic::onlyTrashed()->get();
  return response()->json([
      'data' =>ClinicResource::collection($Clinics),
      'message' => "Show Deleted Clinic Successfully."
  ]);
  }

  public function restore(string $id)
  {
    $this->authorize('manage_users');
  $Clinic = Clinic::withTrashed()->where('id', $id)->first();
  if (!$Clinic) {
      return response()->json([
          'message' => "Clinic not found."
      ]);
  }

  $Clinic->restore();
  return response()->json([
      'data' =>new ClinicResource($Clinic),
      'message' => "Restore Clinic By Id Successfully."
  ]);
  }

  public function forceDelete(string $id)
  {
      return $this->forceDeleteModel(Clinic::class, $id);
  }
}
