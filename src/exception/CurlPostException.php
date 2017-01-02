<?php

namespace m8rge\curl;


use m8rge\curl\result\CurlBaseResult;

class CurlPostException extends CurlException
{
    public $postFields;

    /**
     * @param CurlBaseResult $curlResult
     * @param array|null $postFields
     * @param \Exception|null $previous
     */
    public function __construct($curlResult, $postFields, \Exception $previous = null)
    {
        $this->postFields = $postFields;
        if ($curlResult->error) {
            $message = "posting to url $curlResult->requestUrl failed with error: $curlResult->error";
        } elseif ($curlResult->statusCode >= 400) {
            $message = "posting to url $curlResult->requestUrl return $curlResult->statusCode response code";
        }

        parent::__construct($curlResult, isset($message) ? $message : '', $previous);
    }
}