<?php namespace Nikapps\BazaarApiLaravel;

use Illuminate\Config\Repository as ConfigRepository;
use Nikapps\BazaarApiLaravel\TokenManager\CacheTokenManager;
use Nikapps\BazaarApiPhp\Configs\AccountConfig;
use Nikapps\BazaarApiPhp\Configs\ApiConfig;
use Nikapps\BazaarApiPhp\Exceptions\ExpiredAccessTokenException;
use Nikapps\BazaarApiPhp\Models\Requests\CancelSubscriptionRequest;
use Nikapps\BazaarApiPhp\Models\Requests\PurchaseStatusRequest;
use Nikapps\BazaarApiPhp\Models\Requests\RefreshTokenRequest;
use Nikapps\BazaarApiPhp\Models\Requests\SubscriptionStatusRequest;

class BazaarApiFactory {

    /**
     * @var ConfigRepository
     */
    protected $config;

    /**
     * @var \Nikapps\BazaarApiPhp\BazaarApi
     */
    protected $bazaarApi;

    /**
     * @var ApiConfig
     */
    protected $apiConfig;

    /**
     * @var AccountConfig
     */
    protected $accountConfig;


    /**
     * constructor
     *
     * @param ConfigRepository $config
     */
    public function __construct(ConfigRepository $config) {

        $this->config = $config;

        $this->bazaarApi = new \Nikapps\BazaarApiPhp\BazaarApi();

        //add configurations
        $this->bazaarApi->setAccountConfig($this->getAccountConfig());
        $this->bazaarApi->setApiConfig($this->getApiConfig());

        //create token manager
        $tokenManager = new CacheTokenManager();
        $tokenManager->setCacheDriver($this->config->get('bazaar-api-laravel::cache.cache_driver'));
        $tokenManager->setCacheName($this->config->get('bazaar-api-laravel::cache.cache_name'));

        $this->bazaarApi->setTokenManager($tokenManager);

    }

    /**
     * refresh access token (and store it)
     *
     * @param bool $shouldStore store access token in tokenManager
     * @return \Nikapps\BazaarApiPhp\Models\Responses\RefreshToken
     * @throws \Nikapps\BazaarApiPhp\Exceptions\NotFoundException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidJsonException
     */
    public function refreshToken($shouldStore = true) {
        $refreshTokenRequest = new RefreshTokenRequest();

        $refreshToken = $this->bazaarApi->refreshToken($refreshTokenRequest);
        if ($shouldStore) {
            $this->bazaarApi->getTokenManager()->storeToken($refreshToken->getAccessToken(),
                $refreshToken->getExpireIn());
        }

        return $refreshToken;

    }

    /**
     * get purchase status
     *
     * @param PurchaseStatusRequest|array|string $packageNameOrRequestOrArray
     * @param string|null $productId
     * @param string|null $purchaseToken
     * @return \Nikapps\BazaarApiPhp\Models\Responses\Purchase
     * @throws ExpiredAccessTokenException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidPackageNameException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidTokenException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\NetworkErrorException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\NotFoundException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidJsonException
     */
    public function purchase($packageNameOrRequestOrArray, $productId = null, $purchaseToken = null) {

        $purchaseRequest = $this->makePurchaseRequest($packageNameOrRequestOrArray, $productId, $purchaseToken);

        try {
            return $this->bazaarApi->getPurchase($purchaseRequest);

        } catch (ExpiredAccessTokenException $e) {

            if (!$this->config->get('bazaar-api-laravel::api.auto_refresh_token')) {
                //auto refresh token is disabled
                throw new ExpiredAccessTokenException;
            } else {
                //refresh access token
                $this->refreshToken();

                //retry request
                return $this->purchase($purchaseRequest);
            }
        }

    }

