<?php 
	
	function get_ims_report_types( $opt = array() ){
		$return = array();
		if( isset( $opt["report"] ) && isset( $opt["report_type"] ) ){
			$reports[ $opt["report"] ][ $opt["report_type"] ] = get_all_accountifunction_exists( 'get_all_accounting_reports2_group' ) ? get_all_accounting_reports2_group( $opt ) : [];
		}else{
			$reports = function_exists( 'get_all_accounting_reports2_group' ) ? get_all_accounting_reports2_group() : [];
		}
		
		$submenus = function_exists( 'accounting_version2_submen' ) ? accounting_version2_submen() : [];
		if( ! empty( $reports ) ){
			foreach( $reports as $key => $value ){
				if( isset( $submenus[ $key ] ) && $submenus[ $key ] && isset( $submenus[ $key ][ 'title' ] ) ){
					$return[ $key ][ 'title' ] = $submenus[ $key ][ 'title' ];

					if( ! empty( $value ) ){
						
						$return[ $key ][ 'options' ] = $value;
						foreach( $value as $k => $v ){
							$return[ $key ][ $k ][ 'title' ] = $v;
							
							/*
							$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'store' ] = array(
								"custom" => 1,
								'field_label' => 'Revenue / Cost Center',
								'form_field' => 'calculated',
								'attributes' => ' action="?action=stores&todo=get_select2" minlength="0" ',
								'class' => ' select2 allow-clear ',
								//'form_field_options' => 'get_stores',
								
								'display_position' => 'display-in-table-row',
								'serial_number' => 14.1,
							);
							*/
							
							switch( $key ){
							case "finance_report":
							case "cash_book_report":
							case "receivables_and_payables":
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'store' ] = array( 'optional' => 1 );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'action' ] = array( "value" => "transactions" );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'todo' ] = array( "value" => "generate_sales_report" );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'return_class_action' ] = array( "value" => 1 );
							break;
							case "fixed_assets_report":
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'store' ] = array( 'optional' => 1 );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'action' ] = array( "value" => "assets" );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'todo' ] = array( "value" => "search_assets_report" );
								
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'return_class_action' ] = array( "value" => 1 );
								
								$return[ $key ][ $k ]["settings"][ 'start_date' ] = 'Y-01-01';
							break;
							case "requisition_report":
							case "procurement_report":
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'store' ] = array( 'optional' => 1 );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'action' ] = array( "value" => "expenditure" );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'todo' ] = array( "value" => "generate_income_expenditure_report" );
								
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'return_class_action' ] = array( "value" => 1 );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'report_status' ] = array( "value" => "stocked" );
								
							break;
							case "sales_report":
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'store' ] = array( 'optional' => 1 );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'action' ] = array( "value" => "sales" );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'todo' ] = array( "value" => "generate_sales_report" );
								
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'return_class_action' ] = array( "value" => 1 );
								
								//$return[ $key ][ $k ]["settings"][ 'start_date' ] = 'Y-01-01';
							break;
							case "inventory_report":
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'store' ] = array( 'optional' => 1 );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'action' ] = array( "value" => "inventory" );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'todo' ] = array( "value" => "generate_app_sales_report" );
								
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'return_class_action' ] = array( "value" => 1 );
								
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'category' ] = array( 'optional' => 1 );
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'consumable' ] = array( 'tags' => 'true', 'optional' => 1 );
								
								
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ]["sub_category"] = array(
									"custom" => 1,
									'field_label' => 'Classification',
									'form_field' => 'calculated',
									'class' => ' select2 allow-clearX ',
									'attributes' => ' action="?action=banks&todo=get_select2&type=item_classification&hide_type=1" minlength="0" ',
									'display_position' => 'display-in-table-row',
									'serial_number' => 14.1,
								);
								
								$return[ $key ][ $k ]["settings"][ 'start_date' ] = 'Y-m-d';
							break;
							case "investigation_report":
							case "custom_report":
							case "performance_report":
							case "general_report":
							case "disease_report":
								//$return[ $key ][ $k ]["settings"][ 'form_action' ] = '?action=all_reports&todo=display_reporting_view';
								
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'action' ] = array( "value" => "all_reports" );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'todo' ] = array( "value" => "display_reporting_view" );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'return_data' ] = array( "value" => 1 );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'return_view' ] = array( "value" => 1 );
							break;
							}
							
							switch( $k ){
							case 'investigation_food_handler';
							case 'investigation_food_handler_emailed';

								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'investigation_status' ] = array(
									"custom" => 1,
									'field_label' => 'Status',
									'form_field' => 'select',
									'form_field_options' => 'get_hospital_investigation_status',
									
									'display_position' => 'display-in-table-row',
									'serial_number' => 14.1,
								);
							break;
							case 'doctor_performance':
							case 'doctor_financial_performance':
							case "general_financial_performance":
							case "general_financial_performance_summary":
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'staff' ] = array( 'optional' => 1 );
							break;
							case "services_rendered_summary":
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'staff' ] = array( 'optional' => 1 );
								$return[ $key ][ $k ][ 'settings' ][ 'fields' ][ 'customer' ] = array( 'optional' => 1 );
							break;
							case "fixed_asset_record":
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'action' ] = array( "value" => "transactions" );
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'todo' ] = array( "value" => "generate_sales_report" );
							break;
							case "stock_supply_history_report":
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'type' ] = array( "value" => "all" );
							break;
							case "all_purchase_orders":
								$return[ $key ][ $k ]["settings"][ 'hidden_fields' ][ 'report_status' ] = array( "value" => "draft" );
							break;
							}
							
						}
					}
				}
			}
		}
		
		return $return;
	}
	
	function get_all_accounting_reports2_group( $opt = array() ){
		
		$r["finance_report"] = get_financial_accounting_reports( array( "finance" => 1 ) );
		$r["cash_book_report"] = get_cash_book_financial_accounting_reports();
		$r["receivables_and_payables"] = get_receivables_and_payable_reports();
		
		$r["fixed_assets_report"] = get_fixed_asset_reports();
		
		$r["inventory_report"] = get_inventory_report_types();
		$r["procurement_report"] = get_expenditure_report_types( array( "type" => "procurement" ) );
		
		$r["requisition_report"] = get_expenditure_report_types( array( "type" => "requisition" ) );
		
		$r["sales_report"] = get_sales_report_types();
		
		if( isset( $r["receivables_and_payables"]["0"] ) ){
			unset( $r["receivables_and_payables"]["0"] );
		}
		
		if( isset( $opt["report"] ) && isset( $opt["report_type"] ) ){
			if( isset( $r[ $opt["report"] ][ $opt["report_type"] ] ) ){
				return $r[ $opt["report"] ][ $opt["report_type"] ];
			}
			return array();
		}
		
		return $r;
	}
	
	function get_ims_report_typesOLD( $opt = array() ){
		$return = array();
		$r = array(
			'consumables_reports' => get_cooperative_loan_report_types(),
			'assets_reports' => get_cooperative_deposit_report_types(),
			'purchase_reports' => get_cooperative_transfer_report_types(),
			'requisition_reports' => get_cooperative_transfer_report_types2(),
		);

		$submenus = accounting_version2_submenu();
		foreach( $r as $key => $value ){
			if( isset( $submenus[ $key ] ) && $submenus[ $key ] && isset( $submenus[ $key ][ 'title' ] ) ){
				$return[ $key ][ 'title' ] = $submenus[ $key ][ 'title' ];

				if( ! empty( $value ) ){
					$return[ $key ][ 'options' ] = $value;
					foreach( $value as $k => $v ){
							// $return[ $key ][ 'settings' ][ $k ][ 'fields' ][ 'payment_method' ] = array(
							// 	"custom" => 1,
							// 	'field_label' => 'Payment Method',
							// 	'form_field' => 'select',
							// 	'form_field_options' => 'get_payment_method_grouped',
								
							// 	'display_position' => 'display-in-table-row',
							// 	'serial_number' => 14.1,
							// );
						$return[ $key ][ 'settings' ][ $k ][ 'fields' ][ 'store' ] = array( 'optional' => 1 );
						switch( $key ){
						case 'consumables_reports';
							$return[ $key ][ 'settings' ][ $k ][ 'fields' ][ 'consumable' ] = array( 'tags' => 'true', 'optional' => 1 );
							$return[ $key ][ 'settings' ][ $k ][ 'fields' ][ 'category' ] = array( 'optional' => 1 );
						break;
						case 'assets_reports';
						break;
						case 'purchase_reports';
						break;
						}
					}
				}
			}
		}
		
		return $return;
	}
?>