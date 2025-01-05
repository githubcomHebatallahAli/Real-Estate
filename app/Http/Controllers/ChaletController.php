<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Chalet;
use Illuminate\Http\Request;
use App\Traits\ManagesModelsTrait;
use App\Http\Requests\ChaletRequest;
use App\Http\Resources\ChaletResource;
use Illuminate\Support\Facades\Storage;

class ChaletController extends Controller
{
    use ManagesModelsTrait;
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
    if (auth()->guard('broker')->check()) {
        $broker = auth()->guard('broker')->user();
        $brokerId = $broker->id;
        $userId = null;
    } elseif (auth()->guard('api')->check()) {
        $user = auth()->guard('api')->user();
        $userId = $user->id;
        $brokerId = null;
    } else {
        return response()->json([
            'message' => 'Unauthenticated.',
        ], 401);
    }

    $chalet = Chalet::create([
        'user_id' => $userId,
        'broker_id' => $brokerId,
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



//     public function update(ChaletRequest $request, string $id)
//     {
//        $Chalet =Chalet::findOrFail($id);

//        if (!$Chalet) {
//         return response()->json([
//             'message' => "Chalet not found."
//         ], 404);
//     }
//        $Chalet->update([
//                 "installment_id" => $request-> installment_id,
//                 "finishe_id" => $request-> finishe_id,
//                 "transaction_id" => $request-> transaction_id,
//                 "property_id" => $request-> property_id,
//                 "water_id" => $request-> water_id,
//                 "electricty_id" => $request-> electricty_id,
//                 "sale" => $request-> sale,
//                 "governorate" => $request-> governorate,
//                 "city" => $request-> city,
//                 "district" => $request-> district,
//                 "street" => $request-> street,
//                 "locationGPS" => $request-> locationGPS,
//                 "propertyNum" => $request-> propertyNum,
//                 "floorNum" => $request-> floorNum,
//                 "roomNum" => $request-> roomNum,
//                 "pathRoomNum" => $request-> pathRoomNum,
//                 "description" => $request-> description,
//                 "area" => $request-> area,
//                 "gardenArea" => $request-> gardenArea,
//                 "ownerType" => $request-> ownerType,
//                 "creationDate" => now()->timezone('Africa/Cairo')
//                 ->format('Y-m-d h:i:s'),
//                 "status" => $request-> status,
//                 "totalPrice" => $request-> totalPrice,
//                 "installmentPrice" => $request-> installmentPrice,
//                 "downPrice" => $request-> downPrice,
//                 "rentPrice" => $request-> rentPrice
//         ]);

//         if ($request->hasFile('media')) {
//             $existingMedia = $Chalet->media;

//             foreach ($existingMedia as $media) {
//                 try {
//                 if (Storage::disk('public')->exists($media->path)) {
//                     Storage::disk('public')->delete($media->path);
//                 }
//                 $media->delete();
//             }

//             foreach ($request->file('media') as $file) {
//                 $path = $file->store('Chalet', 'public');

//                 $allowedExtensions = [
//                     'image' => ['jpeg', 'jpg', 'png', 'gif', 'webp'],
//                     'video' => ['mp4', 'mov', 'avi', 'mkv'],
//                     'audio' => ['mp3', 'wav', 'aac', 'ogg', 'flac', 'm4a']
//                 ];

//                 $extension = $file->getClientOriginalExtension();
//                 $mediaType = null;
//                 foreach ($allowedExtensions as $type => $extensions) {
//                     if (in_array($extension, $extensions)) {
//                         $mediaType = $type;
//                         break;
//                     }
//                 }

//                 if ($mediaType) {
//                     $Chalet->media()->create([
//                         'path' => $path,
//                         'type' => $mediaType,
//                     ]);
//                 }
//             } catch (\Exception $e) {
//                 Log::error('Error uploading file: ' . $e->getMessage());
//                 return response()->json([
//                     'message' => 'Error uploading media files.',
//                 ], 500);
//             }
//             }
//         }

//        $Chalet->save();
//        return response()->json([
//         'data' =>new ChaletResource($Chalet),
//         'message' => " Update Chalet By Id Successfully."
//     ]);

//   }

public function update(ChaletRequest $request, string $id)
{
    if (auth()->guard('broker')->check()) {
        $broker = auth()->guard('broker')->user();
        $brokerId = $broker->id;
        $userId = null;
    } elseif (auth()->guard('api')->check()) {
        $user = auth()->guard('api')->user();
        $userId = $user->id;
        $brokerId = null;
    } else {
        return response()->json([
            'message' => 'Unauthenticated.',
        ], 401);
    }
    $Chalet = Chalet::findOrFail($id);

    if (!$Chalet) {
        return response()->json([
            'message' => "Chalet not found."
        ], 404);
    }

    // تحديث البيانات الأساسية
    $Chalet->update([
        'user_id' => $userId,
        'broker_id' => $brokerId,
        "installment_id" => $request->installment_id,
        "finishe_id" => $request->finishe_id,
        "transaction_id" => $request->transaction_id,
        "property_id" => $request->property_id,
        "water_id" => $request->water_id,
        "electricty_id" => $request->electricty_id,
        "sale" => $request->sale,
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
        "status" => $request->status,
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
                $media->delete();
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
