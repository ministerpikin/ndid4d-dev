<?php 
	//if( isset( $data["report_data"] ) )print_r( $data["report_data"] );
	
	$row_heading = '';
	$row_body = '';
	
	if( isset( $data["item"] ) && is_array( $data["item"] ) && !empty( $data["item"] ) ){
		$data["items"] = $data["item"];
	}
	
	
	$data[ 'report_data' ] = isset( $data["saved_items"] )?$data["saved_items"]:'';
	
	$table = isset( $data["table"] )?$data["table"]:'';
	$last_id = 0;
	$previous_id = 0;
   $very_first_id = isset( $data["query"]["very_first_id"] )?$data["query"]["very_first_id"]:0;
   $first_id = 0;
	
	if( is_array( $data[ 'report_data' ] ) && ! empty( $data[ 'report_data' ] ) ){
		/* ?>
		<form class="activate-ajax" method="post" action="?action=<?php echo $data["table"]; ?>&todo=save_multiple_new_popup_form">
			<input type="submit" value="Confirm Loading of <?php echo count( $data[ 'report_data' ] ); ?> Record(s) &rarr;" class="btn blue" />
			<textarea name="data" style="display:none;"><?php echo json_encode( $data[ 'report_data' ]  ); ?></textarea>
		</form>
		<?php */
		
		echo '<h4>Records Waiting to be Loaded ('.count( $data[ 'report_data' ] ).')</h4>';
		include "bulk-data-capture-preview-table.php";
	}
	 
	$data[ 'report_data' ] = array();
	$data[ 'report_data' ] = isset( $data["unsaved_items"] )?$data["unsaved_items"]:'';
	if( is_array( $data[ 'report_data' ] ) && ! empty( $data[ 'report_data' ] ) ){
		echo '<h4>Problematic Records ('.count( $data[ 'report_data' ] ).')</h4>';
		include "bulk-data-capture-preview-table.php";
	}
?>