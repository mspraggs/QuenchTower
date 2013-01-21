<?php
/*Just a small script to end the user's session
by unsetting their uid and setting the logged in
flag to N*/

//Include some useful code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/message.php");
include_once("reused-code-vnF434/navimenu.php");

//Check to see if they're already logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in']=="N")
{
	/*Makes more sense to display the forum menu after we know whether
	they're logged in or not.*/
	include("reused-code-vnF434/forummenu.php");
	navimenu(1,"","N");
	//Let them know their request was pointless
	htmlmessage("Invalid Request", "You are not logged in. Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-1)\">here</a> to return to the previous page.");
}
else
{
	//Otherwise connect to the database
	sql_connect("orentago_forum");
	//Grab the session uid
	$uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
	//Set the user's session data
	$_SESSION['logged_in']="N";
	unset($_SESSION['uid']);
	//Set the online flag in the database to N
	mysql_query("UPDATE users SET online='N' WHERE id=".$uid) or die();
	
	//Display the forum menu and a success message
	include("reused-code-vnF434/forummenu.php");
	navimenu(1,"","N");
	htmlmessage("Logout Successful", "You are now logged out. Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-2)\">here</a> to return to the previous page.");
	//Close the database
	mysql_close();
}
?>
