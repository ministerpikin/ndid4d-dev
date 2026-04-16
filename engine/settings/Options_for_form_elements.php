<?php 
	/**
	 * Kwaala Select Combo Box Population File
	 *
	 * @used in  				classes/cForms.php, includes/ajax_server_json_data.php
	 * @created  				none
	 * @database table name   	none
	 */
		
	function get_bank_names(){
		return get_record_details( array( "id" => 'banks', "table" => "banks" ) );
	}
	
	function get_pay_roll_report_types(){
		
		return array(
			// 'Salary Summary' => 'payment_summary',
			
			'payment_summary' => 'Salary Summary',
			'work_schedule' => 'Work Schedule',
			'summary_of_payroll_schedule' => 'Summary of Pay Roll Schedule',
			'' => '',
			'staff_salary_schedule' => 'Staff Pay Roll',
			'payment_schedule' => 'Payment Schedule (Summary)',
			'payment_schedule_bank' => 'Payment Schedule for Bank',
			'payment_schedule_details' => 'Payment Schedule (Details)',
			'payment_schedule_nsitf' => 'Payment Schedule (NSITF)',
			'payment_schedule_itf' => 'Payment Schedule (ITF)',
			'payment_schedule_paye' => 'Detailed Paye',
			'payment_schedule_paye_summary' => 'Summary Paye',
			'payment_schedule_paye_values' => 'Summary Paye (Final Value)',
			'payment_schedule_pension' => 'Pension',
			'cooperative_contribution' => 'Staff Contributions',
			'   ' => '',
			'payment_schedule_all_other_deductions' => 'All Other Deductions',
			'payment_schedule_housing_scheme' => 'Housing Scheme Deductions',
			'payment_schedule_salary_advance' => 'Salary Advance Deductions',
			'payment_schedule_absent_deduction' => 'Absent Deductions',
			'payment_schedule_nma_deduction' => 'NMA Deductions',
			'payment_schedule_loan_deduction' => 'Loan Deductions',
			'payment_schedule_medical_deduction' => 'Medical Deductions',
			'payment_schedule_cooperative_deduction' => 'Cooperative Deductions',
			'payment_schedule_disciplinary_deduction' => 'Disciplinary Deductions',
			'payment_schedule_union' => 'Union Due',
			'payment_schedule_other_deduction' => 'Other Deductions',
			//'payment_schedule_health_insurance' => 'Health Insurance Deductions',
			' ' => '',
			'pay_slip' => 'Pay Slips',
		);
	}
	
	function get_salary_schedule(){
		return get_currencies_symbol();
		
		return array(
			'naira' => 'Naira',
			'dollar' => 'US Dollar',
		);
	}
	
	function get_salary_generation_type(){
		return array(
			'previous_month_data' => 'Use Previous Month Data',
			'use_salary_details' => 'Use Salary Details',
			'salary_schedule' => 'Use Grade Level Data',
		);
	}
	
	function get_report_periods_without_weeks(){
		$r = get_report_periods();
		unset( $r["weekly"] );
		return $r;
	}
	
	function get_type_of_vendor(){
		$return = array(
			'supplier' => 'Supplier',
			'service_provider' => 'Service Provider',
			'consultant' => 'Consultant',
			'lender' => 'Lender',
			'factory' => 'Factory',
		);
		if( class_exists("cNwp_vendor_managed_inventory") ){
			$return["vmi"] = 'VMI Vendor';
		}
		return $return;
	}
	
	function get_report_periods(){
		return array(
			'daily' => 'Daily',
			'weekly' => 'Weekly',
			'monthly' => 'Monthly',
            'yearly' => 'Yearly',
		);
	}
	
	function get_factories(){
		return get_stores();
		return array();
	}
	
	function get_income_verse_expenditure_report_types(){  
		return array(
			'income_expenditure_report' => 'Tabular Report',
			'income_expenditure_graphical_report' => 'Graphical Report',
		);
	}
	
	function get_inventory_report_types(){
		$return = array(
			'low_stock_level_report' => 'Low Stock Level Report',
			'low_stock_level_report2' => 'Low Stock Level Report',
			'stock_level_report3' => 'Stock Level Report (Summary)',
			'stock_level_report2' => 'Stock Level Report',
			'stock_level_report_grouped_by_stores' => 'Stock Level Report (by Stores)',
			
			'' => '',
			'stock_reconciliation' => 'Stock Reconciliation Report',
			'stock_value_report' => 'Stock Value Report',
			'stock_value_report2' => 'Stock Value Report',
			'stock_value_report_with_cost2' => 'Stock Value Report (Cost Only)',
			'0' => '',
			'stock_purchase_price_report' => 'Stock Purchase Price History',
			'stock_supply_history_report' => 'Stock Order & Supply History Report',
			'stock_supply_history_report_only' => 'Stock Supply History Report',
			'stock_expiration_report' => 'Stock Expiration Report',
			'stock_expiration_report2' => 'Stock Expiration Report (Expired Items)',
			'stock_supply_history_report_picture' => 'Stock Supply History Catalog',
		);
		
		if( get_match_expiry_dates_settings() != 2 ){
			unset( $return['stock_expiration_report2'] );
		}

		if( get_single_store_settings() ){
			unset( $return['stock_level_report_grouped_by_stores'] );
			unset( $return['stock_level_report2'] );
			unset( $return['stock_value_report2'] );
			unset( $return['low_stock_level_report2'] );
			unset( $return['stock_value_report_with_cost2'] );
		}else{
			unset( $return['stock_value_report'] );
			unset( $return['low_stock_level_report'] );
		}
		
		if( ! get_show_expiry_dates_settings() ){
			unset( $return['stock_expiration_report'] );
		}
		
		switch( get_package_option() ){
		case "professional":
			unset( $return['stock_purchase_price_report'] );
			unset( $return['stock_supply_history_report_picture'] );
			
			if( isset( $return['stock_expiration_report'] ) ){
				unset( $return['stock_expiration_report'] );
			}
		break;
		}
		
		return $return;
	}
	
	function get_actual_production_report_types(){
		return array(
			'daily_production_report' => 'Daily Production Report',
		);
	}
	
	function get_sales_report_types(){
		
		if( defined( "HYELLA_PACKAGE" ) ){
			switch( HYELLA_PACKAGE ){
			case "hotel":
				return array(
					'today_sales_report' => 'Today Sales Report',
					//'periodic_sales_report' => 'Periodic Sales Report',
					'unpaid_sales_report' => 'Sales Report: All Transactions',
					'part_payment_sales_report' => 'Sales Report: Unpaid Transactions',
					'' => '',
					'most_sold_item_report' => 'Most Sold Item Report',
					'most_profitable_item_report' => 'Most Income Generating Item Report',
				);
			break;
			case "property":
				return array(
					//'today_sales_report' => 'Today Sales Report',
					'periodic_sales_report' => 'Periodic Rent Report',
					'unpaid_sales_report' => 'Rent Report: All Transactions',
					'part_payment_sales_report' => 'Rent Report: Unpaid Transactions',
					//'booked_sales_report' => 'Booked Sales',
					//'' => '',
					//'customers_transaction_report' => 'Tenants Transaction',
					//'most_valued_customers_report' => 'Most Valued Tenants',
					//' ' => '',
					//'most_sold_item_report' => 'Most Sold Item Report',
					//'most_profitable_item_report' => 'Most Income Generating Item Report',
				);
			break;
			case "hospital":
				return array(
					"items_revenue" => "Revenue of Items",
					"category_revenue" => "Revenue of Categories",
					"pending_sales_order" => "Pending Orders",
					"refunds" => "Refunds",
					"edited_bils" => "Edited Bills",
				);
			break;
			}
		}
		
		return array(
			
			"items_revenue" => "Revenue of Items",
			"category_revenue" => "Revenue of Categories",
			"pending_sales_order" => "Pending Orders",
			'sp' => 0,
			'today_sales_report' => 'Today Report',
			'periodic_sales_report' => 'Periodic Report',
			'unpaid_sales_report' => 'All Transactions',
			'unpaid_sales_report2' => 'All Transactions & Orders',
			'part_payment_sales_report' => 'Outstanding Invoices',
			'daily_sales_summary' => 'Daily Sales Summary Report',
			'  ' => '',
			'staff_sales_summary' => 'Staff Sales Summary Report',
			'item_sales_summary' => 'Item Sales Summary Report',
			'' => '',
			//'customers_owing_report' => 'Customers Owing',
			'customers_transaction_report' => 'Customers Transaction',
			'most_valued_customers_report' => 'Most Valued Customers (Valued By Payments Made)',
			'most_valued_customers_report2' => 'Most Valued Customers (Valued By Amount Billed)',
			'most_valued_customers_report3' => 'Most Valued Customers (Valued By Items Purchased)',
			' ' => '',
			'most_sold_item_report' => 'Most Sold Item Report',
			'most_profitable_item_report' => 'Most Income Generating Item Report',
		);
	}
	
	function get_expenditure_batch_payment_report_types(){
		return array(
			'draft_batch_payment_report' => 'Draft Expenses',
			'pending_payment_report' => 'Unpaid Expenses',
			'all_payment_report' => 'Paid Expenses',
			'all_expenses_report' => 'All Expenses',
		);
	}
	
	function get_epo_analysis_option(){
		return array(
			'pending_purchase_orders_age_analysis' => 'Pending Purchase Orders (Age Analysis)',
			'pending_purchase_orders_age_analysis_summary' => 'Pending Purchase Orders (Age Analysis) - Summary',
			'purchase_orders_past_due_date' => 'Purchase Orders (Past Due Date)',
			'purchase_orders_past_due_date_summary' => 'Purchase Orders (Past Due Date) - Summary',
			
			'suppliers_invoice_age_analysis' => 'Suppliers Invoice (Age Analysis)',
			'suppliers_invoice_age_analysis_summary' => 'Suppliers Invoice (Age Analysis) - Summary',
		);
	}
	
	function get_ewo_analysis_option(){
		return array(
			'work_order_age_analysis' => 'Work Orders (Age Analysis)',
			'work_order_age_analysis_summary' => 'Work Orders (Age Analysis) - Summary',
			
			'work_order_job_completion_age_analysis' => 'Job Completion Certificate (Age Analysis)',
			'work_order_job_completion_age_analysis_summary' => 'Job Completion Certificate (Age Analysis) - Summary',
		);
	}
	
	function get_expenditure_report_dp(){
		$return = array(
			'all_purchase_orders_summary' => 'Details',
			//'all_purchase_orders' => 'Details & FOB',
			'vendor_summary' => 'Vendor Summary',
			'store_summary' => 'Store Summary',
			'category_summary' => 'Item Category Summary',
			'category_summary2' => 'Item Summary',
			'pcategory_summary' => 'Purchase Category Summary',
		);
		return $return;
	}
	
	function get_stock_issue_dp(){
		$return = array(
			// 'all_purchase_orders_summary' => 'Details',
			//'all_purchase_orders' => 'Details & FOB',
			// 'vendor_summary' => 'Vendor Summary',
			'store_summary' => 'Store Summary',
			'category_summary' => 'Item Category Summary',
			'category_summary2' => 'Item Summary',
			'category_summary3' => 'Items',
			// 'pcategory_summary' => 'Purchase Category Summary',
		);
		return $return;
	}
	
	function get_expenditure_report_dpr(){
		$return = array(
			'requisition_report' => 'Details',
			'requisition_report_summary' => 'Summary',
			'req_summary_items' => 'Items Summary',
			'req_summary_category' => 'Item Category Summary',
			'preq_summary_category' => 'Req. Category Summary',
			//'requisition_age_analysis' => 'Age Analysis',
			//'requisition_age_analysis_summary' => 'Age Analysis - Summary',
		);
		return $return;
	}
	
	function get_expenditure_report_types2(){
		return array_merge( get_expenditure_report_types(), get_ewo_analysis_option(), get_epo_analysis_option() );
	}
	
	function get_expenditure_report_types( $opt = array() ){
		$req = array(
			'requisition_report' => 'Requisitions',
		);
		
		$return = array(
			//'pending_purchase_orders' => 'Pending Purchase Orders',
			//'received_purchase_orders' => 'Received Purchase Orders',
			//'all_purchase_orders' => 'Purchase Orders',
			'all_purchase_orders_summary' => 'Purchased Goods',
			'pending_purchase_orders_age_analysis' => 'Purchase Analysis',
			'stock_purchase_price_report' => 'Goods Purchase Price History',
			
			
			' ' => '',
			'work_order_report' => 'Work Orders',
			//'work_order_report_summary' => 'Work Orders - Summary Report',
			'work_order_age_analysis' => 'Work Orders - Analysis',
			
			
			
		);
		
		if( isset( $opt["type"] ) ){
			switch( $opt["type"] ){
			case "procurement":
				return $return;
			break;
			case "requisition":
				return $req;
			break;
			}
		}
		$return['  '] = '';
		$return = array_merge( $return, $req );

		$return['   '] = '';
		$return['stock_issue_report'] = 'Stock Issue Report';
		
		return $return;
	}
	
	function get_customers_details( $settings = array() ){
		$cache_key = 'customers';
		if( isset( $settings["table"] ) && $settings["table"] ){
			$cache_key = $settings["table"];
		}
        return get_from_cached( array(
            'cache_key' => $cache_key."-".$settings["id"],
			'directory_name' => $cache_key,
        ) );
	}
	
	function get_record_details( $settings = array() ){
		if( isset( $settings["table"] ) && $settings["table"] ){
			$cache_key = $settings["table"];
			
			$clear = 0;
			if( isset( $settings["clear"] ) && $settings["clear"] ){
				$clear = 1;
			}
			
			if( isset( $settings["serial_num"] ) && $settings["serial_num"] ){
				$settings["id"] = get_from_cached( array(
					'cache_key' => $cache_key."-".$settings["serial_num"],
					'directory_name' => $cache_key,
					'clear' => $clear,
				) );
			}
				
			$return = get_from_cached( array(
				'cache_key' => $cache_key."-".$settings["id"],
				'directory_name' => $cache_key,
				'clear' => $clear,
			) );
			
			if( ( is_array( $return ) && ! empty( $return ) ) || $return ){
				return $return;
			}else{
				
				if( defined("AUTO_RELOAD_MISSING_CACHE_FOR_TABLES") && AUTO_RELOAD_MISSING_CACHE_FOR_TABLES && isset( $settings["id"] ) && $settings["id"] ){
					$mc = explode(",", AUTO_RELOAD_MISSING_CACHE_FOR_TABLES );
					if( in_array( $cache_key, $mc ) ){
						$set = array(
							'cache_key' => 'nwp-cache-auto-reloaded',
							'permanent' => true,
						);
						$auto = get_cache_for_special_values( $set );
						
						if( ! isset( $auto[ $cache_key . $settings["id"] ] ) ){
							unset( $auto );
							
							$set = array(
								'cache_key' => 'nwp-cache-auto-reload',
								'permanent' => true,
							);
							$auto = get_cache_for_special_values( $set );
							$auto[ $cache_key ][ $settings["id"] ] = 1;
							
							$set["cache_values"] = $auto;
							set_cache_for_special_values( $set );
						}
					}
					
				}
				/*
				if( isset( $settings["id"] ) && $settings["id"] ){
					if( isset( $settings["force"] ) && $settings["force"] ){
						$pr = get_project_data();
						$url = $pr["domain_name"] . 'php/ajax_request_processing_script.php';
						
						$params["nw_p_todo"] = 'refresh_cache';
						$params["nw_p_action"] = $cache_key;
						$params["ids2"] = "'". $settings["id"] ."'";
						$params["ids"] = "'". $settings["id"] ."'";
						curl_post_async( $url, $params );
						//sleep(4);
						
						return get_from_cached( array(
							'cache_key' => $cache_key."-".$settings["id"],
							'directory_name' => $cache_key,
						) );
						
					}else{
						$_SESSION["rebuild_cache"][ $cache_key ]["todo"] = 'refresh_cache';
						$_SESSION["rebuild_cache"][ $cache_key ]["ids"][] = "'". $settings["id"] ."'";
					}
				}
				*/
				//echo $params["id"] . 'bbbbb';
				//echo $url; exit;
			}
		}
	}
	
	function curl_post_async($url, $params = array() )
	{
		$post_params = array();
		foreach ($params as $key => &$val) {
		  if (is_array($val)) $val = implode(',', $val);
			$post_params[] = $key.'='.urlencode($val);
		}
		$post_string = implode('&', $post_params);

		$parts=parse_url($url);

		$fp = fsockopen($parts['host'], 
			isset($parts['port'])?$parts['port']:80, 
			$errno, $errstr, 30);

		if( $fp == 0 ){
			return "Couldn't open a socket to ".$url." (".$errstr.")";
		}
		//pete_assert(($fp!=0), "Couldn't open a socket to ".$url." (".$errstr.")");

		$out = "POST ".$parts['path']." HTTP/1.1\r\n";
		$out.= "Host: ".$parts['host']."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".strlen($post_string)."\r\n";
		$out.= "Connection: Close\r\n\r\n";
		if (isset($post_string)) $out.= $post_string;

		fwrite($fp, $out);
		fclose($fp);
	}
	
	function get_customers_by_phone( $settings = array() ){
		$cache_key = 'customers';
        return get_from_cached( array(
            'cache_key' => $cache_key."-".$settings["phone"],
			'directory_name' => $cache_key,
        ) );
	}
	
	function get_customers(){
		if( function_exists("_get_customers") ){
			return _get_customers();
		}
		
		$cache_key = 'customers';
		if( isset( $settings["table"] ) && $settings["table"] ){
			$cache_key = $settings["table"];
		}
		
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_items_categories_all(){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        return $return;
	}
	
	function get_items_categories_details( $settings = array() ){
		$cache_key = 'category';
		if( isset( $settings["id"] ) && $settings["id"] ){
			$return = get_from_cached( array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
			) );
			
			if( isset( $return[ $settings["id"] ] ) )return $return[ $settings["id"] ];
		}
	}
	
	function get_items_categories_by_store( $settings = array() ){
		$cache_key = 'category';
		if( isset( $settings["store"] ) && $settings["store"] ){
			$return = get_from_cached( array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
			) );
			
			if( isset( $return['store'][ $settings["store"] ] ) )return $return['store'][ $settings["store"] ];
		}
		return get_items_categories_grouped_goods( $settings );
	}
	
	function get_items_categories(){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_items_sub_categories(){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_items_categories_grouped(){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['group'] ) )return $return['group'];
	}
	
	function get_items_categories_grouped_category_type( $type ){
		$cache_key = 'category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		$t = get_product_types();
		$tr = (isset( $t[$type] )?$t[$type]:$type );
		
		if( isset( $return['group'][ $tr ] ) ){
			$return = $return['group'][ $tr ];
			asort( $return );
			return $return;
		}
		
		return array();
	}
	
	function get_items_categories_grouped_raw_materials(){
		return get_items_categories_grouped_category_type( "raw_materials" );
	}
	
	function get_items_categories_grouped_goods( $opt = array() ){
		$return = get_items_categories_grouped_category_type( "purchased_goods" );
		$r3 = get_items_categories_grouped_category_type( "consignment" );
		$r2 = get_items_categories_grouped_category_type( "produced_goods" );
		if( ! empty( $r3 ) ){
			foreach( $r3 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		if( ! empty( $r2 ) ){
			foreach( $r2 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		
		if( ! ( isset( $opt["no_service"] ) && $opt["no_service"] ) ){
			$r1 = get_items_categories_grouped_category_type( "service" );
			$r4 = get_items_categories_grouped_category_type( "service_open" );
			if( ! empty( $r1 ) ){
				foreach( $r1 as $rk => $rv )
					$return[ $rk ] = $rv;
			}
			if( ! empty( $r4 ) ){
				foreach( $r4 as $rk => $rv )
					$return[ $rk ] = $rv;
			}
		}
		
		return $return;
	}
	
	function get_items_categories_grouped_purchased_goods(){
		$return = get_items_categories_grouped_category_type( "purchased_goods" );
		$r2 = get_items_categories_grouped_category_type( "raw_materials" );
		$r1 = get_items_categories_grouped_category_type( "consignment" );
		if( ! empty( $r1 ) ){
			foreach( $r1 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		if( ! empty( $r2 ) ){
			foreach( $r2 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		
		return $return;
	}
	
	function get_items_categories_grouped_service(){
		$return = get_items_categories_grouped_category_type( "service" );
		$r1 = get_items_categories_grouped_category_type( "service_open" );
		if( ! empty( $r1 ) ){
			foreach( $r1 as $rk => $rv )
				$return[ $rk ] = $rv;
		}
		
		return $return;
	}
	
	function get_items_categories_grouped_lab_request(){
		return get_items_categories_grouped_category_type( "service_lab" );
	}
	
	function get_items_categories_grouped_procedure(){
		return get_items_categories_grouped_category_type( "service_procedure" );
	}
	
	function get_items_categories_grouped_produced_goods(){
		return get_items_categories_grouped_category_type( "produced_goods" );
	}
	
	function get_store_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'stores';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key ,
				'directory_name' => $cache_key,
			) );
			
			if( isset( $cached_values[ $settings['id'] ] ) )
				return $cached_values[ $settings['id'] ];
		}
		
		return array();
	}
	
	function get_stores(){
		$cache_key = 'stores';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_stores_data(){
		$cache_key = 'stores';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )unset( $return['all'] );
        return $return;
	}
	
	function get_sales_status(){
		if( defined( "HYELLA_PACKAGE" ) ){
			switch( HYELLA_PACKAGE ){
			case "giftcodes":				
				return array(
					'pending' => 'Pending',
					'awaiting_approval' => 'Awaiting Approval',
					'approved' => 'Approved',
					'' => 'All Order',
				);
			break;
			case "property":
				return array(
					'all_sales' => 'All',
					'occuppied' => 'Occuppied',
					'vacated' => 'Vacated',
				);
			break;
			case "duke-and-bauer":
				if( function_exists( "_get_sales_status" ) ){
					return _get_sales_status();
				}
			break;
			case "catholic":
				return array(
					'all_sales' => 'All Sales',
					'sold' => 'Sold to Customer',
					'priest' => 'Sold to Priest',
					'parish' => 'Sold to Parish',
					'booked' => 'Reserved',
					'sales_order' => 'Sales Invoice',
					'sales_order_return' => 'Returned',
					'sold_return' => 'Returned',
				);
			break;
			}
		}
		
		return array(
			'all_sales' => 'All Sales',
			'sold' => 'Sold',
            'booked' => 'Reserved',
            'sales_order' => 'Sales Invoice',
            'unvalidated_sales_order' => 'Sales Invoice (Pending Validation)',
			'i_menu_order' => 'eMenu Order',
			"e_menu_delivered" => 'eMenu Delivered',
            'return' => 'All Returns',
            'sales_order_return' => 'Returned Sales Invoice',
            'sold_return' => 'Returned Sales',
		);
	}
	
	function get_sales_e_menu_status(){
		return array(
			'i_menu_order' => 'eMenu Order',
			"e_menu_delivered" => 'eMenu Delivered',
		);
	}
	
	function get_sales_status_combine(){
		return array(
			'all_sales' => 'All',
			'sold' => 'Point of Sale',
            'booked' => 'Reserved',
            'sales_order' => 'Sales Invoice',
			'unvalidated_sales_order' => 'Sales Invoice (Pending Validation)',
			'sales_order_return' => 'Returned Sales Invoice',
            'sold_return' => 'Returned',
            'return' => 'All Returns',
			'priest' => 'Sold to Priest',
			'parish' => 'Sold to Parish',
			'i_menu_order' => 'eMenu Order',
			"e_menu_delivered" => 'eMenu Delivered',
			'occuppied' => 'Occuppied',
			'vacated' => 'Vacated',
		);
	}
	
	function get_sales_status_label(){
		return array(
			'all_sales' => array(
				"sold" => "Sold",
				"income" => "Income",
				"paid" => "Amount Paid",
				"owed" => "Amount Owed",
			),
			'sold' => array(
				"sold" => "Sold",
				"income" => "Income",
				"paid" => "Amount Paid",
				"owed" => "Amount Owed",
			),
            'booked' => array(
				"sold" => "Reserved",
				"income" => "Income",
				"paid" => "Amount Paid",
				"owed" => "Amount Owed",
			),
            'sales_order' => array(
				"sold" => "Sold",
				"income" => "Income",
				"paid" => "Amount Paid",
				"owed" => "Amount Owed",
			),
            'return' => array(
				"sold" => "Returned",
				"income" => "Refund",
				"paid" => 0,
				"owed" => 0,
			),
		);
	}
	
	function get_stock_status(){
		return array(
			'complete' => 'Production Complete',
            'in-progress' => 'In Production',
            'pending' => 'Pending Production',
            'stock-converted' => 'Stock Converted',
            'materials-utilized' => 'Materials Utilized',
            'materials-utilized-farm' => 'Materials Utilized (F)',
            'materials-transfer' => 'Stock Transfer',
            'materials-transfer-pending' => 'Pending Transfer',
            'damaged-materials' => 'Damaged Materials',
            'direct-deductions' => 'Direct Deductions',
            'direct-additions' => 'Direct Additions',	//08-jan-23
            'composite_production' => 'Composite Production',
            'sales-order' => 'Picking Slips & Delivery Notes',
		);
	}
	
	function get_stock_status2(){
		$r = get_stock_status();

		foreach( $r as $r1 => $r2 ){
			switch( $r1 ){
			// case 'complete':
            // case 'in-progress':
            // case 'pending':
            // case 'stock-converted':
            case 'materials-utilized':
            // case 'materials-utilized-farm':
            case 'materials-transfer':
            case 'materials-transfer-pending':
            case 'damaged-materials':
            case 'direct-deductions':
            // case 'composite_production':
            // case 'sales-order':
			break;
			default:
				unset( $r[ $r1 ] );
			break;
			}
		}

		return $r;
	}
	
	function get_message_types(){
		return array(
			'email' => 'Email',
            'sms' => 'SMS',
		);
	}
	
	function get_type_of_note(){
		return array(
			'note' => 'Note',
            'minutes' => 'Minutes',
            'report' => 'Report',
            //'follow_up' => 'Follow Up',
		);
	}
	
	function get_product_types(){
		$r = array();
		
		$r = array(
			'purchased_goods' => 'Purchased Goods for Sale',
			'raw_materials' => 'Raw Material',
			'consignment' => 'Consignment',
			'produced_goods' => 'Produced Goods for Sale',
			'raw_materials_purchased_goods' => 'Raw Material + Purchased Goods',
			'service' => 'Service',
			'service_open' => 'Service (Open)',
			'service_imenu' => 'Service (iMenu)',
			'composite' => 'Composite Item (Sales)',
			'composite_production' => 'Composite Item (Production)',
		);
		
		if( defined( "HYELLA_PACKAGE" ) ){
			switch( HYELLA_PACKAGE ){
			case "jewelry":				
				$r = array(
					'purchased_goods' => 'Purchased Goods for Sale',
					'consignment' => 'Consignment',
					'service' => 'Service',
				);
			break;
			case "giftcodes":
				$r = array(
					'service' => 'Active',
					'disabled' => 'In-active',
				);
			break;
			case "hospital":
				$r = array(
					'purchased_goods' => 'Purchased Goods for Sale',
					'raw_materials' => 'Consumables',
					'consignment' => 'Consignment',
					'service' => 'Service',
					'service_open' => 'Service (Open)',
					'produced_goods' => 'Produced Goods for Sale',
				);
			break;
			case "farm":
				$r = array(
					'purchased_goods' => 'Purchased Goods for Sale',
					'raw_materials' => 'Raw Materials',
					'produced_goods' => 'Produced Goods for Sale',
					'service' => 'Service',
					'feed' => 'Layers Feed',
					'rearing_feed' => 'Rearing Feed',
					'crate_of_eggs' => 'Crate of Eggs',
					'composite' => 'Composite Item',
				);
			break;
			}
		}
		
		return add_nwp_plugin_options( array( "type" => "get_product_types", "data" => $r ) );
		
		return $r;
	}
	
	function get_calendar(){
		return array(
			'general_calendar' => 'General Calendar',
		);
	}
	
	function get_halls_types(){
		return array(
			'hall' => 'Hall Only',
			'package' => 'Package: Hall + Other Items',
		);
	}
	
	function get_unit_types(){
		return array(
			'daily' => 'per Day',
			'hourly' => 'per Hour',
		);
	}
	
	function get_event_category(){
		$return = array(
			'meeting' => 'Meetings & Conference',
			'events' => 'Functions & Special Events',
            'wedding' => 'Wedding',
            'concert_and_shows' => 'Concerts & Shows',
            'camping' => 'Camping',
            'birthday' => 'Birthday',
            'training' => 'Training',
            'gala_night' => 'Gala Night',
		);
		asort( $return );
		return $return;
	}
	
	function get_event_types(){
		return array(
			'once' => "One Time Event",
			'weekly' => "Recurring Weekly",
			'bi_weekly' => "Recurring Bi-Weekly",
			'monthly' => "Monthly",
			'bi_monthly' => "Bi-Monthly",
			'quarterly' => "Quarterly",
			'half_year' => "Half Year",
			'yearly' => "Yearly",
			'bi_yearly' => "Bi Yearly",
		);
	}
	
	function get_list_of_guests(){
		return array(
			'email' => 'Email',
            'sms' => 'SMS',
		);
	}
	
	function get_reminder_frequency(){
		return array(
			'one_day' => 'A Day Before',
            'two_days' => '2 Days Before',
            'three_days' => '3 Days Before',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
		);
	}
	
	function get_types_of_expenditure(){
		$return = array();
		return $return;
		if( function_exists( "get_account_children" ) ){
			$return = get_account_children( array( "id" => "operating_expense", "parent" => "parent1" ) );
			//$return = get_account_children( array( "id" => "expenses", "parent" => "type" ) );
			//return $return;
			
			$return1 = get_account_children( array( "id" => "cost_of_goods_sold", "parent" => "type" ) );
			if( ! empty( $return1 ) ){
				foreach( $return1 as $k => $v )$return[$k] = $v;
			}
			
			$return1 = get_account_children( array( "id" => "other_expenses", "parent" => "type" ) );
			if( ! empty( $return1 ) ){
				foreach( $return1 as $k => $v )$return[$k] = $v;
			}
			
			$return1 = get_account_children( array( "id" => "inventory_expenses", "parent" => "type" ) );
			if( ! empty( $return1 ) ){
				foreach( $return1 as $k => $v )$return[$k] = $v;
			}
			
			asort( $return );
			return $return;
			
			//$return1 = get_account_children( array( "id" => "cost_of_goods_sold", "parent" => "parent1" ) );
			$return2 = array_merge( $return1 , $return );
			
			asort( $return2 );
			return $return2;
		}
		
		return array(
            'purchase_of_items' => 'Purchase of Goods for Sale',
			'purchase_of_materials' => 'Purchase of Raw Materials',
			//'cost_of_production' => 'Extra Cost of Production',
			'purchase_of_replacement' => 'Purchase of Replacement Parts',
			'payment_of_utilities' => 'Payment of Utilities',
			'drug' => 'Drug / Vaccine',
			'fueling' => 'Fueling',
			'repairs' => 'Repairs / Maintenance',
			'consultancy' => 'Consultancy',
			'salary' => 'Salary',
			'rent' => 'Rent',
			'vehicle_depreciation' => 'Vehicle Depreciation',
			'equipment_depreciation' => 'Equipment Depreciation',
			'others' => 'Others',
		);
	}
	
	function get_types_of_expenditure_grouped(){
		$return = array();
		if( function_exists( "get_account_children" ) ){
			$return = get_account_children( array( "id" => "operating_expense", "parent" => "parent1" ) );
			$return1 = get_account_children( array( "id" => "cost_of_goods_sold", "parent" => "parent1" ) );
			
			asort( $return );
			
			return array(
				//"Direct Expenses" => $return1,
				"In-direct Expenses" => $return,
			);
		}
		
		return array(
			"Direct Expenses" => array(
				'purchase_of_items' => 'Purchase of Goods for Sale',
				'purchase_of_materials' => 'Purchase of Raw Materials',
			),
            "In-direct Expenses" => array(
				'purchase_of_replacement' => 'Purchase of Replacement Parts',
				'payment_of_utilities' => 'Payment of Utilities',
				'fueling' => 'Fueling',
				'repairs' => 'Repairs / Maintenance',
				'consultancy' => 'Consultancy',
				'salary' => 'Salary',
				'rent' => 'Rent',
				
				'pr_and_advert' => 'PR & Advert',
				'staff_welfare' => 'Staff Welfare',
				'motor_vehicle_running_expense' => 'Motor Vehicle Running Expense',
				'transport_and_travels' => 'Transport & Travels',
				'printing_and_stationery' => 'Printing & Stationery',
				'staff_uniform' => 'Staff Uniform',
				'motor_vehicle' => 'RM Motor Vehicle',
				'building' => 'RM Building',
				'equipment' => 'RM Equipment',
				'telephone_and_postage' => 'Telephone & Postage',
				'purchase_commission' => 'Purchase Commission',
				'sales_commission' => 'Sales Commission',
				'security_and_safety_measures' => 'Security & Safety Measures',
				'diesel' => 'Diesel',
				'registration_and_bill' => 'Registration & Bill',
				'furniture_and_fitting' => 'Furniture & Fitting',
				'power_and_electricity' => 'Power & Electricity',
				'staff_meal' => 'Staff Meal',
				'internet_subscription' => 'Internet Subscription',
				'cable_tv' => 'Cable TV',
				'bank_charges' => 'Bank Charges',
				'paye_feb' => 'Paye Feb',
				
				'cleaning_and_sanitation' => 'Cleaning & Sanitation',
				'medical' => 'Medical',
				'sundry_expenses' => 'Sundry Expenses',
				'loan' => 'Loan',
				'gratuity' => 'Gratuity',
				'consultancy_fees' => 'Consultancy Fees',
				'leave_allowance' => 'Leave Allowance',
			),
            "Depreciating Asset" => array(
				'vehicle_depreciation' => 'Vehicle Depreciation',
				'equipment_depreciation' => 'Equipment Depreciation',
			),
			"Others" => array(
				'others' => 'Others',
			)
		);
	}
	
	function get_types_of_income(){
		return array(
			'hall' => 'Hall Rental',
            'none' => 'Other Items Rental',
		);
	}
	
	function get_vendors_supplier(){
		return get_vendors_type( "supplier" );
	}
	
	function get_vendors_factory(){
		return get_vendors_type( "factory" );
	}
	
	function get_vendors_type( $type ){
		$cache_key = 'vendors';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		
        if( isset( $return['all'] ) ){
			foreach( $return['all'] as $k => $v ){
				if( isset( $return[ $k ][ "type" ] ) && $return[ $k ][ "type" ] != $type ){
					unset( $return['all'][ $k ] );
				}
			}
		}
		
		if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_vendors(){
		$cache_key = 'vendors';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
		
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_vendors_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'vendors';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
			return $cached_values;
		}
		
		return array();
	}
	
	function get_discount(){
		$cache_key = 'discount';
        $key = 'list';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) )return $cached_values;
		return $return;
		
	}
	
	function get_items_group_items(){
        $return = get_items_group();
	}
	
	function get_items_grouped_category_type( $type, $type1 = "", $type2 = "" ){
		$cache_key = 'items';
        $return = get_from_cached( array(
            'cache_key' => $cache_key."-grouped-category",
			'directory_name' => $cache_key,
        ) );
		
		if( $return ){
			$r1 = array();
			$p = get_product_types();
			foreach( $return as $k => $v ){
				if( ( $k == $type || $k == $type1 || $k == $type2 ) ){
					$r1[ isset( $p[$k] )?$p[$k]:$k ] = $v;
				}
			}
			$return = $r1;
		}
		
		return $return;
	}
	
	function get_items_grouped_raw_materials(){
		return get_items_grouped_category_type( "raw_materials" );
	}
	
	function get_items_grouped_goods(){
		return get_items_grouped_category_type( "produced_goods", "purchased_goods", "service" );
	}
	
	function get_items_grouped_default(){
		return get_items_grouped_category_type( "produced_goods", "purchased_goods", "service", "raw_materials" );
	}
	
	function get_items_produced_goods(){
		$r = get_items_grouped_category_type( "produced_goods" );
		$r1 = array();
		foreach( $r as $rv ){
			$r1 = array_merge( $r1, $rv );
		}
		return $r1;
	}
	
	function get_items_raw_materials(){
		$r = get_items_grouped_category_type( "raw_materials" );
		$r1 = array();
		foreach( $r as $rv ){
			$r1 = array_merge( $r1, $rv );
		}
		return $r1;
	}
	
	function get_items_grouped(){
		$cache_key = 'items';
        $return = get_from_cached( array(
            'cache_key' => $cache_key."-grouped",
			'directory_name' => $cache_key,
        ) );
        if( is_array( $return ) )asort( $return );
		return $return;
	}
	
	function get_items(){
		$cache_key = 'items';
        $return = get_from_cached( array(
            'cache_key' => $cache_key."-all",
			'directory_name' => $cache_key,
        ) );
		
        if( is_array( $return ) )asort( $return );
		return $return;
	}
	
	function get_items_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
			return $cached_values;
		}
		
		return array();
	}
	
	function get_items_details_by_barcode( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-barcode-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
			return $cached_values;
		}
		return array();
	}
	
	function get_all_months(){
		$return = get_months_of_year();
		$return["all"] = "All Months";
		return $return;
	}
	
	function get_all_weekdays(){
		$return = get_days_of_week();
		$return["all"] = "All Days";
		return $return;
	}
	
	function get_months_of_year(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December',
		);
	}
	
	function get_days_of_week(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			1 => 'Monday',
			2 => 'Tuesday',
			3 => 'Wednesday',
			4 => 'Thursday',
			5 => 'Friday',
			6 => 'Saturday',
			7 => 'Sunday',
		);
	}
	
	function get_payment_method_grouped(){
		
		$return_group = array();
		$settings = array(
			'cache_key' => 'payment-method-grouped',
			'permanent' => true,
		);
		$cached_values = get_cache_for_special_values( $settings );
		if( ! empty( $cached_values ) )return $cached_values;
		
		$settings = array(
			'cache_key' => 'payment-method',
			'permanent' => true,
		);
		clear_cache_for_special_values( $settings );
		get_payment_method();
			
		$settings = array(
			'cache_key' => 'payment-method-grouped',
			'permanent' => true,
		);
		$cached_values = get_cache_for_special_values( $settings );
		if( ! empty( $cached_values ) ){
			return $cached_values;
		}else{
			$return_group["Error"] = array( "error" => "Chart of Accounts is not refreshed or does not have CASH/BANK Accounts" ); 
			/* $r = get_payment_method_fallback();
			if( defined( "HYELLA_PACKAGE" ) ){
				switch( HYELLA_PACKAGE ){
				case "hotel":
					$return_group["Others"][ 'complimentary' ] = 'Complimentary';
					
					if( ! get_disable_complimentary_staff() ){
						$return_group["Others"][ 'complimentary_staff' ] = 'Complimentary Staff';
					}
					
					$return_group["Others"][ 'charge_to_room' ] = 'Charge to Room';
					$return_group["Others"][ 'charge_to_group_account' ] = 'Charge to Group Account';
					$return_group["Others"][ 'charge_to_previous_deposit' ] = 'Charge to Previous Deposit';
				break;
				}
			}
			$return_group["Main"] = $r; */
		}
		
		return $return_group;
	}
	
	function get_payment_method_list(){
		
		$return_group = array();
		$settings = array(
			'cache_key' => 'payment-method-list',
			'permanent' => true,
		);
		$cached_values = get_cache_for_special_values( $settings );
		if( ! empty( $cached_values ) )return $cached_values;
		
		$settings = array(
			'cache_key' => 'payment-method',
			'permanent' => true,
		);
		clear_cache_for_special_values( $settings );
		get_payment_method();
			
		$settings = array(
			'cache_key' => 'payment-method-list',
			'permanent' => true,
		);
		$cached_values = get_cache_for_special_values( $settings );
		if( ! empty( $cached_values ) ){
			return $cached_values;
		}else{
			return array();
		}
	}
	
	function get_payment_method(){
		//check for cache_key
		$set_cache = 0;
		$r = get_payment_method_fallback();
		$return = array();
		$return_group = array();
		$return_list = array();
		$return_list_from_deposit = array();
		
		if( function_exists( "get_account_children" ) ){
			$settings = array(
				'cache_key' => 'payment-method',
				'permanent' => true,
			);
			$cached_values = get_cache_for_special_values( $settings );
			if( ! empty( $cached_values ) )return $cached_values;
			
			$set_cache = 1;
			$return3 = get_account_children( array( "id" => "cash_book", "parent" => "parent1" ) );
			//$return2 = get_account_children( array( "id" => "main_cash", "parent" => "parent1" ) );
			$return4 = get_account_children( array( "id" => "cash_at_hand", "parent" => "parent1" ) );
			
			if( is_array( $return4 ) ){
				$return1 = array_merge( $return3, $return4 );
			}else{
				$return1 = $return3;
			}
			
			if( ! empty( $return1 ) ){
				foreach( $return1 as $key => $val ){
					if( strtolower( trim($val) ) != "bank" && strtolower( trim($val) ) != "bank ()" ){
						switch( $key ){
						case "main_cash":	
						case "petty_cash":
							$return[ $key ] = $val;
							$return_group[ "Cash" ][ $key ] = $val;
						break;
						default:
							$return[ $key ] = $val;
							$return_group[ "Bank" ][ $key ] = $val;
							/*
							foreach( $r as $k => $v ){
								if( $k == "cash" )continue;
								$return[ $key.':::'.$k ] = $v .': '. $val;
								$return_group[ $val ][ $key.':::'.$k ] = $v .': '. $val;
							}
							*/
						break;
						}
						$return_list[ $key ] = $val;
					}
				}
			}
		}else{
			$return = $r;
			$return_group[ "Main" ] = $r;
		}
		
		if( defined( "HYELLA_PACKAGE" ) ){
			switch( HYELLA_PACKAGE ){
			case "hotel":
				$return['complimentary'] = 'Complimentary';
				if( ! get_disable_complimentary_staff() ){
					$return['complimentary_staff'] = 'Complimentary Staff';
				}
				$return['charge_to_room'] = 'Charge to Room';
				
				$return[ 'charge_to_group_account' ] = 'Charge to Group Account';
				$return[ 'charge_to_previous_deposit' ] = 'Charge to Previous Deposit';
				
				$return_group[ "Others" ][ 'complimentary' ] = 'Complimentary';
				if( ! get_disable_complimentary_staff() ){
					$return_group[ "Others" ][ 'complimentary_staff' ] = 'Complimentary Staff';
				}
				
				$return_group[ "Others" ][ 'charge_to_room' ] = 'Charge to Room';
				$return_group["Others"][ 'charge_to_group_account' ] = 'Charge to Group Account';
				$return_group["Others"][ 'charge_to_previous_deposit' ] = 'Charge to Previous Deposit';
				
				if( get_allow_advance_deposit_payment_settings() ){
					$return['charge_from_deposit'] = 'Charge from Deposit';
					$return_group[ "Others" ][ 'charge_from_deposit' ] = 'Charge From Deposit';
				}
				
			break;
			}
		}
		
		if( $set_cache ){
			$settings = array(
				'cache_key' => 'payment-method',
				'cache_values' => $return,
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$settings = array(
				'cache_key' => 'payment-method-grouped',
				'cache_values' => $return_group,
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
			
			$settings = array(
				'cache_key' => 'payment-method-list',
				'cache_values' => $return_list,
				'permanent' => true,
			);
			set_cache_for_special_values( $settings );
		}
		
		return $return;
	}
	
	function get_payment_method_fallback(){
		$return = array(
			'cash' => 'Cash',
			'cheque' => 'Cheque',
			'pos' => 'POS',
			'transfer' => 'Transfer',
		);
		return $return;
	}
	
	function get_inventory_status(){
		$return = array(
			'good' => 'Good Condition',
			'need_repairs' => 'Need Repairs',
			'damaged' => 'Damaged',
			'sold' => 'Sold',
		);
		return $return;
	}
	
	function get_discount_types(){
		$return = array(
			'fixed_value' => 'Before Tax: Specific Amount',
			'percentage' => 'Before Tax: Percentage Discount',
			
			'fixed_value_after_tax' => 'After Tax: Specific Amount',
			'percentage_after_tax' => 'After Tax: Percentage Discount',
			
			'surcharge' => 'Specific Amount Surcharge',
			'surcharge_percentage' => 'Percentage Surcharge',
		);
		return $return;
	}
	
	function get_discount_types_grouped(){
		$return = array(
			"Before Tax" => array(
				'fixed_value' => 'Specific Amount',
				'percentage' => 'Percentage Discount',
			),
			"After Tax" => array(
				'fixed_value_after_tax' => 'Specific Amount',
				'percentage_after_tax' => 'Percentage Discount',
			),
			"Surcharge / Tax" => array(
				'surcharge' => 'Surcharge',
				'surcharge_percentage' => 'Percentage Surcharge',
			),
		);
		return $return;
	}
	
	function get_approval_status(){
		$return = array(
            'pending' => 'Pending',
			
			//'part_payment' => 'Part Payment',
			
            'paid' => 'Paid',
			'paid_unavailable' => 'Paid & Unavailable',
			
			'unavailable_date' => 'Unavailable Date',
		);
		return $return;
	}
	
	function get_approval_status_icons(){
		$return = array(
			'cancelled' => '<i class="icon-remove"></i>',
			're_scheduled' => '<i class="icon-refresh"></i>',
            
            'draft' => '<i class="icon-question-sign"></i>',
            'pending' => '<i class="icon-question-sign"></i>',
			
            'approved' => '<i class="icon-check"></i>',
			'declined' => '<i class="icon-remove"></i>',
			'postponed' => '<i class="icon-retweet"></i>',
			
			'paid' => '<i class="icon-thumbs-up"></i>',
			'completed' => '<i class="icon-thumbs-up"></i>',
			'complete' => '<i class="icon-thumbs-up"></i>',
			'missed' => '<i class="icon-thumbs-down"></i>',
		);
		return $return;
	}
	
	function get_approval_status_colors(){
		$return = array(
			'cancelled' => '#d84a38;',
			're_scheduled' => '#852b99;',
            
            'draft' => '#ed9c28;',
            'pending' => '#ed9c28;',
            'pending payment' => '#ed9c28;',
			
            'approved' => '#35aa47;',
			'declined' => '#d84a38;',
			'postponed' => '#852b99;',
			
			'paid' => '#0362fd;',
			'complete' => '#0362fd;',
			'completed' => '#0362fd;',
			'missed' => '#852b99;',
		);
		return $return;
	}
	
	function get_appsettings(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$cache_key = 'appsettings';
		return get_from_cached( array(
			'cache_key' => $cache_key,
		) );
	}
    
	function get_site_user_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'site_users';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
			return $cached_values;
		}
		
		return array();
	}
	
	function get_calendar_years(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$this_year = date("Y") + 10;
		
		$return = array();
		for( $i = $this_year; $i > 1997; --$i ){
			$return[ $i ] = $i;
		}
		return $return;
	}
	
	function get_payment_status_color(){
		$return = array(
			'pending' => 'warning',
            'paid' => 'info',
            'completed' => 'success', 
			//'unconfirmed' => 'warning',
			'failed' => 'danger',
			'cancel' => 'default',
		);
		return $return;
	}
	
	function get_payment_type_status(){
		$return = array(
			'pending' => 'Pending',

            'paid' => 'Unverified',
            'completed' => 'Paid & Verified',
			//'unconfirmed' => 'Unconfirmed',
			'failed' => 'Failed',
			'cancelled' => 'Cancelled',
		);
		return $return;
	}
	
	function get_pages(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'login' => 'Login Page',
			'register' => 'Register Page',
		);
	}
	
	function get_type_invoice_quotation(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'quotation' => 'Quotation',
			'invoice' => 'Invoice',
		);
	}
	
	function get_my_customers( $settings = array() ){
		$r = array();
		if( isset( $_SESSION["customers_option"] ) && $_SESSION["customers_option"] ){
			return $_SESSION["customers_option"];
		}
		return $r;
	}
	
	function get_my_surcharges_taxes( $settings = array() ){
		$r = array();
		if( isset( $_SESSION["surcharges_taxes_option"] ) && $_SESSION["surcharges_taxes_option"] ){
			$r = $_SESSION["surcharges_taxes_option"];
		}
		$r[""] = "--Select Tax--";
		$r["new"] = "Add Tax";
		return $r;
	}
	
	function get_payment_status(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'pending' => 'Pending',
			'paid' => 'Paid',
			'failed' => 'Failed',
			'cancel' => 'Cancelled',
		);
	}
	
	function get_payment_type(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'paid_in_full' => 'Paid in Full',
			'partial_payment' => 'Partial Payment',
		);
	}
	
	function get_payment_type2(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'payment' => 'Payment',
			'refund' => 'Refund',
			'payment-posted' => 'Payment Posted',
		);
	}
	
	function get_payment_option(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'paid_to_teller' => 'Paid to Teller',
			'electronic_payment' => 'Electronic Payment',
			'transfer' => 'Transfer',
		);
	}
	
	function get_class_names(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'items' => 'Items',
			'customers' => 'Customers',
			'vendors' => 'Vendors',
			
			//===========================================================
			'hmo_coverage' => 'HMO Coverage',
			'pay_row' => 'Pay Roll',
			'site_users' => 'Site Users (Students)',
			'sales' => 'Sales',
			'discount' => 'Discount',
			'inventory' => 'Inventory',
			'barcode' => 'Barcode',
			'hotel_room_checkin' => 'Hotel Room Check-In',
			'hotel_checkin' => 'Hotel Check-In',
			'expenditure' => 'Expenditure',
			'customer_deposits' => 'Customer Deposits',
			'farm_daily_record' => 'Farm Daily Record',
			'transactions' => 'Transactions',
			'general_settings' => 'General Settings',
			'chart_of_accounts' => 'Chart of Accounts',
			'calendar' => 'Calendar',
			//===========================================================



			//===========================================================
			'contribution' => 'Deposits (Contributions)',
			'priest' => 'Priest',
			'parish' => 'Parish',
			//===========================================================



			//===========================================================
			'loyalty_points' => 'Loyalty points',
			'cart' => 'Cart',
			'assets' => 'Assets',
			'assets_category' => 'Assets Category',
			'production' => 'Production',
			'vendor_bill' => 'Vendor Bill / Work Order',
			//===========================================================
			

			//===========================================================
			'users' => 'Users',
			'users_performance_appraisal_history' => 'Users Performance Appraisal',				   
			'users_employee_profile' => 'Users Employee Profile',
			'bulk_message' => 'Bulk Message',
			//===========================================================



			//===========================================================
			'nwp_crm' => 'Nwp CRM',
			//===========================================================



			//===========================================================
			'leaves' => 'Leaves',
			'users_disciplinary_history'=> 'Disciplinary History',
			//===========================================================



			//===========================================================
			'nwp_project_management' => 'Project Management',
			'project_schedule' => 'Project Schedule',
			'nwp_crm' => 'Nwp CRM',
			//===========================================================

			"files" => "Files",		  


			//===========================================================
			'stores' => 'Stores',
			'orders' => 'Orders',
			'membership' => 'Membership',
			'newsletter' => 'Newsletter',
			'stock_request' => 'Stock Request',
			'parish_transaction' => 'Parish Transaction',
			'payment_template' => 'Payment Template',
			'hospital' => 'HOSPITAL SETTINGS',
			//===========================================================


			'all' => 'All',
		);
	}
	
	function get_website_pages_width(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'wide' => 'Wide',
			'narrow' => 'Narrow',
		);
	}
	
	function get_website_pages(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		$cache_key = 'website_pages';
		return get_from_cached( array(
			'cache_key' => $cache_key,
		) );
	}
	
	function get_production_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'production';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
		
	function get_production_details_based_on_reference( $settings = array() ){
		if( isset( $settings['reference'] ) && isset( $settings['reference_table'] ) ){
			$cache_key = 'production';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['reference_table'].'-'.$settings['reference'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_sales_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'sales';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_production_items_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'production_items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_sales_items_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'sales_items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_merchants( $settings = array() ){
		$cache_key = 'site_users';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key.'-merchants',
			'directory_name' => $cache_key,
		) );
		if( is_array( $cached_values ) )
			return $cached_values;
		
		return array();
	}
	
	function get_website_sidebars_type(){
		return array(
			'user_defined' => 'User Defined',
			'system_defined' => 'System Defined',
		);
	}
	
	function get_discount_type(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'percentage' => 'Percentage',
			'fixed_value' => 'Fixed Value',
		);
	}
	
	function get_notification_states(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'read' => 'Read',
			'unread' => 'Unread',
		);
	}
	
	function get_task_status(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'pending' => 'Pending',
			'complete' => 'Complete',
		);
	}

	function get_unit_of_time(){
		//RETURN ARRAY OF TIME UNITS
		return array(
			'1' => 'Seconds',
			'60' => 'Minutes',
			'3600' => 'Hours',
			'86400' => 'Days',
			'2592000' => 'Months',
			'31536000' => 'Years',
		);
	}

	function get_notification_types(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'pending_task' => 'Pending Task',
			'completed_task' => 'Completed Task',
			'no_task' => 'No Task',
		);
	}

	function get_yes_no(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'yes' => 'Yes',
			'no' => 'No',
		);
	}

	function get_positive_negative(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'' => '-',
			'positive' => 'Positive (+)',
			'negative' => 'Negative (-)',
		);
	}

	function get_no_yes2(){
		$r = get_no_yes();
		unset( $r[''] );
		return $r;
	}
	function get_no_yes(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'' => '',
			'no' => 'No',
			'yes' => 'Yes',
		);
	}
    
	function get_i_agree(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'no' => 'I Do Not Agree',
			'yes' => 'I Agree',
		);
	}
    
	function get_entry_exit(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'entry' => 'Entry',
			'exit' => 'Exit',
		);
	}
    
	function get_form_field_types(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'text' => 'Textbox',
			'number' => 'Number',
			'decimal' => 'Decimal',
			'tel' => 'Phone Number',
			'url' => 'URL',
			'email' => 'Email Address',
			'date' => 'Date',
			'textarea' => 'Textarea',
			'textarea-unlimited' => 'Textarea Unlimited (SMALL)',
			'textarea-unlimited-med' => 'Textarea Unlimited (MEDIUM)',
			'file' => 'File Upload',
		);
	}

	function get_staff_roles(){
		//RETURN ARRAY OF STAFF ROLES
		
		if(isset($_SESSION['temp_storage']['access_roles']['access_roles']) && is_array($_SESSION['temp_storage']['access_roles']['access_roles'])){
			return $_SESSION['temp_storage']['access_roles']['access_roles'];
		}else{
			return array();
		}
	}

	function get_file_import_options(){
		//RETURN ARRAY OF FILE IMPORT OPTIONS
		return array(
			'100' => 'Insert Data As New Records',
			'200' => 'Update Existing Records',
		);
	}

	function get_import_items_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'import_items';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_import_file_field_mapping_options(){
		//RETURN ARRAY OF IMPORT FILE FIELD MAPPING OPTIONS
		return array(
			'serial-import' => 'Serial Import of Excel Fields',
			'200' => 'Use Field Names Defined in First Row of Excel Table',
			'400' => 'Use NAPIMS Cash Calls Template-1',
		);
	}

	function get_import_table_fields(){
		//RETURN ARRAY OF IMPORT TABLE FIELDS
		$returning_array = array();
		
		if( isset( $_SESSION['temp_storage'][ 'excel_import_table' ] ) && $_SESSION['temp_storage'][ 'excel_import_table' ] ){
			$function_name = $_SESSION['temp_storage'][ 'excel_import_table' ];
			
			if( function_exists( $function_name ) ){
				$untransformed_field_data = $function_name();
				
				foreach( $untransformed_field_data as $key => $value ){
					$returning_array[$key] = $value[ 'field_label' ];
				}
			}
		}
		
		return $returning_array;
	}
	
	function get_sex(){
		//RETURN ARRAY OF SEX
		return array(
			'' => '',
			'male' => 'Male',
			'female' => 'Female',
			//'male_and_female' => 'Male & Female',
		);
	}
	
	function get_marital_status(){
		//RETURN ARRAY OF MARITAL STATUS
		return array(
			'single' => 'Single',
			'married' => 'Married',
			'divorced' => 'Divorced',
			'seperated' => 'Seperated',
			'widowed' => 'Widowed',
		);
	}
	

	function get_accessible_functions_tooltips(){
		//RETURN ARRAY OF ACCESSIBLE FUNCTIONS TOOLTIPS
		if(isset($_SESSION['temp_storage']['accessible_functions_tooltips']) && is_array($_SESSION['temp_storage']['accessible_functions_tooltips'])){
			return $_SESSION['temp_storage']['accessible_functions_tooltips'];
		}else{
			return array();
		}
	}

	function get_paper_size(){
		//RETURN ARRAY OF PAPER SIZE
		return array(
			'a4' => 'A4',
			'a0' => 'A0',
			'a1' => 'A1',
			'a2' => 'A2',
			'a3' => 'A3',
			'a5' => 'A5',
			'a6' => 'A6',
			'a7' => 'A7',
			'a8' => 'A8',
			'a9' => 'A9',
			'a10' => 'A10',
			'b0' => 'B0',
			'b1' => 'B1',
			'b2' => 'B2',
			'b3' => 'B3',
			'b4' => 'B4',
			'b5' => 'B5',
			'b6' => 'B6',
			'b7' => 'B7',
			'b8' => 'B8',
			'b9' => 'B9',
			'b10' => 'B10',
			'c0' => 'C0',
			'c1' => 'C1',
			'c2' => 'C2',
			'c3' => 'C3',
			'c4' => 'C4',
			'c5' => 'C5',
			'c6' => 'C6',
			'c7' => 'C7',
			'c8' => 'C8',
			'c9' => 'C9',
			'c10' => 'C10',
			'ra0' => 'RA0',
			'ra1' => 'RA1',
			'ra2' => 'RA2',
			'ra3' => 'RA3',
			'ra4' => 'RA4',
			'sra0' => 'SRA0',
			'sra1' => 'SRA1',
			'sra2' => 'SRA2',
			'sra3' => 'SRA3',
			'sra4' => 'SRA4',
			'letter' => 'LETTER',
			'legal' => 'LEGAL',
			'ledger' => 'LEDGER',
			'tabloid' => 'TABLOID',
			'executive' => 'EXECUTIVE',
			'folio' => 'FOLIO',
			/*
			'commercial #10 envelope' => 'COMMERCIAL #10 ENVELOPE',
			'catalog #10 1/2 envelope' => 'CATALOG #10 1/2 ENVELOPE',
			*/
			'8.5x11' => '8.5x11',
			'8.5x14' => '8.5x14',
			'11x17' => '11x17',
		);
	}

	function get_orientation(){
		//RETURN ARRAY OF PAPER ORIENTATION
		return array(
			'portrait' => 'Portrait',
			'landscape' => 'Landscape',
		);
	}

	function get_report_css_styling(){
		//RETURN ARRAY OF CSS STYLE SHEET FOR REPORT
		return array(
			'pdf-report-plain' => 'Plain No Borders',
			'pdf-report' => 'Plain with Borders',
			'pdf-report-grid' => 'Show Grids',
		);
	}
	
	//Returns an array of all countries
	function get_countries(){
		$cache_key = 'country_list';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key,
		) );
		
		$country = array();
		$country2 = array();
		
		if( $cached_values && is_array( $cached_values ) ){
			
			foreach( $cached_values as $id => $val ){
				$country[ $id ] = $val['country'];
			}
			$country[ "1157" ] = '*' . $country["1157"];
			asort( $country );
			//$country2 = array_merge( array( "1157" => $country["1157"] ), $country );
			
		}
		return $country;
	}
	
	function get_app_modules(){
		$cache_key = 'modules';
		return get_from_cached( array(
			'cache_key' => $cache_key . '-list',
		) );
	}
	
	function get_modules_in_application(){
		return get_app_modules();
	}
	
	function get_accessible_functions(){
		$cache_key = 'functions';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key."-all-functions",
			'directory_name' => $cache_key,
		) );
		
		$country = array();
		
		if( $cached_values && is_array( $cached_values ) ){
			$country = $cached_values;
			asort( $country );
		}
		return $country;
	}
	
	function get_functions_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'functions';
			return get_from_cached( array(
				'cache_key' => $cache_key."-".$settings['id'],
				'directory_name' => $cache_key,
			) );
			
		}
	}
	
	function get_access_roles(){
		$cache_key = 'access_roles';
		$cached_values = get_from_cached( array(
			'cache_key' => $cache_key."-all-access-roles",
			'directory_name' => $cache_key,
		) );
		
		$country = array();
		
		if( $cached_values && is_array( $cached_values ) ){
			$country = $cached_values;
			asort( $country );
		}
		return $country;
	}
	
	//Returns an array of all countries
	function get_access_roles_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'access_roles';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key."-".$settings['id'],
				'directory_name' => $cache_key,
			) );
			
			if( isset( $cached_values ) && $cached_values ){
				return $cached_values;
			}
		}
	}
	
	function get_countries_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'country_list';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key,
			) );
			
			if( isset( $cached_values[ $settings['id'] ] ) && $cached_values[ $settings['id'] ] ){
				return $cached_values[ $settings['id'] ];
			}
		}
	}
	
	//Returns an array of all countries
	function get_countries_data(){
        $cache_key = 'country_list';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        
        if( isset( $cached_values ) && $cached_values ){
            return $cached_values;
        }
        
		return array();
	}
	
	//Returns an array of all states in a country
	function get_state_details( $settings = array() ){
		if( isset( $settings['country_id'] ) && $settings['country_id'] && isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'state_list';
			$cached_values = get_from_cached( array(
				'data' => $settings['country_id'],
				'cache_key' => $cache_key . '-' . $settings['country_id'],
				'directory_name' => $cache_key,
			) );
            
			if( isset( $cached_values[ $settings['state_id'] ] ) && $cached_values[ $settings['state_id'] ] ){
				return $cached_values[ $settings['state_id'] ];
			}
		}
	}
	
    function get_currency(){
		$currency = array(
			'ngn' => 'NGN',
			'gbp' => 'GBP',
			'eur' => 'EUR',
			'usd' => 'USD',
			
			//'cfa' => 'CFA',
			//'cedi' => 'Cedi',
			
		);
		return $currency;
	}
    
    function get_currency_display(){
		$currency = array(
			'ngn' => '&#8358;',
			//'ngn' => 'NGN',
			'cfa' => 'CFA',
			//'cedi' => 'Cedi',
			'usd' => '$',
		);
		return $currency;
	}
    
    //Returns an array of all states in a country
	function get_all_states_in_country( $settings = array() ){
		if( isset( $settings['country_id'] ) && $settings['country_id'] ){
			$cache_key = 'state_list';
			return get_from_cached( array(
				'data' => $settings['country_id'],
				'cache_key' => $cache_key . '-' . $settings['country_id'],
				'directory_name' => $cache_key,
			) );
		}
	}
	
	function get_all_states_in_nigeria(){
		return get_states_in_country( array( 'country_id' => "1157" ) );
	}
	
	function get_city_details( $settings = array() ){
		if( isset( $settings['city_id'] ) && $settings['city_id'] && isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'cities_list';
			$cached_values = get_from_cached( array(
				'data' => $settings['state_id'],
                'cache_key' => $cache_key . '-' . $settings['state_id'],
				'directory_name' => $cache_key,
			) );
			
			if( isset( $cached_values[ $settings['city_id'] ] ) && $cached_values[ $settings['city_id'] ] ){
				return $cached_values[ $settings['city_id'] ];
			}
		}
	}
	
	function get_all_cities_in_state( $settings = array() ){
		if( isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'cities_list';
			return get_from_cached( array(
				'data' => $settings['state_id'],
                'cache_key' => $cache_key . '-' . $settings['state_id'],
				'directory_name' => $cache_key,
			) );
			
		}
	}
	
	function get_state_name( $settings = array() ){
        $state = get_state_details( $settings );
		
        if( isset( $state['state'] ) ){
            return $state['state'];
        }
        
        return $settings['state_id'];
	}
	
	function get_city_name( $settings = array() ){
        $state = get_city_details( $settings );
		
        if( isset( $state['city'] ) ){
            return $state['city'];
        }
        
        return $settings['city_id'];
	}
	
	//Returns an array of all states in a country
	function get_states_in_country( $settings = array() ){
		/* if( isset( $_SESSION['temp_storage']['selected_country_id'] ) && $_SESSION['temp_storage']['selected_country_id'] ){
            $settings['country_id'] = $_SESSION['temp_storage']['selected_country_id'];
        } */
        $return = array();
        if( isset( $settings['country_id'] ) && $settings['country_id'] ){
			$cache_key = 'state_list';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['country_id'],
				'directory_name' => $cache_key,
			) );
            
            if( is_array($cached_values) ){
                foreach( $cached_values as $id => $val ){
                    if( isset( $val['name'] ) )
                        $return[ $id ] = $val['name'];
                }
            }
			
		}
        return $return;
	}
	
	//Returns an array of all states in a country
	function get_cities_in_state( $settings = array() ){
		if( isset( $_SESSION['temp_storage']['selected_state_id'] ) && $_SESSION['temp_storage']['selected_state_id'] ){
            $settings['state_id'] = $_SESSION['temp_storage']['selected_state_id'];
        }
        $return = array();
        if( isset( $settings['state_id'] ) && $settings['state_id'] ){
			$cache_key = 'cities_list';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['state_id'],
				'directory_name' => $cache_key,
			) );
            
            if( is_array($cached_values) ){
                foreach( $cached_values as $id => $val ){
                    if( isset( $val['city'] ) )
                        $return[ $id ] = $val['city'];
                }
            }
		}
        return $return;
	}
    
	function get_cities_in_state_pay_on_delivery( $settings = array() ){
        return get_cities_in_state( $settings );
    }
    
	//Returns an array of all countries
	function get_countries_general_settings(){
		
		$country = get_countries();
		$country['default'] = '-default-';
		asort($country);
		return $country;
	}
	
	//Returns an array of all countries
	function get_active_inactive(){
		return array(
			'active' => 'Active',
			'in_active' => 'In Active',
		);
	}
	
	function get_states(){
		//RETURN ARRAY OF STATES IN THE FEDERATION
		$states = array(
			1 => 'Abia',
			10 => 'Adamawa',
			20 => 'Akwa Ibom',
			30 => 'Anambra',
			40 => 'Bauchi',
			50 => 'Bayelsa',
			60 => 'Benue',
			70 => 'Borno',
			80 => 'Cross River',
			90 => 'Delta',
			100 => 'Ebonyi',
			110 => 'Edo',
			120 => 'Ekiti',
			130 => 'Enugu',
			140 => 'Federal Capital Territory',
			145 => 'Gombe',
			150 => 'Imo',
			160 => 'Kaduna',
			170 => 'Kano',
			180 => 'Katsina',
			190 => 'Kogi',
			200 => 'Kwara',
			210 => 'Lagos',
			220 => 'Nassarawa',
			230 => 'Niger',
			240 => 'Ogun',
			250 => 'Ondo',
			260 => 'Osun',
			270 => 'Oyo',
			280 => 'Plateau',
			290 => 'Rivers',
			300 => 'Sokoto',
			310 => 'Taraba',
			320 => 'Jigawa',
			330 => 'Yobe',
			340 => 'Zamfara',
			350 => 'None (International)',
		);
		return $states;
	}

	function get_audit_trail_logs(){
		//RETURN ARRAY OF AUDIT TRAILS
		$pagepointer = '';
		if(isset($_SESSION['temp_storage']['pagepointer']) &&  $_SESSION['temp_storage']['pagepointer']){
			$pagepointer = $_SESSION['temp_storage']['pagepointer'];
		}
		
		$oldurl = 'tmp/audit_logs/';
		
		$array_to_return = array();
		
		if(is_dir($pagepointer.$oldurl)){
			//3. Open & Read all files in directory
			$cdir = opendir($pagepointer.$oldurl);
			while($cfile = readdir($cdir)){
				if(!($cfile=='.' || $cfile=='..')){
					//Check if report exists
					$get_name = explode('.',$cfile);
					if(isset($get_name[0]) && $get_name[0]){
						$array_to_return[$get_name[0]] = date('j-M-Y',($get_name[0]/1)).' Log';
					}
				}
			}
			closedir($cdir);
		}
		
		return $array_to_return;
	}
    
	//Returns an array of all states in a country
	function get_users_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'site_users';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_users( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'users';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_employees( $settings = array() ){
		//$key = 'all-users';
		$key = 'list';
        $cache_key = 'users';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) )return $cached_values;
		
        return $return;
	}
	
	function get_employees_with_designation( $settings = array() ){
		$key = 'all-users-info';
        $cache_key = 'users';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) && ! empty( $cached_values ) ){
			foreach( $cached_values as $a ){
				$return[ $a["id"] ] = '<strong>'.$a['lastname'] . ' '. $a['firstname'] . "</strong> <br />[ " . $a['ref_no'] ." ]";
				
				//$return[ $a["id"] ] = '<strong>'.$a['lastname'] . ' '. $a['firstname'] . "</strong> <br />REF NO: ".$a['ref_no']."<br /> [" . get_select_option_value( array( "id" => $a["department"], "function_name" => "get_departments" ) )."]";
			}	
		}
        
        return $return;
	}
	
	function get_employees_with_ref( $settings = array() ){
		$key = 'all-users-info';
        $cache_key = 'users';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) && ! empty( $cached_values ) ){
			foreach( $cached_values as $a ){
				$return[ $a["id"] ] = $a['lastname'] . ' '. $a['firstname'] . " ( ".$a['ref_no']." )";
			}	
		}
        
        return $return;
	}
	
	function get_employees_with_names( $settings = array() ){
		$key = 'all-users-info';
        $cache_key = 'users';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) && ! empty( $cached_values ) ){
			foreach( $cached_values as $a ){
				$return[ $a["id"] ] = $a['lastname'] . ' '. $a['firstname'];
			}	
		}
        
        return $return;
	}
	
	function get_all_employees_info(){
		$key = 'all-users-info';
        $cache_key = 'users';
        return get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
	}
	
    function enquiry_processing_status(){
        //RETURN ARRAY OF ORDER STATUS
        return array(
            '1' => 'open ticket',
            '2' => 'processing',
            '3' => 'resolved ticket',
        );
    }
    
    function get_add_to_budget_line_items_options(){
        //RETURN ARRAY OF ORDER STATUS
        return array(
            'budget_details' => 'Annual Budget',
            'cash_calls' => 'Cash Calls',
            'performance_returns' => 'Performance Returns',
        );
    }
    
	//Returns an array of all states in a country
	function get_all_users_countries(){
		$cache_key = 'site_users'.'-all-users-countries';
        return get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
	}
	function get_divisions(){
		$cache_key = 'division';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
    
	function get_job_roles(){
		$cache_key = 'job_roles';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_branch_offices(){
		$cache_key = 'branch_offices';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_units(){
		$cache_key = 'units';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_departments(){
		$key = 'list';
        $cache_key = 'departments';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) ){
			asort( $cached_values );
			return $cached_values;
		}
		
        return $return;
		
		$cache_key = 'departments';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
    function get_station_nigeria(){
        $province = array(
            1 => 'Aba',
            2 => 'Umuahia',
            10 => 'Yola',
            20 => 'Uyo',
            21 => 'Eket',
            30 => 'Nnewi',
            31 => 'Onitsha',
            40 => 'Bauchi',
            50 => 'Yenagoa',
            60 => 'Makurdi',
            70 => 'Maiduguri',
            80 => 'Calabar',
            90 => 'Asaba',
            91 => 'Warri',
            92 => 'Sapele',
            100 => 'Abakaliki',
            110 => 'Benin',
            120 => 'Ado Ekiti',
            130 => 'Enugu',
            131 => 'Nsukka',
            140 => 'Abuja',
            141 => 'Gwagwalada',
            150 => 'Owerri',
            160 => 'Kaduna',
            161 => 'Zaria',
            170 => 'Kano',
            180 => 'Katsina',
            190 => 'Lokoja',
            200 => 'Ilorin',
            210 => 'Lagos',
            220 => 'Lafia',
            230 => 'Minna',
            240 => 'Abeokuta',
            241 => 'Ijebu Ode',
            250 => 'Akure',
            260 => 'Oshogbo',
            261 => 'Ife',
            270 => 'Ibadan',
            280 => 'Jos',
            290 => 'Port Harcourt',
            291 => 'Bonny',
            300 => 'Sokoto',
            310 => 'Jalingo',
            320 => 'Gusau',
        );
        
        return $province;
    }
    
    function get_website_menu_items( $settings ){
        $cache_key = 'website_menu';
        $cached = get_from_cached( array(
            'cache_key' => $cache_key,
        ) );
        
        $returned = array();
        
        foreach( $settings as $set ){
            if( isset( $cached[ $set ] ) ){
                $returned[ $set ] = $cached[ $set ];
            }
        }
        
        return $returned;
    }
    
	function get_languages(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'US' => 'English',
			'FR' => 'French',
			'SA' => 'Arabic',
			'ZA' => 'Afrikanas',
		);
	}
	
	function get_active_batch_number(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		
		$cache_key = 'batch_number-active-batch';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => 'batch_number',
        ) );
		if( isset( $return["batch_number"] ) )
			return $return["batch_number"];
		
		return -1;
	}
	
	function get_divisional_reports_item_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'divisional_reports';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_divisional_reports(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		
		$cache_key = 'divisional_reports-all';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => 'divisional_reports',
        ) );
		if( is_array( $return ) ){
			ksort( $return );
			return $return;
		}
		return array();
	}
	
	function get_divisional_reports_table_of_contents(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		
		$cache_key = 'divisional_reports_table_of_content-all';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => 'divisional_reports_table_of_content',
        ) );
		if( is_array( $return ) ){
			$return['none'] = '0. No Parent';
			asort( $return );
			return $return;
		}
		return array( 'none' => '0. No Parent' );
	}
	
	function get_divisional_reports_table_of_contents_with_data(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		
		$cache_key = 'divisional_reports_table_of_content-all-with-data';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => 'divisional_reports_table_of_content',
        ) );
		if( is_array( $return ) ){
			return $return;
		}
		return array();
	}
	
	function get_import_template_types(){
		//RETURN ARRAY OF GENERAL SETTINGS VALUES
		return array(
			'operator-budget' => 'Operator Proposed Budget',
			'operator-cash-calls' => 'Operator Monthly Cash Calls',
			'operator-performance-returns' => 'Operator Monthly Performance Returns',
			
			'napims-budget' => 'NAPIMS Budget',
			'napims-cash-calls' => 'NAPIMS Monthly Cash Calls',
			'napims-performance-returns' => 'NAPIMS Monthly Performance Returns',
			
			'realigned-budget' => 'Realigned Budget',
		);
	}
	
	function get_columns_from_excel_raw_data(){
		$return = array();
		for( $x = 1; $x < 41; ++$x ){
			if( $x < 10 )$key = '0'.$x;
			else $key = $x;
			
			$return[ 'cash_calls_raw_data_import0'.$key ] = $x;
		}
		return $return;
	}
	
	if( defined("HYELLA_PACKAGE") && HYELLA_PACKAGE ){
		include "package/".HYELLA_PACKAGE."/Options_for_form_elements.php";
	}
	
	function get_call_categories(){
		return array(
			'follow_up' => 'Follow Up',
			'new_lead' => 'New Lead',
		);
	}
	
	function get_repairs_status(){
		return array(
			'item_collected' => 'Collected From Customer',
			'sent_to_vendor' => 'Sent to Vendor',
			'received_from_vendor' => 'Received from Vendor',
			'return_to_customer' => 'Returned to Customer',
		);
	}
	
	function get_expenditure_status2(){
		$return = array(
			'draft' => 'Purchase Order',
			'draft-purchase-order' => 'Draft Purchase Order',
			'stocked' => 'Goods Received - Validated',
			'unvalidated_stocked' => 'Goods Received - Unvalidated',
			'stock' => 'Direct Restock',
			//'stocked_reversed' => 'Reversed Goods Received Note',
			//'pending-stocked' => 'Goods Received & Pending',
			//'pending' => 'Supplier Invoice',
			'draft-purchase-ordered' => 'Returned Draft Purchase Order',
			//'returned' => 'Goods Returned By Customer',
			'revoked' => 'Revoked Purchase Order',
			
			//'submitted' => 'PO Submitted for Approval',	//bcos no client is using workflow for PO
		);
		return $return;
	}
	
	function get_expenditure_status( $type = '' ){
		$return = array(
			'stock' => 'Restocked Goods (Direct)',
			'stocked_reversed' => 'Reversed Goods Received Note',
			'unvalidated_stocked' => 'Goods Received Note (Invoice Pending Validation)',
			'stocked' => 'Goods Received Note (Validated Invoice)',
			'pending-stocked' => 'Goods Received & Pending',
			'pending' => 'Supplier Invoice',
			'draft' => 'Purchase Order',
			'draft-purchase-order' => 'Draft Purchase Order',
			'draft-purchase-ordered' => 'Draft Purchase Order II',
			'returned' => 'Goods Returned By Customer',
			'revoked' => 'Revoked Purchase Order',
			
			'submitted' => 'Submitted for Approval',
		);
		
		switch( $type ){
		case "validation":
			unset( $return[ 'stock' ] );
			unset( $return[ 'draft' ] );
		case "purchase":
			unset( $return[ 'pending' ] );
			unset( $return[ 'stocked_reversed' ] );
			unset( $return[ 'draft-purchase-order' ] );
			unset( $return[ 'draft-purchase-ordered' ] );
			unset( $return[ 'returned' ] );
			unset( $return[ 'revoked' ] );
		break;
		}
		
		return $return;
		
		return array(
			'stocked' => 'Received & Stocked',
			'pending' => 'Pending Receipt of Items',
			'draft' => 'Draft',
		);
	}
	
	function get_currencies(){
		return array(
			'ngn' => '&#8358;',
			'usd' => '$',
			'eur' => '&euro;',
			'gbp' => '&pound;',
		);
	}
	
	function get_currencies_symbol(){
		return array(
			'ngn' => 'NGN',
			'usd' => 'USD',
			'eur' => 'EUR',
			'gbp' => 'GBP',
		);
	}
	
	function get_grade_levels_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'grade_level';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings['id'],
				'directory_name' => $cache_key,
			) );
			
            return $cached_values;
		}
	}
	
	function get_grade_levels(){
		$key = 'list';
        $cache_key = 'grade_level';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) )return $cached_values;
		return $return;
	}
	
	function get_assets_category_all(){
		$cache_key = 'assets_category';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        return $return;
	}
	
	function get_assets_category(){
		$cache_key = 'assets_category';
        $key = 'list';
        $cached_values = get_from_cached( array(
            'cache_key' => $cache_key . '-' . $key,
            'directory_name' => $cache_key,
        ) );
        
        $return = array();
        if( is_array( $cached_values ) )return $cached_values;
		return $return;
		
		
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_computation_methods(){
		return array(
			"none" => "Non-Depreciating",
			"straight" => "Straight Line Method",
			"manual" => "Manually Depreciated via Journal Posting",
			//"linear" => "Linear Method",
		);
	}
	
	function get_time_methods(){
		return array(
			"no_of_depreciation" => "Number of Depreciation",
			//"ending_date" => "Ending Date",
		);
	}
	
	/***********Accounting************/
	function get_fixed_asset_reports(){
		return array(
			'new_assets_report' => 'New Assets Report',
			'working_assets_report' => 'Working Assets Report',
			'sold_assets_report' => 'Sold Assets Report',
			'fixed_asset_record2' => 'Fixed Assets Record',
			'fixed_asset_record' => 'Fixed Assets Valuation',

			'asset_report' => 'Asset Report',
			'asset_report_summary_category' => 'Asset Report Summary - Category',
			'asset_report_summary_types' => 'Asset Report Summary - Types',
			'asset_report_summary_status' => 'Asset Report Summary - Status',
		);
	}
	
	function get_financial_accounting_reports_all( $opts = array() ){
		return get_financial_accounting_reports( array( "all_reports" => 1 ) );
	}
	
	function get_financial_accounting_reports( $opts = array() ){
		$return = array(
			"chart_of_accounts_report" => "Chart of Accounts",
			//"chart_of_accounts_report_stores" => "Chart of Accounts [Stores]",
			
			"general_ledger_search" => "General Ledger",
			"general_ledger3" => "General Ledger",
			//"general_ledger2" => "General Ledger II",
			"income_statement" => "Statement of Comprehensive Income", //"Income Statement",
			"income_statement_stores" => "Statement of Comprehensive Income [SBU]",
			"income_statement_enterprise" => "Statement of Comprehensive Income [Enterprise]", 
			"income_statement_stores2" => "Statement of Comprehensive Income [Department]",
			
			//"income_statement_monthly" => "Statement of Comprehensive Income (Monthly Summary)",
			"income_expenditure_sheet" => "Income & Expenditure Sheet",
			"income_expenditure_sheet2" => "Income & Expenditure (Provisional)",
			
			"trial_balance" => "Trial Balance",
			"trial_balance2" => "Time-bound Balances",
			"balance_sheet" => "Statement of Financial Position", //"Balance Sheet",
			"balance_sheet_enterprise" => "Statement of Financial Position [Enterprise]",
			
			"balance_sheet_details" => "Statement of Financial Position (Details)", //"Balance Sheet (Details)",
			
			"cash_flow_statement" => "Statement of Cash Flow",
			"budget_performance" => "Budget Performance Report",
			"fixed_asset_record" => "Fixed Assets Valuation", //"Balance Sheet (Details)",
			"fixed_asset_record2" => "Fixed Assets Record", //"Balance Sheet (Details)",
			"inventory_record" => "Stocks Record", //"Balance Sheet (Details)",
			
			
			"other_income_analysis2" => "Income Analysis Details",
			"other_income_analysis" => "Income Analysis Details (Grouped)",
			"expense_analysis_details2" => "Expense Analysis Details",
			"expense_analysis_details" => "Expense Analysis Details (Grouped)",
			
			"Loan Reports" => "",
			//"general_ledger_summary" => "General Ledger Summary",
			//"income_statement_summary" => "Income Statement Summary",
			//"trial_balance_summary" => "Trial Balance Summary",
			
			//"flood_sheet" => "Flood Sheet",
			//"flood_sheet_summary" => "Flood Sheet Summary",
			
			"loan_report_staff" => "Loan Report (Staff)",
			"loan_report_received" => "Loan Received Report",
			"loan_report_non_staff" => "Loan Report (Non-Staff)",
			"loan_types_accounts_transactions2" => "Loan Balance",
			
			"poorly_posted_journal_entries" => "Poorly Posted Journal Entries",
			"incomplete_journal_entries" => "Incomplete Journal Entries",
			
		);
		
		
		if( ! get_hyella_development_mode() ){
			//27-mar-23
			$ret2 = $return;
			
			//unset( $return["trial_balance2"] );
			
			if( get_use_enterprise_report_settings() ){
				
				$package = get_package_option();
				$rm = 1;
				
				switch( $package ){
				case "hotel":
					unset( $return["loan_types_accounts_transactions2"] );
					unset( $return["income_statement_enterprise"] );
					//unset( $return["chart_of_accounts_report"] );
				break;
				case "cooperative":
					unset( $return["loan_report_non_staff"] );
					unset( $return["income_statement_stores"] );
				break;
				case "essential":
				case "professional":
					unset( $return["loan_report_non_staff"] );
					unset( $return["loan_report_received"] );
					unset( $return["loan_report_staff"] );
					unset( $return["income_statement_stores"] );
					unset( $return["balance_sheet_enterprise"] );
					unset( $return["trial_balance"] );
					unset( $return["cash_flow_statement"] );
					unset( $return["budget_performance"] );
					unset( $return["fixed_asset_record"] );
					unset( $return["fixed_asset_record2"] );
					
					unset( $return["income_statement_enterprise"] );
					unset( $return["income_statement_stores2"] );
					unset( $return["loan_types_accounts_transactions2"] );
					unset( $return["income_statement_stores"] );
					
					switch( $package ){
					case "essential":
						unset( $return["balance_sheet"] );
						unset( $return["inventory_record"] );
					break;
					}
					
					$rm = 0;
				break;
				default:
					unset( $return["loan_types_accounts_transactions2"] );
					//unset( $return["income_statement_stores"] );
					//unset( $return["chart_of_accounts_report_stores"] );
				break;
				}
				
				unset( $return["income_statement_monthly"] );
				unset( $return["balance_sheet_details"] );
				
				if( $rm ){
					unset( $return["income_statement"] );
					unset( $return["balance_sheet"] );
				}
			}else{
				unset( $return["income_expenditure_sheet2"] );
				unset( $return["income_expenditure_sheet"] );
				
				unset( $return["balance_sheet_enterprise"] );
				unset( $return["income_statement_enterprise"] );
				//unset( $return["chart_of_accounts_report_stores"] );
				unset( $return["income_statement_stores"] );
				unset( $return["income_statement_monthly"] );
			}
			
			if( ! class_exists("cBudget") && isset( $return["budget_performance"] ) ){
				unset( $return["budget_performance"] );
			}

            //27-mar-23
            if( defined("HYELLA_ALLOWED_REPORTS") && HYELLA_ALLOWED_REPORTS ){
                $ar = explode(",", HYELLA_ALLOWED_REPORTS );
                if( $ar ){
                    foreach( $ar as $arv ){
                        if( isset( $ret2[ $arv ] ) && ! isset( $return[ $arv ] ) ){
                            $return[ $arv ] = $ret2[ $arv ];
                        }
                    }
                }
            }
		}
		
		if( isset( $opts["finance"] ) && $opts["finance"] ){
			$f = array();
			foreach( $return as $k => $v ){
				switch( $k ){
				case "chart_of_accounts_report":
				//case "general_ledger_search":
				case "general_ledger3":
				case "income_statement":
				//case "income_expenditure_sheet":
				case "income_statement_stores2":
				case "income_statement_stores":
				case "income_statement_enterprise":
				case "income_statement_monthly":
				case "trial_balance":
				case "trial_balance2":
				case "balance_sheet":
				case "balance_sheet_enterprise":
				case "balance_sheet_details":
				case "cash_flow_statement":
				case "budget_performance":
				case "fixed_asset_record":
				case "fixed_asset_record2":
				case "inventory_record":
				case "poorly_posted_journal_entries":
				case "incomplete_journal_entries":
					$f[ $k ] = $v;
				break;
				}
			}
			return $f;
		}
		
		$show_stats = 0;
		$xno = '';
		if( function_exists("get_top_no_for_revenue_analysis_settings") ){
			$xno = get_top_no_for_revenue_analysis_settings();
		}
		$_top = " (Top ".$xno." Categories)";
		$_bottom = " (Bottom ".$xno." Categories)";
		
		$stats = array(
			"annual_profit_loss" => "Annual Profit / Loss",
			"annual_revenue" => "Annual Revenue",
			"annual_revenue_top" => "Annual Revenue" . $_top,
			"annual_revenue_bottom" => "Annual Revenue" . $_bottom,
			
			"revenue_analysis" => "Revenue Analysis",
			"revenue_analysis_top" => "Revenue Analysis" . $_top,
			"revenue_analysis_bottom" => "Revenue Analysis" . $_bottom,
			
			"annual_expenditure" => "Annual Expenditure",
			"annual_expenditure_top" => "Annual Revenue" . $_top,
			"annual_expenditure_bottom" => "Annual Revenue" . $_bottom,
			
			"expenditure_analysis" => "Expenditure Analysis",
			"expenditure_analysis_top" => "Expenditure Analysis" . $_top,
			"expenditure_analysis_bottom" => "Expenditure Analysis" . $_bottom,
		);
		
		if( get_hyella_development_mode() ){
			if( isset( $opts["all"] ) && $opts["all"] ){
				$show_stats = 1;
			}
		}
		if( isset( $opts["all_reports"] ) && $opts["all_reports"] ){
			$show_stats = 1;
		}

		if( $show_stats ){
			$return[ 'Analytics' ] = '';
			
			foreach( $stats as $k => $v ){
				$return[ $k ] = $v;
			}
		}
		
		
		return $return;
	}
	
	function get_receivables_only(){
		return array(
			//"customers_list" => "List of Customer(s)",
			"customers_debtor_list" => "List of Debtors (Customers)",
			"customers_credit_list" => "List of Creditors (Customers)",
			"customers_transactions2" => "Customer Account",
		);
	}
	
	function get_hmo_ledger_only(){
		return array(
			"hmo_ledger_debtor_list" => "List of Debtors (HMO Ledger)",
			"hmo_ledger_credit_list" => "List of Creditors (HMO Ledger)",
			"hmo_ledger_transactions2" => "HMO Ledger Account",
		);
	}
	
	function get_hmo_fee_for_service_only(){
		return array(
			"hmo_fee_for_service_debtor_list" => "List of Debtors (HMO Fee for Service)",
			"hmo_fee_for_service_credit_list" => "List of Creditors (HMO Fee for Service)",
			"hmo_fee_for_service_transactions2" => "HMO Fee for Service Account",
		);
	}
	
	function get_customers_control_ledger_only(){
		return array(
			"customers_control_debtor_list" => "List of Debtors (Control)",
			"customers_control_credit_list" => "List of Creditors (Control)",
			"customers_control_transactions2" => "Customers Control Account",
		);
	}
	
	function get_city_ledger_only(){
		return array(
			"city_ledger_debtor_list" => "List of Debtors (Guest Ledger)",
			"city_ledger_credit_list" => "List of Creditors (Guest Ledger)",
			"city_ledger_transactions2" => "Guest Ledger Account",
		);
	}
	
	function get_package_ledger_only(){
		return array(
			"package_ledger_debtor_list" => "List of Debtors (Package Ledger)",
			"package_ledger_credit_list" => "List of Creditors (Package Ledger)",
			"package_ledger_transactions2" => "Package Ledger Account",
		);
	}
	
	function get_priest_receivables_only(){
		return array(
			"priest_debtor_list" => "List of Debtors (Priests)",
			"priest_credit_list" => "List of Creditors (Parish)",
			"priest_transactions2" => "Priest Account",
		);
	}
	
	function get_parish_receivables_only(){
		return array(
			"parish_debtor_list" => "List of Debtors (parishs)",
			"parish_credit_list" => "List of Creditors (Parish)",
			"parish_transactions2" => "Parish Account",
		);
	}
	
	function get_payables_only(){
		return array(
			//"vendors_list" => "List of Vendor(s)",
			"vendors_debtor_list" => "List of Debtors (Vendors)",
			"vendors_credit_list" => "List of Creditors (Vendors)",
			"vendors_transactions2" => "Vendor Account",
		);
	}
	
	function get_suppliers_control_only(){
		return array(
			//"vendors_list" => "List of Vendor(s)",
			"suppliers_control_debtor_list" => "List of Debtors (Suppliers Control)",
			"suppliers_control_credit_list" => "List of Creditors (Suppliers Control)",
			"suppliers_control_transactions2" => "Suppliers Control Account",
		);
	}
	
	function get_loan_types_accounts_only(){
		return array(
			//"loan_types_accounts_debtor_list" => "List of Debtors",
			//"loan_types_accounts_credit_list" => "List of Creditors",
			"loan_types_accounts_transactions2" => "Account Balance(s)",
		);
	}
	
	function get_receivables_and_payable_reports( $q = '' ){
		
		switch( $q ){
		case 'v1r':
		case 'v1p':
		case 'v1':
			$return = array(
				"vendors" => "Vendors",
				"vendors_age_analysis" => "Vendors Age Analysis",
				"customers" => "Customers",
				"customers_age_analysis" => "Customers Age Analysis",
				"" => "",
				"vendors_control" => "Vendors Control",
				"vendors_control_age_analysis" => "Vendors Control Age Analysis",
				"customers_control" => "Customers Control",
				"customers_control_age_analysis" => "Customers Control Age Analysis",
			);
			
			if( ! defined("HYELLA_CUSTOMERS_AGE_ANALYSIS") ){
				unset( $return["customers_age_analysis"] );
				unset( $return["customers_control_age_analysis"] );
			}
			
			switch( get_package_option() ){
			case "professional":
			case "gynae":
			case "hospital":
				$return[" "] = '';
				$return["hmo"] = 'HMOs';
				$return["hmo_age_analysis"] = 'HMOs Age Analysis';
				$return["package"] = 'Packages';
			break;
			}
			
			switch( $q ){
			case 'v1r':
				unset( $return["vendors"] );
				unset( $return["vendors_age_analysis"] );
				unset( $return[""] );
				unset( $return["vendors_control"] );
				unset( $return["vendors_control_age_analysis"] );
			break;
			case 'v1p':
				unset( $return["customers"] );
				if( isset( $return["package"] ) ){
					unset( $return[" "] );
					unset( $return["hmo"] );
					unset( $return["hmo_age_analysis"] );
					unset( $return["package"] );
				}
				
				if( isset( $return["customers_age_analysis"] ) ){
					unset( $return["customers_age_analysis"] );
					unset( $return["customers_control_age_analysis"] );
				}
				
				unset( $return[""] );
				unset( $return["customers_control"] );
			break;
			}
			
			return $return;
		break;
		}
		$return = array_merge( array( "0" => "" ), get_receivables_only(), array( "" => "" ), get_payables_only() );
		
		if( get_use_city_ledger_settings() ){
			$return = array_merge( $return, array( " " => "" ), get_city_ledger_only() );
		}
		
		switch( get_package_option() ){
		case "catholic":
			$return = array_merge( $return, array( " " => "" ), get_parish_receivables_only(), array( "  " => "" ), get_priest_receivables_only() );
		break;
		case "professional":
		case "gynae":
		case "hospital":
			//$return = array_merge( $return, array( "  " => "" ), get_customers_control_ledger_only(), array( "   " => "" ), get_hmo_ledger_only(), array( "    " => "" ), get_hmo_fee_for_service_only() );
			$return = array_merge( $return, array( "  " => "" ), get_customers_control_ledger_only(), array( "   " => "" ), get_hmo_ledger_only(), array( "    " => "" ), get_package_ledger_only() );
		break;
		case "cooperative":
			$return = array_merge( get_loan_types_accounts_only(), $return );
		break;
		}
		
		if( function_exists("get_validate_purchase_invoice_settings") && get_validate_purchase_invoice_settings() ){
			$return = array_merge( $return, array( " " => "" ), get_suppliers_control_only() );
		}
		
		return $return;
	}
	
	function get_receivables_and_payable_reports_display_option(){
		return array(
			"summary_view" => "Show Summary",
			"details_view" => "Show Details",
		);
	}
	
	function get_customers_financial_accounting_reports(){
		if( function_exists("_get_customers_financial_accounting_reports") ){
			return _get_customers_financial_accounting_reports();
		}
		
		return array(
			"customers_transactions" => "Accounts Receivable",
			"customers_transactions_summary" => "Accounts Receivable Summary",
			"" => "",
			"customers_owing" => "Customers Owing",
			"customers_owing_summary" => "Customers Owing Summary",
		);
	}
	
	function get_cash_book_financial_accounting_reports( $o = array() ){
		$r = array(
			"cash_book_transactions" => "Cash Book",
			"cash_book_transactions_summary" => "Cash Book Summary",
			"cash_book_transactions_analysis_bank" => "Cash Book Analysis - by Payment Method",
			"cash_book_transactions_analysis_bank_expenses" => "Cash Book Analysis - by Payment Method (Expenses)",
			"cash_book_transactions_analysis_bank_income" => "Cash Book Analysis - by Payment Method (Income)",
			"cash_book_transactions_analysis" => "Cash Book Analysis - by Category (Expenses)",
			"cash_book_transactions_analysis_income" => "Cash Book Analysis - by Category (Income)",
			//"cash_book_transactions_analysis2" => "Cash Book Analysis II",
			//"" => "",
			//"cash_book_transactions_analysis_unreconciled" => "Bank Reconciliation (Pending)",
			//"cash_book_transactions_analysis_reconciled" => "Bank Reconciliation (Completed)",
		);
		if( isset( $o["type"] ) ){
			switch( $o["type"] ){
			case "hospital_simple":
				$r = array(
					"cash_book_transactions_analysis_bank_income" => "Income - by Payment Method",
					"cash_book_transactions_analysis_income" => "Income - by Category",
				);
			break;
			}
		}
		return $r;
	}
	
	function get_cash_book_frontend_reports(){
		return array(
			"cash_book_transactions_frontend" => "Cash Book",
		);
	}
	
	function get_vendors_financial_accounting_reports(){
		return array(
			"vendors_transactions" => "Accounts Payable",
			"vendors_transactions_summary" => "Accounts Payable Summary",
		);
	}
	
	function get_types_of_account_debit_and_credit(){
		
		$r = array(
			"cash_book" => "debit",
			"asset" => "debit",
			"inventory" => "debit",
			"asset_receivables" => "debit",
			"goods_in_transit_control" => "debit",
			"interest_receivables" => "debit",
			"asset_receivables" => "debit",
			
			
			"intangible_asset" => "debit",
			"other_asset" => "debit",
			"fixed_asset" => "debit",
			"contra_asset" => "credit",
			
			"liabilities" => "credit",
			"other_liabilities" => "credit",
			
			"equity" => "credit",
			
			"equity_profit_loss_account" => "debit",
			"equity_share_captial" => "credit",
			"equity_deposit_for_shares" => "credit",
			
			"profit_loss_adjustment" => "debit",
			
			"other_income" => "credit",
			"revenue" => "credit",
			"revenue_category" => "credit",
			
			"expenses" => "debit",
			"cost_of_goods_sold" => "debit",
			"cost_of_goods_sold_others" => "debit",
			"damaged_goods" => "debit",
			
			"financial_expenses" => "debit",
			"other_expenses" => "debit",
			
			"other_expenses_tax" => "debit",
			
			"package_ledger" => "debit",
			"hmo_ledger" => "debit",
			"city_ledger" => "debit",
			"accounts_receivable" => "debit",
			"account_payable" => "credit",
			"suppliers_control" => "credit",
			
			"customers_control" => "debit",
		);
		
		if( ( defined( 'NWP_SEPERATE_CONTRA_ASSETS') && NWP_SEPERATE_CONTRA_ASSETS ) ){
			$r["contra_asset"] = "debit";
		}
		
		return $r;
	}
	
	function get_types_of_account_type(){
		
		return array(
			"asset" => "Assets",
			"cash_book" => "Assets",
			
			"inventory" => "Assets",
			"goods_in_transit_control" => "Assets",
			
			"asset_receivables" => "Assets",
			"interest_receivables" => "Assets",
			"other_asset" => "Assets",
			
			"fixed_asset" => "Assets", //"fixed_Assets" => "Fixed Assets",
			"intangible_asset" => "Assets",
			"contra_asset" => "Assets",
			"contra_asset2" => "Assets",
			
			"liabilities" => "Liabilities",
			"other_liabilities" => "Liabilities", //"other_liabilities" => "Long Term Liabilities",
			
			"profit_loss_adjustment" => "Equity",
			"equity" => "Equity",
			"equity_profit_loss_account" => "Equity",
			"equity_share_captial" => "Equity",
			"equity_deposit_for_shares" => "Equity",
			
			"other_income" => "Income",
			"investment_income" => "Income",
			"revenue" => "Income",
			"revenue_category" => "Income",	//Sales
			
			"expenses" => "Expenses",
			"cost_of_goods_sold" => "Expenses",
			"cost_of_goods_sold_others" => "Expenses",
			"damaged_goods" => "Expenses",
			
			"depreciation_expense" => "Expenses",
			"amortization_expense" => "Expenses",
			"interest_expense" => "Expenses",
			"dividends_expense" => "Expenses",
			
			"financial_expenses" => "Expenses",
			//"other_expenses" => "Other Expenses",
			"other_expenses_tax" => "Expenses",
			"accounts_receivable" => "Assets",
			"account_payable" => "Liabilities",
			"city_ledger" => "Assets",
			"hmo_ledger" => "Assets",
			"package_ledger" => "Assets",
			
			"hmo_fee_for_service" => "Assets",
			"customers_control" => "Assets",
			"suppliers_control" => "Assets",
			
			"fixed_asset_sales" => "Income",
			"intangible_asset_sales" => "Income",
		);
	}
	
	function get_types_of_account( $opt = array() ){
		
		$return = array(
			"asset" => "Current Assets",
			"cash_book" => "Cash/Bank Accounts (Current Assets)",
			
			"inventory" => "Inventory",
			"goods_in_transit_control" => "Goods In-transit",
			
			"asset_receivables" => "Other Receivables",
			"interest_receivables" => "Interest Receivables",
			"other_asset" => "Investments / Long-term Assets",
			
			"fixed_asset" => "Non-current Assets", //"fixed_asset" => "Fixed Asset",
			"intangible_asset" => "Intangible Assets",
			"contra_asset" => "Accumulated Depreciations",
			"contra_asset2" => "Accumulated Amortizations",
			
			"liabilities" => "Current Liabilities",
			"other_liabilities" => "Non-current Liabilities", //"other_liabilities" => "Long Term Liabilities",
			
			"profit_loss_adjustment" => "Profit / Loss Adjustment",
			"equity" => "Equity",
			"equity_profit_loss_account" => "Prior Year (Profit / Loss Carried Forward)",
			"equity_share_captial" => "Equity (Share Capital)",
			"equity_deposit_for_shares" => "Equity (Deposit for Shares)",
			
			"other_income" => "Other Income",
			"investment_income" => "Investment Income",
			"revenue" => "Revenue (Direct)",
			"revenue_category" => "Revenue (Sales)",	//Sales
			
			"expenses" => "Operating Expenses",
			"cost_of_goods_sold" => "Cost of Sales",
			"cost_of_goods_sold_others" => "Other Cost of Sales",
			"damaged_goods" => "Cost of Sales (Damaged Goods)",
			
			"depreciation_expense" => "Depreciation Expenses",
			"amortization_expense" => "Amortization Expenses",
			"interest_expense" => "Interest Expenses",
			"dividends_expense" => "Dividends Paid",
			
			"financial_expenses" => "Financial Expenses",
			//"other_expenses" => "Other Expenses",
			"other_expenses_tax" => "Taxation (Expenses)",
			"accounts_receivable" => "Accounts Receivables",
			"account_payable" => "Accounts Payable",
			"city_ledger" => "Guest Ledger",
			"hmo_ledger" => "HMO Ledger",
			"package_ledger" => "PACKAGE Ledger",
			
			"hmo_fee_for_service" => "HMO Fee for Service",
			"customers_control" => "Customers Control Account",
			"suppliers_control" => "Suppliers Control Account",
			
			"fixed_asset_sales" => "Profit/(Loss) on sale of fixed assets",
			"intangible_asset_sales" => "Profit/(Loss) on sale of intangible assets",
		);
		
		if( defined("NWP_STRICT_CHART_OF_ACCOUNTS") && NWP_STRICT_CHART_OF_ACCOUNTS ){
			
			$return[ "revenue" ] = 'Income';
			$return[ "expenses" ] = 'Expenses';
			
			unset( $return[ "other_expenses_tax" ] );
			unset( $return[ "financial_expenses" ] );
			unset( $return[ "other_income" ] );
			unset( $return[ "revenue_category" ] );
			unset( $return[ "investment_income" ] );
			
			unset( $return[ "depreciation_expense" ] );
			unset( $return[ "cost_of_goods_sold" ] );
			unset( $return[ "cost_of_goods_sold_others" ] );
			unset( $return[ "damaged_goods" ] );
			unset( $return[ "dividends_expense" ] );
			unset( $return[ "amortization_expense" ] );
			unset( $return[ "interest_expense" ] );
		}
		
		$pk = get_package_option();
		switch( $pk ){
		case "farm":
			$return[ "intangible_asset" ] = "Biological Stock";
		break;
		case "catholic":
		case "anglican":
			$return[ "expenses" ] = 'Expenses';
			$return[ "revenue_category" ] = 'Revenue from Sales';
			$return[ "revenue" ] = 'Income';
			
			unset( $return[ "other_expenses_tax" ] );
			unset( $return[ "financial_expenses" ] );
			unset( $return[ "other_income" ] );
			
			unset( $return[ "intangible_asset" ] );
			unset( $return[ "goods_in_transit_control" ] );
			unset( $return[ "customers_control" ] );
			unset( $return[ "hmo_fee_for_service" ] );
			unset( $return[ "city_ledger" ] );
			unset( $return[ "package_ledger" ] );
			unset( $return[ "hmo_ledger" ] );
			unset( $return[ "hmo_fee_for_service" ] );
			
			switch( $pk ){
			case "anglican":
				$return[ "other_asset" ]  = 'Special Fund Assets';
			break;
			}
		break;
		default:
			unset( $return[ "other_expenses_tax" ] );
			unset( $return[ "financial_expenses" ] );
			unset( $return[ "other_income" ] );
			
			unset( $return[ "equity_share_captial" ] );
			unset( $return[ "equity_deposit_for_shares" ] );
		break;
		}
		
		asort( $return );
		
		if( isset( $opt["grouped"] ) && $opt["grouped"] ){
			$gs = get_account_groups();
			$r1 = $return;
			$return = array();
			
			foreach( $gs as $rk => $rv ){
				if( isset( $rv['accounts'] ) ){
					$rk = strtoupper( isset( $rv['title'] )?$rv['title']:str_replace( "_", " ", $rk ) );
					
					foreach( $rv['accounts'] as $rv1 ){
						if( isset( $r1[ $rv1 ] ) ){
							$return[ $rk ][ $rv1 ] = $r1[ $rv1 ];
							unset( $r1[ $rv1 ] );
						}
					}
				}
			}
			
			if( ! empty( $r1 ) ){
				foreach( $r1 as $rk => $rv ){
					$return[ "Others" ][ $rk ] = $rv;
				}
			}
		}
		
		return $return;
	}
	
	function get_types_of_account2(){
		
		$return = get_types_of_account();
		$return["cash_book"] = 'Current Asset (Cash/Bank Accounts Only)';
		
		return $return;
	}
	
	function get_account_groups( $opt = array() ){
		$return = array(
			'income' => array( 
				'title' => 'Income',
				'type' => 'temporary',
				'accounts' => array( 'other_income', 'revenue', 'revenue_category', 'investment_income' ),
			),
			'sales_of_fixed_asset' => array( 
				'title' => 'Proceed on sale of assets',
				'type' => 'temporary',
				'accounts' => array( 'fixed_asset_sales', 'intangible_asset_sales' ),
			),
			'expense_tax' => array( 
				'title' => 'Expenses [Taxes on Income]',
				'type' => 'temporary',
				'accounts' => array( "other_expenses_tax" ),
			),
			'expense' => array( 
				'title' => 'Expenses',
				'type' => 'temporary',
				'accounts' => array( "cost_of_goods_sold", "cost_of_goods_sold_others", "damaged_goods", "financial_expenses", "other_expenses", "expenses", "operating_expense", "depreciation_expense", "amortization_expense", "interest_expense", "dividends_expense" ),
			),
			
			'cash_book' => array( 
				'title' => 'Current Assets [Cash & Cash Equivalents]',
				'accounts' => array( "cash_book" ),
			),
			'inventory' => array( 
				'title' => 'Current Assets [Inventory]',
				'accounts' => array( "inventory", "goods_in_transit_control" ),
			),
			'receivables' => array( 
				'title' => 'Current Assets [Receivables]',
				'accounts' => array( "interest_receivables", "asset_receivables", "accounts_receivable", "city_ledger", "hmo_ledger", "package_ledger", "customers_control" ),
			),
			'current_assets' => array( 
				'title' => 'Current Assets',
				'accounts' => array( "asset" ),
			),
			'fixed_asset' => array( 
				'title' => 'Non-Current Assets',
				'accounts' => array( "fixed_asset", "intangible_asset", "contra_asset", "contra_asset2", "other_asset" ),
			),
			'payables' => array( 
				'title' => 'Current Liabilities [Payables]',
				'accounts' => array( "account_payable", "suppliers_control" ),
			),
			'current_liabilites' => array( 
				'title' => 'Current Liabilities',
				'accounts' => array( "liabilities" ),
			),
			'non_current_liabilites' => array( 
				'title' => 'Non-Current Liabilities',
				'accounts' => array( "other_liabilities" ),
			),
			'equity' => array( 
				'title' => 'Equity',
				'accounts' => array( "profit_loss_adjustment", "equity", "equity_profit_loss_account","equity_share_captial", "equity_deposit_for_shares" ),
			),
		);
		
		if( defined("NWP_STRICT_CHART_OF_ACCOUNTS") && NWP_STRICT_CHART_OF_ACCOUNTS ){
			unset( $return['expense_tax'] );
		}
		return $return;
	}
	
	function get_account_groups2(){
		$r = array();
		$r1 = get_account_groups();
		if( ! empty( $r1 ) ){
			foreach( $r1 as $k => $v ){
				$r[ $k ] = isset( $v["title"] )?$v["title"]:$k;
			}
		}
		return $r;
	}
	
	function get_types_of_account_grouped(){
		return get_types_of_account( array( "grouped" => 1 ) );
	}
	
	function get_transaction_status(){
		return array(
			"draft" => "Unvalidated",
			"submitted" => "Submitted",
			"approved" => "Validated",
		);
	}
	
	function get_transaction_type(){
		return array(
			"debit" => "Debit",
			"credit" => "Credit",
		);
	}
	
	function get_first_level_accounts(){
		$return = get_account_children( array( "id" => "0", "parent" => "parent1" ) );
		$return["0"] = "-None-";
		asort( $return );
		return $return;
	}
	
	function get_account_children( $settings = array() ){
		if( isset( $settings["id"] ) && isset( $settings["parent"] ) && $settings["parent"] ){
			$cache_key = 'chart_of_accounts';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key . '-' . $settings["parent"] . '-' . $settings["id"],
				'directory_name' => $cache_key,
			) );
			$return = $cached_values;
		}
		return $return;
	}
	
	function get_second_level_accounts(){
		$return["0"] = "-None-";
		asort( $return );
		return $return;
	}
	
	function get_chart_of_accounts_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			$cache_key = 'chart_of_accounts';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_debit_and_credit_details( $settings = array() ){
		if( isset( $settings['id'] ) && $settings['id'] ){
			
			if( isset( $settings['draft'] ) && $settings['draft'] ){
				$cache_key = 'debit_and_credit_draft';
				$cached_values = get_from_cached( array(
					'cache_key' => $cache_key.'-debit_and_credit_draft-'.$settings['id'],
					'directory_name' => $cache_key,
				) );
				return $cached_values;
			}
			
			$cache_key = 'debit_and_credit';
			$cached_values = get_from_cached( array(
				'cache_key' => $cache_key.'-debit_and_credit-'.$settings['id'],
				'directory_name' => $cache_key,
			) );
			return $cached_values;
		}
	}
	
	function get_cash_book_accounts(){
		$return = array();
		$return3 = get_account_children( array( "id" => "cash_book", "parent" => "parent1" ) );
		//$return2 = get_account_children( array( "id" => "main_cash", "parent" => "parent1" ) );
		$return4 = get_account_children( array( "id" => "cash_at_hand", "parent" => "parent1" ) );
		
		if( is_array( $return4 ) ){
			$return1 = array_merge( $return3, $return4 );
		}else{
			$return1 = $return3;
		}
		
		if( ! empty( $return1 ) ){
			foreach( $return1 as $key => $val ){
				if( strtolower( trim($val) ) != "bank" && strtolower( trim($val) ) != "bank ()" ){
					$return[ $key ] = $val;
				}
			}
		}
		
		return $return;
	}
	
	function get_liabilities_accounts(){
		$return = get_account_children( array( "id" => "payroll_liabilities", "parent" => "parent1" ) );
		$return1 = get_account_children( array( "id" => "13122036520", "parent" => "parent1" ) );
		return array_merge( $return1, $return );
	}
	
	function get_prepaid_expense_accounts(){
		return get_account_children( array( "id" => "13800790262", "parent" => "parent1" ) );
	}
	
	function get_contra_asset_accounts(){
		return get_account_children( array( "id" => "13809099149", "parent" => "parent1" ) );
	}
	
	function get_fixed_asset_accounts(){
		$return = get_account_children( array( "id" => "13809097168", "parent" => "parent1" ) );
		if( ! empty( $return ) )return $return;
		
		//catholic
		return get_account_children( array( "id" => "14164751457", "parent" => "parent1" ) );
	}
	
	function get_income_accounts(){
		$return = array();
		$return1 = get_account_children( array( "id" => "revenue", "parent" => "parent1" ) );
		return $return1;
	}
	
	function get_security_question_details( $settings = array() ){
		$cache_key = 'security_question';
		if( isset( $settings["id"] ) && $settings["id"] ){
			$return = get_from_cached( array(
				'cache_key' => $cache_key,
				'directory_name' => $cache_key,
			) );
			
			if( isset( $return[ $settings["id"] ] ) )return $return[ $settings["id"] ];
		}
	}
	
	function get_security_questions(){
		$cache_key = 'security_question';
        $return = get_from_cached( array(
            'cache_key' => $cache_key,
			'directory_name' => $cache_key,
        ) );
        if( isset( $return['all'] ) )return $return['all'];
	}
	
	function get_renewal_period_type(){
		return array(
			'once' => "One-time Fee",
			'once_members' => "One-time Fee (Members Only)",
			'daily' => "Daily",
			'monthly' => "Monthly",
			'yearly' => "Yearly",
		);
	}
	
	function get_pay_roll_options(){
		return array(
			'none' => 'None',
			'pension' => 'Calculate Pension(s)',
			'tax' => 'Calculate Paye (Tax)',
			'contributions' => 'Include NSITF, ITF',
		);
	}
	
	function get_options_type(){
		$return = array(
			'banks' => 'Banks',
			'organization' => 'Organization',
			'divisions' => 'Divisions',
			'professions' => 'Professions',
			'means_of_identification' => 'Means of Identification',
			'item_classification' => 'Item Classification',
			'customer_category' => 'Customer Category',
			'staff_category' => 'Staff Category',
			'pfa' => 'Pension Fund Managers',
			'health_insurance' => 'Health Insurance',
			'housing_scheme' => 'Housing Scheme',
			'tax_office' => 'Tax Office',
			'performance_appraisal' => 'Type of Performance Appraisal',
			'dependents_relationship' => 'Dependent Relationship',
			'next_of_kin_relationship' => 'Next of Kin Relationship',
			'training_category' => 'Training Category',
			'training_type' => 'Training Types',								  
			'paye_tax' => 'Paye Tax Scale',
			'payment_category' => 'Payment Category',
			'purchase_category' => 'Purchase Category',
			'security_question' => 'Security Questions',
			'fixed_asset_type' => 'Fixed Asset Type',
			'language' => 'Language',
			'ethnic_group' => 'Ethnic Groups',
			'race' => 'Racial Origin',
			'help_bulider' => 'Help Builder Category',
			'institution_types' => 'Institution Types',
			'religion' => 'Religion',

			'hmo_profile_type' => 'HMO Profile Type',								
			'purchase_category' => 'Purchase Categpry',
			'hmo_interest_type' => 'HMO Profile Interest Type',
			'pharmacy_instructions' => 'Pharmacy Instructions',

			'cause_of_death' => 'Cause of Death',
			'mode_of_delivery' => 'Mode of Delivery',
			'customer_titles' => 'Customer Titles',
			'skin_integrity' => 'Skin Integrity',
			'stool_type' => 'Stool Type',

			'hear_about_us' => 'Hear About Us',
			'specimen_bottle_type' => 'Specimen Bottle Type',
			'vitals_123_options' => 'Vitals 123 Options',
			'neonatal_resuscitation' => 'Neonatal Resuscitation',
			'presentation' => 'Presentation',
			'labour' => 'Labour',
			'placenta_delivery' => 'Placenta Delivery',

			'pace_maker_fitted' => 'Pace Maker Fitted Options',
			// 'mode_of_delivery' => 'Mode of Delivery',
			'waterlow_signs' => 'Waterlow Signs to Look for',
			'cannula_inertion_sites' => 'Cannula Insertion Sites',
			'formation' => 'Formations',

			'category_groups' => 'Category Groups',
			'rank' => 'Rank/Designation'
		);
		
		if( get_use_imported_goods_settings() ){
			$return[ "cfr" ] = 'CFR';
			$return[ "local_charges" ] = 'Local Charges';
		}
		
		if( function_exists( HYELLA_PACKAGE."_get_options_type" ) ){
			$f = HYELLA_PACKAGE."_get_options_type";
			$r1 = $f();
			$return = array_merge( $return, $r1 );
		}
		
		asort( $return );
		return $return;
	}
	
	function get_professions_options_value(){
		$return = get_record_details( array( "id" => 'professions', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		if( ! empty( $return ) )asort( $return );
		return array_merge( array( "none" => "None" ), $return );
	}
	
	function get_organization_options_value(){
		$return = get_record_details( array( "id" => 'organization', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return $return;
	}
	
	function get_divisions_options_value(){
		$return = get_record_details( array( "id" => 'divisions', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return $return;
	}
	
	function get_means_of_identification_options_value(){
		$return = get_record_details( array( "id" => 'means_of_identification', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return $return;
	}
	
	function get_security_question_options_value(){
		$return = get_record_details( array( "id" => 'security_question', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return $return;
	}
	
	function get_payment_category_options_value(){
		$return = get_record_details( array( "id" => 'payment_category', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		
		if( function_exists("get_enforce_payment_category_settings") && get_enforce_payment_category_settings() ){
			return array_merge( array( "" => "--Specify Payment Category--" ), $return );
		}
		
		return array_merge( array( "none" => "No Payment Category" ), $return );
		
	}
	
	function get_fixed_asset_type_options_value(){
		$return = get_record_details( array( "id" => 'fixed_asset_type', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return array_merge( array( "none" => "No Fixed Asset Type" ), $return );
		
	}
	
	function get_item_classification_options_value(){
		$return = get_record_details( array( "id" => 'item_classification', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return array_merge( array( "none" => "No Classification" ), $return );
		
	}
	
	function get_tax_office_options_value(){
		$return = get_record_details( array( "id" => 'tax_office', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return array_merge( array( "none" => "No Tax Office" ), $return );
		
	}
	
	function get_housing_scheme_options_value(){
		$return = get_record_details( array( "id" => 'housing_scheme', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return array_merge( array( "none" => "No Housing Scheme" ), $return );
		
	}
	
	function get_pfa_options_value(){
		$return = get_record_details( array( "id" => 'pfa', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return array_merge( array( "none" => "No PFA" ), $return );
		
	}
	
	function get_health_insurance_options_value(){
		$return = get_record_details( array( "id" => 'health_insurance', "table" => "banks" ) );
		if( ! is_array( $return ) )$return = array();
		return array_merge( array( "none" => "No HEALTH INSURANCE" ), $return );
	}
	
	function get_paye_tax_options_value(){
		return get_record_details( array( "id" => 'paye_tax', "table" => "banks" ) );
	}
	
	function get_cfr_options_value(){
		return get_record_details( array( "id" => 'cfr', "table" => "banks" ) );
	}
	
	function get_local_charges_options_value(){
		return get_record_details( array( "id" => 'local_charges', "table" => "banks" ) );
	}
	
	function get_customer_category(){
		return get_record_details( array( "id" => 'customer_category', "table" => "banks" ) );
	}
	
	function get_paye_tax_options_value_details(){
		$return = get_paye_tax_options_value();
		
		$sval = array();
		if( is_array( $return ) && !empty( $return ) ){
			foreach( $return as $key => $val ){
				$a = get_record_details( array( "id" => $key, "table" => "banks" ) );
				if( isset( $a["serial"] ) ){
					$sval[ doubleval( $a["serial"] ) ] = $a;
				}
			}
			ksort( $sval );
		}
		return $sval;
	}
	
	function get_renewal_period_transformed( $type = '' ){
		switch( $type ){
		case "daily":
			return "day";
		break;
		case "monthly":
			return "month";
		break;
		case "yearly":
			return "year";
		break;
		}
	}
	
	function get_billing_cycle(){
		return array(  
			"yearly" => "Yearly",
			"monthly" => "Monthly", 
			"weekly" => "Weekly",
			"daily" => "Daily",
		);
	}
	
	function get_billing_cycle_text( $cycle ){
		switch( $cycle ){
		case "daily":
			return "Day";
		break;
		case "weekly":
			return "Week";
		break;
		case "monthly":
			return "Month";
		break;
		case "yearly":
			return "Year";
		break;
		}
	}
	
	function get_billing_cycle_seconds( $cycle ){
		switch( $cycle ){
		case "daily":
			return ( 24 * 3600 );
		break;
		case "weekly":
			return ( 24 * 3600 * 7 );
		break;
		case "monthly":
			return ( 24 * 3600 * 31 );
		break;
		case "yearly":
			return ( 24 * 3600 * 365 );
		break;
		}
	}
	
	function get_billing_cycle_years( $cycle ){
		switch( $cycle ){
		case "daily":
			return 365.4;
		break;
		case "weekly":
			return 52;
		break;
		case "monthly":
			return 12;
		break;
		case "yearly":
		default:
			return 1;
		break;
		}
	}
	
	function get_form_fields(){
		return array( 
			"text" => "Text Box",
			"select" => "Select Box",
			'multi-select' => 'Multi-Select',
			"date-5" => "Date",
			"date-5time" => "Date (Show Time)",
			"time" => "Time",
			"textarea" => "Text Area",
			"password" => "Password",
			"old-password" => "Old Password",
			"tel" => "Telephone",
			"email" => "Email",
			"decimal" => "Decimals",
			"decimal_long" => "Decimals (LONG)",
			"number" => "Numbers",
			"currency" => "Currency",
			"calculated" => "Calculated",
			"file" => "File",
			"picture" => "Picture",
			'textarea-unlimited' => 'Textarea Unlimited (SMALL)',
			'textarea-unlimited-med' => 'Textarea Unlimited (MEDIUM)',
			'single_json_data' => 'Single JSON Data',
			'multiple_json_data' => 'Multiple JSON Data',
			'radio' => 'Radio Button',
			'checkbox' => 'Checkbox Button',
			"field_group" => "Field Group",
			"html" => "HTML",
			"color" => "Color",
		);
	}
	
	function get_form_field_access_control(){
		return array( 
			"no-edit" => "Cannot Edit",
			"no-create" => "Cannot Create",
			"no-api-field" => "Exclude From API Read Fields",
			"no-api-create" => "Exclude From API Create Fields",
		);
	}

	function get_form_field_options_source(){
		return array( 
			0 => "None",
			1 => "Function",
			2 => "List Box Class",
		);
	}
	
	function get_field_display_options(){
		return array( 
			"display-in-table-row" => "Display in Table",
			"do-not-display-in-table" => "Do not display in Table",
		);
	}

	function get_field_appearance(){
		return array( 
			"show" => "Show",
			"hide" => "Hide",
		);
	}
	
	function get_units_of_measure(){
		$return = array(
			"" => "",
			"kg" => "Kg",
			"grams" => "Grams",
			"meters" => "Meters",
			"square_meter" => "Square Meter",
			"litres" => "Litres",
			"millilitres" => "Millilitres",
			"centilitres" => "Centilitres",
			"quantity" => "Quantity",
			"boxes" => "Boxes",
			"bundles" => "Bundles",
			"packets" => "Packets",
			"pieces" => "Pieces",
			"bottle" => "Bottle",
			"dose" => "Dose",
			"tabs" => "Tabs",
			"syrup" => "Syrup",
			"suppositories" => "Suppositories",
			"infusion" => "Infusion",
			"cream" => "Cream",
			"ampoule" => "Ampoule",
			"vial" => "Vial",
			"card" => "Card",
			"blister" => "Blister",
			"rim" => "Rim",
			"carton" => "Carton",
			"cannister" => "Cannister",
		);
		ksort( $return );
		return $return;
	}
	
	function get_project_expense_status(){
		return array(
			"draft" => "Draft",
			"approved" => "Approved",
			"paid" => "Paid",
		);
	}
	
	function get_project_categories(){
		return array(
			"single_client" => "Single Client",
			"multiple_client" => "Multiple Clients",
		);
	}
	
	function get_project_status(){
		return array(
			"in-progress" => "In-progress",
			"completed" => "Completed",
			"suspended" => "Suspended",
			"abandoned" => "Abandoned",
		);
	}
	
	function get_project_activity(){
		return array(
			"client_brief" => "Client Brief",
			"milestone" => "Milestone",
			"admin" => "Admin",
			"labour" => "Labour",
			"material" => "Material",
			"others" => "Others",
			"" => "",
			"sub_architecture" => "Architecture",
			"sub_structural_engineer" => "Structural Engineer",
			"sub_qty_surveyor" => "Quantity Surveyors",
			"sub_mechanical_electrical" => "Mechanical / Electrical Engineer",
		);
	}
	
		
	function get_customers_with_all(){
		$return = get_customers();
		return array_merge( array( "all" => "ALL CUSTOMERS", "use_category" => "USE CUSTOMERS CATEGORY" ) , $return );
	}
	
	function get_items_for_sale(){
		return get_items_category_type( "produced_goods", "purchased_goods", "service", "crate_of_eggs" );
	}

	function get_items_category_type( $type, $type1 = "", $type2 = "", $type3 = "" ){
		$cache_key = 'items';
        $return = get_from_cached( array(
            'cache_key' => $cache_key."-grouped-category",
			'directory_name' => $cache_key,
        ) );
		
		if( $return ){
			$r1 = array();
			$p = get_product_types();
			foreach( $return as $k => $v ){
				if( ( $k == $type || $k == $type1 || $k == $type2 || $k == $type3 ) ){
					$r1 = array_merge( $r1, $v );
				}
			}
			$return = $r1;
		}
		
		return $return;
	}
	
	function get_table_classification(){
		return array(
			"table" => "Database Table",
			"plugin_table" => "Plugin Table",
			"plugin" => "Plugin",
			"package" => "Package",
			"single_json_data" => "Single JSON Data",
			"multiple_json_data_horizontal" => "Multiple JSON Data (Horizontal Display)",
			"multiple_json_data_vertical" => "Multiple JSON Data (Vertical Display)",
		);
	}
	
	function get_debit_and_credit_info( $txv = array() ){
		$price = isset( $txv["amount"] )?$txv["amount"]:0;
		$fx_comment = '';
		
		$default_currency = get_default_currency_settings();
		$default_exchange_rate = 1;
		
		//if( $price && isset( $txv["currency"] ) && $txv["currency"] != $default_currency && isset( $txv["exchange_rate"] ) && doubleval( $txv["exchange_rate"] ) ){
		if( $price && isset( $txv["exchange_rate"] ) && doubleval( $txv["exchange_rate"] ) ){
			$ex = doubleval( $txv["exchange_rate"] );
			if( $ex != 1 ){
				$fx_comment = strtoupper( $txv["currency"] ) . ' ' . format_and_convert_numbers( $price, 4 ) . ' at ' . format_and_convert_numbers( $ex, 4 );
				$price = $price * $ex;
			}
			/* 
			if( isset( $txv["currency"] ) && $txv["currency"] == $default_currency ){
				$fx_comment = '';
			} */
		}
		
		$show_account2 = 0;
		$key1 = '';
		$key2 = '';
		$key3 = '';
		$table = '';
		$account = $txv["account"];
		$title_prefix = '';
		$title_prefix_full = '';
		
		$title = "";
		switch( $txv["account_type"] ){
		case "cost_of_goods_sold":
			$title_prefix = 'CGS';
			$key1 = 'name';
			$table = 'category';
			
			if( isset( $txv["account2"] ) && $txv["account2"] ){
				$account = $txv["account2"];
				$table = "items";
				$key1 = "description";
			}
		break;
		case "parish":
		case "priest":
			$key1 = 'name';
			$table = $txv["account_type"];
		break;
		case "hmo_ledger":
			$key1 = 'name';
			$table = "hmo_organization";
			
			$title_prefix = 'HMO';
			$title_prefix_full = 'HMO Ledger';
			
			if( isset( $txv["account2"] ) && $txv["account2"] ){
				$account = $txv["account2"];
				$table = "hmo";
				$key1 = "name";
			}
		break;
		case "hmo_fee_for_service":
			$key1 = 'name';
			$table = "hmo";
			
			$title_prefix = 'HMO';
			$title_prefix_full = 'HMO Fee for Service';
		break;
		case "package_ledger":
			$key1 = 'name';
			$table = "packaged_services";
			
			$title_prefix = 'PK';
			$title_prefix_full = 'PACKAGES';
		break;
		case "customers_control":
		case "city_ledger":
		case "accounts_receivable":
			$key1 = 'name';
			$table = 'customers';
			
			$title_prefix = 'AR';
			
			switch( $txv["account_type"] ){
			case "city_ledger":
				$title_prefix = 'CL';
				$title_prefix_full = 'Guest Ledger';
			break;
			case "customers_control":
				$title_prefix = 'CC';
				$title_prefix_full = 'Customers Control Account';
				$key2 = 'first_name';
			break;
			case "accounts_receivable":
				$key2 = 'first_name';
				$key3 = 'file_number';
			break;
			}
			
			if( isset( $txv["account_source"] ) && $txv["account_source"] ){
				switch( $txv["account_source"] ){
				case "users":
					$table = $txv["account_source"];
					$key1 = 'firstname';
					$key2 = 'lastname';
				break;
				case "vaccination":
					$table = $txv["account_source"];
					$key1 = 'name';
					$key2 = 'first_name';
					$key3 = 'file_number';
				break;
				case "priest":
				case "parish":
					$table = $txv["account_source"];
				break;
				}
			}
			
		break;
		case "onetime":
			$title = $txv['account'];
		break;
		case "inventory_marketing_expense":
			$title_prefix = 'MKT';
			$key1 = 'name';
			$table = 'category';
			
			if( isset( $txv["account2"] ) && $txv["account2"] ){
				$account = $txv["account2"];
				$table = "items";
				$key1 = "description";
			}
		break;
		case "damaged_items":
		case "damaged_goods":
			$title_prefix = 'DAMAGE';
			$key1 = 'name';
			$table = 'category';
			
			if( isset( $txv["account2"] ) && $txv["account2"] ){
				$account = $txv["account2"];
				$table = "items";
				$key1 = "description";
			}
		break;
		case "goods_in_transit_control":
		case "inventory":
		case "intangible_asset":
			$title_prefix = 'INV';
			$key1 = 'name';
			$table = 'category';
			
			switch( $txv["account_type"] ){
			case "goods_in_transit_control":
				$title_prefix = 'GIT';
				$title_prefix_full = 'Goods In-transit';
			break;
			case "intangible_asset":
				$title_prefix = 'IA';
				$title_prefix_full = 'Intangible Asset';
			break;
			}
			
			if( isset( $txv["account2"] ) && $txv["account2"] ){
				$account = $txv["account2"];
				$table = "items";
				$key1 = "description";
			}
		break;
		case "revenue_category":
			$title_prefix = 'REV';
			$key1 = 'name';
			$table = 'category';
			
			if( isset( $txv["account2"] ) && $txv["account2"] ){
				$account = $txv["account2"];
				$table = "items";
				$key1 = "description";
				$show_account2 = 1;
			}
		break;
		case "suppliers_control":
		case "account_payable":
			$table = "vendors";
			$key1 = "name_of_vendor";
			
			$title_prefix = 'AP';
			
			switch( $txv["account_type"] ){
			case "suppliers_control":
				$title_prefix = 'SC';
				$title_prefix_full = 'Suppliers Control Account';
			break;
			}
			
		break;
		case "cash_book":
			if( isset( $txv["data"] ) && $txv["data"] ){
				$d = json_decode( $txv["data"], true );
				if( isset( $d["action"] ) && $d["action"] == "refund" ){
					$title_prefix_full = 'REFUND';
				}
			}
		break;
		}
		
		$txv["account_type2"] = $txv["account_type"];
		
		if( $account && $table ){
			$st = get_record_details( array( "id" => $account, "table" => $table ) );
			if( $key1 && isset( $st[ $key1 ] ) ){
				$title = $st[ $key1 ];
				if( $key2 && isset( $st[ $key2 ] ) ){
					$title .= ' ' . $st[ $key2 ];
				}
				if( $key3 && isset( $st[ $key3 ] ) ){
					$title .= ' [' . $st[ $key3 ] . ']';
				}
			}
			
			if( $show_account2 && ! $title ){
				$title = $account;	//condition for open food / beverage
			}
		}
		
		if( ! $title ){
			$acc = get_chart_of_accounts_details( array( "id" => $txv['account'] ) );
			if( isset( $acc[ "title" ] ) && $acc[ "title" ] ){
				$txv["account_type2"] = $acc[ "type" ];
				$title = $acc[ "title" ];
				if( ! ( isset( $txv["skip_code"] ) && $txv["skip_code"] ) ){
					if( $acc[ "code" ] ){
						$title .= ' ['. $acc[ "code" ] . ']';
					}
				}
			}
		}
		//$title = $txv["account_type"];
		$subtitle = '';
		if( isset( $txv['comment'] ) && $txv['comment'] ){
			$subtitle = '<br />' . $txv['comment'];
		}
		if( $fx_comment ){
			$subtitle = '<br />' . $fx_comment;
		}
		
		if( isset( $txv["return_title_only"] ) && $txv["return_title_only"] ){
			return $title;
		}
		
		return array(
			"title_prefix_full" => $title_prefix_full,
			"title_prefix" => $title_prefix,
			"title" => $title,
			"subtitle" => $subtitle,
			"amount" => $price,
			"account_type2" => $txv["account_type2"],
			"account_type" => $txv["account_type"],
			"foreign_exchange_comment" => $fx_comment,
		);
	}
	
	function get_name_of_referenced_record2( $data = array() ){
		$r = '';
		if( isset( $data["return_data"] ) && $data["return_data"] ){
			$r = array();
		}
		if( isset( $data["id"] ) && $data["id"] && isset( $data["table"] ) && $data["table"] ){
			$exp = explode( ',', $data["id"] );
			if( ! empty( $exp ) ){
				foreach( $exp as $ex ){
					$dx = $data;
					$dx[ 'id' ] = $ex;

					if( $r ){
						$r .= ', ';
					}
					
					if( isset( $data["return_data"] ) && $data["return_data"] ){
						$r[ $ex ] = get_name_of_referenced_record( $dx );
					}else{
						$r .= get_name_of_referenced_record( $dx );
					}
					
				}
			}
		}
		return $r;
	}

	function get_name_of_referenced_record( $data = array() ){
		$name = '';
		$d2 = array();
		
		if( isset( $data["id"] ) && $data["id"] && isset( $data["table"] ) && $data["table"] ){
			$data["force"] = 1;
			$c = get_record_details( $data );
			// print_r($data );exit;
			$d2 = $c;
			
			$name = $data["id"];
			//22-feb-23
			if( isset( $data["valid_only"] ) && $data["valid_only"] ){
				$name = '';
			}
				
			$link_action = $data["table"];
			$link_todo = 'view_invoice';
			$params = isset( $data["params"] )?$data["params"]:'';
			
			if( isset( $data["reference_number"] ) && $data["reference_number"] ){
				$prefix = '';
				
				switch( $data["table"] ){
				case "prescription2":
					$prefix = 'PRS';
				break;
				case "sales":
					$prefix = 'S';
				break;
				case "stock_request":
					$prefix = 'R';
				break;
				case "operation_booking":
					$prefix = 'OB';
				break;
				case "post_operative_note":
					$prefix = 'PON';
				break;
				case "packaged_services":
					$prefix = 'PK';
				break;
				case "icu":
					$prefix = 'ICU';
				break;
				case "scbu":
					$prefix = 'SCBU';
				break;
				case "stock_request":
					$prefix = 'R';
				break;
				case "vendor_bill":
					$prefix = 'VI';
				break;
				case "parish_transaction":
					$prefix = 'T';
				break;
				case "expenditure":
					$prefix = 'P';
					
					if( isset( $c["status"] ) ){
						switch( $c["status"] ){
						case "stock":
						case "stocked":
						case "unvalidated_stocked":
							$prefix = 'GR';
						break;
						case 'draft-purchase-order':
						case 'draft-purchase-ordered':
							$prefix = 'DP';
						break;
						case "returned":
							$prefix = 'RG';
						break;
						}
					}
				break;
				}
				
				$source = '';
				if( isset( $c["serial_num"] ) ){
					$source = mask_serial_number( $c["serial_num"], '#' . $prefix );
				}
				
				if( isset( $data["link"] ) && $data["link"] ){
					$source = '<a href="#" class="custom-single-selected-record-button" override-selected-record="'.$c["id"].'" action="?action='. $link_action .'&todo=' . $link_todo . '" title="View Details">' . $source . '</a>';
				}
				
				return $source;
			}
			
			switch( $data["table"] ){
			case "loan_types":
			case "items":
			case "sub_items":
				$key = "description";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
				}else{
					$data[ 'table' ] = 'sub_items';
					$c = get_record_details( $data );
					if( isset( $c[ $key ] ) ){
						$name = $c[ $key ];
					}
				}
				
				if( isset( $data["link"] ) && $data["link"] ){
					$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo=view_the_details" override-selected-record="'. ( isset( $c["id"] ) ? $c["id"] : '' ) .'">' . $name . '</a>';
				}
				
				$d2["name"] = $name;
			break;
			case "chart_of_accounts":
				$key = "title";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
				}
			break;
			case "access_roles":
				$key = "role_name";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
				}
			break;
			case "property_type":
				$key = "property_type";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
				}
			break;
			case "consultation_template":
				$key = "template";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
				}
			break;
			case "icd":
				$key = "title";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
				}
				// print_r( $data );exit;
				
				$key = "code";
				if( isset( $c[ $key ] ) ){
					$name .= ' [' . $c[ $key ] . ']';
				}
			break;
			case "enrolment_device":
				$key = "model";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
				}
			break;
			case "vaccination":
				$key = "name";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
				}
				
				$key = "first_name";
				if( isset( $c[ $key ] ) ){
					$name .= ' ' . $c[ $key ];
				}
				
				$key = "other_name";
				if( isset( $c[ $key ] ) ){
					$name .= ' ' . $c[ $key ];
				}
				
				$key = "file_number";
				if( isset( $c[ $key ] ) ){
					$name .= ' [' . $c[ $key ] . ']';
				}
			break;
			case "ward":
				$key = "name";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
				}
				
				$key = "building";
				if( ! ( isset( $data["hide_building"] ) && $data["hide_building"] ) ){
					if( isset( $c[ $key ] ) && $c[ $key ] ){
						$delimiter = isset( $data["delimiter"] )?$data["delimiter"]:' ';
						
						$name = get_name_of_referenced_record( array( "id" => $c[ $key ], "table" => "banks" ) ) . $delimiter . $name;
					}
				}
				
			break;
			case "parish":
			case "priest":
			case "customers":
				$key = "name";
				$key2 = "first_name";
				
				switch( get_package_option() ){
				case "blood-hound":
					$key = "first_name";
					$key2 = "last_name";
				break;
				}
				
				$cflg = '';

				if( isset( $c[ 'status' ] ) && $c[ 'status' ] ){
					switch( $c[ 'status' ] ){
					case 'deceased':
						if( isset( $c["status_time"] ) && $c["status_time"] ){
							$gset = intval( get_general_settings_value( array( "key" => "NUMBER OF HOURS TO LABEL PATIENT DECEASED AFTER DEATH", "table" => 'hospital' ) ) );
							$gtm = 60*60*$gset;
							$adlabelStyle = 1;
							if( isset( $data[ 'html_friendly' ] ) && $data[ 'html_friendly' ] ){
								$adlabelStyle = 0;
							}
							if( ( doubleval( $c["status_time"] ) + $gtm ) < time() ){
								if( $adlabelStyle ){
									$cflg = " <sup class='text-danger'>Deceased: ".date("j.M.y", $c["status_time"] )."</sup>";
								}else{
									$cflg = ' *Deceased: '.date("j.M.y", $c["status_time"] ).'*';
								}
							}
						}
					break;
					}
				}
			
				if( isset( $c[ $key ] ) ){
					$d2 = $c;
					
					if( isset( $data["return_patient_hmo"] ) && $data["return_patient_hmo"] ){
						$hmo_id = '';
						
						if( isset( $c[ "type" ] ) && $c[ "type" ] == "dependent" && isset( $c[ "principal" ] ) && $c[ "principal" ] ){
							$pp = get_record_details( array( "id" => $c[ "principal" ], "table" => "customers", "force" => 1 ) );
							if( isset( $pp["type_organisation"] ) ){
								$hmo_id = $pp["type_organisation"];
							}
						}else{
							//if( isset( $c["type"] ) && $c[ "type" ] != "private" ){
								if( isset( $c["type_organisation"] ) && $c["type_organisation"] && $c["type_organisation"] != "none" ){
									$hmo_id = $c["type_organisation"];
								}
							//}
						}
						
						return $hmo_id;
					}
					
					if( isset( $c[ $key2 ] ) && $c[ $key2 ] ){
						$c[ $key ] .= ' ' . $c[ $key2 ]; 
					}
					
					if( isset( $c["file_number"] ) && $c["file_number"] ){
						$c["serial_num"] = $c["file_number"];
						$d2["file_number"] = $c["file_number"];
					}
					$name = $c[ $key ];
					
					if( ! isset( $data["no_serial_num"] ) ){
						$name .= ' ['.$c["serial_num"].']';
					}
					
					if( isset( $c["principal_file_number"] ) && $c["principal_file_number"] ){
						$name = $c[ $key ] . ' ['.$c["serial_num"].'-'.$c["principal_file_number"].']';
					}
					
					if( isset( $data["link"] ) && $data["link"] ){
						$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo=view_customer_details&show_financial_info=1'.$params.'" override-selected-record="'.$c["id"].'">' . $name . '</a>';
					}

					if( $cflg && ! ( isset( $data[ 'no_flag' ] ) && $data[ 'no_flag' ] ) ){
						$name .= $cflg;
					}
					
					if( isset( $c["date_of_birth"] ) && doubleval( $c["date_of_birth"] ) ){
						$dage = get_age( $c["date_of_birth"], 0, 0, 1 );
						$d2["age"] = isset( $dage["age"] )?$dage["age"]:'';
						$d2["age_data"] = $dage;
					}
					
					if( isset( $data["age"] ) && $data["age"] ){
						if( isset( $d2["age"] ) ){
							$name .= '<br /><strong>Age:</strong> ' . $d2["age"];
							
							$sx = get_sex();
							$name .= ' - <strong>'. ( isset( $sx[ $d2["sex"] ] )?$sx[ $d2["sex"] ]:$d2["sex"] ) .'</strong> ';
						}
					}
					
					if( isset( $c["type"] ) && $c["type"] == "dependent" && isset( $data["principal"] ) && $data["principal"] && isset( $c["principal"] ) && $c["principal"] ){
						if( isset( $data["return_principal"] ) && $data["return_principal"] ){
							return $c["principal"];
						}
						
						$data["type"] = 0;
						$d2["principal_details"] = get_name_of_referenced_record( array( "id" => $c["principal"], "table" => "customers", "link" => 1, "type" => 1 ) );
						$name .= '<br /><br /><small><strong>Principal</strong><br />' . $d2["principal_details"] . '</small>';
					}
					
					if( isset( $data["return_principal"] ) && $data["return_principal"] ){
						return 0;
					}
					
					//if( isset( $c["type"] ) && $c[ "type" ] != "private" ){
						if( isset( $data["type"] ) && $data["type"] ){
							$d2["organization_id"] = '';
							
							if(  isset( $c["type_organisation"] ) && $c["type_organisation"] && $c["type_organisation"] != "none" ){
								
								$d2["organization"] = get_name_of_referenced_record( array( "id" => $c["type_organisation"], "table" => "hmo" ) );
								
								if( $data["type"] == 1 ){
									$name .= '<br />' . $d2["organization"];
								}
								
								if( isset( $c["type_id"] ) && $c["type_id"] ){
									$d2["organization_id"] = $c["type_id"];
									
									if( $data["type"] == 1 ){
										$name .= ' - ' . $c["type_id"];
									}
								}
							}else{
								if( isset( $c["type"] ) && $c["type"] ){
									$d2["organization"] = get_select_option_value( array( "id" => $c["type"], "function_name" => "get_hospital_hmo_types2" ) );
									
									if( $data["type"] == 1 ){
										$name .= '<br />' . $d2["organization"];
									}
								}
							}
						}
					//}
					
					$d2["name"] = $name;
				}
				
			break;
			case "assets":
				$key = "description";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ] . ' ['.$c["serial_num"].']';
					
					if( isset( $data["link"] ) && $data["link"] ){
						$todo = 'view_asset_schedule_details';
						
						if( isset( $data["report_display_option"] ) ){
							switch( $data["report_display_option"] ){
							case "summary_view":
							break;
							default:
								$todo = 'view_asset_schedule';
							break;
							}
						}
						
						$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo='.$todo.'" override-selected-record="'.$c["id"].'">' . $name . '</a>';
					}
					
					$d2["name"] = $name;
				}
			break;
			case "vendors":
				$key = "name_of_vendor";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ] . ' ['.$c["serial_num"].']';
					if( defined("HYELLA_REMOVE_VENDOR_SERIAL_NUM") && HYELLA_REMOVE_VENDOR_SERIAL_NUM ){
						$name = $c[ $key ];
					}
					
					if( isset( $data["link"] ) && $data["link"] ){
						$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo=view_vendor_details" override-selected-record="'.$c["id"].'">' . $name . '</a>';
					}
					
					$d2["name"] = $name;
				}
			break;
			case "users":
			case "users_employee_profile":
				$key = "firstname";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
					$key = 'lastname';
					if( $c[ $key ] )$name .= ' ' . $c[ $key ];
					
					if( isset( $data["link"] ) && $data["link"] ){
						$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo=view_customer_details" override-selected-record="'.$c["id"].'">' . $name . '</a>';
					}

					$d2["name"] = $name;
				}
			break;
			case "enrolment_agent":
				$key = "agentfirstname";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
					$key = 'agentlastname';
					if( $c[ $key ] )$name .= ' ' . $c[ $key ];
					
					if( isset( $data["link"] ) && $data["link"] ){
						$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo=view_customer_details" override-selected-record="'.$c["id"].'">' . $name . '</a>';
					}

					$d2["name"] = $name;
				}
			break;
			case "tele_health_customer":
			case "crm_client":		 
				$key = "first_name";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
					$key = 'last_name';
					if( $c[ $key ] )$name .= ' ' . $c[ $key ];
					
					if( isset( $data["link"] ) && $data["link"] ){
						$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&nwp_action='.$data["table"].'&nwp_todo=view_details&action=nwp_health&todo=execute" override-selected-record="'.$c["id"].'">' . $name . '</a>';
					}

					$d2["name"] = $name;
				}
			break;
			case "people":
				$key = "first_name";
				if( isset( $c[ $key ] ) ){
					$name = $c[ $key ];
					$key = 'last_name';
					if( $c[ $key ] )$name .= ' ' . $c[ $key ];
					
					if( isset( $data["link"] ) && $data["link"] ){
						// $name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo=view_customer_details" override-selected-record="'.$c["id"].'">' . $name . '</a>';
					}
					
					$d2["name"] = $name;
				}
			break;
			case "orders":
				$key = "serial_num";
				if( isset( $c[ $key ] ) ){
					if( ! isset( $data["prefix"] ) )$data["prefix"] = '';
					
					$name = mask_serial_number( $c[ $key ], $data["prefix"] );
					
					if( isset( $data["link"] ) && $data["link"] ){
						//$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo=view_invoice" override-selected-record="'.$c["id"].'">' . $name . '</a>';
						$pr = get_project_data();
						$name = '<a href="'. $pr["domain_name"] .'print.php?page=print-invoice&record_id='. $c["id"] .'&type='. $data["table"] .'" target="_blank">' . $name . '</a>';
					}
				}
			break;
			case "ecm2":
			case "workflow_settings":
			case "lga_list":
			case "state_list":
			case "country_list":
			case "workflow":
			case "payment_template":
			case "units":
			case "departments":
			case "prescription_formulary":
			case "consultation_rooms":
			case "category":
			case "bed":
			case "leave_types":
			case "banks":
			case "hmo_organization":
			case "hmo":
			case "packaged_services":
			case "stores":
			case "pump":
			default:
				$key = "name";
				if( isset( $c[ $key ] ) ){
					$d2 = $c;
					$name = $c[ $key ];
				}else {
					$key = "title";
					if( isset( $c[ $key ] ) ){
						$d2 = $c;
						$name = $c[ $key ];
					}	
				}
				
				
				if( isset( $data["link"] ) && $data["link"] ){
					$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo=view_the_details" override-selected-record="'.$c["id"].'">' . $name . '</a>';
				}
				
				$d2["name"] = $name;
			break;
			}
		}
		
		if( isset( $data[ 'field_key' ] ) && $data[ 'field_key' ] ){
			$key = $data[ 'field_key' ];
			if( isset( $c[ $key ] ) ){
				$d2 = $c;
				$name = $c[ $key ];
			}
			if( isset( $data["link"] ) && $data["link"] ){
				$name = '<a href="#" class="custom-single-selected-record-button" action="?module=&action='.$data["table"].'&todo=view_the_details" override-selected-record="'.$c["id"].'">' . $name . '</a>';
			}
		}

		$d2["name"] = $name;
		
		if( isset( $data["return_data"] ) && $data["return_data"] ){
			return $d2;
		}
		
		return $name;
	}
	
	function get_account_transactions_summary( $data = array() ){
		
		$billt = 0;
		$payt = 0;
		$balance = 0;
		$text = 'Balance';
		$color = '';
		$debt = 0;
		
		if( isset( $data[ "transaction" ][0]["amount"] ) && $data[ "transaction" ][0]["amount"] ){ 
			if( $data[ "transaction" ][0]["type"] == "debit" ){
				$billt = doubleval( $data[ "transaction" ][0]["amount"] );
			}else{
				$payt = doubleval( $data[ "transaction" ][0]["amount"] );
			}
		}
		
		if( isset( $data[ "transaction" ][1]["amount"] ) && $data[ "transaction" ][1]["amount"] ){ 
			if( $data[ "transaction" ][1]["type"] == "debit" ){
				$billt += doubleval( $data[ "transaction" ][1]["amount"] );
			}else{
				$payt += doubleval( $data[ "transaction" ][1]["amount"] );
			}
		}
		
		$balance = round( $billt , 2 ) - round( $payt , 2 );
		if( $balance > 0 ){
			$text = 'Outstanding Debt';
			$color = '#d32333';
			$debt = 1;
		}
		//$balance = $billt - $payt;
		
		return array(
			"debt" => $debt,
			"color" => $color,
			"text" => $text,
			"bill" => $billt,
			"payment" => $payt,
			"balance" => $balance,
		);
	}
	
	function set_hyella_source_path( $path, $echo = 0 ){
		$attributes = '';
		
		if( get_hyella_development_mode() ){
			$line_style = '1px dashed #f99';
			if( defined( "HYELLA_DEVELOPMENT_STYLE" ) && HYELLA_DEVELOPMENT_STYLE ){
				$line_style = HYELLA_DEVELOPMENT_STYLE;
			}
			
			//$attributes = ' title="'. $path . '" style="border:'.$line_style.'; margin:5px; padding:5px;" ';
			
			$h = ' class="hyella-source-container" hyella-source="'. $path . '" ' . $attributes;
			if( defined( "HYELLA_DEVELOPMENT_FULL" ) && HYELLA_DEVELOPMENT_FULL ){
				$origin = get_nw_req_origin();
				$h .= '><pre>origin: '.$origin.'</pre';
			}
			
			if( $echo )echo $h;
			return $h;
		}
		
		$h = ' style="width:100%;" ';
		if( $echo )echo $h;
		return $h;
	}
	
	function get_stock_request_sending_status(){
		return array(
			"none" => "None",
			"sent" => "Sent to Colleague",
			"return_to_sender" => "Return to Sender",
		);
	}
	
	function get_vendor_bill_status(){
		return array(
			'draft' => 'Draft',
			'returned' => 'Returned',
			'submitted' => 'Submitted',
			
			'pending' => 'Pending Approval (Procurement)',
			'pending-returned' => 'Returned to Procurement',
			
			'pending-audit' => 'Pending Approval (Audit)',
			'pending-audit-returned' => 'Returned to Audit',
			
			'pending-md' => 'Pending Approval (MD)',
			
			'approved' => 'Approved',
			
			'completed' => 'Job Completed',
			'verified' => 'Job Verified',
			
			'invoice_unvalidated' => 'Invoice Pending Validation',
			'invoice_validated' => 'Invoice Validated',
			'paid' => 'Paid',
			
			'cancelled' => 'Cancelled',
		);
	}
	
	function get_vendor_bill_status_for_return(){
		return array(
			'returned' => 'Returned to Creator',
			'pending-returned' => 'Return to Procurement',
			'pending-audit-returned' => 'Return to Audit',
		);
	}
	
	function get_order_by(){
		return array(
			'' => '',
			'desc' => 'Descending Order',
			'asc' => 'Ascending Order',
		);
	}
	
	function get_purchase_state(){
		return array(
			'new' => 'Brand New',
			'refurbished' => 'Refurbished',
			'fairly_used' => 'Fairly Used',
			'used' => 'Used (Working)',
			'need_repairs' => 'Used (Need Repairs)',
		);
	}
	
	function get_types_of_assets_log(){
		return array(
			'pending' => 'Pending Validation of Invoice',
			'new' => 'Pending Documentation',
			'documented' => 'Documentation Complete',
			'revoke' => 'Revoked',
			'reassign' => 'Re-Assigned',
			'assign' => 'Assigned',
			'transfer' => 'Transfer',
			'bulk_transfer' => 'Bulk Transfer',
			'returned' => 'Returned to Vendor',
			'sold' => 'Sold',
			'retired' => 'Retired',
			'reclassified' => 'Reclassified',
			'status_update' => 'Status Update',
			'other_cost' => 'Other Cost',
			'maintenance' => 'Maintenance',
			'insurance' => 'Insurance',
			'impairment' => 'Impairment',
			'revaluation' => 'Re-valuation',
			'depreciation' => 'Depreciation',
		);
	}
	
	function get_asset_retirement_types(){
		return array(
			'decommissioned' => 'Decommissioned',
			'gift' => 'Gift to Staff',
			'destruction' => 'Destruction',
			'accident' => 'Accidents',
			'theft' => 'Theft',
			'others' => 'Others',
		);
	}
	
	function get_message_sending_status(){
		return array(
			'draft' => 'Draft',
			'sending' => 'Sending',
			'sent' => 'Sent',
		);
	}
		
	function get_recurring_message_status(){
		$r = get_report_periods();
		unset( $r["weekly"] );
		$r["0"] = "None";
		krsort( $r );
		
		return $r;
	}
	
	function get_special_message_types(){
		return array(
			'none' => 'None',
			'priest_birthday' => 'Birthday Message for Religious',
			'employees_birthday' => 'Birthday Message for Employees',
		);
	}
	
	function get_transaction_reference_tables(){
		$return = array(
			'sales' => 'Sales',
			'chart_of_accounts' => 'Admin Expenses / Bank Transfers',
			'customer_payments' => 'Journal Posting (Customers)',
			'vendor_payments' => 'Journal Posting (Vendors)',
			'expenditure' => 'Goods Received Notes / Credit Notes',
			'production' => 'Stock Issue / Transfer',
		);
		
		switch( get_package_option() ){
		case "hotel":
			$return["hotel_checkin"] = 'Accommodation';
		break;
		}
		asort( $return );
		
		return $return;
	}
	
	function get_asset_maintenance_status(){
		return array(
			'pending' => 'Pending Maintenance',
			'complete' => 'Maintenance Completed',
			'disabled' => 'Disabled',
		);
	}
	
	function get_asset_insurance_types(){
		return array(
			'onetime' => 'One-time Insurance',
			'auto' => 'Auto Renewed',
		);
	}
	
	function get_stock_request_summary_fields(){
		return array(
			//'type' => 'Type', 
			'destination_store' => 'Request By (Store)', 
			'department' => 'Request By (Department)', 
			'store' => 'Sent To (Store)', 
			'destination_department' => 'Sent To (Department)',
			//'status' => 'Status',
		);
	}
	
	function get_stock_request_status2(){
		$r = get_stock_request_status();
		unset( $r['stock-request-unvalidated'] );
		unset( $r['stock-request-unvalidated-returned'] );
		unset( $r['stock-request-md'] );
		unset( $r['stock-request-audit'] );
		unset( $r['stock-request-audit-returned'] );
		
		unset( $r['stock-request'] );
		
		return $r;
	}
	
	function get_stock_request_status(){
		return array(
			'stock-request-draft' => 'Draft',	//can be edited or sent for approval
			
			'stock-request-returned' => 'Returned',
			
			//'draft_procurement' => 'Draft Procurement',
			'stock-request-unvalidated' => 'Pending Validation (Procurement)',
			'stock-request-unvalidated-returned' => 'Returned to Procurement',
			
			'stock-request-audit' => 'Pending Validation (Audit)',
			'stock-request-audit-returned' => 'Returned to Audit',
			
			'stock-request-md' => 'Pending Validation (MD)',
			
			'transfer' => 'Pending Transfer',
			'stock-request-validated' => 'Approved Request',
			'stock-request' => 'Approved Stock Request',	//can be edited or sent for approval
			
			//'stock-dispatch' => 'Pending Request',
			'processed' => 'Being Processed',
			'processed-i' => 'Issued & Saved',
			'processed-i-po' => 'Issued & Awaiting Purchase',
			'processed-po' => 'Awaiting Purchase',
			'processed-i-preq' => 'Issued & Awaiting Purchase Req.',
			'processed-preq' => 'Awaiting Purchase Req.',
			
			'stock-dispatch-approved' => 'Completed Request',
			'stock-request-approved' => 'Completed Request (PO)',
			
			'submitted' => 'Submitted for Approval',
			'merged' => 'Merged & Cancelled',
			'cancelled' => 'Cancelled',
		);
	}
	
	function get_stock_request_status_for_return(){
		return array(
			'stock-request-returned' => 'Returned to Creator',
			'stock-request-unvalidated-returned' => 'Return to Procurement',
			'stock-request-audit-returned' => 'Return to Audit',
		);
	}
	
	function get_stock_request_types(){
		return array(
			'asset_requisition' => 'Asset Requsition',
			'asset_transfer' => 'Asset Transfer',
			'internal_requisition' => 'Internal Requsition',
			'purchase_requisition' => 'Purchase Requsition',
			'purchase_requisition_s' => 'Request for Services',
		);
	}
	
	//moved to budget class: 28-dec-22
	/* function get_budget_types(){
		return array(
			'organization' => 'Organization',
			'branch' => 'Branch',
			'department' => 'Branch & Department',
			'department_all' => 'Organization Department',
		);
	} */
	
	function get_person_title(){
		$return = array(
			"" => "",
			"mr" => "Mr",
			"mrs" => "Mrs",
			"miss" => "Miss",
			"master" => "Master",
			"chief" => "Chief",
			"dr" => "Dr.",
			"engr" => "Engr.",
			"prof" => "Prof.",
			"hon" => "Hon.",
		);
		
		asort( $return );
		
		return $return;
	}
	
	
	function get_audit_trail_report_types(){
		return array( 
			"daily" => "Daily Analysis",
			"monthly" => "Monthly Analysis",
			//"yearly" => "Yearly Analysis",
		);
	}
	
	function get_audit_trail_report_actions(){
		return array( 
			"read" => "Read",
			"sql_error" => "SQL Error",
			"write" => "Create",
			"execute" => "Execute",
			"insert" => "Insert",
			"update" => "Update",
			"delete" => "Delete",
			"file" => "File Upload",
			"page_view" => "Page View",
			"login" => "Login",
			'rebuild_cache' => 'Rebuild Cache', 
			'console' => 'Console',
		);
	}
	
	function get_audit_trail_report_format(){
		return array( 
			"day" => "Based on Timeline",
			"user" => "Based on Users",
			"action" => "Based on Actions",
		);
	}
	
	function get_religions(){
		return array( 
			"christianity" => "Christianity",
			"islam" => "Islam",
			"nonreligious" => "Nonreligious",
			"hinduism" => "Hinduism",
			"chinese" => "Chinese traditional religion",
			"buddhism" => "Buddhism",
			"primal" => "Primal-indigenous",
			"african" => "African traditional and Diasporic",
			"sikhism" => "Sikhism",
			"juche" => "Juche",
			"spiritism" => "Spiritism",
			"judaism" => "Judaism",
			"bahai" => "Bahai",
			"jainism" => "Jainism",
			"shinto" => "Shinto",
			"cao_dai" => "Cao Dai",
			"zoroastrianism" => "Zoroastrianism",
			"tenrikyo" => "Tenrikyo",
			"paganism" => "Neo-Paganism",
			"unitarian" => "Unitarian-Universalism",
		);
	}
	
	function get_database_object_types(){
		
		$return = array();
		
		$ar = array();
		if( function_exists("get_form_fields") ){
			$ar = get_form_fields();
		}
		
		return array_merge( $return, $ar );
	}
	
	function get_quotation_types(){
		return array( 
			"auto" => "Auto",
		);
	}
	
	function get_quotation_status(){
		return array( 
			"draft" => "Draft",
			"billed" => "Billed",
			"cancel" => "Cancel",
		);
	}
	
	function get_account_linked_type(){
		$return = array( 
			"" => "--None--",
			"account_source" => "Account Source",
		);
		if( defined("HYELLA_LINK_TYPE_PAYMENT_METHOD") && HYELLA_LINK_TYPE_PAYMENT_METHOD ){
			$return["payment_method"] = "Payment Method";
		}
		return $return;
	}
	
	function get_account_linked_source(){
		return array( 
			"" => "--None--",
			"assets" => "Assets",
			"customers" => "Customers",
			"items" => "Items",
			"users" => "Users",
			"vendors" => "Vendors",
		);
	}
	
	function get_relationship_type_no_head(){
		$return = get_relationship_type();
		unset( $return["individual"] );
		unset( $return["head"] );
		
		return $return;
	}
	
	function get_relationship_type(){
		return array(
			"head" => "Head of Family",
			"spouse" => "Spouse",
			"child" => "Child",
			"parent" => "Parent",
			"sibling" => "Sibling",
			//"in-law" => "In-law",
			"grand-parent" => "Grand Parent",
			"grand-child" => "Grand Child",
			"cousin" => "Cousin",
			"individual" => "Individual",
			/* "friend" => "Friend",
			"helper" => "Help", */
		);
	}
	
	function get_bank_reconciliation_status(){
		return array(
			"pending" => "Pending",
			"reconciled" => "Reconciled",
			"complete" => "Complete",
			"cancelled" => "Cancelled",
		);
	}
	
	function get_budget_status(){
		return array(
			"draft" => "Draft",
			"reviewed" => "Review",
			"approved" => "Approved",
			"archived" => "Archived",
		);
	}
	
	function get_budget_items_type(){
		return array(
			"parent" => "Parent",
			"child" => "Child",
		);
	}
	
	function get_access_role_types(){
		return array(
			'users' => 'Users',
			'api' => 'API',
		);
	}
	
	function get_online_payment_methods( $o = array() ){
		$return = array(
			'pay_later' => 'Pay Later',
			'bank_transfer' => 'Bank Transfer / Bank Deposit',
			//'rave' => 'Rave (Use Debit / Credit Card)',
			//'pay_stack' => 'Pay Stack (Use Debit / Credit Card)',
			//'gt_pay' => 'GT Pay (Use Debit / Credit Card)',
		);
		
		if( defined("NWP_RAVE_TEXT") && defined("NWP_RAVE_PUBLIC_KEY") && defined("NWP_RAVE_ENCRYPTION_KEY") ){
			$return["rave"] = NWP_RAVE_TEXT;
		}
		
		if( defined("NWP_PAY_STACK_TEXT") && defined("NWP_PAY_STACK_PUBLIC_KEY") && defined("NWP_PAY_STACK_ENCRYPTION_KEY") ){
			$return["pay_stack"] = NWP_PAY_STACK_TEXT;
		}
		
		if( isset( $o["unset"] ) && is_array( $o["unset"] ) && ! empty( $o["unset"] ) ){
			foreach( $o["unset"] as $ov ){
				if( isset( $return[ $ov ] ) )unset( $return[ $ov ] );
			}
		}
		
		return $return;
	}
	
	function get_ticket_department(){
		return array(
			"nsr" => "NSR",
			"ssr" => "SSR",
			"backend_module" => "Backend Module",
		);
	}
	
	function get_ticket_priority_options(){
		return array(
			"low" => "Low",
			"medium" => "Medium",
			"high" => "High",
		);
	}
	
	function get_ticket_type(){
		return array(
			"internal_ticket" => "ICT Tickets",
			"grievance" => "Grievance & Redress",
		);
	}
	
	function get_ticket_status_options(){
		return array(
			"open" => "Open",
			"closed" => "Closed",
			"cancelled" => "Cancelled",
			"re_opened" => "Re-Opened",
			"assigned" => "Assigned",
			"responded" => "Responded",
		);
	}
	
	function get_open_ticket_status(){
		return 'open';
	}
	
	function get_closed_ticket_status(){
		return 'closed';
	}
	
	function get_re_opened_ticket_status(){
		return 're_opened';
	}
	
	function get_assigned_ticket_status(){
		return 'assigned';
	}
	
	function get_responded_ticket_status(){
		return 'responded';
	}
	
	function get_grievance_category(){
		return array(
			"cbtt" => "Community Based Targeting Team",
			"socu" => "State Operations Coordinating Unit",
			"ssn_program" => "Social Program",
		);
	}
	
	function get_method_of_receiving_grievance(){
		return array(
			"website" => "Website",
			"socu" => "State Operations Coordinating Unit",
		);
	}
	function get_files_type( $o = array() ){
		if( isset( $o["policy"] ) ){
			return array(
				"policy" => "Social Safety Net Policy",
				"budget_line" => "Budget Line",
			);
		}
		
		$return = array(
			"create_file" => "Create File",
			"archive" => "Archived File",
			"memo" => "Memo",
			"attachment" => "Attachment",
			"old_version" => "Old Version",
			"shared" => "Shared",
			"harmonized_list" => "Harmonized List",
			"policy" => "Social Safety Net Policy",
			"budget_line" => "Budget Line",
			"report" => "Report",
			"search" => "Search",
		);

		if( defined( 'HYELLA_V3_ORTHANC' ) && HYELLA_V3_ORTHANC ){
			$return[ 'digital_images' ] = 'Digital Images';
		}

		return $return;

	}
	
	function get_labels_type(){
		return array(
			"files" => "Files",
			"library" => "Library",
			"library_child" => "Folder", //Library 
			"comments" => "Comments",
			"records" => "Records",
			"workflow" => "Workflow",
			"selection" => "Selection",
			"share" => "Shared",
		);
	}
	
	function get_revision_history_type(){
		return array(
			"front_end" => "Frontend",
			"code" => "Backend",
		);
	}
	
	function get_stores_type(){
		return array(
			//"branch" => "Branch",
			"main-store" => "SBU",	//"Main Store"
			"sub-store" => "Sub-SBU", //"Sub Store"
		);
	}
	
	function get_category_sub_type(){
		return array(
			"child" => "Child",
			"parent" => "Parent",
		);
	}
	
	function get_true_false(){
		return array(
			"true" => "True",
			"false" => "False",
		);
	}
	
	function get_empty_checkbox(){
		return array(
			"1" => "",
		);
	}
	
	function get_user_status(){
		return array(
			'active' => 'Active',
			'in_active' => 'in_active',
			'pending' => 'Pending',
			'pending_activation' => 'Pending Activation'
		);
	}
	
	function get_payment_status_new(){
		return array(
			'completed' => 'Completed',
			'returned' => 'Returned',
			'submitted' => 'Awaiting Approval',
		);
	}
	
	function get_days_in_month(){
		return array(
			"1" => "1",
			"2" => "2",
			"3" => "3",
			"4" => "4",
			"5" => "5",
			"5" => "5",
			"6" => "6",
			"7" => "7",
			"8" => "8",
			"9" => "9",
			"10" => "10",
			"11" => "11",
			"12" => "12",
			"13" => "13",
			"14" => "14",
			"15" => "15",
			"16" => "16",
			"17" => "17",
			"18" => "18",
			"19" => "19",
			"20" => "20",
			"21" => "21",
			"22" => "22",
			"23" => "23",
			"24" => "24",
			"25" => "25",
			"26" => "26",
			"27" => "27",
			"28" => "28",
			"29" => "29",
			"30" => "30",
			"31" => "31",
		);
	}
	
?>