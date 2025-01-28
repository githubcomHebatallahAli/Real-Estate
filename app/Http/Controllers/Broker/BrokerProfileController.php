<?php

namespace App\Http\Controllers\Broker;

use App\Models\Broker;
use App\Http\Requests\PhotoRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Auth\BrokerRegisterResource;
use App\Http\Requests\Broker\UpdateBrokerProfileRequest;

class BrokerProfileController extends Controller
{
    public function updateProfilePhoto(PhotoRequest $request , string $id)
    {
        $Broker= auth()->guard('broker')->user();
        if ($Broker->id != $id) {
            return response()->json([
                'message' => "Unauthorized to update this profile."
            ]);
        }
        if ($request->hasFile('photo')) {
            if ($Broker->photo) {
                Storage::disk('public')->delete($Broker->photo);
            }
            $photoPath = $request->file('photo')->store('Broker', 'public');
            $Broker->photo = $photoPath;

        }
        $Broker->save();
            return response()->json([
             'data' => new BrokerRegisterResource($Broker),
                'message' => 'Profile photo updated successfully'
            ]);
        }


    public function profileUpdateProfile(UpdateBrokerProfileRequest $request, string $id)
    {
        $Broker= auth()->guard('broker')->user();
        if ($Broker->id != $id) {
            return response()->json([
                'message' => "Unauthorized to update this profile."
            ]);
        }
        $Broker = Broker::findOrFail($id);

        if ($request->filled('name')) {
            $Broker->name = $request->name;
        }

        if ($request->filled('email')) {
            $Broker->email = $request->email;
        }

        if ($request->filled('phoNum')) {
            $Broker->phoNum= $request->phoNum;
        }

        if ($request->filled('governorate')) {
            $Broker->governorate = $request->governorate;
        }

        if ($request->filled('address')) {
            $Broker->address = $request->address;
        }

        if ($request->filled('targetPlace')) {
            $Broker-> targetPlace= $request->targetPlace;
        }

        if ($request->filled('commission')) {
            $Broker->commission= $request->commission;
        }

        if ($request->filled('brief')) {
            $Broker-> brief= $request->brief;
        }

        if ($request->filled('realEstateType')) {
            $Broker->realEstateType = $request->realEstateType;
        }

        $Broker->save();

        return response()->json([
            'data' => new BrokerRegisterResource($Broker),
            'message' => "Update Broker By Id Successfully."
        ]);
    }

}
