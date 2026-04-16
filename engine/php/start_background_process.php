<?php 
	define("PLATFORM", "windows" );
	
	$Command = "background_data_upload_only";
	$args = '';
	if( defined("PLATFORM") && PLATFORM == "linux" ){
	   if( $Command ){
		   //$Command = "php /var/www/html/onebill.northwindproject.com/public_html/engine/php/" . $Command . ".php";
			$Command = "php " . $Command . ".php";
			shell_exec("$Command > /dev/null 2>/dev/null &");
	   }
   }elseif( defined("PLATFORM") && PLATFORM == "mac" ){
	   
   }else{
		if( $Command ){
			$Command = $Command . ".bat";
			pclose(popen("start \"HYELLA\" \"" . $Command . "\" " . escapeshellarg($args) , "r"));
	   }
   }
?>