<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class MediaRequest extends FormRequest
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

                'media.*.file' => [
                    'nullable',
                    'file',
                    function ($attribute, $value, $fail) {
                        $allowedAudioExtensions = ['mp3', 'wav', 'aac', 'ogg', 'flac', 'm4a'];
                        $allowedImageExtensions = ['jpeg', 'png', 'jpg', 'gif', 'webp', 'bmp', 'svg'];
                        $allowedVideoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'flv', 'wmv', 'webm', '3gp'];

                        $extension = $value->getClientOriginalExtension();
                        $type = request()->input(str_replace('.file', '.type', $attribute));

                        if ($type === 'audio' && !in_array($extension, $allowedAudioExtensions)) {
                            $fail("يجب أن يكون الملف الصوتي من الأنواع التالية: " . implode(', ', $allowedAudioExtensions) . ".");
                        } elseif ($type === 'image' && !in_array($extension, $allowedImageExtensions)) {
                            $fail("يجب أن تكون الصورة من الأنواع التالية: " . implode(', ', $allowedImageExtensions) . ".");
                        } elseif ($type === 'video' && !in_array($extension, $allowedVideoExtensions)) {
                            $fail("يجب أن يكون الفيديو من الأنواع التالية: " . implode(', ', $allowedVideoExtensions) . ".");
                        }
                    },
                ],
                'media.*.type' => 'required|in:image,video,audio',
                'media.*.file' => 'max:10240',


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
