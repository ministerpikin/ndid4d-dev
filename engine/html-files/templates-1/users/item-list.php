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
	$selected_parish = isset( $hidden_field["reference"] )?$hidden_field["reference"]:'';
	
	if( ! $selected_parish ){
		unset( $GLOBALS["fields"]["parish"] );
	}
	$data["action_buttons"] = '';
	
	$data["edit_link"] = 1;
	
	$show_delete_box = 1;
	$show_details = 1;
	$show_edit = 0;
	
	unset( $GLOBALS["fields"]["store_options"] );
	
	if( $show_delete_box ){
		$data["delete_checkbox"] = '<input type="checkbox" name="id[]" value=":::id:::" class="checkbox delete-checkbox" />';
	}
	
	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=view_details" title="View Details">View Details</a>';
	
	if( $show_edit ){
		$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=edit_popup_form" mod="edit-'.md5( $data["table"] ).'" title="Edit">Edit</a>';
	}
	
	if( isset( $data["action"] ) ){
		
		switch( $data["action"] ){
		case 'search_list2':
			
		break;
		}
		
	}
	
	unset( $GLOBALS["fields"]["password"] );
	unset( $GLOBALS["fields"]["confirmpassword"] );
	unset( $GLOBALS["fields"]["oldpassword"] );
	unset( $GLOBALS["fields"]["category"] );
	unset( $GLOBALS["fields"]["type"] );
	unset( $GLOBALS["fields"]["rank"] );
	unset( $GLOBALS["fields"]["rank_text"] );
	unset( $GLOBALS["fields"]["date_of_birth"] );
	unset( $GLOBALS["fields"]["means_of_identification"] );
	unset( $GLOBALS["fields"]["address"] );
	unset( $GLOBALS["fields"]["other_identification"] );
	unset( $GLOBALS["fields"]["identification_number"] );
	unset( $GLOBALS["fields"]["date_employed"] );
	unset( $GLOBALS["fields"]["date_of_confirmation"] );
	unset( $GLOBALS["fields"]["serial_number"] );
	unset( $GLOBALS["fields"]["file_number"] );
	unset( $GLOBALS["fields"]["account_number"] );
	unset( $GLOBALS["fields"]["bank_name"] );
	unset( $GLOBALS["fields"]["pfa"] );
	unset( $GLOBALS["fields"]["pfa_pin"] );
	unset( $GLOBALS["fields"]["tin"] );
	unset( $GLOBALS["fields"]["tax_office_location"] );
	unset( $GLOBALS["fields"]["housing_scheme"] );
	unset( $GLOBALS["fields"]["housing_scheme_number"] );
	unset( $GLOBALS["fields"]["health_insurance"] );
	unset( $GLOBALS["fields"]["health_pin"] );
	unset( $GLOBALS["fields"]["nationality"] );
	unset( $GLOBALS["fields"]["state"] );
	unset( $GLOBALS["fields"]["city"] );
	unset( $GLOBALS["fields"]["qualification"] );
	unset( $GLOBALS["fields"]["other_names"] );
	unset( $GLOBALS["fields"]["reason"] );
	unset( $GLOBALS["fields"]["status"] );
	
	unset( $GLOBALS["fields"]["sex"] );
	unset( $GLOBALS["fields"]["ref_no"] );
	//unset( $GLOBALS["fields"]["grade_level"] );
	unset( $GLOBALS["fields"]["department"] );
	unset( $GLOBALS["fields"]["division"] );
	
	$table_class = 'shopping-cart-table';
	$table_class = 'report-table-preview-20';
	
	include dirname( dirname( __FILE__ ) ) . "/globals/table_view.php";
	
	if( isset( $GLOBALS["labels"] ) )unset( $GLOBALS["labels"] );
	if( isset( $GLOBALS["fields"] ) )unset( $GLOBALS["fields"] );
}
?>
</div>