<!--This is the main blog page. It includes blogpage.php-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html>
<head>
<title>Blog - The Quench Tower</title>
<link rel="stylesheet" type="text/css" href="mystyle.css" />
<script type="text/javascript">
//Google's stuff again
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
<!--Add a nice header-->
<table align="center" class="header">
	<tr>
		<td>
			<a href="blog.php" class="htitle"><h1 class="header">TQT Blog</h1></a>
		</td>
		<td>
			<div align="right"><a href="/admin/blog_admin" class="header">Edit Blog</a></div>
		</td>
	</tr>
	<tr>
		<td colspan="2"><h2 class="header">The blog of the forum.</h3></td>
	</tr>
</table>
<?php
//Turn of magic quotes again, then include the blog posts
include("2IAcowwxOi/reused-code-vnF434/mqoff.php");
include("2IAcowwxOi/blogpage.php"); ?>
</body>
</html>