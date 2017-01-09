<?php

namespace m8rge\curl\exception;


use m8rge\curl\result\CurlBaseResult;

class CurlException extends \Exception
{
    /**
     * @var CurlBaseResult
     */
    public $curlResult;

    /**
     * @param CurlBaseResult $curlResult
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($curlResult, $message = "", \Exception $previous = null)
    {
        $this->curlResult = $curlResult;
        if (empty($message)) {
            if ($curlResult->error) {
                $message = "retrieving url $curlResult->requestUrl failed with error: $curlResult->error";
            } elseif ($curlResult->statusCode >= 400) {
                $message = "retrieving url $curlResult->requestUrl return $curlResult->statusCode response code";
            }
        }

        parent::__construct($message, 0, $previous);
    }
}
