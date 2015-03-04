<?php namespace Nikapps\BazaarApiLaravel\Models\Responses;

class CancelSubscription implements BazaarApiResponse {

    /**
     * @var bool
     */
    private $ok = false;

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