<?php
/**
 * demo_postback_nohtml.php is a single page web application that allows us to request and view 
 * a customer's name
 *
 * This version uses no HTML directly so we can code collapse more efficiently
 *
 * This page is a model on which to demonstrate fundamentals of single page, postback 
 * web applications.
 *
 * Any number of additional steps or processes can be added by adding keywords to the switch 
 * statement and identifying a hidden form field in the previous step's form:
 *
 *<code>
 * <input type="hidden" name="act" value="next" />
 *</code>
 * 
 * The above live of code shows the parameter "act" being loaded with the value "next" which would be the 
 * unique identifier for the next step of a multi-step process
 *
 * @package ITC281
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 1.1 2011/10/11
 * @link http://www.newmanix.com/
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo finish instruction sheet
 * @todo add more complicated checkbox & radio button examples
 */

# '../' works for a sub-folder.  use './' for the root  
require 'config_inc.php'; #provides configuration, pathing, error handling, db credentials

$ducks[] = new Duck('Huey','Fishing',.15);
$ducks[] = new Duck('Dewey','Camping',.12);
$ducks[] = new Duck('Louie','Flying Kites',.11);

/*
echo'<pre>';
foreach($ducks as $duck){
    echo $duck;
}
echo'</pre>';

die;

dumpDie($ducks);
*/
 
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


/*switch ($myAction) 
{//check 'act' for type of process
	case "display": # 2)Display user's name!
	 	showFeeds();
	 	break;
	default: # 1)Ask user to enter their name 
	 	feedForm();
}*/

//fuction feedForm(){
//}

function feedForm()
{# shows form so user can enter their name.  Initial scenario
	get_header(); #defaults to header_inc.php	
	
	echo 
	'<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.Name,"Please Enter Duck Name")){return false;}
			if(empty(thisForm.Hobby,"Please Enter Hobby")){return false;}
			if(empty(thisForm.Allowance,"Please Enter Allowance")){return false;}
			return true;//if all is passed, submit!
		}
	</script>
	<h3 align="center">' . smartTitle() . '</h3>
	<p align="center">Please enter your name</p> 
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
		<table align="center">
			<tr>
				<td align="right">
					Name
				</td>
				<td>
					<input type="text" name="Name" /><font color="red"><b>*</b></font> <em>(alphabetic only)</em>
				</td>
			</tr>
            
            <tr>
				<td align="right">
					Hobby
				</td>
				<td>
					<input type="text" name="Hobby" /><font color="red"><b>*</b></font> <em>(alphabetic only)</em>
				</td>
			</tr>
            <tr>
				<td align="right">
					Allowance
				</td>
				<td>
					<input type="text" name="Allowance" /><font color="red"><b>*</b></font> <em>(numeric only)</em>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<input type="submit" value="Please Enter Your Duck"><em>(<font color="red"><b>*</b> required field</font>)</em>
				</td>
			</tr>
		</table>
		<input type="hidden" name="act" value="display" />
	</form>
	';
	get_footer(); #defaults to footer_inc.php
}

$urls = [];
//add get data
$cTest = $_GET['id'];
//*/
//$cTest = 2;

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
    
    //add if statement that checks if the users last reload was within ten minutes
    
    //add check if !isset($_SESSION['firstTime'])
    //if it is the first time loading page
    //else
    //if there is already a session
    
    
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
    
        //get xml
        //$request = "https://news.google.com/news/rss/headlines/section/topic/TECHNOLOGY?ned=us&hl=en&gl=US";
        //$response = file_get_contents($request);

        //add to $xml array
        //array_push($xml, simplexml_load_string($response));

        //create $feed array
        $feeds = array();

        //title
        //print '<h1>' . $xml->channel->title . '</h1>';

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
                echo $display['site'];
                echo '<br>';
                echo $display['title'];
                
                echo '<br>';
                echo $display['desc'];
                echo '<br>';
                echo $display['link'];
                echo '<br>';
                echo $display['date'];
                echo '<br>';
                //dumpDie($display);
                foreach ($display as $item){
                    //dumpDie($item);
                    //echo '<pre>';
                    //echo $item;
                    //echo '</pre>';
                }
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
    
    
    
    //$_SESSION['Ducks'][] = new Duck($_POST['Name'],$_POST['Hobby'],$_POST['Allowance']);
    
    /*
	if(!isset($_POST['YourName']) || $_POST['YourName'] == '')
	{//data must be sent	
		feedback("No form data submitted"); #will feedback to submitting page via session variable
		myRedirect(THIS_PAGE);
	}  
	
	if(!ctype_alnum($_POST['YourName']))
	{//data must be alphanumeric only	
		feedback("Only letters and numbers are allowed.  Please re-enter your name."); #will feedback to submitting page via session variable
		myRedirect(THIS_PAGE);
	}
	
	$myName = strip_tags($_POST['YourName']);# here's where we can strip out unwanted data
	
	echo '<h3 align="center">' . smartTitle() . '</h3>';
	echo '<p align="center">Your name is <b>' . $myName . '</b>!</p>';
	echo '<p align="center"><a href="' . THIS_PAGE . '">RESET</a></p>';
	get_footer(); #defaults to footer_inc.php
    */
}


class Duck {
    public $Name = '';
    public $Hobby = '';
    public $Allowance = 0;
    
    public function __construct($Name,$Hobby,$Allowance){
        $this->Name = $Name;
        $this->Hobby = $Hobby;
        $this->Allowance = $Allowance;
    }//end Duck constructor
    
    public function __toString(){
        setlocale(LC_MONETARY,'en_US');
        $Allowance = money_format('%i',$this->Allowance);

        $myReturn = '';
        $myReturn .= 'Name: ' . $this->Name . ' ';
        $myReturn .= 'Hobby: ' . $this->Hobby . ' ';
        $myReturn .= 'Allowance: ' . $this->Allowance . ' ';


        return $myReturn;
    
}//end function toString
    
}//end Duck class



?>
