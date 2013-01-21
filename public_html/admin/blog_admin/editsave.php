<?php
require ('../protected/sql_connect.inc.php');
include('../public_html/2IAcowwxOi/reused-code-vnF434/mqoff.php');
//connecting to our SQL database
sql_connect('orentago_blog');

//collects the POST data

$title = $_POST['title'];
$entry = $_POST['entry'];
$id = $_POST['id'];

//Cleans the data before imputing the values
trim ($title);
trim ($entry);
trim ($user);

//adds necessary escape characters
$title = addslashes ($title);
$entry = addslashes ($entry);

//define the query
$query = "UPDATE blog SET title='$title', entry='$entry' WHERE id='$id'";

//executes the query
$results = mysql_query ($query);

//execute the query
?>
<table>
<?php
if (mysql_affected_rows() == 1) { 
//if it updated
?>
<table>
	<tr>
		<td>
			Update Saved Successful
		</td>
	</tr>
<?php
 } else {
//if it failed
?>
	<tr>
		<td>
			Update Failed
		</td>
	</tr>

<?php
} 
?>
 
	<tr>
		<td>
			<form action="index.php" method="post">
			<input type="submit" name="submit" value="Ok" />
			</form>
		</td>
	</tr>
</table>


<?php

mysql_close(); //Closes our SQL session

?>
