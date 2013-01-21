<?php
require ('../protected/sql_connect.inc.php');
include('../public_html/2IAcowwxOi/reused-code-vnF434/mqoff.php');
//connecting to our SQL database
sql_connect('orentago_blog');

//Define the query

$id=filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);

$query = "DELETE FROM blog WHERE id=" . $id . " LIMIT 1";

//sends the query to delete the entry
mysql_query ($query);

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
