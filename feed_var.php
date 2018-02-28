<?
//read-feed-simpleXML.php
//our simplest example of consuming an RSS feed

  $xml = array();

  $request = "https://news.google.com/news/rss/headlines/section/topic/TECHNOLOGY?ned=us&hl=en&gl=US";
  $response = file_get_contents($request);
  array_push($xml, simplexml_load_string($response));

  $request2 = "https://news.google.com/news/rss/headlines/section/topic/TECHNOLOGY?ned=us&hl=en&gl=US";
  $response2 = file_get_contents($request2);
  array_push($xml, simplexml_load_string($response2));

  $request3 = "https://news.google.com/news/rss/headlines/section/topic/TECHNOLOGY?ned=us&hl=en&gl=US";
  $response3 = file_get_contents($request3);
  array_push($xml, simplexml_load_string($response3));

  print '<h1>' . $xml->channel->title . '</h1>';



  foreach($xml as $feed)
  {
      print '<h1>' . $feed->channel->title . '</h1>';
      /*echo '<pre>';
      var_dump($feed);
      echo '</pre>';*/
      foreach($feed->channel->item as $story)
      {
        echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
        echo '<p>' . $story->description . '</p><br /><br />';
      }
      
  }



/*  print '<h1>' . $xml->channel->title . '</h1>';
  foreach($xml->channel->item as $story)
  {
    echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
    echo '<p>' . $story->description . '</p><br /><br />';
  }*/
?>