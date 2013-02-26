<script language="JavaScript" type="text/javascript"> 
<!-- 
function confirmDelete()
{
	//Is the user sure?
	if (confirm("Are you sure you want to delete this post?")) {
		return true; 
	}
	else
	{
		return false;
	}
} 
//--> 
</script> 
<?php
/*Get the posts from the database and output them to the webpage*/
//The usual useful stuff
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/navimenu.php");
include_once("reused-code-vnF434/forummenu.php");

//Function to get replace the tags in the post with the appropriate html
function replaceTag($tag,$replace_pattern,$entry)
{
	/*$tag specifies the string between the square brackets
	used in the post, which we want to replace. $replace pattern 
	specifies the html that we want to replace the post tags. It
	contains the placeholder "string", where we'll stick the stuff
	between the square bracket tags. $entry is the post that we're
	foramtting.*/
	
	//Create the regex search pattern
	$patt="/\[".$tag."\].*?\[\/".$tag."\]/i";
	//Find how many tag pairs there are in the post
	$num_matches=preg_match_all($patt,$entry,$matches,PREG_SET_ORDER);
	
	//Somewhere to hold
	$expr=array();
	$i=0;
	
	//Loop through the list of matches
	foreach ($matches as $tag1)
	{
		//Results are a nested array so we need two loops
		foreach($tag1 as $subtag)
		{
			//Strip out the post tags
			$expr[]=str_replace("[".$tag."]","",$subtag);
			$expr[$i]=str_replace("[/".$tag."]","",$expr[$i]);
			
			//If the user hasn't added the http thing, add it for them, the fools
			if(substr($expr[$i],0,7)!="http://" && ($tag=="url" || $tag=="img"))
			{
				//Prepend the url with the http thing
				$expr[$i]="http://".$expr[$i];			
			}
			//Replace the placeholder "string" with the url or whatever
			$new_tag=str_replace("string",$expr[$i],$replace_pattern);
			$entry=str_replace($tag1,$new_tag,$entry);
			$i++;
		}
	}
	//Return the formatted entry.
	return $entry;
}

//Connect to the database
sql_connect("orentago_forum");

//If thread id not set, return to index.
if(!isset($_GET['id']))
{
	//ob_start();
	header("Location:index.php");
}

//Get the thread id
$thread_id=filter_var($_GET["id"],FILTER_SANITIZE_NUMBER_INT);

//Form the database query, grab all posts in the thread
//Get id only to improve efficiency
$query = "SELECT id FROM posts WHERE thread_id=".$thread_id." ORDER BY date_entered";

//Get the user's id, if available
if(isset($_SESSION['uid']) && $_SESSION['logged_in']=="Y")
{
	$uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
	//Get the user's username and security group
	$r=mysql_query("SELECT uname, secgroup FROM users WHERE id=".$uid);
	$row=mysql_fetch_array($r);
	$uname=$row['uname'];
	$secgroup=$row['secgroup'];
	//Set some CSRF token stuff
	$_SESSION['token'] = md5(uniqid(mt_rand(),true));
}

