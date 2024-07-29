<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'twilio' => [
        'sid_sms' => env('TWILIO_SID_SMS'),
        'token_sms' => env('TWILIO_TOKEN_SMS'),
        'from_sms' => env('TWILIO_FROM_SMS'),
        'sid_whatsapp' => env('TWILIO_SID_WHATSAPP'),
        'token_whatsapp' => env('TWILIO_TOKEN_WHATSAPP'),
        'from_whatsapp' => env('TWILIO_FROM_WHATSAPP'),
    ],

];
