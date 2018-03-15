<?php
/*
 *todo:
 *add logic to detect if ID didn't come off querystring
 *formatting for the news stories?
 */

class Feed
{
  public $Id = 0;
  public $Title = '';
  public $Rss = array();

  public function __construct($Id, $conn)
  {
    $this->Id = $Id; //assigns object with unique feed ID (ID pulled from query string/first argument of constructor)

    $sql = 'SELECT * FROM p4_Feeds WHERE ID = "' . $this->Id . '"'; //sql command to get feed data
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $this->Title = $row['Title']; //sets object's title property from db
    $lastUpdated = strtotime($row['LastUpdated']); //stores the last update into a variable

    if(time() - $lastUpdated > 10 * 60 || !isset($_SESSION['Feed'])) //reset feed if 10 mins has passed, or $_SESSION['Feed'] not set
    {
      //michael's code to store xml in array starts here
      $xml = array();
      $request = $row['URL']; //pulls feed URL from db
      $response = file_get_contents($request);
      array_push($xml, simplexml_load_string($response)); //creates an array of articles

      $feeds = array();
      foreach($xml as $feed)
      {
        foreach($feed->channel->item as $story)
        {
          $articles = array (
            'site' => (string)$feed->channel->title,
            'title' => (string)$story->title,
            'desc' => (string)$story->description,
            'link' => (string)$story->link,
            'date' => (string)$story->pubDate,
          );
          array_push($feeds, $articles);
        }
      }
      //michael's code ends here

      $_SESSION['Feed'] = serialize($feeds); //serializes object to store in session variable
      $this->Rss = $feeds; //sets object's Rss property to the the feed variable
      $sql = 'UPDATE p4_Feeds SET LastUpdated=now() WHERE ID = "' . $this->Id . '"'; //updates db with new LastUpdate date
      $conn->query($sql);
    } else { //was updated within 10 minutes, store the object's Rss property with the last retrieved data
        $this->Rss = unserialize($_SESSION['Feed']); //sets object's Rss property to the most recent feed
    }
  } //end Feed constructor

  public function showFeed()
  {
    echo '<h1>' . $this->Title . '</h1>';

    foreach ($this->Rss as $article){
        foreach ($article as $item){
          echo $item;
        }
    }
  } //end showFeed() method

} //end Feed class
