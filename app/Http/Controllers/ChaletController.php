<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Admin;
use App\Models\Broker;
use App\Models\Chalet;
use Illuminate\Http\Request;
use App\Traits\AuthGuardTrait;
use App\Traits\ManagesModelsTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ChaletRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ChaletResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesAuthorizationTrait;

class ChaletController extends Controller
{
    use HandlesAuthorizationTrait;
    use ManagesModelsTrait;
    use AuthGuardTrait;
    public function showAll()
    {
        $Chalets = Chalet::get();
        return response()->json([
            'data' => ChaletResource::collection($Chalets),
            'message' => "Show All Chalets Successfully."
        ]);
    }




    public function create(ChaletRequest $request)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $chalet = Chalet::create([
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

    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            try {
                $path = $file->store('Chalet', 'public');

                $allowedExtensions = [
                    'image' => ['jpeg', 'jpg', 'png', 'gif', 'webp'],
                    'video' => ['mp4', 'mov', 'avi', 'mkv'],
                    'audio' => ['mp3', 'wav', 'aac', 'ogg', 'flac', 'm4a']
                ];

                $extension = $file->getClientOriginalExtension();
                $mediaType = null;

                foreach ($allowedExtensions as $type => $extensions) {
                    if (in_array($extension, $extensions)) {
                        $mediaType = $type;
                        break;
                    }
                }

                if ($mediaType) {
                    $chalet->media()->create([
                        'path' => $path,
                        'type' => $mediaType,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error uploading file: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Error uploading media files.',
                ], 500);
            }
        }
    }

    return response()->json([
        'data' => new ChaletResource($chalet),
        'message' => 'Chalet Created Successfully.',
    ]);
}






