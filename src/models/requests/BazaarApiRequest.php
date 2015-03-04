<?php namespace Nikapps\BazaarApiLaravel\Models\Requests;

interface BazaarApiRequest {

    /**
     * get request uri
     *
     * @return string
     */
    public function getUri();
} 