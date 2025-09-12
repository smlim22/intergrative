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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'),
    ],
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),

    ],

    // Sentiment analysis service config with driver switch
    'sentiment' => [
        'driver' => env('SENTIMENT_DRIVER', 'offline'), // offline | azure
        'azure' => [
            'endpoint' => env('AZURE_TEXT_ENDPOINT'), // e.g., https://<region>.api.cognitive.microsoft.com
            'key' => env('AZURE_TEXT_KEY'),
            'language' => env('SENTIMENT_LANGUAGE', 'en'),
        ],
        // Optional: add keys for other providers if you switch later
        'textrazor' => [
            'key' => env('TEXTRAZOR_API_KEY'),
        ],
    ],


];
