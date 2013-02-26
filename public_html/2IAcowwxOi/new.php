<?php
/*This script allows the user to create a new post.
  If they're logged in, then we'll collect the title
  and body of the post and add it to the database.*/

//Include some useful files
include_once("../protected/sql_connect.inc.php");
include("reused-code-vnF434/forummenu.php");
include_once("reused-code-vnF434/editform.php");
include_once("reused-code-vnF434/navimenu.php");
include_once("reused-code-vnF434/message.php");

//Connect to the database
sql_connect("orentago_forum");

//If no relevant post or get data is set, return the user to the homepage
if(!isset($_GET['id']) && (!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['entry']))) {
  header("Location:index.php");
}

//Get the thread id
if(isset($_GET['id'])) $forum_id=filter_var($_GET["id"],FILTER_SANITIZE_NUMBER_INT);
if(isset($_POST['id'])) $forum_id=filter_var($_POST["id"],FILTER_SANITIZE_NUMBER_INT);

//Display the navigation menu
navimenu($forum_id,"forum","N");

//Check if the user is logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in']=="N") {
  //If not, tell them they need to log in
  htmlmessage("Authentication Required","Please <a href=\"index.php?action=login\" class=\"entry\">login</a> or <a href=\"index.php?action=register\" class=\"entry\">register</a> to participate in the forum.");
}
else {
  //Are they saving a post?
  if(!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['entry'])) {
    //If not, give them the edit form
    $_SESSION['token'] = md5(uniqid(mt_rand(),true));
    editform("",$forum_id,"new");
  }
  else {
    if($_SESSION['token'] == $_GET['csrf']) {
      //Otherwise extract the data, using filter's to make sure
      //we don't screw up the database
      $uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
      $forum_id=filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
      $title=addslashes(filter_var(trim($_POST['title']),FILTER_SANITIZE_SPECIAL_CHARS));
      $entry=addslashes(htmlspecialchars(trim($_POST['entry'])));
		
      //Get the user's username
      $r=mysql_query("SELECT uname FROM users WHERE id=".$uid) or die();
      $row=mysql_fetch_array($r);
      $user=$row['uname'];
		
      //Retrieve the maximum current thread id and increment to get
      //the thread id to use in the database
      $r=mysql_query("SELECT MAX(thread_id) FROM posts") or die();
      $row=mysql_fetch_array($r);
      $thread_id=$row['MAX(thread_id)']+1;
		
      //Get the current time and offset it properly
      $time = time()-$offset;

      //Make sure they haven't bypassed the javascript form checker
      if($title=="" || $entry=="" || is_null($entry) || is_null($title)) die();

      //Add the post to the database
      mysql_query("INSERT INTO posts (id, forum_id, thread_id, thread_title, entry, user, date_entered, stickied, files) VALUES (0,'$forum_id','$thread_id','$title','$entry','$user',FROM_UNIXTIME('$time'),'N','')") or die();
      //Notify the user of the success
      htmlmessage("Post Successful","Post added successfully. Click <a href=\"index.php?action=thread&id=".$thread_id."\" class=\"entry\">here</a> to return to the thread.");
    }
    else {
      htmlmessage("Session Error","There was an error whilst validating your session.");
    }
  }
  //Close the database
  mysql_close();
}
?>
