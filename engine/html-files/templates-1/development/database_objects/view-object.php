<div <?php set_hyella_source_path(__FILE__, 1);?>>
<?php
	$table = isset( $data["table"] )?$data["table"]:'';
	$params = '';
	if( isset( $data[ "html_replacement_selector" ] ) && $data[ "html_replacement_selector" ] ){
		$params = '&html_replacement_selector=' . $data[ "html_replacement_selector" ];
	}
	
	//print_r( $data["items"] );
	$h1 = '';
	$h2 = '';
	
	$dx = array();
	if( isset( $data["items"] ) && is_array( $data["items"] ) && ! empty( $data["items"] ) ){
		foreach( $data["items"] as $dv ){
			
			$ax = array(
				"get_form" => 1,
				"field_id" => $dv["id"],
				"object_ids" => $dv["id"],
			);
			$h1 .= get_nw_database_object( $ax ); 
			
			// echo $h1;exit;
		}
	}
	
	echo $h1;
?>	
</div>