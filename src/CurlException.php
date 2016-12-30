<?php namespace m8rge;


class CurlException extends \Exception
{
    /**
     * @var CurlResult
     */
    public $curlResult;

    public $postFields;

    /**
     * @param CurlResult $curlResult
     * @param array|null $postFields
     * @param \Exception|null $previous
     */
    public function __construct($curlResult, $postFields = null, \Exception $previous = null)
    {
        $this->curlResult = $curlResult;
        $this->postFields = $postFields;
        if ($curlResult->error) {
            $message = ($postFields ? 'posting to' : 'retrieving') . " url $curlResult->requestUrl failed with error: $curlResult->error";
        } elseif ($curlResult->statusCode >= 400) {
            $message = ($postFields ? 'posting to' : 'retrieving') . " url $curlResult->requestUrl return $curlResult->statusCode response code";
        }

        parent::__construct(isset($message) ? $message : '', 0, $previous);
    }
}
