<?php
/*Allows the user to search all threads and posts
for given keywords. It uses the mysql full-text
search feature to search for the keywords. My hope
is to allow the user to search within given forums
and threads, as well as throughout the whole forum.*/

//Include some useful code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/navimenu.php");

//Send the user back to homepage if they haven't specified a query
if(!isset($_GET['query']))
{
	header("Location:index.php");
}

/*Need to implement boolean capabilities at some point.
Not sure whether it's worth skipping this to start off
with then add it later. It's possible that could make
the code quite messy. I reckon a switch block to adapt
the query will suffice.

I imagine this would be achieved using additional GET
variables like thread=?? or forum=??, then additional
filters could be added into the sql query to narrow
down the results.

Great tutorial on full-text searching can be found here:
http://devzone.zend.com/26/using-mysql-full-text-searching/

Definitely need some input validation to strip out
any dangerous mysql code that might be injected.

Need to bear the following in mind:
- Must only display only unique threads, no duplicates
- Pagination
*/

//Connect to the database
sql_connect("orentago_forum");

$string=addslashes($_GET['query']);

$query = "
	SELECT DISTINCT thread_id, thread_title, entry, stickied FROM posts
	WHERE MATCH(thread_title, entry) AGAINST ('$string')
	";

if(isset($_GET['forum']))
{
	$forum_id=filter_var($_GET["forum"],FILTER_SANITIZE_NUMBER_INT);
	$query.=" AND forum_id=".$forum_id;
}

//Output the forum naavigation menu
navimenu(1,"","N");
//Run the query
if($r=mysql_query($query))
{
	//See if the the user has requested a specific page
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
	$r=mysql_query($query." $max") or die();

	//Write some table headings
?>
<table align="center" class="forum">
	<tr>
		<td><h2 class="entry">Topic</h2></td>
	</tr>
<?php
	//loop through the results
	while($row = mysql_fetch_array($r))
	{
		$thread_id=$row['thread_id'];
		$thread_title=$row['thread_title'];
		
		$stickied=$row['stickied'];
		//Add our results to the table
?>
	<tr>
		<td align="left">
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
	$search=$_GET['query'];//Just so we don't have to use the straight
	//GET variable below.
	
	//Same as the blogpage script
	//If we're on the first page, don't write a link to previous
	//pages, otherwise write the link
	if($pagenum==1)
	{
	}
	else
	{
		echo "<a href='index.php?action=search&query=$search&pagenum=1' class='header'>First</a>";
		$previous=$pagenum-1;
		echo " <a href='index.php?action=search&query=$search&pagenum=$previous' class='header'>  Previous</a>";
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
		echo " <a href='index.php?action=search&query=$search&pagenum=$next' class='header'>Next  </a> ";
		echo "<a href='index.php?action=search&query=$search&pagenum=$last' class='header'>Last</a>";
	}
?>
</td>
</tr>
</table>
