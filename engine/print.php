<?php 
	if( ! isset( $pagepointer ) )$pagepointer = './';
    if( ! isset( $display_pagepointer ) ) $display_pagepointer = '';
    if( file_exists( $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php" ) ){
		require_once $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php";
		cNwp_app_core::startSession();
	}
	//require_once $pagepointer."settings/Config.php";
	require_once $pagepointer."settings/Setup.php";
	
	$fpagepointer = $pagepointer;
	if( defined("NWP_APP_DIR") && NWP_APP_DIR ){
		$fpagepointer = '../' . NWP_APP_DIR . '/';
	}

    if( isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] ){
        $s = explode("/", $_SERVER['REQUEST_URI'] );
        $pr = get_project_data();
        foreach( $s as $ss ){
            if( $ss && ! strrchr( $ss, '?' ) ){
                //$display_pagepointer = $pr['domain_name'] . 'engine/';
                $display_pagepointer = $pr['domain_name'];
                break;
            }
        }
    }
    
	$stream = 0;
	if( isset( $_GET["durl"] ) && $_GET["durl"] ){
		if( isset( $_GET["id"] ) && $_GET["id"] && isset( $_GET["hash"] ) && $_GET["hash"] ){
			$na = array( "hash" => 1, "file_id" => $_GET["id"], "date_filter" => 'd-M-Y' );
			if( isset( $_GET["ns"] ) && $_GET["ns"] ){
				$na["no_session"] = 1;
			}
			if( get_file_hash( $na ) == $_GET["hash"] ){
				$img["name"] = ( isset( $_GET["name"] ) && $_GET["name"] )?rawurldecode( $_GET["name"] ):'download';
				$img["src"] = get_file_hash( array( "decrypt" => $_GET["durl"], "key" => $_GET["hash"] ) );
				
				$stream = 2;
			}else{
				echo 'Access Violation: '; exit;
			}
		}else{
			echo 'Authorization Denied'; exit;
		}
	}
	
	if( isset( $_GET["img_src"] ) && $_GET["img_src"] ){
		$stream = 1;
		$settings = array(
			'cache_key' => "image-" . $_GET["img_src"],
			'directory_name' => 'images',
			'permanent' => true,
		);
		$img = get_cache_for_special_values( $settings );
		clear_cache_for_special_values( $settings );
	}
	if( $stream ){
		if( isset( $img["src"] ) && isset( $img["name"] ) ){
			$finfo = pathinfo( $pr["domain_name"] . $img["src"] );
			//print_r( pathinfo( $pr["domain_name"] . $img["src"] ) ); exit;
			header_remove();
			
			//echo $fpagepointer . $img["src"]; exit;
			if( $stream == 2 ){
				$app = 'application/';
				
				switch( strtolower( $finfo[ 'extension' ] ) ){
				case "jpg":
				case "jpeg":
				case "png":
				case "gif":
				case "svg":
					$app = 'image/';
				break;
				}
				
				header('Content-Type: ' . $app . $finfo[ 'extension' ] );	//display in browser
				header('Content-Disposition: inline; filename="'.( $img["name"] ).'.'. $finfo[ 'extension' ] .'"');
				//header('Content-Transfer-Encoding: binary');
				header('Accept-Ranges: bytes');
			}else{
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');	//download
				header('Content-Disposition: attachment; filename="'.( $img["name"] ).'.'. $finfo[ 'extension' ] .'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
			}
			
			header('Pragma: public');
			header('Content-Length: ' . filesize( $fpagepointer . $img["src"] ));
			readfile( $fpagepointer . $img["src"] );
			exit;
		}
		echo 'Access Denied';
		exit;
	}else if( isset( $_GET["download_file"] ) && $_GET["download_file"] ){
		if( isset( $_SESSION["download"][ $_GET["download_file"] ] ) ){
				
			if( class_exists("Imagick") ){
				$pdf = new Imagick();
				$pdf->readImage(realpath( $_SESSION["download"][ $_GET["download_file"] ] ));
				$pdf->setImageFormat('pdf');
				
				if( file_exists( realpath( $_SESSION["download"][ $_GET["download_file"] ] ) . ".pdf" ) ){
					unlink( realpath( $_SESSION["download"][ $_GET["download_file"] ] ) . ".pdf" );
				}
				
				$finfo = pathinfo( $pr["domain_name"] . $img["src"] );
				
				$pdf->writeImage( $fpagepointer . $_SESSION["download"][ $_GET["download_file"] ] . ".pdf" );
				//echo $pagepointer . $_SESSION["download"][ $_GET["download_file"] ]; exit;
				//$pdf->writeImage( realpath( $_SESSION["download"][ $_GET["download_file"] ] ) . ".pdf" );

				//header('Content-type: application/pdf');
				$fname = "nipc-business-registration-certificate";
				if( isset( $_SESSION["download_name"][ $_GET["download_file"] ] ) && $_SESSION["download_name"][ $_GET["download_file"] ] ){
					$fname = $_SESSION["download_name"][ $_GET["download_file"] ];
				}
				
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'. $fname .'.pdf"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize( realpath( $_SESSION["download"][ $_GET["download_file"] ] ) . ".pdf" ));
				readfile( realpath( $_SESSION["download"][ $_GET["download_file"] ] ) . ".pdf" );
				
				unlink( realpath( $_SESSION["download"][ $_GET["download_file"] ] ) . ".pdf" );
				exit;
				echo $pdf;
			}else{
				echo 'Imagick not installed: ' . $_SESSION["download"][ $_GET["download_file"] ]; exit;
			}
			exit; 
		}
	}
	interprete_website_page_url();
	
	$return_website_object = true;
	$skip_required_files = true;
	require_once "php/ajax_request_processing_script.php";
	
	// print_r( json_decode( $website_object, true ) );exit;
	$app = json_decode( $website_object );
?>	
    <!DOCTYPE html>
	<html>
	<head>
	<style id="pagepointer"><?php if( $display_pagepointer )echo $display_pagepointer; else echo $pagepointer; ?></style>
	
<?php	
	if( isset( $app->stylesheet ) )
		echo $app->stylesheet;
		
	if( isset( $app->html_head_tag ) )
		echo $app->html_head_tag;
?>	
	</head>
	<body class="<?php if( isset( $app->action_performed ) ) echo $app->action_performed; ?>"><div class="page-wrapper overflow-x-none " id="wrapper">
<?php	
	if( isset( $app->html_markup ) )
		echo $app->html_markup;
	
	if( isset( $app->html_header ) )
		echo $app->html_header;
	
	if( isset( $app->msg ) && $app->msg )	
		echo $app->msg;
		
	if( isset( $app->html ) )	
		echo $app->html;
		
	if( isset( $app->html_footer ) )	
		echo $app->html_footer;
?>	
	</div>
<?php
	if( isset( $app->javascript ) ){
		echo $app->javascript;
	}
?>
	<script type="text/javascript">
	<?php if( defined("NWP_ENDPOINT") && NWP_ENDPOINT ){ ?>
			$.fn.cProcessFormUrl.customURL = 1;
			$.fn.cProcessFormUrl.requestURL = document.location.origin + "<?php echo NWP_ENDPOINT; ?>";
	<?php } ?>
	</script>
	</body>
	</html>