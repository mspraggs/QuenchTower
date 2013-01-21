<?php
function emailform($email)
{
//Form for sharing the site with others via email.
?>
<table align="center" class="entry">
	<tr>
		<td colspan=2 align="center"><h1 class="entry">Email User</h1></td>
	</tr>
	<tr>
		<td colspan=2><p class="entry">Use this form to email a specific user.</p></td>
	</tr>
	<form action="index.php?action=email" method="post" name="share" onsubmit="return validateForm()">
	<tr>
		<td><p class="entry">Subject: </p></td>
		<td><input type="text" name="subject" /></td>
	</tr>
	<tr>
		<td><input type="hidden" name="email" value=<?php echo "\"$email\""; ?> /></td>
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
