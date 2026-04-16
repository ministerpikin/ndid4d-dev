<?php

    require_once __DIR__ . '/../classes/vendor/autoload.php';

    $pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';

    if( ! class_exists("cNwp_app_core") ){
        require_once $pagepointer . "/plugins/nwp_app_core/cNwp_app_core.php";
    }else{
        exit("Missing Necessary Class");
    }
	
	$path = 'data.json';
	$inputData = json_decode( file_get_contents( $path ), 1 );
	$_GET = $inputData["data"]["get"];
	$_POST = $inputData["data"]["post"];

	$num = 300;
	for ( $i=0; $i < $num ; $i++ ) {
		echo "\n=============================================\n";
		echo $i . "\n";
		$result = json_decode(
			cNwp_app_core::app( [
				'pagepointer' => $pagepointer, 
				'loggedIn' => true, 
				'defaultUser' => 'test',
				'doNotLoadSession' => 1, 
				'returnObject' => true, 
				'openConnection' => true,
				'skip_headers' => 1,
			] 
		), 1);
		print_r( $result['msg'] );  
		echo "=============================================\n";
	}

?>