<?php
include('../public_html/2IAcowwxOi/reused-code-vnF434/mqoff.php');
if (isset($_POST['submit']))
{
	require ('../protected/sql_connect.inc.php');

//connecting to our SQL database
	sql_connect('orentago_blog');
	$title = $_POST['title'];
	$entry = $_POST['entry'];
	$user = $_POST['user'];

	//Trims the values to clean up the text
	trim ($title);
	trim ($entry);
	trim ($user);
	
	//add in escape characters
	$title = addslashes ($title);
	$entry = addslashes ($entry);
	$user = addslashes ($user);
	
	//define query
	$time = time()-$offset;
	$query = "INSERT INTO blog (id, title, entry, date_entered) VALUES (0, '$title', '$entry', FROM_UNIXTIME('$time'))";
	
	//execute query
	if(@mysql_query ($query)){
?>
	<center>The event was successfully saved. 
	<a href="index.php">Return to administrative 
		page</a></center>
<?php 
   } else {
   die('Invalid query: ' . mysql_error());
?>
     <center>The event could not be saved. 
<a href="index.php">Return to administrative 
page</a></center>
<?php 
   }
mysql_close(); //Closes our SQL session
} else {
?>
<center>
There is no data to process. 
<a href="index.php">Return to administrative page</a></center>
<?php
}
?>