    public function edit(string $id)
    {
        $Chalet = Chalet::find($id);

        if (!$Chalet) {
            return response()->json([
                'message' => "Chalet not found."
            ], 404);
        }

        return response()->json([
            'data' =>new ChaletResource($Chalet),
            'message' => "Edit Chalet By ID Successfully."
        ]);
    }


public function update(ChaletRequest $request, string $id)
{
    $ids = $this->getAuthenticatedIds();

    if (!is_array($ids)) {
        return $ids;
    }
    $Chalet = Chalet::findOrFail($id);

    if (!$Chalet) {
        return response()->json([
            'message' => "Chalet not found."
        ], 404);
    }

    $Chalet->update([
        'user_id' => $ids['user_id'],
        'broker_id' => $ids['broker_id'],
        'admin_id' => $ids['admin_id'],
        "installment_id" => $request->installment_id,
        "finishe_id" => $request->finishe_id,
        "transaction_id" => $request->transaction_id,
        "property_id" => $request->property_id,
        "water_id" => $request->water_id,
        "electricty_id" => $request->electricty_id,
        // "sale" => $request->sale,
        "governorate" => $request->governorate,
        "city" => $request->city,
        "district" => $request->district,
        "street" => $request->street,
        "locationGPS" => $request->locationGPS,
        "propertyNum" => $request->propertyNum,
        "floorNum" => $request->floorNum,
        "roomNum" => $request->roomNum,
        "pathRoomNum" => $request->pathRoomNum,
        "description" => $request->description,
        "area" => $request->area,
        "gardenArea" => $request->gardenArea,
        "ownerType" => $request->ownerType,
        "creationDate" => now()->timezone('Africa/Cairo')->format('Y-m-d h:i:s'),
        // "status" => $request->status,
        "totalPrice" => $request->totalPrice,
        "installmentPrice" => $request->installmentPrice,
        "downPrice" => $request->downPrice,
        "rentPrice" => $request->rentPrice
    ]);

    if ($request->hasFile('media')) {

        $existingMedia = $Chalet->media;

        foreach ($existingMedia as $media) {
            try {

                if (Storage::disk('public')->exists($media->path)) {
                    Storage::disk('public')->delete($media->path);
                }

                DB::table('media')
                ->where('id', $media->id)
                ->delete();
            } catch (\Exception $e) {
                Log::error('Error deleting old media: ' . $e->getMessage());
            }
        }

        foreach ($request->file('media') as $file) {
            try {
                $path = $file->store('Chalet', 'public');

                $allowedExtensions = [
                    'image' => ['jpeg', 'jpg', 'png', 'gif', 'webp'],
                    'video' => ['mp4', 'mov', 'avi', 'mkv'],
                    'audio' => ['mp3', 'wav', 'aac', 'ogg', 'flac', 'm4a']
                ];

                $extension = $file->getClientOriginalExtension();
                $mediaType = null;

                foreach ($allowedExtensions as $type => $extensions) {
                    if (in_array($extension, $extensions)) {
                        $mediaType = $type;
                        break;
                    }
                }

                if ($mediaType) {
                    $Chalet->media()->create([
                        'path' => $path,
                        'type' => $mediaType,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error uploading file: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Error uploading media files.',
                ], 500);
            }
        }
    }


    return response()->json([
        'data' => new ChaletResource($Chalet),
        'message' => "Chalet updated successfully."
    ]);
}


public function notActive(string $id)
{
    $this->authorize('manage_users');
    $Chalet =Chalet::findOrFail($id);

    if (!$Chalet) {
     return response()->json([
         'message' => "Chalet not found."
     ], 404);
 }

    $Chalet->update(['status' => 'notActive']);

    return response()->json([
        'data' => new ChaletResource($Chalet),
        'message' => 'Chalet has been Not Active.'
    ]);
}
public function active(string $id)
{
    $Chalet =Chalet::findOrFail($id);

    if (!$Chalet) {
     return response()->json([
         'message' => "Chalet not found."
     ], 404);
 }

    $Chalet->update(['status' => 'active']);

    return response()->json([
        'data' => new ChaletResource($Chalet),
        'message' => 'Chalet has been Active.'
    ]);
}


public function notSold(string $id)
{

    $Chalet =Chalet::findOrFail($id);

    $authorization = $this->authorizeSale($Chalet);
    if ($authorization !== true) {
        return $authorization;
    }


    // $this->authorize('updateSale',$Chalet);

    $Chalet->update(['sale' => 'notSold']);

    return response()->json([
        'data' => new ChaletResource($Chalet),
        'message' => 'Chalet has been Not Active.'
    ]);
}

public function sold(string $id)
{
    $Chalet =Chalet::findOrFail($id);

    if (!$Chalet) {
     return response()->json([
         'message' => "Chalet not found."
     ], 404);
 }

 $authorization = $this->authorizeSale($Chalet);
 if ($authorization !== true) {
     return $authorization;
 }


// $this->authorize('updateSale',$Chalet);

    $Chalet->update(['sale' => 'sold']);

    return response()->json([
        'data' => new ChaletResource($Chalet),
        'message' => 'Chalet has been Sold.'
    ]);
}

  public function destroy(string $id)
  {
      return $this->destroyModel(Chalet::class, ChaletResource::class, $id);
  }

  public function showDeleted(){
  $Chalets=Chalet::onlyTrashed()->get();
  return response()->json([
      'data' =>ChaletResource::collection($Chalets),
      'message' => "Show Deleted Chalet Successfully."
  ]);
  }

  public function restore(string $id)
  {

  $Chalet = Chalet::withTrashed()->where('id', $id)->first();
  if (!$Chalet) {
      return response()->json([
          'message' => "Chalet not found."
      ]);
  }

  $Chalet->restore();
  return response()->json([
      'data' =>new ChaletResource($Chalet),
      'message' => "Restore Chalet By Id Successfully."
  ]);
  }

  public function forceDelete(string $id)
  {
      return $this->forceDeleteModel(Chalet::class, $id);
  }
}
