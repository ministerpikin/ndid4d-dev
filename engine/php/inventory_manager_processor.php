<?php 
	$request = '';
	$fetch_data = '';
	$table_name = '';
	$request_id = '';
	
	if(isset($_GET['request']))
		$request = $_GET['request'];
		
	unset($_GET['request']);
	
	if(isset($_GET['table_name']))
		$table_name = $_GET['table_name'];
		
	unset($_GET['table_name']);
	
	if(isset($_GET['fetch_data'])){
		$fetch_data = $_GET['fetch_data'];
	}
	$_POST['fetch_data'] = $fetch_data;
	unset($_GET['fetch_data']);
	
	if(isset($_GET['request_id'])){
		$request_id = $_GET['request_id'];
	}
	$_POST['request_id'] = $request_id;
	unset($_GET['request_id']);
	
	//Skip retrieval of IP address in Set-up.php on line 44
	$skip_ip_address_get = 1;
    $skip_required_files = 1;
    
    $pagepointer = '../';
    require_once $pagepointer . "settings/Config.php";
    require_once $pagepointer . "settings/Setup.php";
        
	switch( $table_name ){
    case "orders_transform_and_push_to_inventory_app":
        $table_name = "order";
    break;
    case "product_properties_n_values":
        $table_name = "product_features_value";
    break;
    }
    
	switch( $request ){
	case "all_terminating_categories":
		$table = 'categories';
		$action_to_perform = 'get_all_terminating_categories';
        echo json_encode( get_terminating_categories() );
        exit;
	break;
	case "card_types":
		$table = 'card_types';
		$action_to_perform = 'get_all_card_types';
        echo json_encode( get_card_types() );
        exit;
	break;
	case "custom_shipping_options":
		$table = 'custom_shipping_options';
		$action_to_perform = 'get_all_custom_shipping_options';
	break;
	case "product_properties":
		echo json_encode( get_all_product_features() );
        exit;
	break;
    case "delete_records":
    case "fetch_records":
	case "push_records":
		$table = $table_name;
		$action_to_perform = $request;
	break;
	}
    
    if( isset( $table ) && $table && isset( $action_to_perform ) && $action_to_perform  ){
        $_GET['action'] = $table;
        $_GET['todo'] = $action_to_perform;
        
        require_once "ajax_request_processing_script.php";
    }
?>