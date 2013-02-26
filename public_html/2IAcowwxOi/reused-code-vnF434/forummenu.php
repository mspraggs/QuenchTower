<?php
/*Here we create the main banner at the top of the forum, with links
to log in or logout, change our account settings, etc.*/
?>
<div id="fb-root"></div>
<script>
//Facebook like button
	(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<table align="center" class="header">
	<tr>
		<form action="index.php?action=search" method="get" name="search">
		<input type="hidden" name="action" value="search" />
		<td><input class="search" type="text" name="query" value="Search" onclick="this.value='';" /></td>
		</form>
	</tr>
	<tr>
		<td width=60%>
			<a href="index.php" class="htitle"><h1 class="header">The Quench Tower</h1></a>
		</td>
		<td width=40% align="right">
			<?php
				if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']=="Y")
				{
				  $_SESSION['logout_token'] = md5(uniqid(mt_rand(),true));
				?>
					<a href="index.php?action=logout&csrf=<?php echo $_SESSION['logout_token']; ?>" class="header">Logout</a><br />
					<a href="index.php?action=account" class="header">Account Settings</a><br />
					<a href="index.php?action=share" class="header">Share This Site</a><br />
					<a href="upload.php" class="header">Upload Files</a>
				<?php
				}
				else
				{
				?>
					<a href="index.php?action=login" class="header">Login</a><br />
					<a href="index.php?action=register" class="header">Register</a>
				<?php
				}
				?>
		</td>
	</tr>
	<tr>
		<td><h2 class="header">Taking the heat out of revision.</h2></td>
		<td align="right">
		<div class="fb-like" data-href="http://www.facebook.com/pages/The-Quench-Tower/185587221550200" data-layout="button_count" data-send="true" data-width="20" data-show-faces="false" colorscheme="light" data-font="Georgia"></div>
		</td>
	</tr>	
	<tr>
		<td width=100% align="center" colspan=2>
			<div id="countdown" name="countdown"></div>
		</td>
	</tr>
</table>
