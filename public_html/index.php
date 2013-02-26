<?php
	/* 
	 * The forum php files form a tree structure, with this file being the main file
	 * out of which everything branches. GET is used to obtain an action, then include the
	 * appropriate file. This way the index file can be changed easily and all pages will be
	 * changed as a result.

	 * The 2IAcowwxOi folder contains the files for the various actions, such as threads and so
	 * forth. The 2IAcowwxOi/reused-code-vnF434 folder contains reused code and functions.

	 * CSS is used to style tables and so forth, and is included in mystyles.css
	*/

//Start a PHP session to keep track of users
session_start();
//Calculate the timezone offset, since the server may not be in the UK and we want the
//times in the posts table to be GMT/British time.
$dateTimeZoneHere = new DateTimeZone(date_default_timezone_get());
$dateTimeHere = new DateTime("now", $dateTimeZoneHere);
$offset =  timezone_offset_get($dateTimeZoneHere,$dateTimeHere);

//Make sure magicquotes is turned off, and update the lastseen field in the user database
//so that we can keep track of those users that are online.
include("2IAcowwxOi/reused-code-vnF434/refreshuser.php");
include("2IAcowwxOi/reused-code-vnF434/mqoff.php");

//Get the ip address of the visitor to get round port forwarding on shitty sky router
//Also use to determine directory we're in in case we're in a subfolder
$folder=dirname($_SERVER['REQUEST_URI']);
if ($folder=="/") $folder="";
$site_url = 'http://orentago.linkpc.net'.$folder;

	/* 
	 * The following are some standard HTML bits and pieces
	*/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html>
<head>
<title>The Quench Tower</title>
<meta name="description" content="The Birmingham fourth year chemical engineering revision forum." />
<link rel="stylesheet" type="text/css" href="mystyle.css" />
<script type="text/javascript">
//Google Analytics script. WHY?
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29596672-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type="text/javascript"
  src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
//Load mathjax so we can display LaTeX
</script>
<script type="text/javascript">
	//Ajax for countdown timer
	var obj;

	function ProcessXML(url) {
	  // native  object
	
	  if (window.XMLHttpRequest) {
	    // obtain new object
		obj = new XMLHttpRequest();
	    // set the callback function
	    obj.onreadystatechange = processChange;
	    // we will do a GET with the url; "true" for asynch
	    obj.open("GET", url, true);
	    // null for GET with native object
	    obj.send(null);
	  // IE/Windows ActiveX object
	  } else if (window.ActiveXObject) {
	    obj = new ActiveXObject("Microsoft.XMLHTTP");
	    if (obj) {
	      obj.onreadystatechange = processChange;
	      obj.open("GET", url, true);
	      // don't send null for ActiveX
	      obj.send();
	    }
	  } else {
	    alert("Your browser does not support AJAX");
	  }
	}
	
	function processChange() {
	    // 4 means the response has been returned and ready to be processed
	    if (obj.readyState == 4) {
	        // 200 means "OK"
	        if(obj.status == 200) {
		        updateTime(obj.responseText);
		}
		else
		{
			//alert("An error occured with Ajax");
		}
		        
	    }
	}
	
	function updateTime(response) {
	  // if response is not empty, we have received data back from the server
	  if (response != '')
	  {
	    // the value of response is returned from timeleft.php:
		var d = document.getElementById("countdown");
		d.innerHTML = "<h2 class='header'>Time left until the end of exams: "+response+"</h2>";
	  }
	  else
	  {
	    //  if response is empty, we need to send the username to the server
	    url = <?php echo "\"".$site_url."/timeleft.php\";"; ?>
	    ProcessXML(url);
	  }
	}
	function timedCount()
	{
		updateTime('')
		t=setTimeout("timedCount()",1000);

	}

timedCount()
</script>
</head>
<body>
<?php
//Extract the action from the GET variable
if(!isset($_GET['action']))
{
	//if it's not set, display the homepage.
	include("2IAcowwxOi/home2.php");
}
else
{ //Else figure out which page to include
	if($_GET['action']=="forum")
	{
		include("2IAcowwxOi/forum.php");
	}
	elseif($_GET['action']=="thread")
	{
		include("2IAcowwxOi/thread.php");
	}
	elseif($_GET['action']=="login")
	{
		include("2IAcowwxOi/login.php");
	}
	elseif($_GET['action']=="validate")
	{
		include("2IAcowwxOi/validate.php");
	}
	elseif($_GET['action']=="edit")
	{
		include("2IAcowwxOi/edit.php");
	}
	elseif($_GET['action']=="delete")
	{
		include("2IAcowwxOi/delete.php");
	}
	elseif($_GET['action']=="new")
	{
		include("2IAcowwxOi/new.php");
	}
	elseif($_GET['action']=="reply")
	{
		include("2IAcowwxOi/reply.php");
	}
	elseif($_GET['action']=="password")
	{
		include("2IAcowwxOi/changepwd.php");
	}
	elseif($_GET['action']=="register")
	{
		include("2IAcowwxOi/register.php");
	}
	elseif($_GET['action']=="logout")
	{
		include("2IAcowwxOi/logout.php");
	}
	elseif($_GET['action']=="reset")
	{
		include("2IAcowwxOi/resetpwd.php");
	}
	elseif($_GET['action']=="share")
	{
		include("2IAcowwxOi/share.php");
	}
	elseif($_GET['action']=="report")
	{
		include("2IAcowwxOi/report.php");
	}
	elseif($_GET['action']=="tos")
	{
		include("2IAcowwxOi/tandcs.php");
	}
	elseif($_GET['action']=="account")
	{
		include("2IAcowwxOi/account.php");
	}
	elseif($_GET['action']=="search")
	{
		include("2IAcowwxOi/search.php");
	}
	elseif($_GET['action']=="email")
	{
		include("2IAcowwxOi/email.php");
	}
	elseif($_GET['action']=="unblock")
	{
		include("2IAcowwxOi/unblock.php");
	}
	else
	{//If the action isn't one of the above, return the user to the homepage.
		include("2IAcowwxOi/home2.php");
	}
}

//If we haven't already, include the sql connection file
include_once("../protected/sql_connect.inc.php");
//Connect to the forum
sql_connect("orentago_forum");
//Work out unix timestamp from 10 mins ago
$time=time()-$offset-600;
//Query the user database to get all users
$r=mysql_query("SELECT id from users") or die();
//Count the total number of users
$num_users=mysql_num_rows($r);
//Next, retrieve number of users that were last seen less than ten minutes ago.
$r2=mysql_query("SELECT id from users WHERE lastseen>$time AND online='Y'") or die();
//And calculate the number of users online
$num_online=mysql_num_rows($r2);
mysql_close();
//Output some contact details in a footer, along with the number of users online.
?>
<table align="center" class="header">
<tr>
<td>
<p class="footer">Contact: webmaster@orentago.linkpc.net</p>
</td>
<td align="right">
<p class="footer"><?php echo $num_online; ?> users online of <?php echo $num_users; ?>
</td>
</tr>
</body>
</html>
