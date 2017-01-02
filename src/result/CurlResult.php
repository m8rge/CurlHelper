<?php

namespace m8rge\curl\result;


class CurlResult extends CurlBaseResult
{
    /**
     * @var string
     */
    public $response;

    /**
     * @var string[][]
     */
    public $headers;

    public function init()
    {
        $response = curl_multi_getcontent($this->curlHandler);
        if (is_string($response) && !empty($response)) {
            list($headers, $body) = explode("\r\n\r\n", $response, 2);
            $this->headers = $this->parseHeaders($headers);
            $this->response = $body;
        }

        parent::init();
    }

    function __toString()
    {
        return '(' . $this->statusCode . ($this->error ? ' ' . $this->error : '') . ') ' .
            strlen($this->response) . ' bytes: ' .
            substr($this->response, 0, 100) . (strlen($this->response) > 100 ? '…' : '');
    }

    protected function parseHeaders($rawHeaders)
    {
        $headers = [];
        $key = '';

        foreach (explode("\n", $rawHeaders) as $headerLine) {
            if (strpos($headerLine, ':') !== false) {
                list($name, $value) = explode(':', $headerLine, 2);
                $key = $name;

                $trimmedValue = trim($value);
                if (!isset($headers[$name])) {
                    $headers[$name] = [$trimmedValue];
                } elseif (is_array($headers[$name])) {
                    $headers[$name][] = $trimmedValue;
                }
            } elseif ($headerLine[0] == "\t") {
                end($headers[$key]);
                $index = key($headers[$key]);
                $headers[$key][$index] .= "\r\n\t" . trim($headerLine);
            } else {
                $headers[] = trim($headerLine);
            }
        }

        return $headers;
    }
}