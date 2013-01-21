<?php
/*Here we update the "lastseen" variable in the user table to the current
UNIX timestamp, so that we can estimate how many users are online.*/
include_once("../protected/sql_connect.inc.php");
if(isset($_SESSION['uid']) && $_SESSION['logged_in']=="Y")
{
	sql_connect("orentago_forum");
	$uid=filter_var($_SESSION['uid'],FILTER_SANITIZE_NUMBER_INT);
	$time=time()-$offset;
	mysql_query("UPDATE users SET online='Y', lastseen=$time WHERE id=".$uid) or die();
	unset($uid);
}
?>
