<?php namespace Nikapps\BazaarApiLaravel;

class AccessTokenExpiredException extends \Exception {
    protected $message = "Access token is expired";
}