<!--A nice little equation editor powered by code cogs, so that 
users can test latex before sticking it in posts-->
<html>
<head>
<title>Equation Editor</title>
<link rel="stylesheet" type="text/css"
  href="http://latex.codecogs.com/css/equation-embed.css" />
<!--[if lte IE 7]>
<link rel="stylesheet" href="http://latex.codecogs.com/css/ie6.css" type="text/css"/>
<![endif]-->
<script type="text/javascript" 
  src="http://latex.codecogs.com/js/eq_config.js" ></script>
<script type="text/javascript" 
  src="http://latex.codecogs.com/js/eq_editor-lite-11.js" ></script>

<link rel="stylesheet" type="text/css" href="mystyle.css" />
<script type="text/javascript">
//Google again
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
<!--Table into which the editor is put, via some javascript from their site.-->
<table align="center" class="entry">
<tr>
<td align="center"><h1 class="entry">Equation Editor</h1></td>
</tr>
<tr>
<td>
<p class="entry">Use this equation editor to construct equations, then export them to the clipboard as LaTeX expressions. 
These expressions can then be pasted into your post. Paste the expression between the appropriate math tags in the post edit window.</p>
<br />
</td>
</tr>
<tr>
<td align="center">
<div id="editor"></div>
<textarea id="testbox" rows="10" cols="70"></textarea>
<br />
<img id="equation" align="middle" />

<script type="text/javascript">
  EqEditor.embed('editor')
  var a=new EqTextArea('equation', 'testbox');
  EqEditor.add(a,false);
</script>
</td>
</tr>
<tr>
<td align="center">
<p class="entry">Powered by Code Cogs.</p>
</td>
</tr>
</table>
</body>
