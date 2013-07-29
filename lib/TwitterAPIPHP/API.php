<?php
namespace TwitterAPIPHP;
/**
 * Base Twitter API class. Allows to make requests and parse
 * json responses. For detailed API documentation see
 * https://dev.twitter.com/docs/api/1.1
 *
 * @author lexicus <sutok85@gmail.com>
 * @file base API class
 * @version 1.0.0
 * @see https://dev.twitter.com/docs/api/1.1
 */


class API
{
    //this class has a set of configuration options
    use Utils\Options;

    //url for making API requests
    const TW_API_URL = "https://api.twitter.com";

    //API version, needed for url building
    const TW_API_VER = '1.1';

    /**
     * @var string - Twitter Application consumer key
     */
    protected $consumerKey = '';

    /**
     * @var string - Twitter Application consumer secret
     */
    protected $consumerSecret = '';

    /**
     * initialize API object
     *
     * @param array $options API options, required keys are
     * consumerKey and consumerSecret
     * @throws \Exception
     */
    public function __construct($options = array())
    {
        $this->options = $this->getDefaultOptions();

        if (empty($options['consumerKey']) ||
            empty($options['consumerSecret'])
           ) {
            throw new \Exception(
                'Twitter App requires "consumerKey" and  "consumerSecret"'
            );
        }

        $this->setOptions($options);
    }

    /**
     * make API call
     *
     * @param string $type request type, get|post in twitter case
     * @param string $uri api endpoint (request uri)
     * @param array $params api call parameters set
     * @param array $headers api call headers set
     * @return mixed API parsed json response
     */
    public function api($type, $uri, $params = array(), $headers = array())
    {
        $ch = $this->getHttpClient();

        $url = $this->buildUrl($uri);

        $response = $ch->makeRequest($type, $url, $params, $headers);

        return json_decode($response);
    }

    /**
     * build full API request url, including hostname and
     * API version
     *
     * @param string $uri API request uri
     * @return string full API request url
     */
    protected function buildUrl($uri)
    {
        return static::TW_API_URL .'/'. static::TW_API_VER .'/'. $uri;
    }

    /**
     * get API default options set
     *
     * @return array API default options set
     */
    protected function getDefaultOptions()
    {
        //we provide custom SSL certificates in case our host doesn't
        //recognize Tiwtter certificates
        $certificatesDir = __DIR__ . DIRECTORY_SEPARATOR . 'certificates';
        $certificate = $certificatesDir . DIRECTORY_SEPARATOR . 'cacert.pem';

        return array(
            'certificatesDir' => $certificatesDir,
            'certificate' => $certificate
        );
    }

    /**
     * get http client object for making API call
     *
     * @return Utils\HttpClient http client object instance
     */
    protected function getHttpClient()
    {
        return new Utils\HttpClient($this->getHttpClientOptions());
    }

    /**
     * get http client default API options
     *
     * @return array http client API options
     */
    protected function getHttpClientOptions()
    {
        return array(
            CURLOPT_CAINFO => $this->options['certificate'], 
            CURLOPT_CAPATH => $this->options['certificatesDir']
        );
    }
}