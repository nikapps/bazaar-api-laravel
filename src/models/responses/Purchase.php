<?php namespace Nikapps\BazaarApiLaravel\Models\Responses;

use Carbon\Carbon;

class Purchase implements BazaarApiResponse {

    /**
     * @var bool
     */
    private $ok = false;

    /**
     * @var int
     */
    private $consumptionState;

    /**
     * @var int
     */
    private $purchaseState;

    /**
     * @var string
     */
    private $kind;

    /**
     * @var string
     */
    private $developerPayload;

    /**
     * @var Carbon
     */
    private $purchaseTime;

    /**
     * @var array
     */
    private $json = [];

    /**
     * is response ok
     *
     * @return boolean
     */
    public function isOk() {
        return $this->ok;
    }

    /**
     * @param bool $isOk
     */
    public function setOk($isOk){
        $this->ok = $isOk;
    }

    /**
     * @return int
     */
    public function getConsumptionState() {
        return $this->consumptionState;
    }

    /**
     * @param int $consumptionState
     */
    public function setConsumptionState($consumptionState) {
        $this->consumptionState = $consumptionState;
    }

    /**
     * @return string
     */
    public function getDeveloperPayload() {
        return $this->developerPayload;
    }

    /**
     * @param string $developerPayload
     */
    public function setDeveloperPayload($developerPayload) {
        $this->developerPayload = $developerPayload;
    }

    /**
     * @return string
     */
    public function getKind() {
        return $this->kind;
    }

    /**
     * @param string $kind
     */
    public function setKind($kind) {
        $this->kind = $kind;
    }

    /**
     * @return int
     */
    public function getPurchaseState() {
        return $this->purchaseState;
    }

    /**
     * @param int $purchaseState
     */
    public function setPurchaseState($purchaseState) {
        $this->purchaseState = $purchaseState;
    }

    /**
     * @return Carbon
     */
    public function getPurchaseTime() {
        return $this->purchaseTime;
    }

    /**
     * @param int $purchaseTime
     */
    public function setPurchaseTime($purchaseTime) {
        $inSeconds = intval($purchaseTime/1000);
        $this->purchaseTime = Carbon::createFromTimestampUTC($inSeconds);
    }

    /**
     * get response json
     *
     * @return array
     */
    public function getResponseJson() {
        return $this->json;
    }

    /**
     * set response json
     *
     * @param array $json
     */
    public function setResponseJson($json) {
        if(!is_array($json)){
            $json = json_decode($json);
        }

        $this->json = $json;
    }


} 