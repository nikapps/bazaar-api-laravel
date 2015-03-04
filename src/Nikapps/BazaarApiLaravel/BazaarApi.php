<?php namespace Nikapps\BazaarApiLaravel;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\FutureResponse;
use Nikapps\BazaarApiLaravel\Models\Requests\CancelSubscriptionRequest;
use Nikapps\BazaarApiLaravel\Models\Requests\PurchaseStatusRequest;
use Nikapps\BazaarApiLaravel\Models\Requests\RefreshToken;
use Nikapps\BazaarApiLaravel\Models\Requests\SubscriptionStatusRequest;
use Nikapps\BazaarApiLaravel\Models\Responses\CancelSubscription;
use Nikapps\BazaarApiLaravel\Models\Responses\Purchase;
use Nikapps\BazaarApiLaravel\Models\Responses\Subscription;

class BazaarApi {

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;
    protected $errors = [
        'Not a valid package name',
        'Invalid access_token',
    ];

    /**
     * access token for testing purposes
     *
     * @var null|string
     */
    protected $accessToken;

    /**
     *
     * @param \GuzzleHttp\Client|null $client = null
     * @param string|null $accessToken = null
     */
    function __construct(\GuzzleHttp\Client $client = null, $accessToken = null) {

        if ($client == null) {
            $client = new \GuzzleHttp\Client([
                'base_url' => \ Config::get('bazaar-api-laravel::api.base_url')
            ]);
        }

        $this->accessToken = $accessToken;
        $this->client = $client;
    }


    /**
     * get a purchase info from cafebazaar
     *
     * @param PurchaseStatusRequest|array $requestData
     * @return Purchase
     * @throws AccessTokenExpiredException
     */
    public function getPurchase($requestData) {
        $purchaseStatusRequest = null;

        if ($requestData instanceof PurchaseStatusRequest) {
            $purchaseStatusRequest = $requestData;
        } else {
            $purchaseStatusRequest = new PurchaseStatusRequest(
                $requestData['package'],
                $requestData['product_id'],
                $requestData['purchase_token']
            );
        }

        $purchase = new Purchase;
        $purchase->setOk(false);

        try {
            /** @var FutureResponse $response */
            $response = $this->client->get($purchaseStatusRequest->getUri(), $this->getRequestOptions());

            $body = trim((string) $response->getBody());

            //if token is expired we should refresh token
            if ($body == 'Access token has been expired') {
                $this->removeAccessTokenFromCache();

                return $this->getPurchase($purchaseStatusRequest);
            }

            if (!$body || $this->hasError($body)) {
                throw new BazaarApiException;
            }

            $result = $response->json();

            if (empty($result)) {
                throw new BazaarApiException;
            }

            //yeah! finally we have a valid response!
            $purchase->setOk(true);
            $purchase->setConsumptionState($result['consumptionState']);
            $purchase->setPurchaseState($result['purchaseState']);
            $purchase->setKind($result['kind']);
            $purchase->setDeveloperPayload($result['developerPayload']);
            $purchase->setPurchaseTime($result['purchaseTime']);
            $purchase->setResponseJson($result);

        } catch (ClientException $e) {
            BazaarApiLogger::logNetworkExceptions($e, 'could not get purchase info');
        } catch (BazaarApiException $e) {

        }

        return $purchase;
    }


    /**
     * get a subscription info from cafebazaar
     *
     * @param SubscriptionStatusRequest|array $requestData
     * @return Subscription
     * @throws AccessTokenExpiredException
     */
    public function getSubscription($requestData) {
        $subscriptionStatusRequest = null;

        if ($requestData instanceof SubscriptionStatusRequest) {
            $subscriptionStatusRequest = $requestData;
        } else {
            $subscriptionStatusRequest = new SubscriptionStatusRequest(
                $requestData['package'],
                $requestData['subscription_id'],
                $requestData['purchase_token']
            );
        }

        $subscription = new Subscription;
        $subscription->setOk(false);

        try {
            /** @var FutureResponse $response */
            $response = $this->client->get($subscriptionStatusRequest->getUri(), $this->getRequestOptions());

            $body = trim((string) $response->getBody());

            //if token is expired we should refresh token
            if ($body == 'Access token has been expired') {
                $this->removeAccessTokenFromCache();

                return $this->getSubscription($subscriptionStatusRequest);
            }

            if (!$body || $this->hasError($body)) {
                throw new BazaarApiException;
            }

            $result = $response->json();

            if (empty($result)) {
                throw new BazaarApiException;
            }

            //yeah! finally we have a valid response!
            $subscription->setOk(true);
            $subscription->setKind($result['kind']);
            $subscription->setInitiationTime($result['initiationTimestampMsec']);
            $subscription->setExpirationTime($result['validUntilTimestampMsec']);
            $subscription->setAutoRenewing($result['autoRenewing']);
            $subscription->setResponseJson($result);

        } catch (ClientException $e) {
            BazaarApiLogger::logNetworkExceptions($e, 'could not get subscription info');
        } catch (BazaarApiException $e) {

        }

        return $subscription;
    }

