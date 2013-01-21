<script type="text/javascript">
//Form validation
function validateForm()
{
	var name=document.forms["share"]["name"].value;
	var email=document.forms["share"]["email"].value;
	
	if (email==null || email=="")
	{
		alert("Please fill out all required fields.");
		return false;
	}
	
	var patt=/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/gi;
	var bhamemails=email.match(patt);
	if(bhamemails==null || bhamemails[0]!=email)
	{
		alert("Not a valid University of Birmingham e-mail address.");
		return false;
	}
	
	return true;
}

</script>
<?php
/*Little page to let the user notify their friends of the page via email.*/
//Some useful code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/shareform.php");
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/message.php");
include_once("reused-code-vnF434/navimenu.php");
//Navigation menu
navimenu(1,"","N");

//Is user logged in?
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in']=="N")
{
	htmlmessage("Authentication Required","You must be logged in to share this site. Click <a class=\"entry\" href=\"index.php?action=login\">here</a> to login.");
}
else
{
	//Is the user submitting the form?
	if(!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['message']))
	{
		shareform(1);
	}
	else
	{
		//Get the post data
		$name=$_POST['name'];
		$email=$_POST['email'];
		$message=$_POST['message'];
		
		//Check the email address to make sure it *is* an email address
		$patt='/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i';
		$num_match=preg_match($patt, $email,$matches);
		//Check the other input
		if($num_match!=1 || $name=="" || $matches[0]!=$email) die();

		//Sanitize the input
		$name=addslashes(filter_var(trim($name),FILTER_SANITIZE_SPECIAL_CHARS));
		$email=addslashes(filter_var(trim($email),FILTER_SANITIZE_EMAIL));
		$message=addslashes(filter_var(trim($message),FILTER_SANITIZE_SPECIAL_CHARS));

		//Connect to the database
		sql_connect("orentago_forum");

		//Get the user's id from the session data
		$uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
		
		//Query the database to get the user's name
		$r=mysql_query("SELECT fname, lname FROM users where id=".$uid) or die();
		$row=mysql_fetch_array($r);
		$fname=$row['fname'];
		$lname=$row['lname'];

		//Close the database
		mysql_close();

		//Create the email message and subject
		$subject="Chemical Engineering Revision Forum";
		$emailmessage=$fname." invited you to the chemical engineering revision forum.";
		
		//If they've specified their own message, add that in
		if($message!="")
		{
			$emailmessage.=" ".$fname." says:\n\n".$message;
		}
		else
		{
			//Otherwise use this message
			$emailmessage.="\n\nThe forum offers a central location to our fourth year chemical engineering year to discuss exam questions and revision problems.";
		}
		//Tell them where the site is N.B. the url here is changed to thequenctower.co.uk by the site-upload script
		$emailmessage.="\n\nTo register for the forum, go to ".$site_url.". See you there!";
		//Send the email message
		mail($email,$subject,$emailmessage);
		//Let the user know it's all sent successfully
		htmlmessage("Email Sent Successfully","You share request completed successfully! Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-3)\">here</a> to return to the previous page.");
	}
}
?>
