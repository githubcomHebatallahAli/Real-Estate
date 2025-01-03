<?php

namespace App\Http\Controllers;

use App\Models\Chalet;
use Illuminate\Http\Request;
use App\Traits\ManagesModelsTrait;
use App\Http\Requests\ChaletRequest;
use App\Http\Resources\ChaletResource;

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

           $Chalet =Chalet::create ([
                'user_id' => auth()->id(),
                'broker_id' => auth()->id(),
                "installment_id" => $request-> installment_id,
                "finishe_id" => $request-> finishe_id,
                "transaction_id" => $request-> transaction_id,
                "property_id" => $request-> property_id,
                "water_id" => $request-> water_id,
                "electricty_id" => $request-> electricty_id,
                "sale" => $request-> sale,
                "governorate" => $request-> governorate,
                "city" => $request-> city,
                "district" => $request-> district,
                "street" => $request-> street,
                "locationGPS" => $request-> locationGPS,
                "propertyNum" => $request-> propertyNum,
                "floorNum" => $request-> floorNum,
                "roomNum" => $request-> roomNum,
                "pathRoomNum" => $request-> pathRoomNum,
                "description" => $request-> description,
                "area" => $request-> area,
                "gardenArea" => $request-> gardenArea,
                "ownerType" => $request-> ownerType,
                'creationDate' => now()->timezone('Africa/Cairo')
                ->format('Y-m-d h:i:s'),
                "status" => $request-> status,
                "facade" => $request-> facade,
                "totalPrice" => $request-> totalPrice,
                "installmentPrice" => $request-> installmentPrice,
                "downPrice" => $request-> downPrice,
                "rentPrice" => $request-> rentPrice
            ]);
            if ($request->hasFile('media')) {
                $file = $request->file('media');
                $path = $file->store('Chalet', 'public');

                $type = $file->getMimeType();
                if (str_contains($type, 'image')) {
                    $mediaType = 'image';
                } elseif (str_contains($type, 'video')) {
                    $mediaType = 'video';
                } elseif (str_contains($type, 'audio')) {
                    $mediaType = 'audio';
                } else {
                    $mediaType = null;
                }

                $Chalet->media()->create([
                    'path' => $path,
                    'type' => $mediaType
                ]);
            }

            $Chalet->load('media');
           $Chalet->save();
           return response()->json([
            'data' =>new ChaletResource($Chalet),
            'message' => "Chalet Created Successfully."
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
       $Chalet =Chalet::findOrFail($id);

       if (!$Chalet) {
        return response()->json([
            'message' => "Chalet not found."
        ], 404);
    }
       $Chalet->update([
                "installment_id" => $request-> installment_id,
                "finishe_id" => $request-> finishe_id,
                "transaction_id" => $request-> transaction_id,
                "property_id" => $request-> property_id,
                "water_id" => $request-> water_id,
                "electricty_id" => $request-> electricty_id,
                "sale" => $request-> sale,
                "governorate" => $request-> governorate,
                "city" => $request-> city,
                "district" => $request-> district,
                "street" => $request-> street,
                "locationGPS" => $request-> locationGPS,
                "propertyNum" => $request-> propertyNum,
                "floorNum" => $request-> floorNum,
                "roomNum" => $request-> roomNum,
                "pathRoomNum" => $request-> pathRoomNum,
                "description" => $request-> description,
                "area" => $request-> area,
                "gardenArea" => $request-> gardenArea,
                "ownerType" => $request-> ownerType,
                "creationDate" => now()->timezone('Africa/Cairo')
                ->format('Y-m-d h:i:s'),
                "status" => $request-> status,
                "totalPrice" => $request-> totalPrice,
                "installmentPrice" => $request-> installmentPrice,
                "downPrice" => $request-> downPrice,
                "rentPrice" => $request-> rentPrice
        ]);

       $Chalet->save();
       return response()->json([
        'data' =>new ChaletResource($Chalet),
        'message' => " Update Chalet By Id Successfully."
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
