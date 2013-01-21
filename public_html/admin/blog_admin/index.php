<?php
require('../protected/sql_connect.inc.php');
include('../public_html/2IAcowwxOi/reused-code-vnF434/mqoff.php');

//connect to database
sql_connect('orentago_blog');

//This is the query for the administration
//Obtain all articles in all columns
$query = 'SELECT * FROM blog ORDER BY date_entered DESC';

//This runs the query to view
//If we get a positive result in $results, it will process the while loop
if($results = mysql_query($query))
{
	//Creates the display table
?>
<table align="center" border="1" width="80%">
	<tr>

		<td colspan="2">
			<b><center>News Management</b></center></b>
		</td>
	</tr>
<?php
	while ($row = mysql_fetch_array($results))
	{
		//input data into table
		$title = $row['title'];
		$entry = $row['entry'];
		$id = $row['id'];
		//Format time data
		$date_entered = date('F j g:i A',strtotime($row['date_entered']));
		$entry = nl2br($entry); //Convert new lines to <br>
?>
	<tr>
		<td colspan="2">
			<table align="center" border="0" width="100%">
				<tr>
					<td>

						<b><?php echo $title ?></b> - Posted by: <b></b>
					</td>
					<td>
						<div align="right"><?php echo $date_entered; ?></div>

					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"><?php echo $entry; ?></td>
	</tr>
	<tr>
		<td>
		   <table align="center" width="200" border="0">
				<tr>
					<td>
						<form action="edit.php" method="post">
							<input type="hidden" name="id" value="<? echo $id ?>">
							<input type="submit" name="submit" value="Edit">
						</form>
					</td>
					<td>
						<form action="delete.php" method="post">
							<input type="hidden" name="id" value="<? echo $id ?>">
							<input type="submit" name="submit" value="Delete">
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	}
?>
	</table>
<?php
} else { 
//if the query did not run.
die ("<p>Could not run query because: <b>" . mysql_error() . "</b></p>\n");
}

mysql_close();
//Closes our SQL session
?>
<form action="add.php" method="POST">
<table align="center">

<tr>
<td>Entry Title: </td>
<td><input type="text" name="title" size="40" maxsize="100" /></td>
</tr>
<tr>

<td>User : </td>
<td><input type="text" name="user" size="40" maxsize="100" /></td>
</tr>
<tr>
<td>Entry Text: </td>

<td><textarea name="entry" cols="100" rows="10"></textarea></td>
</tr>
<tr>
<td><input type="submit" name="submit" value="Add Event" /></td>

</tr>
</table>
</form>

