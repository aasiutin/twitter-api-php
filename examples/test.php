<?php
define("TWITTER_CONSUMER_KEY", "YOUR_CONSUMER_KEY");
define("TWITTER_CONSUMER_SECRET", "YOUR_CONSUMER_SECRET");
define("TWITTER_BEARER_TOKEN", "YOUR_BEARER_TOKEN");

require_once '../vendor/autoload.php';

$api = new TwitterAPIPHP\ApplicationOnlyAPI(array(
    'consumerKey' => TWITTER_CONSUMER_KEY,
    'consumerSecret' => TWITTER_CONSUMER_SECRET,
    //if bearer token isn't provided, it will be
    //fetched through API
    //'bearerToken' => TWITTER_BEARER_TOKEN
));

$tweets = $api->api(
    'GET',
    'statuses/user_timeline.json',
    array(
        'screen_name' => 'twitter',
        'count' => 1
    )
);

echo "<pre>";
var_dump($tweets);
echo "</pre>";

