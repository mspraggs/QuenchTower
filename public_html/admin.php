<!--Just a basic page while this page is being made-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html>
<head>
<title>The Quench Tower</title>
<link rel="stylesheet" type="text/css" href="mystyle.css" />
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29596672-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
<?php
include("2IAcowwxOi/reused-code-vnF434/navimenu.php"); //We'll  need this in a moment
?>
<!--Make another header-->
<table align="center" class="header">
	<tr>
		<td>
			<h1 class="header">The Quench Tower</h1>
		</td>
	</tr>
</table>
<?php
//Show the forum menu
navimenu(1,"","N");
//Include the html message file, then output a message
include_once("2IAcowwxOi/reused-code-vnF434/message.php");
htmlmessage("Under Construction","This page is still under construction. Best check back here later.");
?>
</body>
</html>
