<?php

namespace App\Http\Controllers\Admin;

use App\Models\Usertype;
use App\Traits\ManagesModelsTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserTypeRequest;
use App\Http\Resources\Admin\UserTypeResource;


class UserTypeController extends Controller
{
    use ManagesModelsTrait;
    public function showAll()
    {
        $this->authorize('manage_users');

        $Usertypes = Usertype::get();
        return response()->json([
            'data' => UserTypeResource::collection($Usertypes),
            'message' => "Show All Usertypes Successfully."
        ]);
    }


    public function create(UserTypeRequest $request)
    {
        $this->authorize('manage_users');

           $Usertype =Usertype::create ([
                "userType" => $request->usertype
            ]);
           $Usertype->save();
           return response()->json([
            'data' =>new UserTypeResource($Usertype),
            'message' => "Usertype Created Successfully."
        ]);
        }


    public function edit(string $id)
    {
        $this->authorize('manage_users');
        $Usertype = Usertype::find($id);

        if (!$Usertype) {
            return response()->json([
                'message' => "Usertype not found."
            ], 404);
        }

        return response()->json([
            'data' =>new UserTypeResource($Usertype),
            'message' => "Edit Usertype By ID Successfully."
        ]);
    }



    public function update(UserTypeRequest $request, string $id)
    {
        $this->authorize('manage_users');
       $Usertype =Usertype::findOrFail($id);

       if (!$Usertype) {
        return response()->json([
            'message' => "Usertype not found."
        ], 404);
    }
       $Usertype->update([
        "userType" => $request->usertype
        ]);

       $Usertype->save();
       return response()->json([
        'data' =>new UserTypeResource($Usertype),
        'message' => " Update Usertype By Id Successfully."
    ]);

  }



  public function destroy(string $id)
  {
      return $this->destroyModel(Usertype::class, UserTypeResource::class, $id);
  }

  public function showDeleted(){
      $this->authorize('manage_users');
  $Usertypes=Usertype::onlyTrashed()->get();
  return response()->json([
      'data' =>UserTypeResource::collection($Usertypes),
      'message' => "Show Deleted Usertype Successfully."
  ]);
  }

  public function restore(string $id)
  {
  $this->authorize('manage_users');
  $Usertype = Usertype::withTrashed()->where('id', $id)->first();
  if (!$Usertype) {
      return response()->json([
          'message' => "Usertype not found."
      ]);
  }

  $Usertype->restore();
  return response()->json([
      'data' =>new UserTypeResource($Usertype),
      'message' => "Restore userType By Id Successfully."
  ]);
  }
  public function forceDelete(string $id)
  {
      return $this->forceDeleteModel(Usertype::class, $id);
  }
}
