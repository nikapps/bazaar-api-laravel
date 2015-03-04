<?php namespace Nikapps\BazaarApiLaravel\Models\Responses;

interface BazaarApiResponse {

    /**
     * is response ok
     *
     * @return boolean
     */
    public function isOk();

    /**
     * @param bool $ok
     */
    public function setOk($ok);

    /**
     * get response json
     *
     * @return array
     */
    public function getResponseJson();

    /**
     * set response json
     *
     * @param array $json
     */
    public function setResponseJson($json);

} 