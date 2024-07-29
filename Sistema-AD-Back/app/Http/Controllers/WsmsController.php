<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class WsmsController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function sendSms(Request $request)
    {
        $to = $request->input('to');
        $message = $request->input('message');

        $sid = $this->twilioService->sendSms($to, $message);

        return response()->json(['sid' => $sid]);
    }

    public function sendWhatsAppMessage(Request $request)
    {
        $to = $request->input('to');
        $message = $request->input('message');

        $sid = $this->twilioService->sendWhatsAppMessage($to, $message);

        return response()->json(['sid' => $sid]);
    }
}
