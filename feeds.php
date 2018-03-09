<?php
/**
 * feeds.php is a landing page with links to three catagories of RSS feeds
 
 * @package nmCommon
 * @author ITC250 Group 3 Aliya Asken, Jill Beasley, Michael Nomura, Marcus Price
 * @version 0.7
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @todo 
 */

# '../' works for a sub-folder.  use './' for the root  
require 'config_inc.php';
#require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
//END CONFIG AREA ----------------------------------------------------------

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

switch ($myAction) 
{//check 'act' for type of process
	case "add": //2) Form for adding new customer data
	 	addForm();
	 	break;
	case "insert": //3) Insert new customer data
		insertExecute();
		break; 
	default: //1)Show existing customers
	 	showFeeds();
}

function showFeeds()
{//Select Customer
	global $config;
	get_header();
	echo '<h3 align="center">' . smartTitle() . '</h3>';

	$sql = "select FeedID,FeedName,CatagoryID,FeedURL from rssFeeds";
	$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
	if (mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		echo '<table align="center" border="1" style="border-collapse:collapse" cellpadding="3" cellspacing="3">';
		echo '<tr>
				<th>FeedID</th>
				<th>Feed Name</th>
				<th>Catagory</th>
				<th>Feed URL</th>
			</tr>
			';
		while ($row = mysqli_fetch_assoc($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
			echo '<tr>
					<td>'	
				     . (int)$row['FeedID'] . '</td>
				    <td><a href="feed_view.php?id=' . (int)$row['FeedID'] . '">' . dbOut($row['FeedName']) . '</a></td>
				    <td>' . dbOut($row['CatagoryID']) . '</td>
				    <td>' . dbOut($row['FeedURL']) . '</td>
				</tr>
				';
		}
		echo '</table>';
	}else{//no records
      echo '<div align="center"><h3>Currently No Feeds in Database.</h3></div>';
	}
	/*
    echo '<div align="center"><a href="' . THIS_PAGE . '?act=add">ADD FEED</a></div>';
	@mysqli_free_result($result); //free resources
    */
	get_footer();
    
}

function addForm()
{# shows details from a single customer, and preloads their first name in a form.
	global $config;
	$config->loadhead .= '
	<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.FeedName,"Please Enter Feed Name")){return false;}
			if(empty(thisForm.Catagory,"Please Enter Catagory")){return false;}
			if(!isEmail(thisForm.FeedURL,"Please Enter a Valid URL")){return false;}
			return true;//if all is passed, submit!
		}
	</script>';
	
	get_header();
	echo '<h3 align="center">' . smartTitle() . '</h3>
	<h4 align="center">Add Feed</h4>
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
	<table align="center">
	   <tr><td align="right">Feed Name</td>
		   	<td>
		   		<input type="text" name="FirstName" />
		   		<font color="red"><b>*</b></font> <em>(alphanumerics & punctuation)</em>
		   	</td>
	   </tr>
	   <tr><td align="right">Catagory</td>
		   	<td>
		   		<input type="radio" name="catagory" value="one"> one
		   		<input type="radio" name="catagory" value="two"> two
		   		<input type="radio" name="catagory" value="three"> three
		   		<font color="red"><b>*</b></font> <em>(alphanumerics & punctuation)</em>
		   	</td>
	   </tr>
	   <tr><td align="right">Email</td>
		   	<td>
		   		<input type="text" name="Email" />
		   		<font color="red"><b>*</b></font> <em>(valid email only)</em>
		   	</td>
	   </tr>
	   <input type="hidden" name="act" value="insert" />
	   <tr>
	   		<td align="center" colspan="2">
	   			<input type="submit" value="Add Feed!"><em>(<font color="red"><b>*</b> required field</font>)</em>
	   		</td>
	   </tr>
	</table>    
	</form>
	<div align="center"><a href="' . THIS_PAGE . '">Exit Without Add</a></div>
	';
	get_footer();
	
}

function insertExecute()
{
	$iConn = IDB::conn();//must have DB as variable to pass to mysqli_real_escape() via iformReq()
	
	$redirect = THIS_PAGE; //global var used for following formReq redirection on failure

	$FirstName = strip_tags(iformReq('FirstName',$iConn));
	$LastName = strip_tags(iformReq('LastName',$iConn));
	$Email = strip_tags(iformReq('Email',$iConn));
	
	//next check for specific issues with data
	if(!ctype_graph($_POST['FirstName'])|| !ctype_graph($_POST['LastName']))
	{//data must be alphanumeric or punctuation only	
		feedback("First and Last Name must contain letters, numbers or punctuation");
		myRedirect(THIS_PAGE);
	}
	
	
	if(!onlyEmail($_POST['Email']))
	{//data must be alphanumeric or punctuation only	
		feedback("Data entered for email is not valid");
		myRedirect(THIS_PAGE);
	}

    //build string for SQL insert with replacement vars, %s for string, %d for digits 
    $sql = "INSERT INTO test_Customers (FirstName, LastName, Email) VALUES ('%s','%s','%s')"; 

    # sprintf() allows us to filter (parameterize) form data 
	$sql = sprintf($sql,$FirstName,$LastName,$Email);

	@mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	#feedback success or failure of update
	if (mysqli_affected_rows($iConn) > 0)
	{//success!  provide feedback, chance to change another!
		feedback("Feed Added Successfully!","notice");
	}else{//Problem!  Provide feedback!
		feedback("Feed NOT added!");
	}
	myRedirect(THIS_PAGE);
}

