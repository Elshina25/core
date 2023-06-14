<?php
class UrlGetClass
{
    const CURL_TIMEOUT = 180;

    private $curlObject;

    public function __construct()
    {
        $this->curlObject = curl_init();
        curl_setopt($this->curlObject, CURLOPT_CONNECTTIMEOUT, static::CURL_TIMEOUT);
        curl_setopt($this->curlObject, CURLOPT_TIMEOUT, static::CURL_TIMEOUT);
        curl_setopt($this->curlObject, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curlObject, CURLOPT_MAXREDIRS, 10);
        curl_setopt($this->curlObject, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curlObject, CURLOPT_HEADER, false);
        curl_setopt($this->curlObject, CURLOPT_RETURNTRANSFER, true);
    }

    public function get($url)
    {
        if (empty($url) === true) {
            return false;
        }

        MessageHelper::addMessageToLog("request: " . $url);

        curl_setopt($this->curlObject, CURLOPT_URL, $url);
        // if(curl_exec($this->curlObject) === false) {
        //     echo 'Error:' . curl_error($this->curlObject);
        // }
        return curl_exec($this->curlObject);
    }

    public function getHttpCode()
    {
        return curl_getinfo($this->curlObject, CURLINFO_HTTP_CODE);
    }

    public function getFile($url)
    {
        if (empty($url) === true) {
            return false;
        }

        $fileContent = $this->get($url);

        $contentType = curl_getinfo($this->curlObject, CURLINFO_CONTENT_TYPE);

        $responseCode = curl_getinfo($this->curlObject, CURLINFO_HTTP_CODE);

        $fileInfo = array();

        if ($responseCode == "200") {

            $fileInfo = array(
                'content' => $fileContent,
                'type' => $contentType,
                'response' => $responseCode,
            );
        }

        return $fileInfo;
    }

    public function __destruct()
    {
        curl_close($this->curlObject);
    }
}
?>
