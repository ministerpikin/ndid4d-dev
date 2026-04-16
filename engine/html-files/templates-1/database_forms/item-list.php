<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
if( isset( $data["items"] ) && is_array( $data["items"] ) && !empty( $data["items"] ) ){
	$data[ "report_data" ] = $data["items"];
	unset( $data["items"] );
	
	if( isset( $data[ 'table_labels' ] ) && is_array( $data[ 'table_labels' ] ) ){
		$GLOBALS["labels"] = $data[ 'table_labels' ];
	}
	
	if( isset( $data[ 'table_fields' ] ) && is_array( $data[ 'table_fields' ] ) ){
		unset( $data[ 'table_fields' ][ 'data' ] );
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
	
	$data["edit_link"] = 1;
	
	$show_delete_box = 1;
	$show_details = 1;
	$show_edit = 0;
	
	unset( $GLOBALS["fields"]["staff_responsible"] );
	unset( $GLOBALS["fields"]["reference_id"] );
	unset( $GLOBALS["fields"]["reference_table"] );

	if( $show_delete_box ){
		$data["delete_checkbox"] = '<input type="checkbox" name="id[]" value=":::id:::" class="checkbox delete-checkbox" />';
	}
	
	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] . '&todo=view_details&preview_from=1" title="View Details">View Form</a>';

	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] . '&todo=edit_form&preview_from=1" title="View Details">Edit</a>';
	
	if( $show_edit ){
		$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action=' . $data["table"] .'&todo=edit_popup_form" mod="edit-'.md5( $data["table"] ).'" title="Edit">Edit</a>';
	}
	
	$table_class = 'shopping-cart-table';
	
	include dirname( dirname( __FILE__ ) ) . "/globals/table_view.php";

	if( isset( $GLOBALS["labels"] ) )unset( $GLOBALS["labels"] );
	if( isset( $GLOBALS["fields"] ) )unset( $GLOBALS["fields"] );
}
?>
</div>