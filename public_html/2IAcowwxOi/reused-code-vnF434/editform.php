<?php
/*This function creates the post edit form, and can be used for 
 post edits, new posts and replies. The switch $action specifies
 which one of these we're going for. $entry and $id contain the body
 of the post (in case we're editing an existing one) and the thread
 id. We add a row of buttons and associate them with actions to edit
 tags and upload files etc, then add text boxes to add a post title
 and body.*/
function editform($entry, $id, $action)
{
  //Check what we need to do and set up the target url
  $_SESSION['token'] = md5(uniqid(mt_rand(),true));
  $form_action="\"index.php?action=".$action."&csrf=".$_SESSION['token']."\"";
	//Need to set up the forum action based on what the user's
	//going to put in the form (more detail on this below).
	if($action=="new")
	{
		$onsubmit="onSubmit=\"return validateForm(2)\"";
	}
	else
	{
		$onsubmit="onSubmit=\"return validateForm(1)\"";
	}
	?>
	
	<script type="text/javascript"> 
	//Little function that allows us to insert tags and so on where the cursor is
	//in the entry edit field. Using some javascript magic I found on the net.

	function insertatcursor(myField, myValue)
	{
		//myField - the field we're adding text to
		//myValue - the stuff we're adding
		if (document.selection)
		{ 
			myField.focus(); 
			sel = document.selection.createRange(); 
			sel.text = myValue; 
		} 
		else if (myField.selectionStart || myField.selectionStart == 0 || myField.selectionStart == '0')
		{ 
			var startPos = myField.selectionStart; 
			var endPos = myField.selectionEnd; 
			myField.value = myField.value.substring(0,startPos) + myValue + myField.value.substring(endPos, myField.value.length); 
		} 
		else { 
			myField.value += myValue; 
		} 
	}

	//And some form validation stuff
	function validateForm(type)
	{
		//Get the entry
		var entry=document.forms["postedit"]["entry"].value;
		
		if(entry==null || entry=="")
		{
			alert("Please complete the body of your post.");
			return false;
		}
		//If this is a new  post, we need to check they've entered
		//a post title.
		if(type==2)
		{
			var title=document.forms["postedit"]["title"].value;
			if(title==null || title=="")
			{
				alert("Please enter a title.");
				return false;
			}
		}
		return true;
	}

	</script>

	<table align="center" class="entry">
		<tr>
			<td colspan=2>
				<button class="entry" onClick="window.open('eqed.php','','width=700,height=550,status=1,scrollbars=yes,resizable=1');">Launch Equation Editor</button>
				<button onclick="insertatcursor(document.forms['postedit']['entry'],'[math][/math]')">Insert Equation</button>
				<button onclick="insertatcursor(document.forms['postedit']['entry'],'[imath][/imath]')">Insert Inline Equation</button>
				<button onclick="insertatcursor(document.forms['postedit']['entry'],'[img][/img]')">Insert Image</button>
				<button onclick="insertatcursor(document.forms['postedit']['entry'],'[url][/url]')">Insert Hyperlink</button>
				<button onclick="window.open('upload.php','','width=700,height=550,status=1,scrollbars=yes,resizable=1');">Add/Upload Files</button>
			</td>
		</tr>
		<tr>
			<td colspan=2>
			<p class="entry">We would prefer it if you could host photos on other sites, such as photobucket or flickr, where possible, to reduce server load.</p>
			</td>
		</tr>
		<form name="postedit" action=<?php echo $form_action." ".$onsubmit; ?> method="POST">
		<input type="hidden" name="id" value=<?php echo $id; ?> />
		<?php
		if($action=="new")
		{
			?>
			<tr>
				<td><p class="entry">Title: </p></td>
				<td><input type="text" name="title" size="40" maxsize="100" /></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td><p class="entry">Text: </p></td>
			<td><textarea name="entry" cols="100" rows="10"><?php if($action=="edit") echo $entry; ?></textarea></td>
		</tr>
		<tr>
			<td colspan=2><input type="submit" name="submit" value="Post" /></td>
		</tr>
	</table>
	</form>
	<?php
}
?>
