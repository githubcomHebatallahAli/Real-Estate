<?php

namespace App\Http\Controllers\Auth;

use Ichtrojan\Otp\Otp;

use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerfiyOTPRequest;
use App\Http\Requests\Auth\VerficationPhoNumRequest;

class VerficationController extends Controller
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

    public function sendSms($to, $message)
    {
        $testNumbers = ['+201114990063', '+201030124015']; // قائمة أرقام الاختبار المسجلة
        $message = 'Hello, this is a test message using Vonage!';
        $basic  = new Basic(env('VONAGE_API_KEY'), env('VONAGE_API_SECRET'));
        $client = new Client($basic);
    
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($to, env('VONAGE_SMS_FROM'), $message)
        );
    
        $message = $response->current();
    
        if ($message->getStatus() == 0) {
            return 'Message sent successfully.';
        } else {
            return 'Message failed with status: ' . $message->getStatus();
        }
    }

  
}