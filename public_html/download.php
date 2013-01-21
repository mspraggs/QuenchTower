<?php
/*This script retrieves files from outside the document root and either puts them inline or asks the user
to download them. This is because putting a writeable directory (for user uploads and so on) within the document
root is a massive security risk.*/

//First check if the file is specified in the GET variables, and if it isn't, kill the script.
if(!isset($_GET['filename']) || !isset($_GET['user']))
{
	header( 'Location: index.php' );
	die("No file specified.");
}

//Extract the file information from the GET variables.
$path = "../files/"; // change the path to fit your websites document structure
$user=trim($_GET['user']);
$filename=trim($_GET['filename']);
//Construct the path to the files. Files are held for each user in a subdirectory in files named after the user
//e.g. for user "spraggs" and file "test.jpg" the path would be ../files/spraggs/test.jpg
$fullPath = $path.$user."/".$filename;

//Check if any funny business is going on. Look for slashes to suggest that the user might be
//trying to use a relative path. If so, kill the script.
if(strpos($filename,"/")!=false || strpos($user,"/")!=false || !file_exists($fullPath)) die();

//Get some information about the file.
$fsize = filesize($fullPath);
$path_parts = pathinfo($fullPath);
$ext = strtolower($path_parts["extension"]);

//Next, check the extension and write the raw http headers accordingly.
switch ($ext)
{
	//PDFs and Word documents should be treated as attachments.
	case "pdf":
		header("Content-type: application/pdf"); // add here more headers for diff. extensions
		header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
		break;
	case "doc":
		header("Content-type: application/msword"); // add here more headers for diff. extensions
		header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
		break;
	case "docx":
		header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document"); // add here more headers for diff. extensions
		header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
		break;
	//Images should be displayed inline.
	case "jpg":
		header("Content-type: image/jpeg");
		header("Content-Disposition: inline; filename=\"".$path_parts["basename"]."\"");
		break;
	case "jpeg":
		header("Content-type: image/jpeg"); // add here more headers for diff. extensions
		header("Content-Disposition: inline; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
		break;
	case "gif":
		header("Content-type: image/gif"); // add here more headers for diff. extensions
		header("Content-Disposition: inline; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
		break;
	case "png":
		header("Content-type: image/png"); // add here more headers for diff. extensions
		header("Content-Disposition: inline; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
		break;
	//Anything else and we should kill the script, in case we're being pointed to a php file or some
	//malicious code.
	default:
		die();
}
//Put the filesize in the http headers
header("Content-length: $fsize");
header("Cache-control: private"); //use this to open files directly
//Finally, read the file.
readfile($fullPath);

exit;
?>
