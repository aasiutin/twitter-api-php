<?php
namespace TwitterAPIPHP\Utils;

/**
 * Class HttpClient
 * @package TwitterAPIPHP\Utils
 *
 * Class for making http requests. Based on curl library.
 *
 * @file make http requests
 * @author lexicus <sutok85@gmail.com>
 * @version 1.0.0
 */

class HttpClient
{
    //this class has set of configuration options
    use Options;

    /**
     * http client onject initialization
     *
     * @param array $options options for curl initialization
     */
    public function __construct($options = array())
    {
        $this->options = $this->getDefaultOptions();
        $this->setOptions($options);
    }

    /**
     * make http request
     *
     * @param string $type request type (get|post)
     * @param string $url request url
     * @param array $params request parameters set
     * @param array $headers request headers set
     * @return mixed http response, @see curl_exec()
     * return description for details
     */
    public function makeRequest($type, $url, $params = array(), $headers = array())
    {
        $ch = curl_init($url);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $this->prepareUrlAndParams($ch, $type, $url, $params);

        curl_setopt_array($ch, $this->getOptions());

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    /**
     * configure curl resource depending on request type
     * and parameters
     *
     * @param resource $ch curl resource
     * @param string $type request method type (get|post)
     * @param string $url request url
     * @param $params request parameters
     * @return void
     */
    protected function prepareUrlAndParams($ch, $type, $url, $params)
    {
        $query = http_build_query($params);

        if ('post' === strtolower($type)) {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            if (!empty($query)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            }
        } else {
            $getUrl = $query ? $url .'?'. $query : $url;
            curl_setopt($ch, CURLOPT_URL, $getUrl);
        }
    }

    /**
     * get default http client params.
     * actually it's an array of curl options
     *
     * @return array default http client parameters
     */
    protected function getDefaultOptions()
    {
        return array(
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 3,
            //CURLINFO_HEADER_OUT     => true
        );
    }
}