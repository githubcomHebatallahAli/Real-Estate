<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ChaletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'creationDate'=> 'nullable|date_format:Y-m-d H:i:s',
            'broker_id' => 'nullable|exists:brokers,id',
            'user_id' => 'nullable|exists:users,id',
            'installment_id' => 'required|exists:installments,id',
            'finishe_id' => 'required|exists:finishes,id',
            'transaction_id' => 'required|exists:transactions,id',
            'property_id' => 'required|exists:propertiess,id',
            'water_id' => 'required|exists:waters,id',
            'electricty_id' => 'required|exists:electricities,id',
            'sale_id' => 'required|exists:sales,id',
            'governorate' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'street' => 'required|string',
            'locationGPS' => 'required|string',
            'propertyNum' => 'nullable|integer',
            'floorNum'  => 'required|integer',
            'roomNum' =>'required|integer',
            'pathRoomNum' =>'required|integer',
            'description' =>'nullable|string',
            'area' =>'required|integer',
            'gardenArea' =>'required|integer',
            'ownerType' =>'required|string',
            'status'  =>'nullable|in:active,notActive',
            'sale' => 'nullable|in:sold,notSold',

        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
