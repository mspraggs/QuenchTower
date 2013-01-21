<?php
include_once("../protected/sql_connect.inc.php");
function navimenu($id, $loc, $del)
{
	/*The following code is horrendously sloppy and needs to
	be streamlined.
	
	$id is the id of either the thread or forum we're in
	$loc specifies the type of location we're at - thread
	means we're listing threads (in a forum), post means
	we're in a list of posts (a thread) and home means we're
	on the homepage.
	
	$del specifies whether we've just deleted a thread (in
	which case it won't be there to navigate to.
	
	There's probably some way of integrating the $loc with the
	"action" variable from the GET data, so the code could be
	streamlined that way.*/
?>
<table align="center" class="forum">
	<tr>
		<td width=25%>
			<div align="center"><a class="menu" href="index.php">Home</a></div>
		</td>
		<td width=25%>
			<div align="center"><a class="menu" href="blog.php">Blog</a></div>
		</td>
		<td width=25%>
			<div align="center"><a class="menu" href="index.php?action=tos">Terms of Service</a></div>
		</td>
		<td width=25%>
			<div align="center"><a class="menu" href="admin.php">Admin</a></div>
		</td>
	</tr>
<?php
	
	//Flags for whether we're going to print the thread and forum.
	$print_thread=0;
	$print_forum=0;
	//Places to keep the forum and thread titles
	$forum_title="";
	$thread_title="";

	//Can skip the navigation menu entirely if we set $loc to ""
	if($loc!="")
	{
		//If we're not on the homepage we need to get the information for the menu
		if($loc!="home")
		{
			//Definitely need to display which forum we're in
			$print_forum=1;
			sql_connect("orentago_forum");
			
			//If we're in a post, e.g. deleting, editing, replying etc, we need to
			//grab the thread we're in
			if($loc=="post")
			{
				$r1 = mysql_query("SELECT thread_id FROM posts WHERE id=".$id." LIMIT 1");
				if($row1 = mysql_fetch_array($r1)) $thread_id=$row1['thread_id'];
			}
			elseif($loc=="thread") $thread_id=$id;
			//Add an if here to make sure we catch dodgy function arguments
			if($loc=="forum" || $loc=="thread" || $loc=="post")
			{
				//If we're in a thread or post we need to grab some more information.
				if($loc=="thread" || $loc=="post")
				{
					//Get the thread title and the forum id number
					$r1 = mysql_query("SELECT thread_title, forum_id FROM posts WHERE thread_id=".$thread_id);
					//If this is the last post in the thread, then we shouldn't print the thread, otherwise
					if(mysql_num_rows($r1)>1 || $del=="N") $print_thread=1;
					
					//And get the data
					if($row1 = mysql_fetch_array($r1))
					{
						$thread_title=$row1['thread_title'];					
						$forum_id = $row1['forum_id'];
					}
				}
				else $forum_id = $id; //Else we need the forum id
				//If we're in a forum then we don't need to fuss around with
				//the posts table
				$r2 = mysql_query("SELECT title FROM forums WHERE id=".$forum_id." LIMIT 1");
				if($row2 = mysql_fetch_array($r2))
				{
					$forum_title = $row2['title'];
				}
			}
		}
?>
	<tr class="forum">
		<td width=75% colspan=3>
			<p class="entry">
			<a href="index.php" class="entry">Forum Home</a>
			<?php
			//Print the forum if the flag says to
			if($print_forum==1)
			{
			?> >
				<a href=<?php echo "index.php?action=forum&id=".$forum_id; ?> class="entry"><?php echo $forum_title; ?></a>
			<?php
			}
			//Print the thread if the flag says so
			if($print_thread==1)
			{
			?> > 
				<a href=<?php echo "index.php?action=thread&id=".$thread_id; ?> class="entry"><?php echo $thread_title; ?></a>
			<?php } ?>
			</p>
		</td>
		<td width=25% align="right">
		<?php
			//And we add links to the right of the menu to create and reply to posts and so on.
			//This uses the GET action variable, which could probably be used in a similar way in
			//the code above.
			if(isset($_GET['action']) && ($_GET['action']=="thread" || $_GET['action']=="forum"))
			{
			?>
				<a class="entry" href=
			<?php
				if($_GET['action']=="thread")
				{
					echo "'index.php?action=reply&id=$thread_id'>Reply</a>";
				}
				else
				{
					echo "'index.php?action=new&id=$forum_id'>New Post</a>";
				}
			}
		?>
		</td>
	</tr>
	<?php
	}
	?>
</table>
<?php
}
?>
