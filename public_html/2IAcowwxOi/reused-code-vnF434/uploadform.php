<?php
/*This form is for adding and uploading files. It also lists all the current
files in the user's directory on the server.*/
include_once("../protected/sql_connect.inc.php");
function uploadform($message, $uid)
{
?>
<table align="center" class="entry">
	<tr>
		<td colspan=2 align="center"><h1 class="entry">File Upload</h1></td>
	</tr>
	<tr>
		<td colspan=2>
			<p class="entry">Use this page to upload files to the server and insert them into posts. To insert them into posts, you'll need 
	copy the file URL into the post using the [url] or [img] tags. iPhone users will need to enable Safari uploads by following the instructions in <a href="http://www.iclarified.com/entry/index.php?enid=16333" class="entry">this link</a>. Alternatively, we recommend the iCab we browser (available from the apple store) to skewer the enemies of freedom.</p>
		<br />
		</td>
	</tr>
<?php
if($message!="")
{
?>
	<tr>
		<td colspan=2 align="center"><p class="entry"><?php echo $message; ?></p></td>
	</tr>
<?php
}
?>
	<form action="upload.php" method="post" enctype="multipart/form-data">
	<tr>
		<td align="center" width=50%><p class="entry">Filename: <input type="file" name="file" id="file" /></td>
	</tr>
	<tr>
		<td align="center" colspan=2><br /><input type="submit" name="submit" value="Upload" /></td>
	</tr>
	</form>
</table>
<br />
<table align="center" class="entry">
	<tr>
		<td align="center" colspan=2><h1 class="entry">Your Uploaded Files</h1></td>
	</tr>
	<?php
	//Connect to the files table and grab the user's file paths, then print them
	sql_connect("orentago_forum");
	$r=mysql_query("SELECT id, name, owner_name FROM files where owner_id=".$uid);
	while($row = mysql_fetch_array($r))
	{
		$url="download.php?user=".$row['owner_name']."&filename=".$row['name'];
		$fid=$row['id'];
		$url1=$site_url."/".$url;
	?>
	<tr>
		<td width=75%><a href=<?php echo "\"/".$url."\""; ?> class="entry"><?php echo $url1; ?></a></td>
		<td align="right" width =25% align="right"><a href=<?php echo "\"upload.php?id=".$fid."\""; ?> class="entry" onClick='return confirmDelete();'>Delete</a></td>
	</tr>
	<?php
	}
	?>
</table>
<?php
}
?>
