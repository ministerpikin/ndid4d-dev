<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gona.com</title>
	
	<?php
		//INCLUDE CSS FILES
		$device_theme = '';	//remove later
		if(isset($css[$page_id]) && is_array(($css[$page_id]))){
			$my_css = $css[$page_id];
			foreach($my_css as $require){
				if(file_exists($pagepointer.'css/'.$require.'.css')){
					//If Browser Specific File
					if(strpos( $require , '-o') || strpos( $require , '-ie')){
						if(strpos( $require , '-ie')){
							echo '<!--[if IE]>';
								echo '<link href="'.$pagepointer.'css/'.$require.'.css" rel="stylesheet" type="text/css" />';
							echo '<![endif]-->';
						}elseif(strpos( $require , '-o')){
							//CHECK FOR DESKTOP LAYOUT
							if(isset($device_theme) && $device_theme=='mobile'){
								echo '<!--[if !IE]><!-->';
									echo '<link href="'.$pagepointer.'css/'.$require.'-'.$device_theme.'.css" rel="stylesheet" type="text/css" />';
								echo '<!--<![endif]-->';
							}else{
								echo '<!--[if !IE]><!-->';
									echo '<link href="'.$pagepointer.'css/'.$require.'.css" rel="stylesheet" type="text/css" />';
								echo '<!--<![endif]-->';
							}
						}
					}else{
						//CHECK FOR DESKTOP LAYOUT
						if(isset($device_theme) && $device_theme=='mobile'){
							echo '<link href="'.$pagepointer.'css/'.$require.'-'.$device_theme.'.css" rel="stylesheet" type="text/css" />';
						}else{
							echo '<link href="'.$pagepointer.'css/'.$require.'.css" rel="stylesheet" type="text/css" />';
						}
					}
				}
			}
		}
	?>
	
	<link rel="shortcut icon" href="<?php echo $pagepointer; ?>favicon.ico">
    <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">-->
	
</head>