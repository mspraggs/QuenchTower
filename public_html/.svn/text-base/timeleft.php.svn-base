<?php 
	header('Content-Type: text/xml');
	//Calculate server time offset
	$dateTimeZoneHere = new DateTimeZone(date_default_timezone_get());
	$dateTimeHere = new DateTime("now", $dateTimeZoneHere);
	$offset =  timezone_offset_get($dateTimeZoneHere,$dateTimeHere);

	//Get the current time
	$time=time()-$offset;
	//Set the target time
	$targetTimeStamp=1337637600;
	//Calculate the difference
	$difference=$targetTimeStamp-$time-2*3600;
	
	if($difference<0) $difference = 0;
	
	//Work out how many days, hours etc.
	$days=floor($difference/(3600*24));
	$difference-=$days*3600*24;
	$hours=floor($difference/3600);
	$difference-=$hours*3600;
	$minutes=floor($difference/60);
	$difference-=$minutes*60;
	$seconds=$difference;
	//$hours=$hours-2;
	
	//Return the result
	echo $days." days, ".$hours." hours, ".$minutes." minutes and ".$seconds." seconds";
?>
