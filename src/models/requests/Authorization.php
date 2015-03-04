<?php namespace Nikapps\BazaarApiLaravel\Models\Requests;

class Authorization implements BazaarApiRequest {

    private $grantType;
    private $code;
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    function __construct($redirectUri, $code) {
        $this->redirectUri = $redirectUri;
        $this->code = $code;

        $this->clientId = \Config::get('bazaar-api-laravel::credentials.client_id');
        $this->clientSecret = \Config::get('bazaar-api-laravel::credentials.client_secret');
        $this->grantType = \Config::get('bazaar-api-laravel::api.authorization.grant_type');

    }

    /**
     * get request uri
     *
     * @return string
     */
    public function getUri() {
        return \Config::get('bazaar-api-laravel::api.authorization.path');
    }

    /**
     * get post data
     *
     * @return array
     */
    public function getPostData() {
        return [
            'grant_type' => $this->getGrantType(),
            'code' => $this->getCode(),
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'redirect_uri' => $this->getRedirectUri()
        ];
    }

    /**
     * @return mixed
     */
    public function getClientId() {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId) {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getClientSecret() {
        return $this->clientSecret;
    }

    /**
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret) {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return mixed
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getGrantType() {
        return $this->grantType;
    }

    /**
     * @param mixed $grantType
     */
    public function setGrantType($grantType) {
        $this->grantType = $grantType;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri() {
        return $this->redirectUri;
    }

    /**
     * @param mixed $redirectUri
     */
    public function setRedirectUri($redirectUri) {
        $this->redirectUri = $redirectUri;
    }




} 