<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $twilioSms;
    protected $twilioWhatsApp;

    public function __construct()
    {

        $sidSms = config('services.twilio.sid_sms');
        $tokenSms = config('services.twilio.token_sms');

        if (empty($sidSms) || empty($tokenSms)) {
        }

        $this->twilioSms = new Client($sidSms, $tokenSms);

        $sidWhatsApp = config('services.twilio.sid_whatsapp');
        $tokenWhatsApp = config('services.twilio.token_whatsapp');
       
        if (empty($sidWhatsApp) || empty($tokenWhatsApp)) {
        }

        $this->twilioWhatsApp = new Client($sidWhatsApp, $tokenWhatsApp);
    }

    public function sendSms($to, $message)
    {
        $from = config('services.twilio.from_sms');
        $message = $this->twilioSms->messages->create($to, [
            'from' => $from,
            'body' => $message
        ]);

        return $message->sid;
    }

    public function sendWhatsAppMessage($to, $message)
    {
        $from = config('services.twilio.from_whatsapp');
        $message = $this->twilioWhatsApp->messages->create("whatsapp:$to", [
            'from' => "whatsapp:$from",
            'body' => $message
        ]);

        return $message->sid;
    }
}
