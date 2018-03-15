<?

require '../../wn18/inc_0700/credentials_inc.php';
require 'Feed.php';
$myFeed->showFeed(); //shows the feed


//read-feed-simpleXML.php
//our simplest example of consuming an RSS feed
/*
  $xml = array();

  $request = "https://news.google.com/news/rss/headlines/section/topic/TECHNOLOGY?ned=us&hl=en&gl=US";
  $response = file_get_contents($request);
  array_push($xml, simplexml_load_string($response));

  $request2 = "https://gizmodo.com/rss";
  $response2 = file_get_contents($request2);
  array_push($xml, simplexml_load_string($response2));

  $request3 = "https://www.wired.com/feed/rss";
  $response3 = file_get_contents($request3);
  array_push($xml, simplexml_load_string($response3));

  $feeds = array();

  print '<h1>' . $xml->channel->title . '</h1>';



  foreach($xml as $feed)
  {
      foreach($feed->channel->item as $story)
      {
        $articles = array ( 
            'site'  => $feed->channel->title,
            'title' => $story->title,
            'desc'  => $story->description,
            'link'  => $story->link,
            'date'  => $story->pubDate,
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

/*$limit = 30;
echo '<ul>';
for ($x = 0; $x < $limit; $x++) {
    $site = $feeds[$x]['site'];
    $title = str_replace(' & ', ' &amp; ', $feeds[$x]['title']);
    $link = $feeds[$x]['link'];
    $description = $feeds[$x]['desc'];
    $date = date('l F d, Y', strtotime($feeds[$x]['date']));
    
    echo '<li>';
    echo '<strong>'.$site.': <a href="'.$link.'" title="'.$title.'" target="_blank">'.$title.'</a>' . 
    $description. '</strong> ('.$date.')';
    echo '</li>';
}
echo '</ul>';*/
