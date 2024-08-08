<?php
require_once('TwitterAPIExchange.php');
require_once("config.php");

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
'oauth_access_token' => "403327819-f87vXLQRAsIaBRudF0if9I6L7D6VgjYaGHAMeRrR",
'oauth_access_token_secret' => "dYNELxOBo3M0dH5alYLaUaRN2J3UuDfMHvIxLCCyT9SdH",
'consumer_key' => "7aiRY3tk76zT5rPXcuqpo9zkq",
'consumer_secret' => "LTKbhttd8TK9u4Nr7jfPFFGAVi0IW2VGCYEBnRFJtIz2OdYWmM"
);

$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$sqlCommand = "SELECT * FROM followers_info ORDER BY id DESC LIMIT 1";
$result = mysqli_query($link, $sqlCommand);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $cursorValue = $row["next_cursor"] ;
    }
} else {
    $cursorValue = -1 ;
}

echo $cursorValue;

if($cursorValue!="0"||$cursorValue!=0)
{
$url = "https://api.twitter.com/1.1/followers/ids.json";
$requestMethod = "GET";
$getfield = "?cursor=$cursorValue&screen_name=narendramodi&count=5000";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest(),$assoc = TRUE);

if($string["errors"][0]["message"] != "") 
{
	echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string["errors"][0]["message"]."</em></p>";
	exit();
}

$cursorValue = $string["next_cursor"];
$previousValue = $string["previous_cursor"];

foreach($string["ids"] as $items)
    {
        $query = "INSERT INTO followers_info(`follower_id`,`previous_cursor`,`next_cursor`) VALUES('$items','$previousValue','$cursorValue')";
        if (mysqli_query($link, $query )) {
        echo "Yes";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($link);
}

    }
}  
    mysqli_close($link);
?>