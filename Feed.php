<?php
/*
 *todo:
 *figure out date logic
 *add logic to detect if ID didn't come off querystring
 *refactor Michael's code in the showFeed() method since we are only displaying 1 feed at a time
 *
 */

class Feed
{
  public $Id = 0;
  public $Rss = '';
  public $Date = '';
  public function __contruct($Id)
  {
    $this->Id = $Id; //assigns object with unique feed ID (ID pulled from query string)
    $conn = new mysqli(server, username, password, db); //makes connection to DB
    $sql = 'SELECT * FROM feeds WHERE ID = "' . $this->Id . '"'; //sql command to get feed data
    $result = $conn->query($sql);  //stores query result in variable
    $row = $result->fetch_assoc(); //row is assoc array of result
    $lastUpdate = $row['LastUpdate']; //stores the last update (minutes) into a variable
    if ($lastUpdate >= 50){
      $lastUpdate = 0;
    }

    if((date('i') > ($lastUpdate + 10)) || (!isset($_SESSION['Feed']))){ //if the last update was more than 10 mins ago or is not set, update feed & date in db
      //michael's code to store xml in array starts here
      $xml = array();
      $request = $row['Link']; //pulls feed URL from db
      $response = file_get_contents($request);
      array_push($xml, simplexml_load_string($response));
      //michael's code ends here

      $_SESSION['Feed'] = $xml; //stores feed array in a session variable
      $this->Rss = $xml; //sets object's Rss property to the the feed variable
      $sql = 'UPDATE feeds SET LastUpdate=now() WHERE ID = "' . $this->Id . '"'; //updates db with new LastUpdate date
      conn->query($sql);
    } else { //was updated within 10 minutes, store the object's Rss property with the last retrieved data
        $this->Rss = $_SESSION['Feed']; //sets object's Rss property to the most recent feed
    }
  }//end Feed constructor

  public function showFeed()
  {
    //michael's code to display feed starts here
    $feeds = array();
    print '<h1>' . $this->Rss->channel->title . '</h1>';

    foreach($this->Rss as $feed)
    {
      foreach($feed->channel->item as $story)
      {
        $articles = array (
          'site' => $feed->channel->title,
          'title' => $story->title,
          'desc' => $story->description,
          'link' => $story->link,
          'date' => $story->pubDate,
        );
        array_push($feeds, $articles);
      }
    }

    usort($feeds, function($a, $b) {
      return strtotime($b['date']) - strtotime($a['date']);
    });

    echo '<ul>';
    for ($x = 0; $x < sizeof($feeds); $x++) {
      $site = $feeds[$x]['site'];
      $title = str_replace(' & ', ' &amp; ', $feeds[$x]['title']);
      $link = $feeds[$x]['link'];
      $description = $feeds[$x]['desc'];
      $date = date('l F d, Y', strtotime($feeds[$x]['date']));
      echo '<li>';
      echo '<strong>'.$site.': <a href="'.$link.'" title="'.$title.'" target="_blank">'.$title.'</a><p>' .
      $description. '</p></strong> ('.$date.')';
      echo '</li>';
    }
    echo '</ul>';
    //michael's code ends here
  }//end showFeed() method

}//end Feed class
