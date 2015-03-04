<?php namespace Nikapps\BazaarApiLaravel\Models\Requests;

class PurchaseStatusRequest implements BazaarApiRequest {

    private $package;
    private $productId;
    private $purchaseToken;

    function __construct($package = null, $productId = null, $purchaseToken = null) {
        $this->package = $package;
        $this->productId = $productId;
        $this->purchaseToken = $purchaseToken;
    }

    /**
     * get request uri
     *
     * @return string
     */
    public function getUri() {

        $uri = \Config::get('bazaar-api-laravel::api.in_app_purchase_status.path');

        $purchaseStatusPath = '%s/inapp/%s/purchases/%s/';
        $uri .= sprintf($purchaseStatusPath,
            $this->getPackage(),
            $this->getProductId(),
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
    public function getProductId() {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId) {
        $this->productId = $productId;
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


}