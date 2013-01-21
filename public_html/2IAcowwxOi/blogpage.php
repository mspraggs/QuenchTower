<?php
//Include some useful files again
include_once ('../protected/sql_connect.inc.php');
include_once('reused-code-vnF434/navimenu.php');
//Display the navigation menu
navimenu(1,"","N");

//Connect to the database
sql_connect('orentago_blog');

//Define our sql query
$query = "SELECT title, entry, date_entered FROM blog ORDER BY date_entered DESC";

// sending the query to the mySQL server
if($r = mysql_query ($query))
{
	//Check if we're using pagination
	if(!(isset($_GET['pagenum'])))
	{
		//If not set, set the pagenumber to 1
		$pagenum = 1;
	}
	else
	{
		//Else get it from the GET variables
		$pagenum=filter_var($_GET['pagenum'],FILTER_SANITIZE_NUMBER_INT);
	}
	
	//Get number of blog entries
	$rows=mysql_num_rows($r);
	
	//How many posts per page?
	$page_rows=10;
	//Calculate the last page number
	$last=ceil($rows/$page_rows);
	//Sanity check the pagenumber
	if($pagenum<1) $pagenum=1;
	elseif($pagenum>$last) $pagenum=$last;
	//Construct the pagination query filter
	$max='LIMIT '.($pagenum-1)*$page_rows.','.$page_rows;
	
	//Is the user after a specific post?
	if(isset($_GET['id']))
	{
		//If so, only get that query from the database
		$id=filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT); //Filter id
		$query="SELECT title, entry, date_entered FROM blog where id=".$id; //Run query
	}
	else
	{
		//Otherwise stick with our original query, but only get one page
		$query="SELECT title, entry, date_entered FROM blog ORDER BY date_entered DESC $max";
	}

	//Run the query
	$r=mysql_query($query) or die();

	//Fetch the rows from the database
	while($row = mysql_fetch_array($r))
	{
		//Format the date and time of the post
		$date_entered = date('l, jS F Y', strtotime($row['date_entered']));
		$time_entered = date('G:i', strtotime($row['date_entered']));

		//Extract remaining data
		$entry = $row['entry'];
		$title = $row['title'];
		$entry = nl2br ($entry); //Switch mysql carriage returns for <br />
		//Replace the <br /> for end of paragraph and new paragraph
		$entry = str_replace("<br />",'</p><p class="entry">',$entry);
		
		//Output the post in a table
?>

<table align="center" class="entry">
	<tr>
		<td>
			<h1 class="entry"><?php echo $title; ?></h1>
		</td>
		<td>
			<div align="right"><h2 class="entry"><?php echo $date_entered; ?></h2></div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<p class="entry"><?php echo $entry; ?><br><br>Posted by Matt Spraggs at <?php echo $time_entered; ?></p>
		</td>
	</tr>
</table><br />
<?php
}

	} else { //Query did not run. Kill the script.
        die ();
    }
	//Close the database connection
	mysql_close();
	//Now add a table to link to the other pages of the blog
?>

<table class="header" align="center">
<tr>
<td width=33%>
<?php
	if($pagenum==1)
	{
	//If we're on the first page, don't link to previous pages
	}
	else
	{
	echo "<a href='{$_SERVER['PHP_SELF']}?pagenum=1' class='header'>First</a>";
	$previous=$pagenum-1;
	echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$previous' class='header'>  Previous</a>";
	}
?>
</td>
<td align="center" width=33%><p class="footer">Page <?php echo $pagenum ?></p></td>
<td align="right" width=33%>
<?php
	if($pagenum==$last)
	{
	//If we're on the last page, don't link to further pages
	}
	else
	{
	$next=$pagenum+1;
	echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$next' class='header'>Next  </a> ";
	echo "<a href='{$_SERVER['PHP_SELF']}?pagenum=$last' class='header'>Last</a>";
	}
?>
</td>
</tr>
</table>
</div>
<?php
?>
