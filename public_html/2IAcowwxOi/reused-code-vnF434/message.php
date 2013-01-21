<?php
//Little script to print a message to the user in appropriately
//formatted html. This is used everywhere!
function htmlmessage($title,$message)
{
?>
<table align="center" class="entry">
	<tr>
		<td align="center">
			<h1 class="entry"><?php echo $title; ?></h1>
		</td>
	</tr>
	<tr>
		<td>
			<p class="entry"><?php echo $message; ?></p>
		</td>
	</tr>
</table>
<?php
}
?>
