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

$numberOfValues = 50;
$incrementalValue = 5;
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS,DB_NAME);
// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($link,"utf8");

$sqlCommand = "SELECT * FROM followers_detailed_info_index ORDER BY id DESC LIMIT 1";
$result = mysqli_query($link, $sqlCommand);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $cursorValue = $row["table_index"] ;
    }
} else {
    $cursorValue = 1 ;
}

echo $cursorValue;

while($numberOfValues>=0)
{
$selectdataCommand = "SELECT * FROM followers_info WHERE table_id='$cursorValue'";
$resultOne = mysqli_query($link, $selectdataCommand);
if (mysqli_num_rows($resultOne) > 0) {
    // output data of each row
    while($row1 = mysqli_fetch_assoc($resultOne)) {
        $followerId .= $row1["follower_id"] ;
    }
} else {
    $followerId = 0 ;
    break;
}

if($followerId!=0)
{
if($numberOfValues!=0)
{
$followerId.=",";
}

$numberOfValues--;
$cursorValue += $incrementalValue;
}
else
{
break;
}
}

echo $followerId;

echo $cursorValue;

$updateCommand = "UPDATE followers_detailed_info_index SET table_index='$cursorValue' WHERE id=1";
$resultOne = mysqli_query($link, $updateCommand);

$url = "https://api.twitter.com/1.1/users/lookup.json";
$requestMethod = "GET";
$getfield = "?user_id=$followerId&include_entities=false";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest(),$assoc = TRUE);

if($string["errors"][0]["message"] != "") 
{
	echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string["errors"][0]["message"]."</em></p>";
	exit();
}

foreach($string as $item)
{

$name = $item["name"];

echo $name;

$screenName = $item["screen_name"];
$profiledescription = mysqli_real_escape_string($link,$string[0]["description"]);
$profileimage = $item["profile_image_url"];
$followerscount = $item["followers_count"];
$friendscount = $item["friends_count"];
$createdat = $item["created_at"];
$statuscount = $item["statuses_count"];
$location = $item["location"];

$query = "INSERT INTO followers_detailed_info(`name`,`screen_name`,`profile_image_url`,`followers_count`,`friends_count`,`created_at`,`status_count`,`location`,`profile_description`) VALUES('$name','$screenName','$profileimage','$followerscount','$friendscount','$createdat','$statuscount','$location','$profiledescription')";
if (mysqli_query($link, $query )) {
        echo "Yes";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($link);
}
}
mysqli_close($link);
?>