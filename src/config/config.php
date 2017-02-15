<?php
/**
 * Configuration
 * BazaarApi by NikApps
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Cafebazaar Credentials
    |--------------------------------------------------------------------------
    | you should set your cafebazaar credentials.
    | @see http://pardakht.cafebazaar.ir/doc/developer-api/?l=fa
    |
    */
    'credentials' => [

        /*
         * your client id
         */
        'client_id'     => 'your-client-id',

        /*
         * your client secret
         */
        'client_secret' => 'your-client-secret',

        /*
         * redirect uri (only for fetching refresh token)
         */
        'redirect_uri'  => 'your-redirect_uri',

        /*
         * your refresh token
         *
         * you can get your refresh token via command:
         * $ php artisan bazaar:refresh-token --code=<code>
         */
        'refresh_token' => 'your-refresh_token'
    ],


    /*
    |--------------------------------------------------------------------------
    | Cafebazaar Api Options
    |--------------------------------------------------------------------------
    | you change default options of api including path, base url, etc.
    |
    */
    'api'         => [

        /*
         * cafebazaar base uri
         */
        'base_url'               => 'https://pardakht.cafebazaar.ir',

        /*
         * verifying ssl certificate by curl
         */
        'verify_ssl'             => false,

        /*
         * refresh access token when access token is expired
         */
        'auto_refresh_token' => true,

        /*
         * authorization options
         */
        'authorization'          => [
            'path'       => '/auth/token/',
            'grant_type' => 'authorization_code',
        ],

        /*
         * refresh token options
         */
        'refresh_token'          => [
            'path'       => '/auth/token/',
            'grant_type' => 'refresh_token',
        ],

        /*
         * purchase status options
         */
        'in_app_purchase_status' => [
            'path' => '/api/validate/{package}/inapp/{product_id}/purchases/{purchase_token}/?',
        ],

        /*
         * subscription status options
         */
        'subscription_status'    => [
            'path' => '/api/applications/{package}/subscriptions/{subscription_id}/purchases/{purchase_token}/',
        ],

        /*
         * cancel subscription options
         */
        'cancel_subscription'    => [
            'path' => '/api/applications/{package}/subscriptions/{subscription_id}/purchases/{purchase_token}/cancel/',
        ],

    ],

    'cache'       => [
        /*
         * cache driver
         */
        'cache_driver' => null, //null for default driver

        /*
         * cache name
         */
        'cache_name'   => 'bazaar-api-laravel::access_token'
    ]

];
