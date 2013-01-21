<?php
function regform($try)
{
	//Same sort of thing as for the login and edit forms, but this time
	//for user registration.
?>
<table align="center" class="entry">
	<tr>
		<td colspan=2 align="center"><h1 class="entry">User Registration</h1></td>
	</tr>
	<?php	
	if($try>1)
	{
	?>
	<tr>
		<td colspan=2 align="center"><p class="entry">You need to use either a different username, or you've already registered with that email address!</p></td>
	</tr>
	<?php
	}
	?>
	<form action="index.php?action=register" method="post" name="register" onsubmit="return validateForm()">
	<tr>
		<td width=50% align="right"><p class="entry">Username: </p></td>
		<td><input type="text" name="uname" /></td>
	</tr>
	<tr>
		<td width=50% align="right"><p class="entry">Password: </p></td>
		<td><input type="password" name="pwd" /></td>
	</tr>
	<tr>
		<td width=50% align="right"><p class="entry">Repeat Password: </p></td>
		<td><input type="password" name="pwd2" /></td>
	</tr>
	<tr>
		<td width=50% align="right"><p class="entry">Email Address: </p></td>
		<td><input type="text" name="email" /></td>
	</tr>
	<tr>
		<td width=50% align="right"><p class="entry">First Name: </p></td>
		<td><input type="text" name="fname" /></td>
	</tr>
	<tr>
		<td width=50% align="right"><p class="entry">Last Name: </p></td>
		<td><input type="text" name="lname" /></td>
	</tr>	
	<tr>
		<td width=50% align="center" colspan=2><p class="entry">Notify me when a user replies to a thread I've posted in: 
		<input type="checkbox" name="notify" value="notify" /></td>
	</tr>
	<tr>
		<td colspan=2 align="center"><p class="entry">By registering with the forum you agree to abide by our <a href="index.php?action=tos" class="entry">terms of service</a>.</p></td>
	</tr>
	<tr>
		<td colspan=2 align="center"><input type="submit" value="Register" /></td>
	</tr>
</table>
<?php
}
?>
