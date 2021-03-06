# Bazaar-Api-Laravel (BazaarApi for Laravel)
An Easy-To-Use CafeBazaar API helper package for Laravel Framework (Laravel 4.2.x)

*Version 2.x is based on [Bazaar-Api-PHP](https://github.com/nikapps/bazaar-api-php).*

## Installation

Simply run command:

```
composer require nikapps/bazaar-api-laravel
```

Or you can add this [package](https://packagist.org/packages/nikapps/bazaar-api-laravel) dependency to your Laravel's composer.json :

~~~json
{
    "require": {
        "nikapps/bazaar-api-laravel": "2.*"
    }
    
}
~~~

Then update composer:

```
composer update
```

-

Add this package provider in your providers array `[app/config/app.php]`:

~~~php
'Nikapps\BazaarApiLaravel\BazaarApiLaravelServiceProvider',
~~~

Next you need to publish configuration file. Run this command:

```
php artisan config:publish nikapps/bazaar-api-laravel
```

Run :

```
php artisan
```
If you see a `bazaar:refresh-token` command, you are all set to go !


## Configuration

#### Create a client
First of all, you should go to your cafebazaar panel and get `client id` and `client secret`.

* Login to your panel and go to this url: *(Developer API section)*
`http://pardakht.cafebazaar.ir/panel/developer-api/?l=fa`

* Click on `new client` and enter your redirect uri (you have to set it for getting `code` and `refresh_token` from cafebazaar)

* Change your configuration file and set your `client_id`, `client_secret` and `redirect_uri`.

#### Get refresh token
* Open this url in your browser:

```
https://pardakht.cafebazaar.ir/auth/authorize/?response_type=code&access_type=offline&redirect_uri=<REDIRECT_URI>&client_id=<CLIENT_ID>
```
*- don't forget to change `<REDIRECT_URI>` and `<CLIENT_ID>`.*

* After clicking on accept/confirm button, you'll go to : `<REDIRECT_URI>?code=<CODE>`

*- copy  `<CODE>`*


* Run this command:

```
php artisan bazaar:refresh-token <CODE>
```
*- replace `<CODE>` with the copied data.*

* Copy `refresh_token` and save in your configuration file. 
*(app/config/packages/nikapps/bazaar-api-laravel/config.php)*

#### Done!



## Usage


#### Purchase

~~~php
$purchase = BazaarApi::purchase('com.package.name', 'product_id', 'purchase_token');

//or you can pass an array
$purchase = BazaarApi::purchase([
	'package' => 'com.package.name',
	'product_id' => 'product_id',
	'purchase_token' => 'purchase_token'
]);

echo "Developer Payload: " . $purchase->getDeveloperPayload();
echo "PurchaseTime: " . $purchase->getPurchaseTime(); //instance of Carbon
echo "Consumption State: " . $purchase->getConsumptionState();
echo "Purchase State: " . $purchase->getPurchaseState();
echo "Kind: " . $purchase->getKind();
~~~

#### Subscription

~~~php
$subscription = BazaarApi::subscription('com.package.name', 'subscription_id', 'purchase_token');

//or you can pass an array
$subscription = BazaarApi::subscription([
    'package' => 'com.package.name',
    'subscription_id' => 'subscription_id',
    'purchase_token' => 'purchase_token'
]);

echo "Initiation Time: " . $subscription->getInitiationTime(); // instance of Carbon
echo "Expiration Time: " . $subscription->getExpirationTime(); // instance of Carbon
echo "Auto Renewing: " . $subscription->isAutoRenewing(); // boolean
echo "Kind: " . $subscription->getKind();
~~~

#### Cancel Subscription

~~~php
$cancelSubscription = BazaarApi::cancelSubscription('com.package.name', 'subscription_id', 'purchase_token');

//or you can pass an array
$cancelSubscription = BazaarApi::cancelSubscription([
    'package' => 'com.package.name',
    'subscription_id' => 'subscription_id',
    'purchase_token' => 'purchase_token'
]);

echo "Cancel Subscription: " . $cancelSubscription->isCancelled(); // bool
~~~

#### Refresh Token (Manually)
This packages refreshes token when it's necessary, but if you want you can do it manually.

~~~php
$token = BazaarApi::refreshToken();

echo 'Access Token: ' . $token->getAccessToken();
echo 'Scope: ' . $token->getScope();
echo 'Expire In: ' . $token->getExpireIn();
echo 'Token Type: ' . $token->getTokenType();
~~~

#### Clear Cache
Run this command to clean your cache, this command also invalidates your token.

```
php artisan bazaar:clear-cache
```

## Dependencies

* [GuzzleHttp 5.2.x](https://packagist.org/packages/guzzlehttp/guzzle)
* [Bazaar-Api-PHP 1.x](https://packagist.org/packages/nikapps/bazaar-api-php)


## Contribute

Wanna contribute? simply fork this project and make a pull request!


## License
This project released under the [MIT License](http://opensource.org/licenses/mit-license.php).

```
/*
 * Copyright (C) 2015 NikApps Team.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * 1- The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * 2- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
```

## Donation

[![Donate via Paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G3WRCRDXJD6A8)
