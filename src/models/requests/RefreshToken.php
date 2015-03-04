<?php namespace Nikapps\BazaarApiLaravel\Models\Requests;

class RefreshToken implements BazaarApiRequest{

    private $grantType;
    private $clientId;
    private $clientSecret;
    private $refreshToken;

    function __construct() {

        $this->clientId = \Config::get('bazaar-api-laravel::credentials.client_id');
        $this->clientSecret = \Config::get('bazaar-api-laravel::credentials.client_secret');
        $this->refreshToken = \Config::get('bazaar-api-laravel::credentials.refresh_token');
        $this->grantType = \Config::get('bazaar-api-laravel::api.refresh_token.grant_type');

    }

    /**
     * get request uri
     *
     * @return string
     */
    public function getUri() {
        return \Config::get('bazaar-api-laravel::api.refresh_token.path');
    }

    /**
     * get post data
     *
     * @return array
     */
    public function getPostData() {
        return [
            'grant_type' => $this->getGrantType(),
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'refresh_token' => $this->getRefreshToken()
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
    public function getRefreshToken() {
        return $this->refreshToken;
    }

    /**
     * @param mixed $refreshToken
     */
    public function setRefreshToken($refreshToken) {
        $this->refreshToken = $refreshToken;
    }



} 