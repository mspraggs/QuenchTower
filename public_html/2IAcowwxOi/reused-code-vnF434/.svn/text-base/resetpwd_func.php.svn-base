<?php
function resetpwd($try)
{
	//Same thing as the accountform.php, but for password resets.
?>
<table align="center" class="entry">
	<tr>
		<td colspan=2 align="center"><h1 class="entry">Reset Password</h1></td>
	</tr>
	<?php
	if($try>1)
	{
	?>
	<tr>
		<td colspan=2 align="center"><p class="entry">Username and email do not match!</p></td>
	</tr>
	<?php
	}
	?>
	<form action="index.php?action=reset" method="post" name="register" onsubmit="return validateForm()">
	<tr>
		<td width=50% align="right"><p class="entry">Username: </p></td>
		<td><input type="text" name="uname" /></td>
	</tr>
	<tr>
		<td width=50% align="right"><p class="entry">Email: </p></td>
		<td><input type="text" name="email" /></td>
	</tr>
	<tr>
		<td colspan=2 align="center"><input type="submit" value="Submit" /></td>
	</tr>
</table>
<?php
}
?>
