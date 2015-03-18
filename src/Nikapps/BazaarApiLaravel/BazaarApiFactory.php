<?php namespace Nikapps\BazaarApiLaravel;

use Illuminate\Config\Repository as ConfigRepository;

class BazaarApi {

    /**
     * @var ConfigRepository
     */
    protected $config;

    /**
     * @param ConfigRepository $config
     */
    public function __construct(ConfigRepository $config) {
    }
}