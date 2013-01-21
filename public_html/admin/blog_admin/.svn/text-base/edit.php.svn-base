<?php
require('../protected/sql_connect.inc.php');
include('../public_html/2IAcowwxOi/reused-code-vnF434/mqoff.php');

//connecting to mySQL database
sql_connect('orentago_blog');

//collects the POST data
$id = $_POST['id'];

//Define the query
$query = "SELECT * FROM blog WHERE id=".$id;
//run the query
if ($results = mysql_query($query)) {

$row = mysql_fetch_array ($results); //retrieve the info

//places the query info into variables for possible parsing or formating.
$title = htmlentities($row['title']);
//$user = htmlentities($row['user']);
$id = htmlentities($row['id']);
$entry = htmlentities($row['entry']);
?>
<center>
The page that will obtain this data is to be named editsave.php, so we point our form there:
<form action="editsave.php" method="POST">
<table>
	<tr>
		<td>
			Entry Title:
		</td>
		<td>
			<input type="text" name="title" size="40" maxsize="100" value="<?php echo stripslashes($title); ?>" />
		</td>
	</tr>
	<tr>
		<td colspan=2>
			Entry Text:
		</td>
	</tr>
	<tr>
		<td>
			<textarea name="entry" cols="100" rows="15"><?php echo stripslashes($entry); ?></textarea>
		</td>
	</tr>
	<tr>
		<td>
			If the user decides to save changes, the page goes to the editsave page:
                                 <form action="editsave.php" method="post">
                                 <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                 <input type="submit" name="submit" value="Edit" />
                                 </form>
		</td>
		<td>
			If the user wants to cancel any edits, we redirect back to the administrative page:	
                                 <form action="index.php" method="post">
                                 <input type="submit" name="submit" value="Cancel" />
                                 </form>
		</td>
	</tr>
</table>
</center>
<?php
}
else {
	echo "<p>Could not retreive news event. </p>";
}

mysql_close(); //Closes our SQL session
?>