<?php
/**
 * feed_view.php
 * Displays all feeds with specified CategoryID
 * Caches data for ten mins after first time loading page
 * @package wn18
 * @author Michael Nomura <mnnomura@gmail.com>
 * @version 0.9 2011/3/22
 * @link http://michaelnomura.dreamhosters.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo finish instruction sheet
 */

# '../' works for a sub-folder.  use './' for the root  
require 'config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

//END CONFIG AREA ----------------------------------------------------------

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

$urls = [];
//add get data
$cTest = $_GET['id'];

$sql = "select ID,CategoryID,Title,URL from p4_Feeds";
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
if (mysqli_num_rows($result) > 0)//at least one record!
{//show results

    while ($row = mysqli_fetch_assoc($result))
    {//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db

        if (dbOut($row['CategoryID']) == $cTest){
            //-----need-----make a array of urls
            //dbOut($row['FeedURL']);
            array_push($urls, dbOut($row['URL']));

        }               
    }
    showFeeds($urls);
}else{//no records
  echo '<h3>Currently No Feeds in Database.</h3>';
}

var_dump($urls);
die;

function showFeeds($urls)
{#form submits here we show entered name
	get_header(); #defaults to footer_inc.php   
    
    $now = (int)date("h:i:sa");
    
    $_SESSION['now'] = $now;
    
    //$_SESSION['pastTen'] = /*lastupdate*/ - 10
    
    if (!isset($_SESSION['firstTime']) || ($_SESSION['firstTime'] + 10) > $now){
        //has been ten mins
        
        session_destroy();
        
        startSession();
        
        $_SESSION['firstTime'] = $now;
        
        if (!isset($_SESSION['Feeds'])){
            $_SESSION['Feeds'] = array();
        }
    
        $xml = array();
        
        foreach ($urls as $site){
            $request = $site;
            $response = file_get_contents($request);
            array_push($xml, simplexml_load_string($response));
        }
        
        //create $feed array
        $feeds = array();

        //loop through $xml
        foreach($xml as $feed)
        {
            //loop through each article
            foreach($feed->channel->item as $story)
            {
                //a
                $articles = array ( 
                    'site'  => (string)$feed->channel->title,
                    'title' => (string)$story->title,
                    'desc'  => (string)$story->description,
                    'link'  => (string)$story->link,
                    'date'  => (string)$story->pubDate,
                    );


                array_push($feeds, $articles);

            }//end foreach

        }
        
        usort($feeds, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
        });


        $_SESSION['Feeds'][] = $feeds;
        
        //dumpDie($_SESSION['Feeds']);

        echo '<pre>';
        foreach ($_SESSION['Feeds'] as $data){
            foreach ($data as $display){
                echo '<div>';
                echo '<h3>';
                echo $display['site'];
                echo '</h3>';
                echo '<br>';
                echo '<pre>';
                echo $display['title'];
                echo '</pre>';
                echo '<br>';
                echo '<div>';
                echo $display['desc'];
                echo '</div>';
                echo '<br>';
                //echo '<pre>';
                //echo $display['link'];
                //echo '</pre>';
                //echo '<br>';
                echo '<pre>';
                echo $display['date'];
                echo '</pre>';
                echo '</div>';

            }
        }
        echo '</pre>';

        }else{
            //has not been ten mins
            dumpDie($_SESSION['Feeds']);
        
            echo '<pre>';
            foreach ($_SESSION['Feeds'] as $data){
            echo $data;
            }
            echo '</pre>';
        }
}



?>
