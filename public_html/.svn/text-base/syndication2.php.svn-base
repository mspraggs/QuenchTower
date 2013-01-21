<?php
//Basically the same as syndication.php, except for the whole forum
function date822($timestamp=0)
{
	if($timestamp==0) return date("r");
	else return date("r",$timestamp);
}


header('Content-type: text/xml');
include_once("../protected/sql_connect.inc.php");
sql_connect("orentago_forum");

$query="SELECT thread_title, thread_id, entry, date_entered, user FROM posts ORDER BY date_entered DESC";
$r=mysql_query($query) or die();
echo "<?xml version='1.0' encoding='iso-8859-1' ?>";
echo "<rss version='2.0'>";
?>
<channel> 
     <title>The Quench Tower</title>
     <link>http://orentago.linkpc.net</link> 
     <pubDate><?php echo date822(); ?></pubDate>
     <managingEditor>webmaster@orentago.linkpc.net</managingEditor>
	 <description>The Quench Tower</description>
<?php
     $i = 0;
     while($row = mysql_fetch_array($r))
       {
          if ($i > 0) echo "</item>";

           $articleDate = $row['date_entered'];
		   $uname=stripslashes($row['user']);
		   $thread_id=$row['thread_id'];
           $articleDateRfc822 = date822(strtotime($articleDate));
           echo "<item>";
           echo "<title>";
           echo stripslashes($row['thread_title']);
           echo "</title>";
		   //Need a CDATA escape thingy here since XML doesn't like ampersands
           echo "<link><![CDATA[http://orentago.linkpc.net/index.php?action=thread&id=".$thread_id."]]></link>";
           echo "<pubDate>";
           echo $articleDateRfc822;
           echo "</pubDate>";
           echo "<description>";
		   $description=$uname." wrote: ".stripslashes($row['entry']);
           echo $description;
           echo "</description>";

           $i++;
     }			
?>
</item>
</channel>
</rss>