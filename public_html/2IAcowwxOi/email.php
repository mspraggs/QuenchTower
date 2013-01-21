<script type="text/javascript">
//Form validation
function validateForm()
{
	var name=document.forms["share"]["subject"].value;
	var email=document.forms["share"]["message"].value;
	
	if (subject==null || subject=="" || message==null || message="")
	{
		alert("Please fill out all required fields.");
		return false;
	}
	
	return true;
}

</script>
<?php
/*Little page to let the user notify their friends of the page via email.*/
//Some useful code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/emailform.php");
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

	//Connect to the database
	sql_connect("orentago_forum");

	//Get the user's id from the session data
	$uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);

	//Query the database to get the user's secgroup
	$r=mysql_query("SELECT secgroup FROM users where id=".$uid) or die();
	$row=mysql_fetch_array($r);
	$secgroup=$row['secgroup'];

	//Close the database
	mysql_close();
	
	if($secgroup==2 || $secgroup==3)
	{
			
		//Is the user submitting the form?
		if(!isset($_POST['subject']) || !isset($_POST['email']) || !isset($_POST['message']))
		{
			if(!isset($_GET['email']))
			{
				htmlmessage("Email Address Not Specified", "Please specify a valid email address.");
			}
			else
			{
				$email=$_GET['email'];
				$patt='/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i';
				$num_match=preg_match($patt, $email,$matches);
				//Check the other input
				if($num_match!=1 || $matches[0]!=$email) htmlmessage("Invalid Email","The supplied email address is invalid.");
				else emailform($email);

			}	
		}
		else
		{
			//Get the post data
			$subject=$_POST['subject'];
			$email=$_POST['email'];
			$message=$_POST['message'];
		
			//Check the email address to make sure it *is* an email address
			$patt='/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i';
			$num_match=preg_match($patt, $email,$matches);
			//Check the other input
			if($num_match!=1 || $message=="" || $subject=="" || $matches[0]!=$email) die();

			//Sanitize the input
			$subject=trim($subject);
			$email=trim($email);
			$message=trim($message);

			//Create the email message and subject
			$subject="The Quench Tower - ".$subject;
			$emailmessage=$message;
			mail($email,$subject,$emailmessage);
			//Let the user know it's all sent successfully
			htmlmessage("Email Sent Successfully","You share request completed successfully! Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-3)\">here</a> to return to the previous page.");
		}
	}
	else
	{
		htmlmessage("Authorisation Required","You are not authorised to view this page. Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-3)\">here</a> to return to the previous page.");
	}
}
?>
