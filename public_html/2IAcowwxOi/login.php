<?php
/*This is the forum login script. It checks to see if the user is already online.
If they aren't it collects their login details and authenticates them.*/

//Include some useful code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/message.php");
include_once("reused-code-vnF434/loginform.php");
include_once("reused-code-vnF434/navimenu.php");

//Check to see if the user is trying to log in
if(!isset($_POST['uname']) || !isset($_POST['pwd']))
{
	//If so, are they already logged in?
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']=="Y")
	{
		//The forum menu contains code relevant to whether the user is logged in,
		//so it makes sense to only include it after we've changed the session data.
		include("reused-code-vnF434/forummenu.php");
		//Display the forum menu
		navimenu(1,"","N");
		//And tell the user they're already online
		htmlmessage("Invalid Request","You are already logged in. Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-1)\">here</a> to return to the previous page.");
	}
	else
	{
		//Otherwise show them the login form
		include("reused-code-vnF434/forummenu.php");
		navimenu(1,"","N");
		loginform(1);
	}
}
else
{
	//Extract the username and password from the POST data	
	$uname=addslashes(filter_var(trim($_POST['uname']),FILTER_SANITIZE_SPECIAL_CHARS));
	//Passwords are encrypted on the database using an md5 hash
	$pwd=md5(addslashes(trim($_POST['pwd'])));

	//Connect to the database
	sql_connect("orentago_forum");
	
	//Run the query
	$r2=mysql_query("SELECT id, uname, pwd, validated FROM users WHERE uname='$uname'");
	//Start grabbing the results
	if($row2=mysql_fetch_array($r2))
	{
		//Get the password
		$pwd2=$row2['pwd'];
		//And validation switch
		$validated=$row2['validated'];
		
		//Check if they're validated or not
		if($validated=='Y')
		{
			//If they are, go ahead and authenticate
			if($pwd==$pwd2)
			{
				//They're authenticated, so set the session data
				$_SESSION['logged_in']="Y";
				$_SESSION['uid']=$row2['id'];
				
				//Now they've changed the session data it makes sense to display the
				//forum menu. If we'd done so earlier then they'd get a menu suggesting
				//they weren't logged in.
				include("reused-code-vnF434/refreshuser.php");
				include("reused-code-vnF434/forummenu.php");
				navimenu(1,"","N");
				//Let them know they've logged in successfully
				htmlmessage("Login Successful","You are now logged in. Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-3)\">here</a> to return to the previous page.");
			}
			else
			{
				//Otherwise their password is incorrect. Let them know.
				$_SESSION['logged_in']="N";
				$_SESSION['uid']=$row2['id'];
				include("reused-code-vnF434/forummenu.php");
				navimenu(1,"","N");
				loginform(2);//2 implies second attempt
			}
		}
		else
		{
			//If they're already logged in, tell them it's a fruitless exercise
			include("reused-code-vnF434/forummenu.php");
			navimenu(1,"","N");
			htmlmessage("Login","Please validate your email first!");
		}
	}
	else
	{
		//Otherwise the database query failed
		$_SESSION['logged_in']="N";
		unset($_SESSION['uid']);
		include("reused-code-vnF434/forummenu.php");
		navimenu(1,"","N");
		//Let the user know
		htmlmessage("Request Failed","There was an error processing your request. Click <a href=\"index.php?action=login\" class=\"entry\">here</a> to try again.");
	}
	//Close the database
	mysql_close();
}
?>
