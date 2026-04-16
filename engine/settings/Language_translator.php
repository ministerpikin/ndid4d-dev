<?php 
	$language = isset( $country_details[ $key ] ) ? $country_details[ $key ] : '';
	
	/*
	USING GETTEXT
	$locale = setlocale(LC_ALL , $language);
	
	$domain = "messages";
	bindtextdomain($domain, "locale");
	textdomain($domain);
	*/
    
	//PHP CONSTANTS
	if( file_exists( $pagepointer . "locale/" . $language . "/GLOBALS.php" ) ){
		include $pagepointer . "locale/" . $language . "/GLOBALS.php";
        define( strtoupper( 'selected_country_'.$key ) , $country_details[ $key ] );
        putenv("LANG=".$language);
	}else{
        $language = $default_country[ $key ];
        if( file_exists( $pagepointer . "locale/" . $language . "/GLOBALS.php" ) ){
            putenv("LANG=".$language);
            include $pagepointer . "locale/" . $language . "/GLOBALS.php";
            define( strtoupper( 'selected_country_'.$key ) , $default_country[ $key ] );
        }else{
            die("Language Error: Could not detect default language file in " . $pagepointer . "locale/" . $language);
        }
	}
	
	unset( $language );
?>