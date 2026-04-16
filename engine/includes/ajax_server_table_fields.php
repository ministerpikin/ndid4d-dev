<?php
	$n = 0;
	$custom_parameters = array();
	$fields = array();
	$result = array();
	$displayed_table_columns = array();
	
	$func = $table;
    if( isset( $table_real ) )$func = $table_real;
    if( isset( $real_table ) )$func = $real_table;
    
	if( ! isset( $controller_table ) ){
		$controller_table = $table;
	}
	
	if( function_exists( $func ) ){
		
		if( isset( $g_more_data["show_selection"]["table_fields_filter"] ) && $g_more_data["show_selection"]["table_fields_filter"] ){
			
			$fl = $func( $g_more_data["show_selection"]["table_fields_filter"] );
			if( isset( $fl["custom_parameters"] ) ){
				$custom_parameters = $fl["custom_parameters"];
				unset( $fl["custom_parameters"] );
			}
			$form_label = $fl;
			unset( $fl );
		}else{
			$form_label = $func();
		}
		
	}else{
		$form_label = array();
	}
	// print_r( $form_label );exit;
	
	//CHECK FOR CONTROLLER TABLE
	if( isset( $field_controller_function ) ){
		if( function_exists( $field_controller_function ) ){
			$form_label = $field_controller_function();
		}
	}
	
	$query = "DESCRIBE `".$database_name."`.`".$controller_table."`";
	//echo $query; exit;
	$query_settings = array(
		'database'=>$database_name,
		'connect'=>$database_connection,
		'query'=>$query,
		'query_type'=>'DESCRIBE',
		'set_memcache'=>1,
		'tables'=>array( $controller_table ),

		//MongoDB
		'table' => $controller_table,
		'where' => array( 
				'table_fields' => 'table_fields',
			),
		'select' => array( 
			'projection' => array( 
				'_id' => 0,
				'table_fields' => 0,
					),
			),
	);
	$result = execute_sql_query($query_settings);

	if( empty( $result ) ){
		switch( DB_MODE ){
		case 'mongoDB':
			foreach( $form_label as $key => $value ){
				$result[][] = $key;
			}
			$result[][] = 'serial_num';
			$result[][] = 'id';
			$result[][] = 'creator_role';
			$result[][] = 'created_by';
			$result[][] = 'creation_date';
			$result[][] = 'modified_by';
			$result[][] = 'modification_date';
			$result[][] = 'ip_address';
			$result[][] = 'device_id';
			$result[][] = 'record_status';
		break;
		}
	}
	
	//print_r( $form_label );exit;
	//print_r( $result );exit;
	//print_r(  $g_more_data["show_selection"] );exit;
	if( isset( $g_more_data["show_selection"]["use_table_fields_filter"] ) && ! empty( $g_more_data["show_selection"]["use_table_fields_filter"] ) ){

		foreach( $g_more_data["show_selection"]["use_table_fields_filter"] as $skey => $sval){
			$fields[] = $skey;
			
			if( isset( $sval['display_position'] ) && $sval['display_position'] ){
				switch( $sval['display_position'] ){
				case 'do-not-display-in-table':
				case 'none':
	            break;
				case 'display-in-table-row':
	            default:
					$displayed_table_columns[] = $skey;
				break;
				}
			}
			
		}
		
	}elseif( $result && is_array( $result ) ){
        
        $result = reorder_fields_based_on_serial_number( $result , $form_label );
        
		if( isset( $custom_parameters["custom_fields"] ) && ! empty( $custom_parameters["custom_fields"] ) ){
			$result = array_merge( $result, $custom_parameters["custom_fields"] );
		}
		
		foreach($result as $sval){
			if( isset( $g_more_data["show_selection"]["skip_fields"] ) && in_array( $sval[0], $g_more_data["show_selection"]["skip_fields"] ) ){
				continue;
			}
			$fields[] = $sval[0];
			
			$field['display_position'] = 'none';
			//$field = get_form_field_type($sval[0]);
			if( isset( $form_label[ $sval[0] ] ) )
                $field = $form_label[ $sval[0] ];
            
			if( ! isset( $field['display_position'] ) ){
				$field = array();
				$field['display_position'] = 'none';
			}
			
			switch( $field['display_position'] ){
			case 'do-not-display-in-table':
			case 'none':
            break;
			case 'display-in-table-row':
            default:
				$displayed_table_columns[] = $sval[0];
			break;
			}
			
			++$n;
		}
		
        //print_r($fields);exit;
	}else{
		//REPORT INVALID TABLE ERROR
		$err = new cError('000001');
		$err->action_to_perform = 'notify';
		
		$err->class_that_triggered_error = 'ajax_server_query.php';
		$err->method_in_class_that_triggered_error = $table;
		$err->additional_details_of_error = 'executed query '.str_replace("'","",$query);
		return $err->error();
	}
    /* 
	$real_table_fields = array();
	if( class_exists("c" . ucwords( $real_table ) ) ){
		$tcl = "c" . ucwords( $real_table );
		$tc = new $tcl();
		if( isset( $tc->table_fields ) ){
			$real_table_fields = $tc->table_fields;
		}
	}
	 */
	
	// print_r( $fields );exit;
	$aColumns = $fields;
	
	/* Indexed column (used for fast and accurate table budgetdinality) */
	$sIndexColumn = "id";
	
?>