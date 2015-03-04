<?php

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use Nikapps\BazaarApiLaravel\BazaarApi;
use Nikapps\BazaarApiLaravel\Models\Requests\CancelSubscriptionRequest;
use Nikapps\BazaarApiLaravel\Models\Requests\PurchaseStatusRequest;
use Nikapps\BazaarApiLaravel\Models\Requests\SubscriptionStatusRequest;

class BazaarApiTest extends TestCase {

    private $accessToken = 'test';

    /**
     * test getting purchase
     *
     * @group bazaarApi
     * @group bazaarApi.purchase
     */
    public function testGetPurchase() {
        $response = [
            "consumptionState" => 1,
            "purchaseState"    => 0,
            "kind"             => "androidpublisher#inappPurchase",
            "developerPayload" => "something",
            "purchaseTime"     => 1414181378566
        ];


        $mockResponse = (string) Response::json($response);

        $mock = new Mock([$mockResponse]);

        $client = new Client([
            'base_url' => Config::get('bazaar-api-laravel::api.base_url')
        ]);

        $client->getEmitter()->attach($mock);

        $bazaarApi = new BazaarApi($client, $this->accessToken);

        $purchaseStatusRequest = new PurchaseStatusRequest();
        $purchaseStatusRequest->setPackage('com.package.name');
        $purchaseStatusRequest->setProductId('package_id');
        $purchaseStatusRequest->setPurchaseToken('755611173491116639');

        $purchase = $bazaarApi->getPurchase($purchaseStatusRequest);

        $this->assertTrue($purchase->isOk());
        $this->assertEquals($response['consumptionState'], $purchase->getConsumptionState());
        $this->assertEquals($response['purchaseState'], $purchase->getPurchaseState());
        $this->assertEquals($response['kind'], $purchase->getKind());
        $this->assertEquals($response['developerPayload'], $purchase->getDeveloperPayload());

        $diffTimestamp = abs($purchase->getPurchaseTime()->timestamp - $response['purchaseTime'] / 1000);
        $this->assertLessThan(1, $diffTimestamp);

    }

    /**
     * test purchase token is not found
     *
     * @group bazaarApi
     * @group bazaarApi.purchase
     */
    public function testPurchaseTokenIsNotFound() {
        $response = [];


        $mockResponse = (string) Response::json($response);

        $mock = new Mock([$mockResponse]);

        $client = new Client([
            'base_url' => Config::get('bazaar-api-laravel::api.base_url')
        ]);

        $client->getEmitter()->attach($mock);

        $bazaarApi = new BazaarApi($client, $this->accessToken);

        $purchaseStatusRequest = new PurchaseStatusRequest();
        $purchaseStatusRequest->setPackage('com.package.name');
        $purchaseStatusRequest->setProductId('package_id');
        $purchaseStatusRequest->setPurchaseToken('855611173491116639');

        $purchase = $bazaarApi->getPurchase($purchaseStatusRequest);

        $this->assertFalse($purchase->isOk());

    }

