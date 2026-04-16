<?php 
	$pagepointer = '../';
    $display_pagepointer = '';
	$cache_dir = 'green/';
	
	//require_once $pagepointer . "settings/Setup.php";
	$r = array( "error" => 'Invalid Request' );
	define("PLATFORM", "windows");
	
	if( isset( $_GET["message"] ) && $_GET["message"] && isset( $_GET["app"] ) && $_GET["app"] && isset( $_GET["key"] ) && $_GET["key"] ){
		$url = '';
		$r = array( "error" => 'Invalid Application' );
		$mkey = $_GET["key"];
		
		switch( $_GET["app"] ){
		case '1':
			$sender_email = 'info@floramicheals.com';
			$admin_email = 'info@floramicheals.com';
			$sender_fullname = 'FLORA MICHAELS BLOG';
			$project_title = 'FLORA MICHAELS BLOG';
			
			$sender_email = 'info@fortunaiuvat.com';
			$admin_email = 'info@fortunaiuvat.com';
			$sender_fullname = 'FORTUNA IUVAT';
			$project_title = 'FORTUNA IUVAT';
			$url = 'http://localhost:819/feyi/engine/tmp/filescache/'.$cache_dir.'newsletter_message/newsletter_message-q-' . $_GET["message"] . '.json';
		break;
		}
		$message_key = $mkey;
		
		if( isset( $_GET["send"] ) && $_GET["send"] ){
			if( $url ){
				$r1 = json_decode( file_get_contents( $url ) , true );
				if( isset( $r1[ $mkey ] ) && is_array( $r1[ $mkey ] ) ){
					$r = $r1[ $mkey ];
					$data = array(
						"id",
						"subject",
						"message",
						"sending_channel",
						"e",
						"p",
						"mailing_name",
						"mailing_list"
					);
					
					$s = 0;
					foreach( $data as $v ){
						if( ! ( isset( $r[ $v ] ) && $r[ $v ] ) ){
							$r = array( "error" => 'Unable to Retrieve Newsletter Info: '.$v );
							$s = 1;
							break;
						}
					}
					
					if( ! $s ){
						if( file_exists( 'mail-status/' . 'queue.json' ) ){
							$queue = json_decode( file_get_contents( 'mail-status/' . 'queue.json' ) , true );
							if( is_array( $queue ) ){
								$queue[] = $message_key;
							}else{
								$queue = array( $message_key );
							}
						}else{
							$queue = array( $message_key );
						}
						
						$r["sender_email"] = $sender_email;
						$r["sender_fullname"] = $sender_fullname;
						$r["admin_email"] = $admin_email;
						$r["project_title"] = $project_title;
						
						file_put_contents( 'mail-status/' . $message_key . '.json', json_encode( $r ) );
						file_put_contents( 'mail-status/' . 'queue.json', json_encode( $queue ) );
						
						_run_in_background( 'send' );
					}
				
				}
			}
		}
		
		if( isset( $_GET["cancel"] ) && $_GET["cancel"] ){
			$state = array();
			if( file_exists( 'mail-status/' . $message_key . '-status.json' ) ){
				$state = json_decode( file_get_contents( 'mail-status/' . $message_key . '-status.json' ), true );
			}
			
			$state["cancelled"] = date("U");
			$state["last_sent"] = date("U");
			file_put_contents( 'mail-status/' . $message_key . '-status.json', json_encode( $state ) );
			
			echo json_encode( $state );
			exit;
		}
		
		if( isset( $_GET["check"] ) && $_GET["check"] ){
			$state = array();
			if( file_exists( 'mail-status/' . $message_key . '-status.json' ) ){
				$state = json_decode( file_get_contents( 'mail-status/' . $message_key . '-status.json' ), true );
				echo json_encode( $state );
				exit;
			}
		}
	}
	
	$json = json_encode( $r );
	echo $json;
	exit;
	
	function _run_in_background( $Command = "", $Priority = 0 ){
		//$Command = "php load_newsletters.php";
	   if( defined("PLATFORM") && PLATFORM == "linux" ){
		   if( $Command ){
				$Command = "php " . $Command . ".php";
				shell_exec("$Command > /dev/null 2>/dev/null &");
		   }
	   }else{
			if( $Command ){
				$Command = $Command . ".bat";
				$args = "";
				pclose(popen("start \"HYELLA\" \"" . $Command . "\" " . escapeshellarg($args), "r"));
		   }
	   }
	}
?>