<?php
/*Allows the user to reply to threads and so on. Similar to
  the new post script. If you've read that file the following
  should be obvious.*/
include_once("../protected/sql_connect.inc.php");
include_once("reused-code-vnF434/navimenu.php");
include_once("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/editform.php");
include_once("reused-code-vnF434/message.php");

sql_connect("orentago_forum");

//Check what we're doing.
if(!isset($_GET['id']) && (!isset($_POST['id']) || !isset($_POST['entry']))) {
  //ob_start();
  header("Location:index.php");
}

//Get the user id if necessary
if(isset($_GET['id'])) $thread_id=$_GET["id"];
if(isset($_POST['id'])) $thread_id=$_POST["id"];

//Print the navigation bar
navimenu($thread_id,"thread","N");

//Are we logged in?
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in']=="N") {
  htmlmessage("Authentication Required","Please <a href=\"index.php?action=login\" class=\"entry\">login</a> or <a href=\"index.php?action=register\" class=\"entry\">register</a> to participate in the forum.");
}
else {
  //Do we need to get input?
  if(!isset($_POST['id']) || !isset($_POST['entry'])) {
    if(!isset($_SESSION['token'])) $_SESSION['token'] = md5(uniqid(mt_rand(),true));
    editform("",$thread_id,"reply");
  }
  else {
    if($_SESSION['token'] == $_GET['csrf']) {
    //Get the user's input and sanitize it
    $uid=$_SESSION['uid'];
    $thread_id=filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
    $entry=addslashes(htmlspecialchars(trim($_POST['entry'])));
		
    //Get the user's username
    $r=mysql_query("SELECT uname FROM users WHERE id=".$uid);
    $row=mysql_fetch_array($r);
    $user=$row['uname'];

    //Get some info on the thread
    $r=mysql_query("SELECT thread_title, forum_id FROM posts WHERE thread_id=".$thread_id." LIMIT 1");
    $row=mysql_fetch_array($r);
    $thread_title=$row['thread_title'];
    $forum_id=$row['forum_id'];

    //Get the current UK time
    $time = time()-$offset;

    //If they're posting nothing, kill the script (if they've evaded javascript
    if(is_null($entry) || $entry=="") die();

    //Add the post to the database
    mysql_query("INSERT INTO posts (id, forum_id, thread_id, thread_title, entry, user, date_entered, stickied, files) VALUES (0,'$forum_id','$thread_id','$thread_title','$entry','$user',FROM_UNIXTIME('$time'),'N','')");
		
    //Get all unique users from the thread to let them know via email that there's a new post
    $r=mysql_query("SELECT DISTINCT user FROM posts WHERE thread_id=".$thread_id) or die();
    while($row=mysql_fetch_array($r)) {
      //Get their username and hence email
      $uname=$row['user'];
      $r2=mysql_query("SELECT email, notification FROM users WHERE uname='$uname'") or die();
      $row2=mysql_fetch_array($r2);
      $email=$row2['email'];
      $notification=$row2['notification'];
			
      //Don't inform the posting user and do inform those who want to know
      if($uname!=$user && $notification=="Y")	{
	mail($email, "The Quench Tower Notification", $user." replied to your post \"".$thread_title."\" at The Quench Tower:\n\n".stripslashes($entry)."\n\nTo reply, follow this link:\n".$site_url."/index.php?action=thread&id=".$thread_id);
      }
    }
    //Let them know it all went ok
    htmlmessage("Post Successful","Post added successfully. Click <a href=\"index.php?action=thread&id=".$thread_id."\" class=\"entry\">here</a> to return to the thread.");
    }
    else {
      htmlmessage("Session Error","There was an error validating your session.");
    }
  }
  //Close the database
  mysql_close();
}
?>