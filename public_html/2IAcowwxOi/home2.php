<?php
/*Output the list of forums, by category, then by name. Include 
info on the number of posts and threads.*/

//Include some useful code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/navimenu.php");

//Display the navigation menu
navimenu(0,"home","N");

//Connect to the database
sql_connect("orentago_forum");

/*To output each group of forums, it's convenient to define a function that'll
accept a category integer and a description, and output the corresponding list
of forums.*/
function group($description,$category)
{
	//Get the list of forums corresponding to the given category
	$query = "SELECT * FROM forums WHERE category=".$category." ORDER BY title";
	//Output a heading for the category
?>
<table class="header" align="center">
<tr>
	<td>
		<h1 class="forum"><?php echo $description; ?></h1>
	</td>
</tr>
</table>
<table class="forum" align="center">
<?php
	
	//query forums table
	if($r=mysql_query($query))
	{

		//loop through the results
		while($row = mysql_fetch_array($r))
		{
			//Get the results from the query
			$id = $row['id'];
			$title = $row['title'];
			$description = $row['description'];
			
			//Find out how many threads and posts there are
			$r2=mysql_query("SELECT DISTINCT thread_id FROM posts WHERE forum_id=".$id);
			$num_threads=mysql_num_rows($r2);
			$r2=mysql_query("SELECT thread_id FROM posts WHERE forum_id=".$id);
			$num_posts=mysql_num_rows($r2);
			//Write the results to the table
?>
	<tr>
		<td width=60%>
			<a href=<?php echo "index.php?action=forum&id=".$id; ?> class="etitle"><?php echo $title; ?></a>
			<p class="entry"><?php echo $description; ?></p>
		</td>
		<td width=20% align="center">
			<h2 class="entry">Threads</h2>
			<p class="entry"><?php echo $num_threads; ?></p>
		</td>
		<td width=20% align="center">
			<h2 class="entry">Posts</h2>
			<p class="entry"><?php echo $num_posts; ?></p>
		</td>
	</tr>
<?php
		}
		//Loop ended, close the table tag
?>
</table>
<?php
	}
	else
	{
		//If the query failed, kill the script
		die();
	}
}

//Now we've defined the function, output the categories
group("The Modules",2);
group("Other Things",1);

//Close the database
mysql_close();
?>
