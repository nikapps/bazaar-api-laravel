<?php namespace Nikapps\BazaarApiLaravel\Models\Requests;

class SubscriptionStatusRequest implements BazaarApiRequest {

    private $package;
    private $subscriptionId;
    private $purchaseToken;

    function __construct($package = null, $subscriptionId = null, $purchaseToken = null) {
        $this->package = $package;
        $this->subscriptionId = $subscriptionId;
        $this->purchaseToken = $purchaseToken;
    }

    /**
     * get request uri
     *
     * @return string
     */
    public function getUri() {

        $uri = \Config::get('bazaar-api-laravel::api.subscription_status.path');

        $subscriptionStatusPath = '%s/subscriptions/%s/purchases/%s/';
        $uri .= sprintf($subscriptionStatusPath,
            $this->getPackage(),
            $this->getSubscriptionId(),
            $this->getPurchaseToken()
        );

        return $uri;

    }

    /**
     * @return mixed
     */
    public function getPackage() {
        return $this->package;
    }

    /**
     * @param mixed $package
     */
    public function setPackage($package) {
        $this->package = $package;
    }

    /**
     * @return mixed
     */
    public function getPurchaseToken() {
        return $this->purchaseToken;
    }

    /**
     * @param mixed $purchaseToken
     */
    public function setPurchaseToken($purchaseToken) {
        $this->purchaseToken = $purchaseToken;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionId() {
        return $this->subscriptionId;
    }

    /**
     * @param mixed $subscriptionId
     */
    public function setSubscriptionId($subscriptionId) {
        $this->subscriptionId = $subscriptionId;
    }




}