    /**
     * cancel a subscription
     *
     * @param CancelSubscriptionRequest|array $requestData
     * @return CancelSubscription
     * @throws AccessTokenExpiredException
     */
    public function cancelSubscription($requestData) {
        $cancelSubscriptionRequest = null;

        if ($requestData instanceof CancelSubscriptionRequest) {
            $cancelSubscriptionRequest = $requestData;
        } else {
            $cancelSubscriptionRequest = new CancelSubscriptionRequest(
                $requestData['package'],
                $requestData['subscription_id'],
                $requestData['purchase_token']
            );
        }

        $cancelSubscription = new CancelSubscription();
        $cancelSubscription->setOk(false);

        try {
            /** @var FutureResponse $response */
            $response = $this->client->get($cancelSubscriptionRequest->getUri(), $this->getRequestOptions());

            $body = trim((string) $response->getBody());

            //if token is expired we should refresh token
            if ($body == 'Access token has been expired') {
                $this->removeAccessTokenFromCache();

                return $this->getSubscription($cancelSubscriptionRequest);
            }

            if ($this->hasError($body)) {
                throw new BazaarApiException;
            }

            //yeah! finally we have a valid response!
            if (!$body) {
                //if body is empty
                $cancelSubscription->setOk(true);
                $cancelSubscription->setResponseJson([]);
            }

        } catch (ClientException $e) {
            BazaarApiLogger::logNetworkExceptions($e, 'could not get subscription info');

        } catch (BazaarApiException $e) {

        }

        return $cancelSubscription;
    }


    /**
     * get access token
     *
     * @throws AccessTokenExpiredException
     * @return string
     */
    protected function getAccessToken() {

        if ($this->accessToken) return $this->accessToken;

        if (!\Cache::has('bazaar_access_token')) {
            if (!$this->fetchNewAccessToken()) {
                //we don't have access token!
                throw new AccessTokenExpiredException;
            }
        }

        return \Cache::get('bazaar_access_token');
    }

    /**
     * fetch new access token from cafebazaar via refresh token
     *
     * @return boolean
     */
    protected function fetchNewAccessToken() {
        $refreshTokenRequest = new RefreshToken();

        try {
            /** @var FutureResponse $response */
            $response = $this->client->post($refreshTokenRequest->getUri(), [
                'body'   => $refreshTokenRequest->getPostData(),
                'verify' => \Config::get('bazaar-api-laravel::api.verify_ssl')
            ]);

            $body = trim((string) $response->getBody());
            if (!$body) {
                throw new BazaarApiException;
            }
            $result = $response->json();

            $accessToken = $result['access_token'];

            if (!$accessToken) {
                throw new BazaarApiException;
            }

            if (\Cache::has('bazaar_access_token')) {
                \Cache::forget('bazaar_access_token');
            }
            \Cache::put('bazaar_access_token', $accessToken, \Config::get('bazaar-api-laravel::cache_ttl'));

            return true;

        } catch (ClientException $e) {
            //log error
            BazaarApiLogger::logNetworkExceptions($e, 'could not get new token');
        } catch (BazaarApiException $e) {

        }

        return false;

    }

    /**
     * get options for GET requests
     *
     * @return array
     * @throws AccessTokenExpiredException
     */
    protected function getRequestOptions() {
        return [
            'query'  => [
                'access_token' => $this->getAccessToken()
            ],
            'verify' => \Config::get('bazaar-api-laravel::api.verify_ssl')
        ];
    }

    /**
     * clear access token from cache
     */
    protected function removeAccessTokenFromCache() {
        if (\Cache::has('bazaar_access_token')) {
            \Cache::forget('bazaar_access_token');
        }
    }

    /**
     * check response has an error
     *
     * @param $body
     * @return bool
     */
    protected function hasError($body) {
        return in_array($body, $this->errors);
    }
}