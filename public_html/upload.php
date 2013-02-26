<?php
//Start the php session for users and so on.
session_start();
//This page provides users with the ability to upload stuff.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html>
<head>
<title>File Upload</title>
<link rel="stylesheet" type="text/css"
  href="http://latex.codecogs.com/css/equation-embed.css" />
  <!--[if lte IE 7]>
  <link rel="stylesheet" href="http://latex.codecogs.com/css/ie6.css" type="text/css"/>
  <![endif]-->
  <script type="text/javascript" 
  src="http://latex.codecogs.com/js/eq_config.js" ></script>

  <link rel="stylesheet" type="text/css" href="mystyle.css" />
  <script type="text/javascript">
  //Google analytics
  var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-29596672-1']);
_gaq.push(['_trackPageview']);

(function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
<!-- 
 //Little function to check if the user really does want to delete the file.
function confirmDelete() { 
  if (confirm("Are you sure you want to delete this file?")) {
    return true; 
  } else {
    return false;
  } 
 } 
//-->
</script>
</head>
<body>
<?php
  //Include some useful code.
include("2IAcowwxOi/reused-code-vnF434/refreshuser.php");
include_once("../protected/sql_connect.inc.php");
//html form used to enter file info, and display messages
//This is used over and over so having a function is handy
include_once("2IAcowwxOi/reused-code-vnF434/uploadform.php");
//Handy function to output message in html table.
include_once("2IAcowwxOi/reused-code-vnF434/message.php");

$folder=dirname($_SERVER['REQUEST_URI']);
if ($folder=="/") $folder="";
$site_url = 'http://orentago.linkpc.net'.$folder;

function check_filetype($ftype) {
  //Checks to see if the filtype is one that we support. No PHP files allowed!
  switch($ftype) {
  case "image/gif":
    return true;
  case "image/jpeg":
    return true;
  case "image/pjpeg":
    return true;
  case "image/png":
    return true;
  case "application/pdf":
    return true;
  case "application/msword":
    return true;
  case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
    return true;
  default:
    return false;
  }
}

if(!isset($_SESSION['uid']) || $_SESSION['logged_in']=="N") {
  //If user isn't logged in then let them know
  htmlmessage("Authentication Required","You must be logged in to upload files. Click <a href=\"index.php?action=login\" class=\"entry\">here</a> to login.");
}
else {
  //Get the user id if they're logged in
  $uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
  if(isset($_FILES["file"]["error"])) {//Check to see if we're uploading a file or not.
    //Is the file a supported filetype, and small enough in size?
    if (check_filetype($_FILES["file"]["type"]) && ($_FILES["file"]["size"] < 5*1024*1024)) {
      if ($_FILES["file"]["error"] > 0) {//If there are errors, kill the script.
	die();
      }
      else {
	if($_SESSION['token'] == $_GET['csrf']) {
	  //Else connect to the database
	  sql_connect("orentago_forum");
	  //Get the user's username.
	  $r=mysql_query("SELECT uname from users where id=".$uid) or die();
	  $row=mysql_fetch_array($r);
	  $uname=$row['uname'];
	
	  //Check if the user's directory exists
	  if(!file_exists("../files/".$uname)) {
	    //if not, make it.
	    mkdir("../files/".$uname);
	  }
	  //If file exists, don't upload it!
	  if (file_exists("../files/".$uname."/".$_FILES["file"]["name"])) {
	    //Let the user try again and let them know.
	    uploadform($_FILES["file"]["name"] . " already exists.");
	  }
	  else {
	    //Else move the file to their folder
	    move_uploaded_file($_FILES["file"]["tmp_name"],
			       "../files/".$uname."/".$_FILES["file"]["name"]);
	
	    //Insert an entry into the files table to keep track of the user's files.
	    $query="INSERT INTO files (id,name,type,owner_id, owner_name) VALUES (0,'".$_FILES["file"]["name"]."','".$_FILES["file"]["type"]."',".$uid.",'$uname')";
	    mysql_query($query) or die();//Run the query
	    //Let them know things went ok, and let them upload another form.
	    uploadform("File uploaded successfully!", $uid);
	  }
	}
	else {
	  htmlmessage("Session Error","There was an error trying to validate your session.");
	}
      }
    }
    else {
      //File invalid
      uploadform("Invalid file!", $uid);
    }
  }
  elseif(isset($_GET['id'])) {//If they've specified an id, maybe they're trying to delete something
    //Extract the file id
    $fid=filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
    //Get the file info
    if($r=mysql_query("SELECT name, owner_id, owner_name from files where id=".$fid)) {
      //Get the user's name and the file path.
      $row=mysql_fetch_array($r);
      $fuid=$row['owner_id'];
      $path="files/".$row['owner_name']."/".$row['name'];
      $uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
	
      if($uid==$fuid) {//Check to see if the user is allowed to delete this file.
	if($_SESSION['token'] == $_GET['csrf']) {
	  //If they're allowed, delete the file
	  mysql_query("delete from files where id=".$fid);
	  if(file_exists("../".$path)) unlink("../".$path); //If the file exists, delete it.
	  //Display the success message
	  uploadform("File deleted successfully!", $uid);
	}
	else {
	  htmlmessage("Session Error","There was an error validating your sesssion.");
	}
      }
      else {
	//Tell them they're not allowed
	htmlmessage("Authorisation Required", "You are not authorised to perform that action. Click <a href=\"index.php?action=login\" class=\"entry\">here</a> to login.");
      }
		
    }
    else
      {
	//Can't find the file in the database? Tell the user
	uploadform("Cannot find the specified file.", $uid);
      }
  }
  else
    {
      //They've just arrived at the form, so just create the form and wait for instructions
      uploadform("", $uid);
    }
}
?>
</body>
