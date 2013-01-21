<?php
function shareform($try)
{
//Form for sharing the site with others via email.
?>
<table align="center" class="entry">
	<tr>
		<td colspan=2 align="center"><h1 class="entry">Share This Site</h1></td>
	</tr>
	<tr>
		<td colspan=2><p class="entry">Share this site with a friend. Enter their details below, and your message then hit share to send them an email.</p></td>
	</tr>
	<?php	
	if($try>1)
	{
	?>
	<tr>
		<td colspan=2 align="center"><p class="entry">Please select a different username!</p></td>
	</tr>
	<?php
	}
	?>
	<form action="index.php?action=share" method="post" name="share" onsubmit="return validateForm()">
	<tr>
		<td><p class="entry">Name: </p></td>
		<td><input type="name" name="name" /></td>
	</tr>
	<tr>
		<td><p class="entry">Email Address: </p></td>
		<td><input type="email" name="email" /></td>
	</tr>
	<tr>
		<td><p class="entry">Message: </p></td>
		<td><textarea name="message" cols="100" rows="10"></textarea></td>
	</tr>
	<tr>
		<td colspan=2 align="center"><input type="submit" value="Share" /></td>
	</tr>
</table>
<?php
}
?>
