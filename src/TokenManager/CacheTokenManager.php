<?php namespace Nikapps\BazaarApiLaravel\TokenManager;

use Illuminate\Support\Facades\Cache;
use Nikapps\BazaarApiPhp\TokenManagers\TokenManagerInterface;

class CacheTokenManager implements TokenManagerInterface
{

    /**
     * cache driver
     */
    protected $cacheDriver = null;

    /**
     * cache name
     */
    protected $cacheName;

    /**
     * when access token is received from CafeBazaar, this method will be called.
     *
     * @param string $accessToken access-token
     * @param int $ttl number of seconds remaining until the token expires
     * @return mixed
     */
    public function storeToken($accessToken, $ttl)
    {

        $ttlInMin = intval($ttl / 60);

        if (\Cache::driver($this->getCacheDriver())->has($this->cacheName)) {
            \Cache::driver($this->getCacheDriver())->forget($this->cacheName);
        }

        \Cache::driver($this->getCacheDriver())->put($this->cacheName, $accessToken, $ttlInMin);
    }

    /**
     * when access token is needed, this method will be called.
     *
     * @return string
     */
    public function loadToken()
    {

        return \Cache::driver($this->getCacheDriver())->get($this->cacheName, '');
    }

    /**
     * should we refresh token? (based on ttl)
     *
     * @return bool
     */
    public function isTokenExpired()
    {

        return !\Cache::driver($this->getCacheDriver())->has($this->cacheName);
    }

    /**
     * @return mixed
     */
    public function getCacheDriver()
    {
        return $this->cacheDriver;
    }

    /**
     * @param mixed $cacheDriver
     */
    public function setCacheDriver($cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;
    }

    /**
     * @return mixed
     */
    public function getCacheName()
    {
        return $this->cacheName;
    }

    /**
     * @param mixed $cacheName
     */
    public function setCacheName($cacheName)
    {
        $this->cacheName = $cacheName;
    }
}