    /**
     * get subscription status
     *
     * @param SubscriptionStatusRequest|array|string $packageNameOrRequestOrArray
     * @param string|null $subscriptionId
     * @param string|null $purchaseToken
     * @return \Nikapps\BazaarApiPhp\Models\Responses\Subscription
     * @throws ExpiredAccessTokenException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidPackageNameException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidTokenException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\NetworkErrorException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\NotFoundException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidJsonException
     */
    public function subscription($packageNameOrRequestOrArray, $subscriptionId = null, $purchaseToken = null) {

        $subscriptionRequest = $this->makeSubscriptionRequest($packageNameOrRequestOrArray, $subscriptionId, $purchaseToken);

        try {
            return $this->bazaarApi->getSubscription($subscriptionRequest);

        } catch (ExpiredAccessTokenException $e) {

            if (!$this->config->get('bazaar-api-laravel::api.auto_refresh_token')) {
                //auto refresh token is disabled
                throw new ExpiredAccessTokenException;
            } else {
                //refresh access token
                $this->refreshToken();

                //retry request
                return $this->subscription($subscriptionRequest);
            }
        }

    }

    /**
     * cancel a subscription
     *
     * @param CancelSubscriptionRequest|array|string $packageNameOrRequestOrArray
     * @param string|null $subscriptionId
     * @param string|null $purchaseToken
     * @return \Nikapps\BazaarApiPhp\Models\Responses\CancelSubscription
     * @throws ExpiredAccessTokenException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidPackageNameException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidTokenException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\NetworkErrorException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\NotFoundException
     * @throws \Nikapps\BazaarApiPhp\Exceptions\InvalidJsonException
     */
    public function cancelSubscription($packageNameOrRequestOrArray, $subscriptionId = null, $purchaseToken = null) {

        $cancelSubscriptionRequest = $this->makeCancelSubscriptionRequest($packageNameOrRequestOrArray, $subscriptionId, $purchaseToken);

        try {
            return $this->bazaarApi->cancelSubscription($cancelSubscriptionRequest);

        } catch (ExpiredAccessTokenException $e) {

            if (!$this->config->get('bazaar-api-laravel::api.auto_refresh_token')) {
                //auto refresh token is disabled
                throw new ExpiredAccessTokenException;
            } else {
                //refresh access token
                $this->refreshToken();

                //retry request
                return $this->cancelSubscription($cancelSubscriptionRequest);
            }
        }

    }


    /**
     * make purchase request
     *
     * @param PurchaseStatusRequest|array|string $packageNameOrRequestOrArray
     * @param string|null $productId
     * @param string|null $purchaseToken
     * @return PurchaseStatusRequest
     */
    protected function makePurchaseRequest($packageNameOrRequestOrArray, $productId = null, $purchaseToken = null) {

        if ($packageNameOrRequestOrArray instanceof PurchaseStatusRequest) {
            return $packageNameOrRequestOrArray;
        }

        $purchaseRequest = new PurchaseStatusRequest();

        if (is_array($packageNameOrRequestOrArray)) {

            $array = $packageNameOrRequestOrArray;
            $purchaseRequest->setPackage($array['package']);
            $purchaseRequest->setProductId($array['product_id']);
            $purchaseRequest->setPurchaseToken($array['purchase_token']);

        } else {

            $purchaseRequest->setPackage($packageNameOrRequestOrArray);
            $purchaseRequest->setProductId($productId);
            $purchaseRequest->setPurchaseToken($purchaseToken);
        }

        return $purchaseRequest;

    }

    /**
     * make subscription request
     *
     * @param SubscriptionStatusRequest|array|string $packageNameOrRequestOrArray
     * @param string|null $subscriptionId
     * @param string|null $purchaseToken
     * @return SubscriptionStatusRequest
     */
    protected function makeSubscriptionRequest($packageNameOrRequestOrArray, $subscriptionId = null, $purchaseToken = null) {

        if ($packageNameOrRequestOrArray instanceof SubscriptionStatusRequest) {
            return $packageNameOrRequestOrArray;
        }

        $subscriptionRequest = new SubscriptionStatusRequest();

        if (is_array($packageNameOrRequestOrArray)) {

            $array = $packageNameOrRequestOrArray;
            $subscriptionRequest->setPackage($array['package']);
            $subscriptionRequest->setSubscriptionId($array['subscription_id']);
            $subscriptionRequest->setPurchaseToken($array['purchase_token']);

        } else {

            $subscriptionRequest->setPackage($packageNameOrRequestOrArray);
            $subscriptionRequest->setSubscriptionId($subscriptionId);
            $subscriptionRequest->setPurchaseToken($purchaseToken);
        }

        return $subscriptionRequest;

    }

