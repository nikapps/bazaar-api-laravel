<?php namespace Nikapps\BazaarApiLaravel;

use GuzzleHttp\Exception\ClientException;

class BazaarApiLogger {

    /**
     * log network exceptions
     *
     * @param ClientException $exception
     * @param string $message
     */
    static function logNetworkExceptions(ClientException $exception, $message){
        //log error
        \Log::critical($message, [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'uri' => $exception->getRequest()->getUrl(),
            'status_code' => $exception->getResponse()->getStatusCode()
        ]);
    }
} 