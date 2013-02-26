<?php
/*Allows the user to report posts that contravene the terms
  and conditions of the site*/

//The usual useful files
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/message.php");

//If post is not specified then send the user to the homepage
if(!isset($_GET['id'])) {
  header("Location:index.php");
}
else {
  //Is the user logged in?
  if(isset($_SESSION['uid']) && $_SESSION['logged_in']=="Y") {
    if($_SESSION['token'] == $_GET['csrf']) {
      //Get the user's id
      $id=filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
      //$code=$_GET['code'];//Can't remember what this is for, may not be needed
      //Keeping it in case it's needed in the include files

      //Connected to the database
      sql_connect("orentago_forum");
		
      //Get the user's id number from the session data
      $uid=filter_var($_SESSION['uid'], FILTER_SANITIZE_NUMBER_INT);
      //Query the database to get both the author of the offending post, and the user reporting it
      //Also get the entry itself and the thread title
      $r2=mysql_query("SELECT uname,secgroup FROM users WHERE id=".$uid) or die();
      $row2=mysql_fetch_array($r2);
      $secgroup=$row2['secgroup'];
		
      //Only mods and admins are allowed to do this.
      if($secgroup==2 || $secgroup==3) {		
	$r=mysql_query("SELECT thread_title, user, entry FROM posts WHERE id=".$id) or die();
	$row=mysql_fetch_array($r);
	$uname=$row2['uname'];
	$entry=$row['entry'];
	$title=$row['thread_title'];
	$user=$row['user'];
		
	/*Need to add a line in here to update the posts table to change the reported flag
	  also need to change the thread script so that flagged posts aren't displayed.*/
	mysql_query("UPDATE posts SET reported='N' WHERE id=".$id) or die();
		
	//Form the email message for the webmaster
	$message=$uname." has unblocked the following post by ".$user.":\n";
	$message.="Title: ".$title."\n".$entry;
	//Send the email to the webmaster
	mail("webmaster@orentago.linkpc.net","Post Unblocked",$message,"webmaster@orentago.linkpc.net");
	htmlmessage("Post Unblocked", "The webmaster has been notified. Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-1)\">here</a> to return to the previous page.");
		
      }
      else {			
	//Close the database and notify the user
	mysql_close();
	htmlmessage("Authorisation Required","You are not authorised to view this page. Click <a class=\"entry\" href=\"#\" onClick=\"history.go(-1)\">here</a> to return to the previous page.");
      }
    }
    else {
      htmlmessage("Session Error","There was an error validating your session.");
    }
  }
  else {
    //If the user isn't logged in, tell them to log in
    htmlmessage("Authentication Required", "You are not logged in. Please click <a href='index.php?action=login' class='entry'>here</a> to login.");
  }
}
?>
