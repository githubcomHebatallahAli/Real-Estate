<?php

namespace App\Http\Controllers\Auth;

use Ichtrojan\Otp\Otp;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerfiyOTPRequest;
use App\Http\Requests\Auth\VerficationPhoNumRequest;

class OTPController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }

    /**
     * إرسال كود OTP.
     */
    public function sendOtp(VerficationPhoNumRequest $request)
    {
        // $validatedData = $request->validate([
        //     'phoNum' => 'required|string',
        // ]);

        // إنشاء كود OTP جديد
        $otp = $this->otp->generate($request->phoNum, 6, 10); // كود من 6 أرقام صالح لمدة 10 دقائق

        // إرسال كود OTP عن طريق Twilio
        try {
            $this->sendSms($request->phoNum, "Your OTP is: " . $otp->token);

            return response()->json([
                'message' => 'OTP sent successfully.',
                'identifier' => $request->phoNum,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send OTP: " . $e->getMessage());

            return response()->json([
                'message' => 'Failed to send OTP. Please try again later.',
            ], 500);
        }
    }

    /**
     * إعادة إرسال كود OTP.
     */
    public function resendOtp(VerficationPhoNumRequest $request)
    {
        // $validatedData = $request->validate([
        //     'phoNum' => 'required|string',
        // ]);

        // إنشاء كود OTP جديد
        $otp = $this->otp->generate($request->phoNum, 6, 10); // كود من 6 أرقام صالح لمدة 10 دقائق

        // إرسال كود OTP عن طريق Twilio
        try {
            $this->sendSms($request->phoNum, "Your OTP is: " . $otp->token);

            return response()->json([
                'message' => 'OTP resent successfully.',
                'identifier' => $request->phoNum,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to resend OTP: " . $e->getMessage());

            return response()->json([
                'message' => 'Failed to resend OTP. Please try again later.',
            ], 500);
        }
    }

    /**
     * التحقق من كود OTP.
     */
    public function verifyOtp(VerfiyOTPRequest $request)
    {
        $otpValidation = $this->otp->validate($request->phoNum, $request->otp);

        if (!$otpValidation->status) {
            return response()->json([
                'message' => 'Invalid or expired OTP.',
            ], 400);
        }

        return response()->json([
            'message' => 'OTP verified successfully.',
        ]);
    }

    /**
     * إرسال رسالة SMS باستخدام Twilio.
     */
    private function sendSms($phoNum, $message)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_PHONE_NUMBER');

        $client = new Client($sid, $token);

        try {
            $client->messages->create($phoNum, [
                'from' => $from,
                'body' => $message,
            ]);
        } catch (\Exception $e) {
            throw new \Exception("Twilio SMS failed: " . $e->getMessage());
        }
    }
}
