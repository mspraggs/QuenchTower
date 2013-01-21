<?php
//Little page to reset the users password

function genRandomString() {
//Function to generate the new password
    $length = 10;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = "";    
    for ($p = 0; $p < $length; $p++)
		{
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

//Include the usual useful files
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/resetpwd_func.php");//The password reset form
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/message.php");
include_once("reused-code-vnF434/navimenu.php");
//Output the navigation menu
navimenu(1,"","N");

//Check to see if the user is logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in']=="N")
{
	//Are they requesting a password reset?
	if(!isset($_POST['uname']) || !isset($_POST['email']))
	{
		//If not, allow them to
		resetpwd(1);
	}
	else
	{
		//Grab the POST data
		$uname=addslashes(trim($_POST['uname']));
		$email=addslashes(trim($_POST['email']));
		
		//Connect to the database
		sql_connect("orentago_forum");
		//Grab the user's email
		$r=mysql_query("SELECT email FROM users WHERE uname='$uname'");
		$row=mysql_fetch_array($r);
		$email2=$row['email'];
		
		//Validate their email input
		if($email!=$email2)
		{
			resetpwd(2);
		}
		else
		{
			//Generate the new password
			$pwd_temp=genRandomString();
			//Hash it
			$pwd=md5($pwd_temp);
			//Run the query
			if(mysql_query("UPDATE users SET pwd='$pwd' WHERE uname='$uname'"))
			{
				//If successful then let the user know
				mail($email,"Revision Forum Password Reset","Your password has been reset. Your new login details are:\nUsername: ".$uname."\nPassword: ".$pwd_temp);
				htmlmessage("Password Changed","Your password was reset successfully. Check your email and click <a class=\"entry\" href=\"index.php?action=login\">here</a> to login.");
			}
			else
			{
				//If things fail, also let them know
				htmlmessage("Request Failed","The request failed. Click <a class=\"entry\" href=\"#\"onClick=\"history.go(-1)\">here</a> to try again.");
			}
		}
		//Close database connection
		mysql_close();
	}
}
else
{
	//If they aren't logged in, tell them they need to be
	include("reused-code-vnF434/refreshuser.php");
	htmlmessage("Invalid Request","You are already logged in. Click <a class=\"entry\" href=\"index.php?action=password\">here</a> to change your password.");
}
?>
