<?php

namespace m8rge\curl\result;


use m8rge\curl\Object;

class CurlBaseResult extends Object
{
    /**
     * @var string
     */
    public $statusCode;

    /**
     * @var string
     */
    public $requestUrl;

    /**
     * @var string
     */
    public $effectiveUrl;

    /**
     * @var string
     */
    public $error;

    /**
     * @var resource
     */
    protected $curlHandler;

    public function init()
    {
        if (curl_errno($this->curlHandler)) {
            $this->error = curl_error($this->curlHandler);
        }

        $this->statusCode = curl_getinfo($this->curlHandler, CURLINFO_HTTP_CODE);
        $this->effectiveUrl = curl_getinfo($this->curlHandler, CURLINFO_EFFECTIVE_URL);
        
        $this->curlHandler = null;
    }

    function __toString()
    {
        return '(' . $this->statusCode . ($this->error ? ' ' . $this->error : '') . ') ';
    }
}