<?php

namespace AmVal\SteamAPIBundle\Service;

use Monolog\Logger;

/**
 * Class SteamUtility
 * This class manages the calls to Steam API & returns the data
 *
 * @package AmVal\SteamAPIBundle\Service
 */
class SteamUtility
{
    /**
     * @var string Steam API URL
     */
    protected $steamAPIBaseUrl;

    /**
     * @var string Steam API Key
     */
    protected $steamAPIKey;

    /**
     * @var Logger $logger
     */
    protected $logger;

    const CONNECT_TIMEOUT = 2; // 2 seconds timeout

    const RESP_FORMAT_JSON = 'json';
    const RESP_FORMAT_VDF = 'vdf';
    const RESP_FORMAT_XML = 'xml';

    /**
     * SteamUtility constructor.
     *
     * @param string $steamAPIBaseUrl
     * @param string $steamAPIKey
     * @param Logger $logger
     */
    public function __construct($steamAPIBaseUrl, $steamAPIKey, Logger $logger)
    {
        $this->steamAPIBaseUrl = $steamAPIBaseUrl;
        $this->steamAPIKey = $steamAPIKey;
        $this->logger = $logger;
    }

    /**
     * Builds the basic URL to be called from its components
     *
     * @param string $scope
     * @param string $method
     * @param string $version
     * @return string
     */
    public function buildBaseUrlFromMethod($scope, $method, $version)
    {
        return $this->steamAPIBaseUrl . '/' . $scope . '/' . $method . '/' . $version;
    }

    /**
     * Performs a GET on the API and returns the XML data
     *
     * @param string      $url    Target URL
     * @param array|null  $params URL Parameters
     * @return array|null         Response content or null
     */
    public function fetchXmlDataFromUrl($url, $params = null) {
        $params['format'] = self::RESP_FORMAT_XML;
        try {
            $content = SteamUtility::fetchURL($url, $params);
            if ($content) {
                $xmlContent = simplexml_load_string($content);
                $jsonContent = json_encode($xmlContent);
                return json_decode($jsonContent,true);
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Performs a GET on the API and returns the JSON data
     *
     * @param string      $url    Target URL
     * @param array|null  $params URL Parameters
     * @return array|null         Response content
     */
    public function fetchJsonDataFromUrl($url, $params = null) {
        $params['format'] = self::RESP_FORMAT_JSON; //Just a security, should be the default format of the response
        try {
            $content = SteamUtility::fetchURL($url, $params);
            if ($content) {
                return json_decode($content, true);
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Fetches content of a given URL via HTTP GET method
     *
     * @param string     $url    Target URL
     * @param array|null $params URL Parameters
     * @return string            Response content or FALSE on error
     */
    public function fetchURL($url, $params = null)
    {
        $url = $this->buildUrl($url, $params);

        $this->logger->addInfo("Scanned address : " . $url);
        if (self::getIniBool('allow_url_fopen'))
        {
            $ctx = stream_context_create(array(
                'http' => array(
                    'timeout' => self::CONNECT_TIMEOUT
                )));
            return file_get_contents($url, false, $ctx);
        }
        elseif (function_exists('curl_init'))
        {
            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_TIMEOUT, self::CONNECT_TIMEOUT);
            return curl_exec($handle);
        }
        else
        {
            return false;
        }
    }

    /**
     * Adds the parameters to the url
     *
     * @param string     $url    Root Target URL
     * @param array|null $params URL Parameters
     * @return string            URL with parameters
     */
    private function buildUrl($url, $params)
    {
        if(!array_key_exists('key', $params) || !is_string($params['key'])) {
            $params['key'] = $this->steamAPIKey;
        }
        if(!is_null($params) && (is_array($params) && count($params) > 0)) {
            $url .='?';
            foreach ($params as $key => $param) {
                $url .= $key . '=' . $param . '&';
            }
        }
        return $url;
    }

    /**
     * Returns boolean value of a php.ini setting
     *
     * @param  string  $ini_name Setting name
     * @return boolean           Setting value
     */
    private function getIniBool($ini_name) {
        $ini_value = ini_get($ini_name);
        switch (strtolower($ini_value))
        {
            case 'on':
            case 'yes':
            case 'true':
                return 'assert.active' !== $ini_name;
            case 'stdout':
            case 'stderr':
                return 'display_errors' === $ini_name;
            default:
                return (bool) (int) $ini_value;
        }
    }
}