<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
if( isset( $data["items"] ) && is_array( $data["items"] ) && !empty( $data["items"] ) ){
	$data[ "report_data" ] = $data["items"];
	unset( $data["items"] );
	
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
	
	$params = '';
	if( isset( $data[ "html_replacement_selector" ] ) && $data[ "html_replacement_selector" ] ){
		$params = '&html_replacement_selector=' . $data[ "html_replacement_selector" ];
	}
	
	$data["action_buttons"] = '';
	/*
	if( isset( $data["action"] ) ){
		
		switch( $data["action"] ){
		case 'search_pending_rad':
			$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=upload_file'.$params.'" title="Upload File">Upload File</a>';
			
			if( isset( $GLOBALS["fields"] ) ){
				unset( $GLOBALS["fields"]["samples_id"] );
			}
		break;
		}
		
		switch( $data["action"] ){
		case 'search_pending':
		case 'search_pending_lab':
			//$data["action_buttons"]["collect_samples"] = '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=collect_samples'.$params.'" title="Collect Samples">Collect Samples</a>';
			$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=collect_samples'.$params.'" title="Collect Samples">Collect Samples</a>';
		case 'search_pending_rad':
		//default:
	
			$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=capture_investigation_result'.$params.'" title="Capture Result">Capture Result</a>';
	
		break;
		}
		
	}
	
	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=view_investigation_results" title="View Result">View Result</a>';
	
	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=view_bill" title="View Bill">View Bill</a>';
	
	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=view_consultation_notes" title="View Consultation Notes">View Con.</a>';
	*/
	
	include dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . "/globals/table_view.php";
	
	if( isset( $GLOBALS["labels"] ) )unset( $GLOBALS["labels"] );
	if( isset( $GLOBALS["fields"] ) )unset( $GLOBALS["fields"] );
}
?>
</div>