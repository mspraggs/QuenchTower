<?php
//PHP script to create the XML for the RSS blog feed.
function date822($timestamp=0)
{
	//Outputs the time in 822 format
	if($timestamp==0) return date("r");
	else return date("r",$timestamp);
}

//Edit the http headers
header('Content-type: text/xml');
//Include the mysql connection function
include_once("../protected/sql_connect.inc.php");
//And connect
sql_connect("orentago_blog");

//Construct mysql query and get the posts
$query="SELECT id, title, entry, date_entered FROM blog ORDER BY date_entered DESC";
$r=mysql_query($query) or die();
//Standard XML/RSS stuff, so I'm told
echo "<?xml version='1.0' encoding='iso-8859-1' ?>";
echo "<rss version='2.0'>";
//Set the xml feed up according to "the standards"
?>
<channel> 
     <title>TQT Blog</title>
     <link>http://orentago.linkpc.net</link> 
     <pubDate><?php echo date822(); ?></pubDate>
     <managingEditor>webmaster@orentago.linkpc.net</managingEditor>
	 <description>The Quench Tower Blog</description>
<?php
//Loop through the query results and write the feed information.
     $i = 0;
     while($row = mysql_fetch_array($r))
       {
          if ($i > 0) echo "</item>"; //if not the first loop, end the last item

           $articleDate = $row['date_entered']; //get the date
		   $id=$row['id']; //and id
           $articleDateRfc822 = date822(strtotime($articleDate)); //and convert the date
           echo "<item>"; //begin item
           echo "<title>"; //title tag
           echo $row['title']; //add the title
           echo "</title>";//close title
           echo "<link>http://orentago.linkpc.net/blog.php?id=".$id."</link>"; //provide a handy link
           echo "<pubDate>";//date of post
           echo $articleDateRfc822;//Stick the date in
           echo "</pubDate>";//Close the date tag
           echo "<author>";//Who wrote it?
           echo "webmaster@orentago.linkpc.net";//Their email address
           echo "</author>"; //close the author tag
           echo "<description>";//Description
           echo htmlspecialchars($row['entry']);//Add the post
           echo "</description>";//close description

           $i++;
     }
//Finish up by closing the item, channel and rss tags.	 
?>
</item>
</channel>
</rss>