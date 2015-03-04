# Bazaar-Api-Laravel (BazaarApi for Laravel 4)
An API wrapper for CafeBazaar based on popular Laravel PHP Framework (Laravel 4.x)


## Installation
Using composer, add this [package](https://packagist.org/packages/nikapps/bazaar-api-laravel) dependency to your Laravel's composer.json :

~~~json
{
    "require": {
        "nikapps/bazaar-api-laravel": "1.*"
    }
}
~~~

Then update composer:

```
composer update
```

Add this package provider in your providers array `[app/config/app.php]`:

~~~php
'Nikapps\BazaarApiLaravel\BazaarApiLaravelServiceProvider',
~~~

Next you should publish configuration file. Run this command:

```
php artisan config:publish nikapps/bazaar-api-laravel
```

Run :

```
php artisan
```
If you see a `bazaar:refresh-token` command, you are all set to go !


## Configuration

#### Create client
First of all, you should go to your cafebazaar panel and get `client id` and `client secret`.

* Login to your panel and go to this url: *(Developer API section)*
`http://pardakht.cafebazaar.ir/panel/developer-api/?l=fa`

* Click on `new client` and enter your redirect uri (it's needed to get returned `code` and `refresh_token`)

* Change your configuration file and set your `client_id`, `client_secret` and `redirect_uri`.

#### Get refresh token
* Open this url in your browser:

```
https://pardakht.cafebazaar.ir/auth/authorize/?response_type=code&access_type=offline&redirect_uri=<REDIRECT_URI>&client_id=<CLIENT_ID>
```
*- don't forget to change `<REDIRECT_URI>` and `<CLIENT_ID>`.*

* After clicking on accept/confirm button, you will be redirected to: `<REDIRECT_URI>?code=<CODE>`

*- copy  `<CODE>`*


* Run this command:

```
php artisan bazaar:refresh-token <CODE>
```
*- replace `<CODE>` with the copied data.*

* Copy `refresh_token` and save in your configuration file.

#### Done!



## Usage


#### Purchase
If you want to get a purchase information:

~~~php
$bazaarApi = new BazaarApi();

//creating purchase request
$purchaseStatusRequest = new PurchaseStatusRequest();
$purchaseStatusRequest->setPackage('com.package.name');
$purchaseStatusRequest->setProductId('product_id');
$purchaseStatusRequest->setPurchaseToken('123456789123456789');

//send request to cafebazaar and get purchase info
$purchase = $bazaarApi->getPurchase($purchaseStatusRequest);

//if response is valid and we have this purchase
if($purchase->isOk()){
    echo "Developer Payload: " . $purchase->getDeveloperPayload();
    echo "PurchaseTime: " . $purchase->getPurchaseTime(); //instance of Carbon
    echo "Consumption State: " . $purchase->getConsumptionState();
    echo "Purchase State: " . $purchase->getPurchaseState();
}else{
    echo 'Failed!';
}
~~~

#### Subscription
If you want to get a subscription information:

~~~php
$bazaarApi = new BazaarApi();

//creating subscription request
$subscriptionStatusRequest = new SubscriptionStatusRequest();
$subscriptionStatusRequest->setPackage('com.package.name');
$subscriptionStatusRequest->setSubscriptionId('subscription_id');
$subscriptionStatusRequest->setPurchaseToken('123456789123456789');

//send request to cafebazaar and get subscription info
$subscription = $bazaarApi->getSubscription($subscriptionStatusRequest);

//if response is valid and we have this subscription
if ($subscription->isOk()) {
    echo "Initiation Time: " . $subscription->getInitiationTime(); // instance of Carbon
    echo "Expiration Time: " . $subscription->getExpirationTime(); // instance of Carbon
    echo "Auto Renewing: " . $subscription->isAutoRenewing(); // boolean
} else {
    echo 'Failed!';
}
~~~

#### Cancel Subscription
If you want to cancel a subscription:

~~~php
$bazaarApi = new BazaarApi();

//creating cancel subscription request
$cancelSubscriptionRequest = new CancelSubscriptionRequest();
$cancelSubscriptionRequest->setPackage('com.package.name');
$cancelSubscriptionRequest->setSubscriptionId('subscription_id');
$cancelSubscriptionRequest->setPurchaseToken('123456789123456789');

//send request to cafebazaar and cancel a subscription
$cancelSubscription = $bazaarApi->cancelSubscription($cancelSubscriptionRequest);

//if response is valid and we cancelled the subscription
if ($cancelSubscription->isOk()) {
    echo "The subscription is cancelled!";
} else {
    echo 'Failed!';
}
~~~


## Dependencies

* [GuzzleHttp 5.2.x](https://packagist.org/packages/guzzlehttp/guzzle)



## Todo
* Custom cache driver
* Improve errors and exceptions
* Create standalone php library


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
