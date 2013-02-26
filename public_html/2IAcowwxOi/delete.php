<?php
/*This script accepts a forum post id, authenicates the user then deletes the
  specified post*/
//Include some useful code
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/navimenu.php");
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/message.php");

//Connect to the database
sql_connect("orentago_forum");

//If id isn't set, return the user to the homepage
if(!isset($_GET['id']))
  {
    //ob_start();
    header("Location:index.php");
  }
else
  {
    //Otherwise extract the id
    $id=$_GET["id"];
  }

//Filter the id t remove foul play
$id=filter_var($id,FILTER_SANITIZE_NUMBER_INT);

//Get the thread id so we can display the navigation menu
$r=mysql_query("SELECT thread_id FROM posts WHERE id=".$id);
$row=mysql_fetch_array($r);
$thread_id=$row['thread_id'];

//And display the menu
navimenu($thread_id,"thread","Y");

//Is the user logged in?
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in']=="N") {
  //If not, tell them they need to log in
  htmlmessage("Authentication Required","Please log in or register to participate in the forum.");
} 
else {
  if($_SESSION['token'] == $_GET['csrf']) {
    //Else get their username and security group
    $uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
    $r=mysql_query("SELECT uname, secgroup FROM users WHERE id=".$uid) or die();
    $row=mysql_fetch_array($r);
    $uname=$row['uname'];
    $secgroup=$row['secgroup'];
	
    //Get the username of the person who's post it is
    $r=mysql_query("SELECT user from posts WHERE id=".$id) or die();
    $row=mysql_fetch_array($r);
    $user=$row['user'];

    //Check to see if the user is allowed to delete said post
    if($user!=$uname && $secgroup!=2 && $secgroup!=3) {
      //If not, tell them
      htmlmessage("Authentication Required","Please log in or register to participate in the forum.");
    }
    else {
      //Try to delete the post
      if($r=mysql_query("DELETE FROM posts WHERE id=".$id)) {
	//If successful, tell the user
	htmlmessage("Deletion Successful","The post was successfully deleted.");
      }
      else {
	//If the deletion failed, also tell the user
	htmlmessage("Deletion Failed","An error occured whilst performing in your request.");
      }
    }
  }
  else {
    htmlmessage("Session Error","There was an error validating your session.");
  }
}
//Close the database
mysql_close();
?>
