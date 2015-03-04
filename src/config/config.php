<?php
/**
 * Configuration
 * BazaarApi by NikApps
 */

return [

    /*
     * Your CafeBazaar credentials.
     */
    'credentials' => [
        /*
         * your client id
         */
//        'client_id'     => 'your-client-id',
        'client_id'     => 'YO4Q1SKuk4xpDxbrOrx7pQ5TJDLQWN7VGYgayZRz',

        /*
         * your client secret
         */
//        'client_secret' => 'your-client-secret',
        'client_secret' => 'JKCflwc4lE0CUYGvuGZ0pxA6hzVzylWqUSAYAOz2ZvwdEszbTb3Cv2hGZS9k',


        /*
         * redirect uri (only for getting refresh token)
         */
//        'redirect_uri' => 'your-redirect_uri',
        'redirect_uri' => 'http://sheytanat.nikapps.com/bazaar',



        /*
         * your refresh token
         *
         * you can get your refresh token via command:
         *  php artisan bazaar:refresh-token --code=<code> --redirect-uri=<redirect>
         */
//        'refresh_token' => 'your-refresh_token'
        'refresh_token' => '3oFomVX56QUwNpeLhfr2pG8D3bD1jB'
    ],

    /*
     * CafeBazaar API options
     */
    'api'         => [
        /*
         * cafebazaar base uri
         */
        'base_url'               => 'https://pardakht.cafebazaar.ir',

        /*
         * verifying ssl certificate
         */
        'verify_ssl'             => false,

        'authorization'          => [
            'path'       => '/auth/token/',
            'grant_type' => 'authorization_code',
        ],
        'refresh_token'          => [
            'path'       => '/auth/token/',
            'grant_type' => 'refresh_token',
        ],

        'in_app_purchase_status' => [
            'path' => '/api/validate/',
        ],

        'subscription_status'    => [
            'path' => '/api/applications/',
        ],

        'cancel_subscription'    => [
            'path' => '/api/applications/',
        ],

    ],

    /*
     * remove cached token after [x] minutes
     */
    'cache_ttl' => 60
];