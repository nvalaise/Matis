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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'deezer' => [
        'client_id' => env('DEEZER_KEY'),
        'client_secret' => env('DEEZER_SECRET'),
        'redirect' => env('APP_URL') . '/auth/deezer/callback'
    ],

    'spotify' => [
        'client_id' => env('SPOTIFY_KEY'),
        'client_secret' => env('SPOTIFY_SECRET'),
        'redirect' => env('APP_URL') . '/auth/spotify/callback'
    ],

    'discogs' => [
        'identifier' => env('DISCOGS_KEY'),
        'secret' => env('DISCOGS_SECRET'),
        'callback_uri' => env('APP_URL') . '/auth/discogs/callback'
    ],
];
