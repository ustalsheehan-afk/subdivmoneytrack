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

    'philsms' => [
        'token' => env('PHILSMS_API_TOKEN'),
        'key' => env('PHILSMS_API_KEY'),
        'url' => env('PHILSMS_URL', 'https://app.philsms.com/api/v3/sms/send'),
        'sender_id' => env('PHILSMS_SENDER'),
    ],

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'philsms'),
    ],

    'semaphore' => [
        'api_key' => env('SEMAPHORE_API_KEY'),
        'url' => env('SEMAPHORE_URL', 'https://api.semaphore.co/api/v4/messages'),
        'sender_name' => env('SEMAPHORE_SENDER_NAME'),
    ],

];