    /**
     * make subscription request
     *
     * @param CancelSubscriptionRequest|array|string $packageNameOrRequestOrArray
     * @param string|null $subscriptionId
     * @param string|null $purchaseToken
     * @return CancelSubscriptionRequest
     */
    protected function makeCancelSubscriptionRequest($packageNameOrRequestOrArray, $subscriptionId = null, $purchaseToken = null) {

        if ($packageNameOrRequestOrArray instanceof CancelSubscriptionRequest) {
            return $packageNameOrRequestOrArray;
        }

        $cancelSubscriptionRequest = new CancelSubscriptionRequest();

        if (is_array($packageNameOrRequestOrArray)) {

            $array = $packageNameOrRequestOrArray;
            $cancelSubscriptionRequest->setPackage($array['package']);
            $cancelSubscriptionRequest->setSubscriptionId($array['subscription_id']);
            $cancelSubscriptionRequest->setPurchaseToken($array['purchase_token']);

        } else {

            $cancelSubscriptionRequest->setPackage($packageNameOrRequestOrArray);
            $cancelSubscriptionRequest->setSubscriptionId($subscriptionId);
            $cancelSubscriptionRequest->setPurchaseToken($purchaseToken);
        }

        return $cancelSubscriptionRequest;

    }


    /**
     * make api config & return
     *
     * @return ApiConfig
     */
    public function getApiConfig() {

        if (!$this->apiConfig) {

            $apiConfig = new ApiConfig();
            $apiConfig->setAuthorizationGrantType($this->config->get('bazaar-api-laravel::api.authorization.grant_type'));
            $apiConfig->setAuthorizationPath($this->config->get('bazaar-api-laravel::api.authorization.path'));
            $apiConfig->setRefreshTokenGrantType($this->config->get('bazaar-api-laravel::api.refresh_token.grant_type'));
            $apiConfig->setRefreshTokenPath($this->config->get('bazaar-api-laravel::api.refresh_token.path'));
            $apiConfig->setPurchasePath($this->config->get('bazaar-api-laravel::api.in_app_purchase_status.path'));
            $apiConfig->setSubscriptionPath($this->config->get('bazaar-api-laravel::api.subscription_status.path'));
            $apiConfig->setCancelSubscriptionPath($this->config->get('bazaar-api-laravel::api.cancel_subscription.path'));
            $apiConfig->setBaseUrl($this->config->get('bazaar-api-laravel::api.base_url'));
            $apiConfig->setVerifySsl($this->config->get('bazaar-api-laravel::api.verify_ssl'));

            $this->apiConfig = $apiConfig;
        }

        return $this->apiConfig;
    }

    /**
     * make account config & return
     *
     * @return \Nikapps\BazaarApiPhp\Configs\AccountConfig
     */
    public function getAccountConfig() {

        if (!$this->accountConfig) {

            $accountConfig = new AccountConfig();
            $accountConfig->setClientId($this->config->get('bazaar-api-laravel::credentials.client_id'));
            $accountConfig->setClientSecret($this->config->get('bazaar-api-laravel::credentials.client_secret'));
            $accountConfig->setRedirectUri($this->config->get('bazaar-api-laravel::credentials.redirect_uri'));
            $accountConfig->setRefreshToken($this->config->get('bazaar-api-laravel::credentials.refresh_token'));

            $this->accountConfig = $accountConfig;
        }

        return $this->accountConfig;
    }

    /**
     * @return \Nikapps\BazaarApiPhp\BazaarApi
     */
    public function getBazaarApi() {
        return $this->bazaarApi;
    }


}