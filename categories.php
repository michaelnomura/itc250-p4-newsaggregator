<?php
/**
 * feeds.php is a landing page with links to three categories of RSS feeds
 
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

showCategories();

function showCategories()
{//Select 
	global $config;
	get_header();
	echo '<h3 align="center">' . smartTitle() . '</h3>';

	$sql = "select ID,Category from p4_Categories";
	$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
	if (mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		echo '<table align="center" border="1" style="border-collapse:collapse" cellpadding="3" cellspacing="3">';
		echo '<tr>
				<th>CategoryID</th>
				<th>Category</th>
			</tr>
			';
		while ($row = mysqli_fetch_assoc($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
			echo '<tr>
					<td>' . (int)$row['ID'] . '</td>
                    <td><a href="feed_view.php?id=' . (int)$row['ID'] . '">' . dbOut($row['Category']) . '</a></td>
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

