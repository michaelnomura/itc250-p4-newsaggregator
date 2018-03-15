<?php
require '../../wn18/inc_0700/credentials_inc.php';//db credentials should be stored here
require 'Feed.php';
$conn = new mysqli('DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_NAME');//db credentials will be placed here
$myFeed = new Feed($_GET['FeedID'], $conn);//!!When coming to the view page, we need to grab the Feed ID as a GET[] variable
$myFeed->showFeed(); //shows the feed
$conn->close();//close db connection
