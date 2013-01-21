<?php
/*Similar idea to the account form, but for logging in. $try
represents the number of attempts the user has made at logging
in. If it's greater than 1, they've got their password wrong.*/
function loginform($try)
{
?>
<table align="center" class="entry">
	<tr>
		<td colspan=2 align="center"><h1 class="entry">Login</h1></td>
	</tr>
	<?php
	if($try>1)
	{
		?>
		<tr>
			<td colspan=2 align="center"><p class="entry">Invalid username or password!</p></td>
		</tr>
		<?php
	}
	?>
	<form action="index.php?action=login" method="post" name="register">
	<tr>
		<td width=50% align="right"><p class="entry">Username: </p></td>
		<td><input type="text" name="uname" /></td>
	</tr>
	<tr>
		<td width=50% align="right"><p class="entry">Password: </p></td>
		<td><input type="password" name="pwd" /></td>
	</tr>
	<tr>
		<td colspan=2 align="center"><input type="submit" value="Login" /></td>
	</tr>
	<tr>
		<td colspan=2 align="center"><p class="entry">Forgotten your password? Click <a href="index.php?action=reset" class="entry">here</a> to reset it.</p></td>
	</tr>
</table>
<?php
}
?>
