<script type="text/javascript">
//First define our form validation

function validateForm()
{
	var uname=document.forms["register"]["uname"].value;
	var pwd=document.forms["register"]["pwd"].value;
	var pwd2=document.forms["register"]["pwd2"].value;
	var email=document.forms["register"]["email"].value;
	var fname=document.forms["register"]["fname"].value;
	var lname=document.forms["register"]["lname"].value;
	
	if (uname==null || uname=="" || pwd==null || pwd=="" || pwd2==null || pwd2=="" || email==null || email=="" || fname==null || fname=="" || lname==null || lname=="")
	{
		alert("Please fill out all required fields.");
		return false;
	}
	if (pwd!=pwd2)
	{
		alert("Passwords do not match.");
		return false;
	}
	if (pwd.length<6)
	{
		alert("Password must be at least six characters long.");
		return false;
	}
	
	//Regex match the user's email to a bham one
	var patt=/([a-z][a-z][a-z]\d\d\d@bham.ac.uk)/gi;
	var bhamemails=email.match(patt);
	if(bhamemails==null || bhamemails[0]!=email)
	{
		alert("Not a valid University of Birmingham e-mail address.");
		return false;
	}
	
	var patt2=/[\s]/gi;
	if(uname.search(patt2)>-1)
	{
		alert("Your username cannot contain spaces.");
		return false;
	}
	
	return true;
}

</script>
<?php
/*Script prints a registration form and adds new user data to the database.*/
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/regform.php");//The registration form
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/message.php");
include_once("reused-code-vnF434/navimenu.php");
navimenu(1,"","N");

//What are we doing? Is post data set?
if(!isset($_POST['uname']) || !isset($_POST['pwd']) || !isset($_POST['fname']) || !isset($_POST['lname']) || !isset($_POST['email']))
{
	//If not, is the user logged in?
	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']=="Y")
	{
		//If they are then tell them they can't register
		htmlmessage("Invalid Request","You are already registered and logged in. Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-1)\">here</a> to return to the previous page.");
	}
	else
	{
		//If they aren't then let them register
		regform(1);
	}
}
else
{
	//If they're trying to register then grab the POST data
	$uname=$_POST['uname'];
	$pwd=$_POST['pwd'];
	$email=strtolower($_POST['email']);
	$fname=$_POST['fname'];
	$lname=$_POST['lname'];

	//The default is for email notifications to be turned off
	$notify="N";
	if(isset($_POST['notify']) && $_POST['notify']=="notify")
	{
		$notify="Y";
	}

	//In case they bypassed the javascript form validation, check their input.
	$patt2='/[\s]/';
	$patt='/[a-z][a-z][a-z]\d\d\d@bham.ac.uk/';

	if(strlen($pwd)<6 || preg_match($patt, $email)!=1 || strlen($email)!=17 || $uname=="" || $pwd=="" || $email=="" || $fname=="" || $lname=="" || preg_match($patt2, $uname)>0) die();

	//Input is good, so make it sql friendly
	$uname=addslashes(filter_var(trim($uname),FILTER_SANITIZE_SPECIAL_CHARS));
	$pwd=addslashes(trim($pwd));
	$email=addslashes(trim($email));
	$fname=addslashes(filter_var(trim($fname),FILTER_SANITIZE_SPECIAL_CHARS));
	$lname=addslashes(filter_var(trim($lname),FILTER_SANITIZE_SPECIAL_CHARS));

	//Connect to the database
	sql_connect("orentago_forum");

	//Try and grab the user's username from the database to see if it already exists
	$r2=mysql_query("SELECT uname FROM users WHERE uname='$uname'");
	$r3=mysql_query("SELECT email FROM users WHERE email='$email'");
	if($row2=mysql_fetch_array($r2) || $row3=mysql_fetch_array($r3))
	{
		//Let them try again
		regform(2);
	}
	else
	{
		//Get the UK time
		$time = time()-$offset;
		//Make a validation code
		$code=rand(10000,99999);
		$pwd=md5($pwd);
		//Define the insert query
		$query = "INSERT INTO users (id,uname,pwd,fname,lname,email,secgroup,date_joined,validate_code,validated,notification) VALUES (0,'$uname','$pwd','$fname','$lname','$email',1,FROM_UNIXTIME('$time'),'$code','N','$notify')";
		
		//Run the registration query
		if(@mysql_query ($query))
		{
			//If it worked, let the user know
			htmlmessage("Registration Successful","Please check your email and validate your email address.");
			//Form the query to get the new user's id
			$query2="SELECT id FROM users WHERE uname='$uname'";
			if($r=mysql_query($query2))
			{
				//get the id
				$row=mysql_fetch_array($r);

				$id=$row['id'];
				
				//Send their validation link to their email address
				mail($email,"Revision Forum Email Validation","Please click the link below to validate your email address.\n\n ".$site_url."/index.php?action=validate&id=".$id."&code=".$code);
			}
			else
			{
				//If there is no id, then kill the script
				die();
			}
		}
		else
		{
			//Let the user know if it all went tits up.
			htmlmessage("Registration Failed","Registration was unsuccessful.");
		}
	}
	//Close the database.
	mysql_close();
}
?>
