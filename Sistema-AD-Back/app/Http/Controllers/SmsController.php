<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class SmsController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function sendSms(Request $request)
    {
        $request->validate([
            'to' => 'required|regex:/^\+\d{10,15}$/',
            'message' => 'required|string'
        ]);

        $messageSid = $this->twilio->sendSms($request->to, $request->message);

        return response()->json(['sid' => $messageSid]);
    }
}