    /**
     * test purchase token is not found
     *
     * @group bazaarApi
     * @group bazaarApi.token
     */
    public function testRefreshTokenWhenTokenIsExpired() {
        $response = [
            'Access token has been expired',
            [
                "access_token" => "uX5qC82EGWjkjjeyvTzTufHOM9HZfM",
                "token_type"   => "Bearer",
                "expires_in"   => 3600,
                "scope"        => "androidpublisher"
            ],
            [
                "consumptionState" => 1,
                "purchaseState"    => 0,
                "kind"             => "androidpublisher#inappPurchase",
                "developerPayload" => "something",
                "purchaseTime"     => 1414181378566
            ]

        ];

        $mockResponseForExpiration = (string) Response::make($response[0]);
        $mockResponseForRefreshingToken = (string) Response::json($response[1]);
        $mockResponseForGettingPurchase = (string) Response::json($response[2]);


        $mock = new Mock([
            $mockResponseForExpiration,
            $mockResponseForRefreshingToken,
            $mockResponseForGettingPurchase
        ]);

        $client = new Client([
            'base_url' => Config::get('bazaar-api-laravel::api.base_url')
        ]);

        $client->getEmitter()->attach($mock);

        if (Cache::has('bazaar_access_token')) {
            Cache::forget('bazaar_access_token');
        }
        Cache::put('bazaar_access_token', $this->accessToken, \Config::get('bazaar-api-laravel::cache_ttl'));

        $bazaarApi = new BazaarApi($client);

        $purchaseStatusRequest = new PurchaseStatusRequest();
        $purchaseStatusRequest->setPackage('com.package.name');
        $purchaseStatusRequest->setProductId('package_id');
        $purchaseStatusRequest->setPurchaseToken('855611173491116639');

        $purchase = $bazaarApi->getPurchase($purchaseStatusRequest);

        if (Cache::has('bazaar_access_token')) {
            Cache::forget('bazaar_access_token');
        }

        $this->assertTrue($purchase->isOk());
        $this->assertEquals($response[2]['consumptionState'], $purchase->getConsumptionState());
        $this->assertEquals($response[2]['purchaseState'], $purchase->getPurchaseState());
        $this->assertEquals($response[2]['kind'], $purchase->getKind());
        $this->assertEquals($response[2]['developerPayload'], $purchase->getDeveloperPayload());

        $diffTimestamp = abs($purchase->getPurchaseTime()->timestamp - $response[2]['purchaseTime'] / 1000);
        $this->assertLessThan(1, $diffTimestamp);

    }

    /**
     * test getting subscription
     *
     * @group bazaarApi
     * @group bazaarApi.subscription
     */
    public function testGetSubscription() {
        $response = [
            "kind"                    => "androidpublisher#subscriptionPurchase",
            "initiationTimestampMsec" => 1414181378566,
            "validUntilTimestampMsec" => 1435912745710,
            "autoRenewing"            => true,
        ];


        $mockResponse = (string) Response::json($response);

        $mock = new Mock([$mockResponse]);

        $client = new Client([
            'base_url' => Config::get('bazaar-api-laravel::api.base_url')
        ]);

        $client->getEmitter()->attach($mock);

        $bazaarApi = new BazaarApi($client, $this->accessToken);

        $subscriptionStatusRequest = new SubscriptionStatusRequest();
        $subscriptionStatusRequest->setPackage('com.package.name');
        $subscriptionStatusRequest->setSubscriptionId('subscription_id');
        $subscriptionStatusRequest->setPurchaseToken('755611173491116639');

        $subscription = $bazaarApi->getSubscription($subscriptionStatusRequest);

        $this->assertTrue($subscription->isOk());
        $this->assertEquals($response['autoRenewing'], $subscription->isAutoRenewing());
        $this->assertEquals($response['kind'], $subscription->getKind());

        $diffInitTimestamp = abs($subscription->getInitiationTime()->timestamp - $response['initiationTimestampMsec'] / 1000);
        $diffExpirationTimestamp = abs($subscription->getExpirationTime()->timestamp - $response['validUntilTimestampMsec'] / 1000);
        $this->assertLessThan(1, $diffInitTimestamp);
        $this->assertLessThan(1, $diffExpirationTimestamp);

    }

    /**
     * test cancelling subscription
     *
     * @group bazaarApi
     * @group bazaarApi.subscription
     */
    public function testCancelSubscription() {
        $response = '';


        $mockResponse = (string) Response::make($response);

        $mock = new Mock([$mockResponse]);

        $client = new Client([
            'base_url' => Config::get('bazaar-api-laravel::api.base_url')
        ]);

        $client->getEmitter()->attach($mock);

        $bazaarApi = new BazaarApi($client, $this->accessToken);

        $cancelSubscriptionStatusRequest = new CancelSubscriptionRequest();
        $cancelSubscriptionStatusRequest->setPackage('com.package.name');
        $cancelSubscriptionStatusRequest->setSubscriptionId('subscription_id');
        $cancelSubscriptionStatusRequest->setPurchaseToken('755611173491116639');

        $cancelSubscription = $bazaarApi->cancelSubscription($cancelSubscriptionStatusRequest);

        $this->assertTrue($cancelSubscription->isOk());

    }
}