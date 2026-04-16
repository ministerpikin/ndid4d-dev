<html>
<head>
<?php 
	$ckey = 'stylesheet';
	if( isset( $data['website_data'][ $ckey ] ) ){
		//print_r( $data['website_data'][ $ckey ] ); exit;
		echo $data['website_data'][ $ckey ];
	}
	
	$pr = get_project_data();
	echo '<div id="pagepointer" style="display:none;">'. $pr["domain_name"] .'</div>';
	$ckey = 'javascript';
	if( isset( $data['website_data'][ $ckey ] ) ){
		echo $data['website_data'][ $ckey ];
	}
?>
</head>
<body style="background:#fdfdfd !important; margin:15px !important;">
<?php
	//print_r( $user_info );
	//print_r( $data['preset_header'] );
	//print_r( $data['preset_header'] );

	$ckey = 'html_markup';
	if( isset( $data['website_data'][ $ckey ] ) ){
		echo $data['website_data'][ $ckey ];
	}

?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$ckey = 'more_params';
	$ckey2 = 'application_name';
	if( isset( $data[ $ckey ][ $ckey2 ] ) && $data[ $ckey ][ $ckey2 ] ){
		echo '<div style="text-align:center;">' . $data[ $ckey ][ $ckey2 ] . '<h1 style="margin-top:0;">'. ( isset( $data[ $ckey ][ 'widget_title' ] )?$data[ $ckey ][ 'widget_title' ]:'' ) .'</h1>';
		
		if( isset( $data[ $ckey ][ "widget_description" ] ) && $data[ $ckey ][ "widget_description" ] ){
			echo '<p>' . $data[ $ckey ][ "widget_description" ] . '</p>';
		}
		
		echo '</div>';
	}
	
	$ckey = 'content';
	$ckey2 = 'html_replacement';
	if( isset( $data[ $ckey ][ $ckey2 ] ) ){
		echo $data[ $ckey ][ $ckey2 ];
	}
	?>
	<script type="text/javascript">
		$(document).ready(function(){
			App.init(); // initlayout and core plugins
			Index.init();
			$.fn.cProcessForm.api = 1;
			$.fn.cProcessForm.activateAjaxRequestButton();
			$.fn.cProcessForm.activateDevelopmentMode();
			<?php
				$ckey = 'content';
				$ckey2 = 'javascript_functions';
				if( isset( $data[ $ckey ][ $ckey2 ] ) && is_array( $data[ $ckey ][ $ckey2 ] ) && !empty( $data[ $ckey ][ $ckey2 ] ) ){
					foreach( $data[ $ckey ][ $ckey2 ] as $jk ){
						echo $jk . '();';
					}
				}
			?>
		});
	</script>
</body>
</html>