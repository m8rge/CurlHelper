<?php

class CurlHelper
{
    /**
     * @param string $url
     * @param array $additionalConfig
     * @param int $retryCount
     * @throws CurlException
     * @return mixed downloaded data or false if failed
     */
    static function getUrlFailSafe($url, $additionalConfig = array(), $retryCount = 5) {
        for ($i=0; $i<$retryCount; $i++) {
            try {
                return self::getUrl($url, $additionalConfig);
            } catch (CurlException $e) {
                if ($i+1 == $retryCount)
                    throw $e;
            }
        }

        return false;
    }

    /**
     * @param string $url
     * @param array $additionalConfig
     * @throws CurlException
     * @return mixed downloaded data
     */
    static function getUrl($url, $additionalConfig = array()) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt_array($ch, $additionalConfig);
        $data = curl_exec($ch);
        if ($data === false)
            throw new CurlException("retrieving url $url failed with error: ".curl_error($ch));
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_status >= 400)
            throw new CurlException("url $url return $http_status response code. returned data: $data", $http_status, $data);

        return $data;
    }

    /**
     * @param string $url
     * @param array $postFields
     * @param array $additionalConfig
     * @return mixed returned data
     * @throws CurlException
     */
    static function postUrl($url, $postFields, $additionalConfig = array()) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt_array($ch, $additionalConfig);
        $data = curl_exec($ch);
        if ($data === false)
            throw new CurlException("posting to $url failed with error: ".curl_error($ch).". postFields: ".print_r($postFields, true));
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_status >= 400)
            throw new CurlException("url $url return $http_status response code. postFields: ".print_r($postFields, true).
                "\nreturned data: $data", $http_status, $data);

        return $data;
    }

    /**
     * @param string $url
     * @param string $toFile file name
     * @param array $additionalConfig
     * @throws CurlException
     */
    static function downloadToFile($url, $toFile, $additionalConfig = array()) {
        $fp = fopen($toFile, 'w');
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt_array($ch, $additionalConfig);
        $res = curl_exec($ch);
        if ($res === false)
            throw new CurlException("retrieving url $url failed with error: ".curl_error($ch));
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);

        if ($http_status >= 400)
            throw new CurlException("url $url return $http_status response code", $http_status);
    }
}

class CurlException extends Exception
{
    /** @var string */
    protected $data;

    public function __construct($message = "", $code = 0, $data = '', Exception $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    public function getData()
    {
        return $this->data;
    }
}
