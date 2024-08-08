<?php
require_once('TwitterAPIExchange.php');
require_once('config.php');

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);

if (!$link) {
    echo "Hello";
    die("Connection failed: " . mysqli_connect_error());
}

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
'oauth_access_token' => "403327819-f87vXLQRAsIaBRudF0if9I6L7D6VgjYaGHAMeRrR",
'oauth_access_token_secret' => "dYNELxOBo3M0dH5alYLaUaRN2J3UuDfMHvIxLCCyT9SdH",
'consumer_key' => "7aiRY3tk76zT5rPXcuqpo9zkq",
'consumer_secret' => "LTKbhttd8TK9u4Nr7jfPFFGAVi0IW2VGCYEBnRFJtIz2OdYWmM"
);
$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";
if (isset($_GET['user'])) {$user = $_GET['user'];} else {$user = "vaikameswaran";}
if (isset($_GET['count'])) {$count = $_GET['count'];} else {$count = 20;}
$getfield = "?screen_name=$user&count=$count";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest(),$assoc = TRUE);

if($string["errors"][0]["message"] != "") 
{
	echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string["errors"][0]["message"]."</em></p>";
	exit();
}



foreach($string as $items)
    {

$query = "INSERT INTO followers_info(`follower_id`) VALUES('1')")";

if (mysqli_query($link, $query )) {
    echo "New record created successfully";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($link);
}

    }
mysqli_close($link);
?>
