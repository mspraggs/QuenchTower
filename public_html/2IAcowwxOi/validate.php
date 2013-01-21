<?php
//Validates the user's email address if given the correct validation code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/message.php");

//Is user id set? If not redirect
if(!isset($_GET['id']) || !isset($_GET['code']))
{
	header("Location:index.php");
}
else
{
	//Get the id
	$id=$_GET['id'];
	//And the activation code
	$code=$_GET['code'];
	
	//Connect to the database
	sql_connect("orentago_forum");

	//Query the database and get the validation code for the user
	$r=mysql_query("SELECT id, validate_code FROM users WHERE id=".$id);
	//Try to fetch the first row. User may not exist, so put it in an if statement
	if($row=mysql_fetch_array($r))
	{
		//Extract the coee
		$code2=$row['validate_code'];
		//Do the codes match.
		if($code==$code2)
		{
			//Update the database and let the user know
			mysql_query("UPDATE users SET validated='Y' WHERE id=".$id) or die();
			htmlmessage("User Registration","Email validation successful!");
		}
		else
		{
			//Otherwise tell them where to stick it
			htmlmessage("User Registration","Email validation failed!");
		}
	}
	else
	{
		//Otherwise something has gone wrong
		htmlmessage("User Registration","User does not exist!");
	}
	//Close the database
	mysql_close();
}
?>