//Run the query
if($r=mysql_query($query))
{
	//Is the user specifying the pagenumber?
	if(!(isset($_GET['pagenum'])))
	{
		$pagenum = 1;
	}
	else
	{
		$pagenum=filter_var($_GET['pagenum'],FILTER_SANITIZE_NUMBER_INT);
	}
	//Get the total number of posts.
	$rows=mysql_num_rows($r);
	
	$page_rows=10; //How many threads per page?
	$last=ceil($rows/$page_rows); //What's the last page number?
	if($last<=0) $last=1; //Sanity check the last page
	if($pagenum<1) $pagenum=1; //and the current page
	elseif($pagenum>$last) $pagenum=$last; //More sanity checking
	$max='LIMIT '.($pagenum-1)*$page_rows.','.$page_rows; //Form the additional query filter

	//Form the new query
	$r=mysql_query("SELECT * FROM posts WHERE thread_id=".$thread_id." ORDER BY date_entered $max") or die();
	//Need separate set of results to get thread title (othewise we'll interfere with our loop).
	$r1=mysql_query("SELECT thread_title FROM posts WHERE thread_id=".$thread_id." LIMIT 1") or die();
	//Print the navigation menu
	navimenu($thread_id,"thread","N");
	
	//Get the thread title
	$row1=mysql_fetch_array($r1);
	$thread_title=$row1['thread_title'];
	
	//Print the title of the thread
?>
<table align="center" class="forum">
	<tr class="forum">
		<td width=80% valign="middle" style="border-bottom:0px"><h2 class="entry"><?php echo $thread_title; ?></h2></td>
		<td width=20% align="center" style="border-bottom:0px"><h2 class="entry"></h2></td>
	</tr>
<?php
	//loop through the posts and print them
	$i=1;
	while($row = mysql_fetch_array($r))
	{
		//Get the post id
		$id=$row['id'];
		
		//$thread_title=$row['thread_title'];
		//Get the post information from the database
		$user=$row['user'];
		$date = date('G:i jS F Y', strtotime($row['date_entered']));
		$reported=$row['reported'];
		//Need to check whether it's safe to post the reply.
		if($reported=="N")
		{
			$entry = $row['entry'];
			$entry = nl2br($entry); //Switch mysql carriage returns for <br>
		
			//Replace the post tags with the appropriate html
			$entry=replaceTag("math","\$\$string\$\$",$entry);
			$entry=replaceTag("img","<img src=\"string\"></img>",$entry);		
			$entry=replaceTag("imath","\(string\)",$entry);				
			$entry=replaceTag("url","<a href=\"string\" class=\"entry\">string</a>",$entry);
		
			//Need paragraph tags instead of carriage returns.
			$entry = str_replace("<br />",'</p><p class="entry">',$entry);
		}
		else
		{
			//If not, post a warning.
			$entry="This post has been flagged for review.";
		}		
		
		//Get the user's security group and email for the given post
		$r1=mysql_query("SELECT secgroup, email FROM users WHERE uname='$user'") or die();
		$row1=mysql_fetch_array($r1);
		$secgroup1=$row1['secgroup'];
		$email=$row1['email'];

	//Add the post to the table
?>
	<tr class="forum">
		<td valign="top">
			<p class="entry"><?php echo $entry; ?></p>
		</td>
		<td align="center" valign="top">
			<p class="entry"><?php echo $user; ?><br />
<?php
		//Display the user's security group
		switch ($secgroup1)
		{
			case 1:
				echo "Member";
				break;
			case 2:
				echo "Moderator";
				break;
			case 3:
				echo "Site Admin";
				break;
		}
?>			<br />
			<?php echo $date; ?><br />
<?php
		//First check we're logged in
		if(isset($_SESSION['uid']) && $_SESSION['logged_in']=="Y")
		{
			//Add edit and delete tags depending on whether the user is the poster, or a mod or admin
			if($user==$uname || $secgroup==2 || $secgroup==3)
			{
				echo "<a class='entry' href='index.php?action=delete&id=$id&csrf=".$_SESSION['token']."' onClick='return confirmDelete();'>Delete</a>";
				echo " <a class='entry' href='index.php?action=edit&id=$id'>Edit</a> ";
				//Only admins and moderators can email users.
				if($secgroup==2 || $secgroup==3)
				{
					echo "<a class='entry' href='index.php?action=email&email=$email'>Email</a><br />";
					if($reported=='Y') echo "<a href='index.php?action=unblock&id=$id&csrf=".$_SESSION['token']."' class='entry'>Unblock</a>";
				}
			}
			if($reported=='N') echo "<a href='index.php?action=report&id=$id&csrf=".$_SESSION['token']."' class='entry'>Report Abuse</a>";
		}
		//Add a link to report the post
?>
			
			</p>
		</td>
	</tr>
<?php
	}
}
else
{
//Query failed, kill the script
	die();
}
?>
</table>
<table class="header" align="center">
	<tr>
		<td width=33%>
<?php
//Add pagination links if necessary
if($pagenum==1)
{
}
else
{
	//If not the first page, display links to the previous page
	echo "<a href='index.php?action=thread&id=$thread_id&pagenum=1' class='header'>First</a>";
	$previous=$pagenum-1;
	echo " <a href='index.php?action=thread&id=$thread_id&pagenum=$previous' class='header'>  Previous</a>";
}
?>
		</td>
		<td align="center" width=33%><p class="footer">Page <?php echo $pagenum ?></p></td>
		<td align="right" width=33%>
<?php
	if($pagenum==$last)
	{
	}
	else
	{
		//If not the last page, display links to the next page
		$next=$pagenum+1;
		echo " <a href='index.php?action=thread&id=$thread_id&pagenum=$next' class='header'>Next  </a> ";
		echo "<a href='index.php?action=thread&id=$thread_id&pagenum=$last' class='header'>Last</a>";
	}
	//Close the table
?>
		</td>
	</tr>
</table>
<?php
//Close the database
mysql_close();
?>
