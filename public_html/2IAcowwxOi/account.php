<script type="text/javascript">
   //Form validation script
   function validateForm()
{
  //Get form data
  var pwd=document.forms["register"]["pwd"].value;
  var pwd2=document.forms["register"]["pwd2"].value;
  var pwd3=document.forms["register"]["pwd2"].value;
	
  //Check to see that the form is actually filled in
  if (pwd==null || pwd=="" || pwd2==null || pwd2=="" || pwd3==null || pwd3=="")
    {
      alert("Please fill out all required fields.");
      return false;
    }
  //Passwords must be consistent
  if (pwd2!=pwd3)
    {
      alert("New passwords do not match.");
      return false;
    }
  //Make sure the password is vaguely secure
  if (pwd2.length<6)
    {
      alert("Password must be at least six characters long.");
      return false;
    }
  //If all's well, return true.
  return true;
}

   </script>
 <?php
     //Include some useful code
   include_once("../sql_connect.inc.php");
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/message.php");
include_once("reused-code-vnF434/accountform.php");
include_once("reused-code-vnF434/navimenu.php");
//Display the navigation bar
navimenu(1,"","N");

//Connect to the database
sql_connect("orentago_forum");

//Check to see if we're logged in
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']=="Y")
  {
    //If so, retrieve the users id from the session.
    $uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
    $r=mysql_query("SELECT notification FROM users WHERE id=".$uid);
    $row=mysql_fetch_array($r);
    //Get the user's notification preference from the database
    $notify=$row['notification'];

    //Check to see whether anything's been posted
    //Variable contact is hidden variable to signify
    //contact variable form submission
    if((!isset($_POST['pwd']) || !isset($_POST['pwd2'])) && !isset($_POST['contact']))
      {
	$_SESSION['token'] = md5(uniqid(mt_rand(),true));
	//If not, create the form.
	chpwdform(1, $notify);
      }
    else
      {
	if($_SESSION['token'] == $_GET['csrf']) {
	  //Otherwise, check to see what has been posted
	  if(isset($_POST['pwd']) && isset($_POST['pwd2']))
	    {
	      //If passwords have been posted, check them and clean them of information
	      $pwd=addslashes(trim($_POST['pwd']));
	      $pwd2=addslashes(trim($_POST['pwd2']));
	      //Check the password length.
	      if(strlen($pwd2)<6) die();
	      $pwd=md5($pwd);
	      $pwd2=md5($pwd2);

	      //Grab the user's password to make sure they have entered their old password correctly
	      $r=mysql_query("SELECT pwd FROM users WHERE id=".$uid);
	      $row=mysql_fetch_array($r);
	      $pwd3=$row['pwd'];
	      //Perform the check
	      if($pwd3!=$pwd)
		{
		  //If they've done it wrong, tell them
		  chpwdform(2, $notify);
		}
	      else
		{
		  //If not, update the database with the new password
		  if(mysql_query("UPDATE users SET pwd='$pwd2' WHERE id=".$uid))
		    {
		      //If the database is updated successfully, let the user know
		      htmlmessage("Password Changed","Your password was changed successfully. Click <a class=\"entry\" href=\"#\"onClick=\"history.go(-2)\">here</a> to return to the previous page.");
		    }
		  else
		    {
		      //If it failed, also let them know
		      htmlmessage("Request Failed","The request failed. Click <a class=\"entry\" href=\"#\"onClick=\"history.go(-1)\">here</a> to try again.");
		    }
		}
	      //Close the database
	      mysql_close();
	    }
	  //Check if they want to change their contact preferences
	  elseif(isset($_POST['contact']) && $_POST['contact']=="Y")
	    {
	      //Set the notify option
	      $notify="N";
	      //If the option was left unchecked, the notify variable
	      //will be unset, so we can leave $notify as "N". Otherwise,
	      //set it to "Y"
	      if(isset($_POST['notify']) && $_POST['notify']=="notify")
		{
		  $notify="Y";
		}

	      //Update the database
	      if(mysql_query("UPDATE users SET notification='$notify' WHERE id=".$uid))
		{
		  //Let user know of success
		  htmlmessage("Password Changed","Your contact preferences were changed successfully. Click <a class=\"entry\" href=\"#\"onClick=\"history.go(-2)\">here</a> to return to the previous page.");
		}
	      else
		{
		  //Or failure
		  htmlmessage("Request Failed","The request failed. Click <a class=\"entry\" href=\"#\"onClick=\"history.go(-1)\">here</a> to try again.");
		}
	      //Close database
	      mysql_close();
	    }
	  else
	    {
	      //Just in case they've made it through all the previous checks on
	      //the post variables.
	      htmlmessage("Invalid Request","Your request could not be processed.");
	    }
	}
	else {
	  htmlmessage("Invalid Session","It looks like your session is invalid.")
	}
      }
  }
else
  {
    //If they're not logged in, get them to
    htmlmessage("Invalid Request","You are not logged in. Click <a class=\"entry\" href=\"index.php?action=login\">here</a> to login.");
  }
?>
