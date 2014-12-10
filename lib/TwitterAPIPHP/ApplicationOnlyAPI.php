<?php
namespace TwitterAPIPHP;
/**
 * provide Twitter Application only API calls
 *
 * @see https://dev.twitter.com/docs/auth/application-only-auth
 * @file provide Twitter Application only API calls
 * @author lexicus <sutok85@gmail.com>
 */

/**
 * Class ApplicationOnlyAPI
 * @package TwitterAPIPHP
 */
class ApplicationOnlyAPI extends API
{

    /**
     * make API call
     *
     * @param string $type request type, get|post in twitter case
     * @param string $uri api endpoint (request uri)
     * @param array $params api call parameters set
     * @param array $headers api call headers set
     * @return mixed parsed json application response object
     */
    public function api($type, $uri, $params = array(), $headers = array())
    {
        $headers[] = 'Authorization: Bearer '. $this->getBearerToken();

        return parent::api($type, $uri, $params, $headers);
    }

    /**
     * get application bearer token
     *
     * @return string bearer token value
     * @throws \Exception in case bearer token is not set
     * and it's imposible to fetch it through API call
     */
    public function getBearerToken()
    {
        if (!empty($this->options['bearerToken'])) {
            return $this->options['bearerToken'];
        }

        $bearerToken = $this->requestBearerToken();

        if (empty($bearerToken)) {
            throw new \Exception("Can't get bearer token");
        }

        return  $this->options['bearerToken'] = $bearerToken;
    }

    /**
     * set application bearer token for making requests
     * from an app without user authentification
     *
     * @param string $token bearer token value
     * @reutrn void
     */
    public function setBearerToken($token)
    {
        $this->setOption("bearerToken", $token);
    }

    /**
     * request bearer token through API call
     *
     * @return string|null bearer token value or null,
     * if something went wrong
     */
    protected function requestBearerToken()
    {
        $bearerTokenCredentials = $this->getBearerTokenCredentials();

        $ch = $this->getHttpClient();

        $response = $ch->makeRequest(
            'post',
            static::TW_API_URL .'/oauth2/token',
            array(//parameters
                'grant_type' => 'client_credentials'
            ),
            array(//headers
                'Authorization: Basic '. $bearerTokenCredentials,
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
            )
        );

        $response = json_decode($response);

        
        if (!empty($response->token_type) &&
            'bearer' == $response->token_type
           ) {
            return $response->access_token;
        }

        return null;
    }

    /**
     * get bearer token header auth credentials
     *
     * @return string bearer credentials
     */
    protected function getBearerTokenCredentials()
    {
        $bearerTokenCredentials =
            urlencode($this->options['consumerKey']) .':'.
            urlencode($this->options['consumerSecret']);

        return base64_encode($bearerTokenCredentials);
    }
}
