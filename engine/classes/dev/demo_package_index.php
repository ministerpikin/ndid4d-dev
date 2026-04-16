<?php
	if( file_exists( dirname( __FILE__ ).'/config.json' ) ){
		$package_config = json_decode( file_get_contents( ( dirname( __FILE__ ).'/config.json' ) ), true );
	}
	
	if( isset( $package_config["classes"] ) && is_array( $package_config["classes"] ) && ! empty( $package_config["classes"] ) ){
		foreach( $package_config["classes"] as $cls ){
			//$classes[ 'ajax_request_processing_script' ][] = "package/catholic/cParish_zones";
			$classes[ 'ajax_request_processing_script' ][] = $cls;
		}
		
		//load only plugins that are called
		if( isset( $_GET['plugin'] ) && $_GET['plugin'] ){
			if( isset( $package_config["plugins_list"][ $_GET['plugin'] ] ) ){
				$package_config["plugins_loaded"] = $package_config["plugins_list"][ $_GET['plugin'] ];
				unset( $package_config["plugins_list"] );
			}
		}
	}
?>