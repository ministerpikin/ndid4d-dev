<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<div id="report-preview-container-id">
<?php
	$error_title = '';
	$title = '';
	$subtitle = '';
	$show_print = 1;
	if( isset( $data["show_print"] ) ){
		$show_print = $data["show_print"];
	}
	
	$hash_key = isset( $data[ 'report_reference_key' ] ) ? $data[ 'report_reference_key' ] : '';

	$mobile = isset( $data[ 'mobile' ] )?$data[ 'mobile' ]:0;

	if( $mobile ){
		$show_print = 0;
	}
	
	if( isset( $data["report_type"] ) && $data["report_type"] ){
		
		if( isset( $data[ 'table_labels' ] ) && is_array( $data[ 'table_labels' ] ) ){
			$GLOBALS["labels"] = $data[ 'table_labels' ];
		}
		
		if( isset( $data[ 'table_fields' ] ) && is_array( $data[ 'table_fields' ] ) ){
			$GLOBALS["fields"] = $data[ 'table_fields' ];
		}
		
		$title = "";
		if( isset( $data["report_title"] ) )$title = $data["report_title"];
		
		$subtitle = "";
		if( isset( $data["report_subtitle"] ) )$subtitle = $data["report_subtitle"];
		
		$stores = array();
		$store_title = '';
		$selected = '';
		$active_store = '';
		if( ! get_single_store_settings() ){
			$stores = get_stores();
			if( isset( $data[ 'store' ] ) && isset( $stores[ $data[ 'store' ] ] ) ){
				$selected = $data[ 'store' ];
				$store_title = ' - ' . $stores[ $data[ 'store' ] ];
				$active_store = $selected;
			}
		}
		
		$start_time = "";
		$time_filter = '';
		$end_time = "";
		if( isset( $data[ 'start_time' ] ) && $data[ 'start_time' ] && isset( $data[ 'end_time' ] ) && $data[ 'end_time' ] ){
			$time_filter = ' H:i';
			$start_time = format_time( $data[ 'start_time' ] , 1 );
			$end_time = format_time( $data[ 'end_time' ] , 1 );
			$subtitle .= "<br /><br />From: <strong>" . $data[ 'start_time' ] . "</strong> To <strong>" . $data[ 'end_time' ] . "</strong>";
		}
		
		if( $show_print ){
			
			$hidden_fields = array(
				array(
					"name" => "reference",
					"value" => $hash_key,
				),
				array(
					"name" => "reference_table",
					"value" => $data["report_type"],
				)
			);
			echo '<div id="report-preview-container-id">';
			echo get_export_and_print_popup( ".table" , "#quick-print-container", "", 1, array( "share" => 1, "save" => 1, "copy" => 1, "send_mail" => 1, "prompt" => 'Recipient(s) Email Address (seperate multiple emails with a comma)', "emails" => '', "hidden_fields" => $hidden_fields, "subject" => $title ) ) . "</div><br /><br />";
		}
		
		echo '<div id="quick-print-container">';
		if( file_exists( dirname( __FILE__ ).'/'.$data["report_type"].'.php' ) ){
			
			if( isset( $data[ "report_data" ] ) && is_array( $data[ "report_data" ] ) && ! empty( $data[ "report_data" ] ) ){
				include $data["report_type"] . ".php";
			}else{
				echo '<div class="note note-danger"><p>No Data Found</p></div>';
			}
			
		}else{
			$error_title = 'Missing Report File';
		}

		if( $show_print )echo '</div>';
		
		if( isset( $GLOBALS["labels"] ) )unset( $GLOBALS["labels"] );
		if( isset( $GLOBALS["fields"] ) )unset( $GLOBALS["fields"] );
		
	}else{
		$error_title = 'Report Type Not Specified';
	}
	
	if( $error_title ){
		echo $error_title;
	}
?>
</div>
<script type="text/javascript" class="auto-remove">
	<?php if( $show_print && file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>