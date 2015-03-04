<?php namespace Nikapps\BazaarApiLaravel\Models\Responses;

use Carbon\Carbon;

class Subscription implements BazaarApiResponse {

    /**
     * @var bool
     */
    private $ok = false;
    /**
     * @var Carbon
     */
    private $initiationTime;

    /**
     * @var Carbon
     */
    private $expirationTime;

    /**
     * @var string
     */
    private $kind;

    /**
     * @var boolean
     */
    private $autoRenewing;

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
     * @return boolean
     */
    public function isAutoRenewing() {
        return $this->autoRenewing;
    }

    /**
     * @param boolean $autoRenewing
     */
    public function setAutoRenewing($autoRenewing) {
        $this->autoRenewing = $autoRenewing;
    }

    /**
     * @return Carbon
     */
    public function getExpirationTime() {
        return $this->expirationTime;
    }

    /**
     * @param int $expirationTime
     */
    public function setExpirationTime($expirationTime) {
        $inSeconds = intval($expirationTime/1000);
        $this->expirationTime = Carbon::createFromTimestampUTC($inSeconds);
    }

    /**
     * @return Carbon
     */
    public function getInitiationTime() {
        return $this->initiationTime;
    }

    /**
     * @param int $initiationTime
     */
    public function setInitiationTime($initiationTime) {
        $inSeconds = intval($initiationTime/1000);
        $this->initiationTime = Carbon::createFromTimestampUTC($inSeconds);
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