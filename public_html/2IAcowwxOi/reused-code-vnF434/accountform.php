<?php
/*Function to create the user account form to let
 the user change their password and notification option*/
function chpwdform($try, $notify)
{
/*$try is the number of attempts the user has made to submit the form
If it's their second attempt, it will be because their old password
is incorrect, so we tell them. $notify is the flag corresponding to the
user's notification preference. We use it to clear or check the checkbox
on the form.*/
?>
<table align="center" class="entry">
	<tr>
		<td colspan=2 align="center"><h1 class="entry">Account</h1></td>
	</tr>
	<form action="index.php?action=account&csrf=<?php echo $_SESSION['token']; ?>" method="post" name="account1" onsubmit="return validateForm()">
	<?php
	if($try>1)
	{
		?>
		<tr>
			<td colspan=2 align="center"><p class="entry">Incorrect old password!</p></td>
		</tr>
		<?php
	}
	?>
	<tr>
		<td width=50% align="right"><p class="entry">Old Password: </p></td>
		<td><input type="password" name="pwd" /></td>
	</tr>
	<tr>
		<td width=50% align="right"><p class="entry">New Password: </p></td>
		<td><input type="password" name="pwd2" /></td>
	</tr>
	<tr>
		<td width=50% align="right"><p class="entry">Repeat New Password: </p></td>
		<td><input type="password" name="pwd3" /></td>
	</tr>
	<tr>
		<td colspan=2 align="center"><input type="submit" value="Change Password" /></td>
	</tr>
	</form>
	<form action="index.php?action=account&csrf=<?php echo $_SESSION['token']; ?>" method="post" name="account2">
	<input type="hidden" name="contact" value="Y" />
	<tr>
		<td align="center" colspan=2><p class="entry">Notify me when a user replies to a thread I've posted in: 
		<input type="checkbox" name="notify" value="notify"<?php if($notify=="Y") echo " checked"; ?> /></td>
	</tr>
	<tr>
		<td colspan=2 align="center"><input type="submit" value="Update Contact Details" /></td>
	</tr>
	</form>
</table>
<?php
}
?>
