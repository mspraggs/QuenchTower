<?php
/*This script displays all the threads in a particular forum. It first
queries the posts database to retrieve a list of all  available threads.*/

//First include some useful code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/navimenu.php");

//Open the database
sql_connect("orentago_forum");

//Check to see if the thead id is specified.
if(!isset($_GET['id']))
{
	//If not, return the user to the homepage
	header("Location:index.php");
}
//Retrieve the thread id
$forum_id=filter_var($_GET["id"],FILTER_SANITIZE_NUMBER_INT);

/*Query the database. We want the latest post from each thread, ordered by both 
whether they're stickied and the date of the most recent post.*/
$query = "SELECT thread_id, MAX(date_entered), stickied FROM posts WHERE forum_id=".$forum_id." GROUP BY thread_id ORDER BY stickied DESC, MAX(date_entered) DESC";

//Output the forum naavigation menu
navimenu($forum_id,"forum","N");
//Run the query
if($r=mysql_query($query))
{
	//See if the the user has requested a specufic page
	if(!(isset($_GET['pagenum'])))
	{
		//If not, assume they want the first one
		$pagenum = 1;
	}
	else
	{
		//Otherwise get the page
		$pagenum=filter_var($_GET['pagenum'],FILTER_SANITIZE_NUMBER_INT);
	}

	//How many threads are there?
	$rows=mysql_num_rows($r);
	//How many do we want to show?
	$page_rows=10;
	//And what's the last page?
	$last=ceil($rows/$page_rows);
	//Sanity check the last page
	if($last<=0) $last=1;
	//And the current page
	if($pagenum<1) $pagenum=1;
	elseif($pagenum>$last) $pagenum=$last;
	//Write the query to get the required threads
	$max='LIMIT '.($pagenum-1)*$page_rows.','.$page_rows;

	/*Query the database. We add on the $max variable to get one page only, and
	we get some additional data, which we'll need later.*/
	$r=mysql_query("SELECT thread_id, thread_title, MAX(date_entered), MIN(date_entered), user, COUNT(id), stickied FROM posts WHERE forum_id=".$forum_id." GROUP BY thread_id ORDER BY stickied DESC, MAX(date_entered) DESC $max") or die();

	//Write some table headings
?>
<table align="center" class="forum">
	<tr>
		<td width=50%><h2 class="entry">Topic</h2></td>
		<td width=10% align="center"><h2 class="entry">Replies</h2></td>
		<td width=20% align="center"><h2 class="entry">Latest Post</h2></td>
		<td width=20% align="center"><h2 class="entry">First Post</h2></td>
	</tr>
<?php
	//loop through the results
	while($row = mysql_fetch_array($r))
	{
		$thread_id=$row['thread_id'];
		//We also want the author of the latest post, so run a query to get that results
		$query3 = "SELECT user FROM posts WHERE thread_id=".$thread_id." ORDER BY date_entered DESC LIMIT 1";
		
		//Create an array of those results
		$r3=mysql_query($query3) or die();
		$row3=mysql_fetch_array($r3);
		//Calculate the number of replies
		$num_replies=$row['COUNT(id)']-1;
		
		//And extract the data from the queries
		$thread_title=stripslashes($row['thread_title']);
		$first_user=stripslashes($row['user']);
		$stickied=$row['stickied'];
		$last_user=stripslashes($row3['user']);
		$first_date = date('G:i jS F Y', strtotime($row['MIN(date_entered)']));
		$last_date = date('G:i jS F Y', strtotime($row['MAX(date_entered)']));
		
		//Add our results to the table
?>
	<tr>
		<td>
			<a href=<?php echo "index.php?action=thread&id=".$thread_id; ?> class="entry"><?php
			if($stickied=="Y")
			{
				echo $thread_title." [STICKIED]";
			}
			else
			{
				echo $thread_title;
			}
			?></a>
		</td>
		<td align="center">
			<p class="entry"><?php echo $num_replies; ?></p>
		</td>
		<td align="center">
			<p class="entry"><?php echo $last_user; ?><br /><?php echo $last_date; ?></p>
		</td>
		<td align="center">
			<p class="entry"><?php echo $first_user; ?><br /><?php echo $first_date; ?></p>
		</td>
	</tr>
<?php
	}
}
else
{
	//If the query failed, kill the sript
	die();
}
//Close the table tag and start the pagination table
?>
</table>
<table class="header" align="center">
<tr>
<td width=33%>
<?php
	//Same as the blogpage script
	//If we're on the first page, don't write a link to previous
	//pages, otherwise write the link
	if($pagenum==1)
	{
	}
	else
	{
		echo "<a href='index.php?action=forum&id=$forum_id&pagenum=1' class='header'>First</a>";
		$previous=$pagenum-1;
		echo " <a href='index.php?action=forum&id=$forum_id&pagenum=$previous' class='header'>  Previous</a>";
	}
?>
</td>
<td align="center" width=33%><p class="footer">Page <?php echo $pagenum ?></p></td>
<td align="right" width=33%>
<?php
	//If we're on the last page, don't link to the next page
	if($pagenum==$last)
	{
	}
	else
	{
		$next=$pagenum+1;
		echo " <a href='index.php?action=forum&id=$forum_id&pagenum=$next' class='header'>Next  </a> ";
		echo "<a href='index.php?action=forum&id=$forum_id&pagenum=$last' class='header'>Last</a>";
	}
?>
</td>
</tr>
</table>
