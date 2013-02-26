<?php
/*This script is used to edit existing forum posts. It extracts
  the post id from the GET data, authenticates the user, then fetches
  the post for editing. If there's data in the post variables, it
  extracts it and updates the relevant row in the database.*/

//First, include some useful code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/navimenu.php");
include("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/message.php");
include_once("reused-code-vnF434/editform.php");

//Connect to the database
sql_connect("orentago_forum");

//Check to see if the id is set in GET or POST.
if(!isset($_GET['id']) && (!isset($_POST['id']) || !isset($_POST['entry']))) {
  //If not, redirect the user 
  header("Location:index.php");
}
//Extract the id from POST or GET
if(isset($_GET['id'])) $id=$_GET["id"];
if(isset($_POST['id'])) $id=$_POST["id"];

//Filter out any crap
$id=filter_var($id,FILTER_SANITIZE_NUMBER_INT);
//Display the navigation menu
navimenu($id,"post","N");

//Check to see if we're logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in']=="N") {
  //If not, inform the user
  htmlmessage("Authentication Required", "Please log in or register to participate in the forum.");
}
else {
  //Else get the user's username and security group
  $uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
	
  //Run the query and extract the results
  $r=mysql_query("SELECT uname, secgroup FROM users WHERE id=".$uid) or die();
  $row=mysql_fetch_array($r);
  $secgroup=$row['secgroup'];
  $uname=$row['uname'];
	
  //Query the posts database to get the post and user who wrote it
  $r=mysql_query("SELECT user, entry from posts WHERE id=".$id) or die();
  $row=mysql_fetch_array($r);
  $user=$row['user'];
  $entry=$row['entry'];
	
  //Is the user authorised to edit the post?
  if($user!=$uname && $secgroup!=2 && $secgroup!=3) {
    //If not, tell them so
    htmlmessage("Authentication Required", "Please log in or register to participate in the forum.");
  }
  else {
    //Check the POST variables
    if(!isset($_POST['id']) || !isset($_POST['entry'])) {
      //If no post, just output the edit form
      $_SESSION['token'] = md5(uniqid(mt_rand(),true));
      echo $_SESSION['token'];
      editform($entry,$id,"edit");
    }
    else {
      if($_GET['csrf'] == $_SESSION['token']) {
	//Check to see if the entry is empty
	if(is_null($entry) || $entry=="") die();
	//If edited by mod, add a line to indicate this.
	if($uname!=$user) {
	  $time = time()-$offset;
	  $entry=$_POST['entry']."\nEdited by ".$uname." on ".date("G:i jS F Y",$time);
	}
	else {
	  $entry=$_POST['entry'];
	}
	//Filter the entry to make it safe for the database			
	$entry=addslashes(htmlspecialchars(trim($entry)));
			
	//Update the posts database
	if($r=mysql_query("UPDATE posts SET entry='$entry' WHERE id=".$id)) {
	  //Get the thread id
	  $r1=mysql_query("SELECT thread_id FROM posts WHERE id=".$id);
	  $row1=mysql_fetch_array($r1);
	  $thread_id=$row1['thread_id'];
	  //Notify the user of the successful edit. Link them to the thread.
	  $message="Your post was updated successfully. Click <a href=\"index.php?action=thread&id=".$thread_id."\" class=\"entry\" >here</a> to return to the thread.";
	  htmlmessage("Edit Successful", $message);
	}
	else {
	  //Otherwise tell them the edit failed.
	  htmlmessage("Edit Failed", "An error occured whilst performing your request.");
	}
      }
      else {
	htmlmessage("Session Error","There was an error validating your session.");
	
      }
    }
  }
}
//Close the database
mysql_close();
?>
