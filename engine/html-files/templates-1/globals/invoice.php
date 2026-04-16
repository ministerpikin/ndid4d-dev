<?php
	$frm_mobile = 0;
	if( isset( $_GET[ 'nwp_request' ] ) && $_GET[ 'nwp_request' ] ){
		$frm_mobile = 1;
	}
	$data[ 'frm_mobile' ] = $frm_mobile;

	if( ! function_exists("__get_receiptS") ){
		
	function __get_receiptS( $data = array(), $tmp = '', $cs = '' ){
		ob_start();
		$frm_mobile = isset( $data[ 'frm_mobile' ] ) ? $data[ 'frm_mobile' ] : 0;
		$package = "";
		if( defined( "HYELLA_PACKAGE" ) ){
			$package = HYELLA_PACKAGE;
		}
		$returned_data = array();
		
		$currency = '';
		if( defined("HYELLA_CURRENCY_VERSION_2") && HYELLA_CURRENCY_VERSION_2 && function_exists("get_currencies") ){
			$c = get_currencies();
			$currency = get_select_option_value( array( "id" => get_default_currency_settings(), "function_name" => "get_currencies" ) ) . ' ';
		}
		
		$no_cap_rev = 0;
		if( defined("HYELLA_DO_NOT_REPORT_CAPITATION_REVENUE") && HYELLA_DO_NOT_REPORT_CAPITATION_REVENUE ){
			$no_cap_rev = 1;
		}
		
		$show_cap_note = 0;
		if( defined("HYELLA_SHOW_CAPITATION_NOTE_ON_BILL") ){
			$show_cap_note = HYELLA_SHOW_CAPITATION_NOTE_ON_BILL;
		}
		
		$show_right_column = 1;
		$show_raised_by = 1;
		$show_printer = 1;	//this was moved up here
		$image_size = 50;
		$ims = get_maximum_image_size_on_receipt_settings();
		if( $ims ){
			$image_size = $ims;
		}
		
		$show_current_store = 0;
		if( get_single_store_settings() ){
			$data['event']["store"] = get_main_store();
		}else{
			$show_current_store = 1;
		}
		
		$stamp = "part-payment.png";
		$skip_logo = 0;
		$show_costing = 1;
		$show_sn = 1;
		$show_total_column = 1;
		$show_bottom_section = 1;
		$get_receipt_message = 1;
		
		$amount_paid = 0;
		if( isset( $data["payment"]["TOTAL_AMOUNT_PAID"] ) ){
			$amount_paid = round( doubleval( $data["payment"]["TOTAL_AMOUNT_PAID"] ) , 2 );
		}
		/*
		if( isset( $data["event"]["amount_paid"] ) ){
			$amount_paid = doubleval( $data["event"]["amount_paid"] );
		}
		*/
		
		$show_logo_only = 0;
		if( function_exists( "get_show_logo_only_on_receipts" ) ){
			$show_logo_only = get_show_logo_only_on_receipts();
		}
		//echo $show_logo_only;
		
		$key = "customer";
		$customer_name = "Not Available";
		if( isset( $data["event"][$key] ) ){
			$c = get_customers_details( array( "id" => $data["event"][$key] ) );
			
			switch( $package ){
			case "catholic":
				if( ! isset( $c["name"] ) ){
					$c = get_parish_details( array( "id" => $data["event"][$key] ) );
				}
				if( ! isset( $c["name"] ) ){
					$c = get_priest_details( array( "id" => $data["event"][$key] ) );
				}
			break;
			case "farm":
				if( isset( $c["name"] ) && $c["address"] )$c["name"] .= ' ('.strtoupper( $c["address"] ).')';
			break;
			default:
				$c["name"] = get_name_of_referenced_record( array( "id" => $data["event"][$key], "table" => "customers" ) );
			break;
			}
			$customer_name = isset($c["name"])?$c["name"]:'';
		}
		
		$pr = get_project_data();
		
		$support_line = "";
		if( isset( $pr['support_line'] ) )$support_line = $pr['support_line'];
		
		$support_email = "";
		if( isset( $pr['support_email'] ) )$support_email = $pr['support_email'];
		
		$support_addr = "";
		if( isset( $pr['street_address'] ) )$support_addr = $pr['street_address'] . " " . $pr['city'] ." ". $pr['state'];
		
		$support_msg = "";
		
		$store_name = "";
		$branch = "";
		$branch_name = "";
		$branch_address = "";
		
		$store = array();
		if( isset( $data['event']["store"] ) && $data['event']["store"] ){
			$store = get_record_details( array( "id" => $data['event']["store"], "table" => "stores" ) );
			
			if( isset( $store["phone"] ) ){
				//test for sub location
				if( $store["id"] != get_main_store() ){ 
					
					$branch_name = $store["name"];
					$branch_name = $store["address"];
					$branch_address = $store["address"];
					
					$store1 = get_record_details( array( "id" => get_main_store(), "table" => "stores" ) );
					if( isset( $store1["phone"] ) ){
						$store = $store1;
					}
				}
				
				$store_name = $store["name"];
				$support_line = $store["phone"];
				$support_addr = $store["address"];
				$support_email = $store["email"];
				//$support_msg = $store["comment"];
				
				if( $store_name == "." ){ 
					$store_name = " ";
				}
			}
			
			switch( $package ){
			case "farm":
				$branch_name = $store_name;
			break;
			}
		}
		
		$show_buttons = 1;
		if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] )
			$show_buttons = 0;
		
		$show_buttons = 0;
		$backend = 0;
		if( isset( $data["backend"] ) && $data["backend"] )
			$backend = $data["backend"];
		
		// $invoice_only = 0;
		// if( isset( $data["backend"] ) && $data["backend"] )
		// 	$invoice_only = $data["backend"];
		
		$invoice_only = 0;
		
		$show_image = 0;
		if( isset( $data["show_item_image"] ) && $data["show_item_image"] )
			$show_image = $data["show_item_image"];
		
		$bookings = 0;
		$reference = 0;
		$extra_comment = "";
		if( isset( $data["event"]["reference_table"] ) ){
			switch( $data["event"]["reference_table"] ){
			case "debit_and_credit"	:
				$reference = 1;
				$show_buttons = 0;
				$bookings = 0;
				$data["event"]["comment"] .= "<br /><i>Customer Payment Posted from Accounting</i><br />Transaction Ref: <strong>#" . $data["event"]["reference"] . "</strong>";
			break;
			}
		}
		
		$reference_label = '';
		$reference_serial_number = '';
		
		$price_label = 'S. Price';
		$customer_label = 'Sold To:';
		
		$show_owing = 0;
		$sales_label = "Sales Receipt";
		if( defined("NWP_SALES_PRINTOUT_LABEL") && NWP_SALES_PRINTOUT_LABEL ){
			$sales_label = NWP_SALES_PRINTOUT_LABEL;
		}
		if( defined("NWP_SALES_PRINTOUT_CLABEL") && NWP_SALES_PRINTOUT_CLABEL ){
			$customer_label = NWP_SALES_PRINTOUT_CLABEL;
		}
		
		$item_label = 'Item';
		$unit_type_text = "Quantity";
		$record_details_table = 'items';
		$comment_text = 'Comment';
		$prefix = 'S';
		$item_key = "item_id";
		$link_serial_number_link = 1;
		$print_type = '';
		
		if( isset( $data["prefix"] ) && $data["prefix"] ){
			$prefix = $data["prefix"];
		}
		
		if( isset( $data["table"] ) && $data["table"] ){
			$print_type = $data["table"];
		}
		
		$staff = "";
		if( isset( $data["event"]["payment_method"] ) ){
			switch( $data["event"]["payment_method"] ){
			case "complimentary_staff":
				$customer_label = 'Staff:';
				$staff = $data["event"]["staff_responsible"];
				
				$st = get_record_details( array( "id" => $staff, "table" => "users" ) );
				if( isset( $st["firstname"] ) && isset( $st["lastname"] ) ){
					$customer_name = $st["firstname"] . ' ' . $st["lastname"];
				}
				
				$data["event"]["staff_responsible"] = $data["event"]["created_by"];
			break;
			}
		}
		
		$use_staff_as_raised_by = 0;
		if( isset( $data["event"]["sales_status"] ) ){
			switch( $data["event"]["sales_status"] ){
			case "pending-order":
				$sales_label = "Sales Order";
				$customer_label = 'Order By:';
				$item_key = "item";
				$link_serial_number_link = 0;
				$use_staff_as_raised_by = 1;
				$show_printer = 0;
			break;
			case "sales_order":
				$sales_label = "Sales Invoice";
				
				switch( get_package_option() ){
				case "project":
					$sales_label = "Invoice";
					$comment_text = 'Description';
					$customer_label = 'Billed To:';
				break;
				}
			break;
			case "membership":
				$sales_label = "Membership Registration Receipt";
				$price_label = 'Rate';
				$record_details_table = 'membership_plan';
				
				$customer_label = 'Client:';
				$unit_type_text = "Unit";
			break;
			case "membership_registration":
				$sales_label = "Membership Plan Subscription Receipt";
				$price_label = 'Rate';
				$record_details_table = 'membership_plan';
				
				$customer_label = 'Client:';
				$unit_type_text = "Unit";
			break;
			case "vacated":
			case "occuppied":
				$sales_label = "Rent Receipt";
				$price_label = 'Rate';
			break;
			case "debit_note":
				$sales_label = "Debit Note";
				$price_label = 'Price';
				$customer_label = 'Returned To:';
				
				$get_receipt_message = 0;
				
				$key = "customer";
				if( isset( $data["event"][$key] ) ){
					$c = get_vendors_details( array( "id" => $data["event"][$key] ) );
					if( isset( $c["name_of_vendor"] ) )$customer_name = $c["name_of_vendor"];
				}
				
				$key = "extra_reference";
				if( isset( $data["event"][$key] ) ){
					$reference_label = 'GRN';
					
					$e = get_expenditure_details( array( "id" => $data["event"][$key] ) );
					if( isset( $e["serial_num"] ) && $e["serial_num"] ){
						$reference_serial_number = '<a href="javascript:;" class="custom-single-selected-record-button" override-selected-record="'.$data["event"][$key].'" action="?action=expenditure&todo=view_invoice" title="View Details">'.mask_serial_number( $e["serial_num"], 'GRN' ).'</a>';
					}
				}
			break;
			default:
				$show_owing = 1;
				
				switch( get_package_option() ){
				case "hospital":
				case "essential":
				case "enterprise":
				case "professional":
				case "wellness":
				case "gynae":
				case "pharmacy":
					$sales_label = "Bill";
					$customer_label = 'Billed To:';
				break;
				case "pharmacyX":
					$sales_label = "Sales Invoice";
					//$customer_label = 'Billed To:';
				break;
				case "travel_booking":
					$sales_label = "Invoice";
					$customer_label = 'Billed To:';
				break;
				}
			break;
			}
		}
		
		//11-jan-23
		$even_more_reference = '';
		$more_reference = '';
		$sd_params = '';
		$sd_label = '';
		$sd_ref = '';
		
		$key = "term2"; 
		if( isset( $data["event"][$key] ) ){
			switch( $data["event"][$key] ){
			case "save_transfer_sale":
				$key = "customer";
				if( isset( $data["event"][$key] ) ){
					$c = get_chart_of_accounts_details( array( "id" => $data["event"][$key] ) );
					if( isset( $c["title"] ) && $c["title"] ){
						$customer_name = $c["title"] . ' ['.$c["code"].']';
					}
				}
			break;
			case "orders":
				$key = "extra_reference";
				if( isset( $data["event"][$key] ) ){
					$reference_label = 'Sales Order';
					$reference_serial_number = get_name_of_referenced_record( array( "id" => $data["event"][$key], "table" => "orders", "link" => 1, "prefix" => "SS" ) );
				}
			break;
			default:
				if( class_exists("c" . ucwords( $data["event"][$key] ) ) ){
					$sd_cl = "c" . ucwords( $data["event"][$key] );
					$sd_cls = new $sd_cl();
					
					$sd_td = ( isset( $sd_cls->bill_link_todo ) && $sd_cls->bill_link_todo )?$sd_cls->bill_link_todo:'view_details2';
					$sd_params = '?todo='.$sd_td.'&action=' . $data["event"][$key] . '&sref=' . $data["event"]["extra_reference"] . '&sid=' . $data["event"]["id"];
					
					$sd_label = isset( $sd_cls->label )?$sd_cls->label:( ucwords( str_replace("_", " ", $sd_cls->table_name) ) );
					$sd_ref = $data["event"]["extra_reference"];
					
					$even_more_reference .= '<tr><td colspan="2"><a href="#" title="Open Source Document" action="'.$sd_params.'" class="custom-single-selected-record-button" override-selected-record="'.$data["event"][ "extra_reference" ].'" target="_blank" new-tab="1">' . $sd_label . ' - '. substr( $sd_ref, 0, 12 ) . '...</a></td></tr>';
				}
			break;
			}
		}
		
		$hide_pos_print = 0;
		
		$extra_customer_data = '';
		$hmo_percentage = '';
		$hmo = '';
		$special_comment = '';
		$key = "data";
		$loyalty_points = 0;
		$edata = array();
		if( isset( $data["event"][$key] ) && $data["event"][$key] ){
			$edata = json_decode( $data["event"][$key], true );
			if( isset( $edata["loyalty_points_type"] ) && isset( $edata["loyalty_points"] ) ){
				switch( $edata["loyalty_points_type"] ){
				case "fixed_value":
					$loyalty_points = $edata["loyalty_points"];
				break;
				}
			}else if( get_enable_loyalty_points_settings() && isset( $data[ 'event' ][ 'loyalty_point' ] ) && $data[ 'event' ][ 'loyalty_point' ] ){
				$loyalty_points = doubleval( $data[ 'event' ][ 'loyalty_point' ] );
			}
				// echo '<pre>'; print_r( $data["event"][$key] ); echo '</pre>';
			
			if( isset( $edata["customer_data"] ) && is_array( $edata["customer_data"] ) && ! empty( $edata["customer_data"] ) ){
				foreach( $edata["customer_data"] as $ck => $cv ){
					$extra_customer_data .= strtoupper( str_replace( '_', ' ', $ck ) ) . ': ' . $cv . '<br />';
				}
			}
			
			if( isset( $edata["more_data"] ) && is_array( $edata["more_data"] ) && ! empty( $edata["more_data"] ) ){
				foreach( $edata["more_data"] as $ck => $cv ){
					$more_reference .= '<tr><td><strong>' . ucwords( str_replace( '_', ' ', $ck ) ) . ':</td>';
					$more_reference .= '<td>' . $cv . '</td></tr>';
				}
			}
			
			if( isset( $edata["labels"] ) && is_array( $edata["labels"] ) && ! empty( $edata["labels"] ) ){
				extract( $edata["labels"] );
				unset( $edata["labels"] );
			}
			
			if( isset( $edata["buttons_data"] ) && is_array( $edata["buttons_data"] ) && ! empty( $edata["buttons_data"] ) ){
				extract( $edata["buttons_data"] );
				//unset( $edata["buttons_data"] );
			}
			
			$key = 'discount_comment';
			if( isset( $edata[ $key ] ) && $edata[ $key ] ){
				$special_comment .= 'DISCOUNT: ' . $edata[ $key ] . '<br />';
			}
			$key = 'vat_comment';
			if( isset( $edata[ $key ] ) && $edata[ $key ] ){
				$special_comment .= 'VAT: ' . $edata[ $key ] . '<br />';
			}
			$key = 'service_charge_comment';
			if( isset( $edata[ $key ] ) && $edata[ $key ] ){
				$special_comment .= 'SERVICE CHARGE: ' . $edata[ $key ] . '<br />';
			}
			$key = 'service_tax_comment';
			if( isset( $edata[ $key ] ) && $edata[ $key ] ){
				$special_comment .= 'SERVICE TAX: ' . $edata[ $key ] . '<br />';
			}
			
			$key = 'hmo_id';
			if( isset( $edata[ $key ] ) && $edata[ $key ] ){
				$hmo_percentage = doubleval( $edata[ "hmo_percentage" ] );
				$hmo = get_name_of_referenced_record( array( "id" => $edata[ $key ], "table" => "hmo" ) );
			}
			
			if( ! empty( $edata ) ){
				foreach( $edata as $kk => $vv ){
					if( ! ( isset( $data["event"][ $kk ] ) ) ){
						$data["event"][ $kk ] = $vv;
					}
				}
			}
		}
		
		$service_tax = 0;
		$service_charge = 0;
		$vat = 0;
		$surcharge = 0;
		
		if( isset( $data["event"]["vat"] ) && doubleval( $data["event"]["vat"] ) )
			$vat = doubleval( $data["event"]["vat"] );
		
		if( isset( $data["event"]["service_charge"] ) && doubleval( $data["event"]["service_charge"] ) )
			$service_charge = doubleval( $data["event"]["service_charge"] );
		
		if( isset( $data["event"]["service_tax"] ) && doubleval( $data["event"]["service_tax"] ) )
			$service_tax = doubleval( $data["event"]["service_tax"] );
		
		if( isset( $data["event"]["surcharge"] ) && doubleval( $data["event"]["surcharge"] ) )
			$surcharge = doubleval( $data["event"]["surcharge"] );
		
		$iservice_tax = $service_tax;
		$iservice_charge = $service_charge;
		$ivat = $vat;
		
		$key = "serial_num"; 
		$serial_number_link = '';
		$serial_number = '';
		if( isset( $data["event"][$key] ) ){
			$serial_number = mask_serial_number( $data["event"][$key] , $prefix );
			
			if( $link_serial_number_link ){
				$serial_number_link = '<a href="#" action="?module=&action=transactions&todo=view_invoice" class="custom-single-selected-record-button" override-selected-record="'.$data["event"][ "id" ].'">' . $serial_number . '</a>';
			}else{
				$serial_number_link = $serial_number;
			}
		}
		
		$raised_by = $data["event"][$key];//'';
		$staff_responsible = '';
		$key = "modified_by"; 
		if( defined( 'HYELLA_V3_USE_CREATED_BY_ON_BILL' ) && HYELLA_V3_USE_CREATED_BY_ON_BILL ){
			$key = "created_by"; 
		}
		
		if( isset( $data["event"][$key] ) ){
			$ru = get_record_details( array( "id" => $data["event"][$key], "table" => "users" ) );
			if( isset( $ru['firstname'] ) && isset( $ru['lastname'] ) ){ 
				$raised_by = $ru['firstname'].' '.$ru['lastname']; 
			} 
		}
		
		$key = "staff_responsible"; 
		if( isset( $data["event"][$key] ) ){
			$ru = get_record_details( array( "id" => $data["event"][$key], "table" => "users" ) );
			if( isset( $ru['firstname'] ) && isset( $ru['lastname'] ) ){ 
				$staff_responsible = $ru['firstname'].' '.$ru['lastname']; 
				if( $use_staff_as_raised_by ){
					$raised_by = $staff_responsible;
				}
			} 
		}
		
		$show_signature = 0;
		if( ! $backend ){
			$show_signature = get_show_signature_in_invoice_settings();
		}
		
		$g_discount_after_tax = get_sales_discount_after_tax_settings();
		$discount = 0;
		
		$show_sub_quantity = 0;
		if( get_items_sub_quantity_settings() ){
			$show_sub_quantity = 1;
		}
		
		$group_text = '';
		if( isset( $data["event"]["group"] ) && $data["event"]["group"] ){
			$group_text = '<br />Group: <strong>' . get_name_of_referenced_record( array( "id" => $data["event"]["group"], "table" => "customers" ) ) . '</strong><br />';
		}
		
		$show_header = 1;
		$table_header_style = '';
		
		
		$print_btn_cls = 'btn btn-lg blue';
		$seperate_items_bcat = 0;
		if( isset( $data["i_menu"] ) && $data["i_menu"] ){
			$print_btn_cls = 'button button-fill color-orange button-raised';
			//$show_printer = 0;
			$backend = 0;
			$show_header = 0;
			$show_bottom_section = 0;
			$show_total_column = 0;
			$show_costing = 0;
			//$data["skip_timeout_script"] = 1;
			
			if( $data["i_menu"] == 'kitchen' ){
				$show_printer = 0;
				if( defined("HYELLA_SEPERATE_POS_ITEMS_BASED_ON_CAT_STORE") ){
					$seperate_items_bcat = HYELLA_SEPERATE_POS_ITEMS_BASED_ON_CAT_STORE;
				}
			}
		}
		
		if( isset( $data["email_only"] ) && $data["email_only"] ){
			$show_printer = 0;
			$show_header = 0;
			$table_header_style = ' color: #ffffff; text-align: center; background-color: #53729e; ';
		}
		
		$no_column = 0;
		if( isset( $data["no_column"] ) && $data["no_column"] ){
			$no_column = $data["no_column"];
			$show_header = 0;
			$show_costing = 0;
			//$show_printer = 0;
		}
		
		
		$show_pay_button = '';
		if( isset( $data["show_pay_button"] ) && $data["show_pay_button"] ){
			$show_pay_button = $data["show_pay_button"];
		}
		
		$live_data_mode = 0;
		if( isset( $data["live_data_mode"] ) && $data["live_data_mode"] ){
			$live_data_mode = $data["live_data_mode"];
		}
		
		if( ! $live_data_mode ){
			$show_printer = 0;
		}
		
		
		if( defined("NWP_SALES_PRINTOUT") ){
			switch( NWP_SALES_PRINTOUT ){
			case "mini":
				$unit_type_text = "";
				$no_header = 1;
				$show_costing = 0;
				$show_bottom_section = 0;
				$show_printer = 0;
				$show_raised_by = 0;
				$show_right_column = 0;
			break;
			}
		}
		?>
		<?php include $tmp; ?>
		<?php
		if( $backend  && $show_pay_button ){
			echo '<hr />' . $show_pay_button;
		}
		
		$html_file_content = ob_get_contents();
		ob_end_clean();
		return array(
			'html' => iconv( "UTF-8", "ASCII//IGNORE", $html_file_content ),
			'returned_data' => $returned_data,
		);
		
		}
		
	}
?>
	
<?php 
	$receipt_printout_settings = "";
	$print_style = "";
	
	$backend = 0;
	if( isset( $data["backend"] ) && $data["backend"] )
		$backend = $data["backend"];
	
	$receipt_per_page = get_print_receipt_per_page_settings();
	
	if( ! $backend ){
		if( ! $receipt_per_page ){
			$print_style = ' style="max-width:100% !important; width:100% !important; min-width:100% !important;" ';
		}
		$receipt_printout_settings = get_print_2_sales_receipt_per_page_settings();
	}
	
	$tmp = "invoice-default.php";
	$cs = "";
	if( isset( $data["show_small_invoice"] ) && $data["show_small_invoice"] ){
		$tmp = "invoice-small.php";
		$cs = "invoice-small";
	}
	
	if( isset( $data["bill_template"] ) && $data["bill_template"] ){
		switch( $data["bill_template"] ){
		case 1: case '1':
			$tmp = "templates/invoice-chivoski.php";
			$cs = "invoice-chivoski";
		break;
		}
	}
	
	if( isset( $data["edit_bill"] ) && $data["edit_bill"] ){
		$tmp = "invoice-default-edit.php";
	}
	
	$package = "";
	if( defined( "HYELLA_PACKAGE" ) ){
		$package = HYELLA_PACKAGE;
	}
	if( $package && file_exists( dirname( __FILE__ ).'/'.$package.'-'.$tmp ) ){
		$tmp = $package.'-'.$tmp;
	}
	if( $package && file_exists( dirname( dirname( __FILE__ ) ).'/package/'.$package.'/'.$tmp ) ){
		$tmp = dirname( dirname( __FILE__ ) ).'/package/'.$package.'/'.$tmp;
	}
	
	// echo '<pre>';print_r( json_encode( $pr ) );echo '</pre>'; 
	// echo '<pre>';print_r( $tmp );echo '</pre>'; 
	// echo '<pre>';print_r( $print_style );echo '</pre>'; 
?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?> >
<div id="invoice-container-wrapper" class="page-container <?php echo $cs; ?>" >
	
	<!-- BEGIN CONTAINER -->   
	<div class="container <?php echo $cs; ?>" id="invoice-container" <?php echo $print_style; ?>>
	<?php
		include "invoice-css.php"; 
		
		// echo '<pre>';print_r( '$cs' );echo '</pre>'; 
		if( function_exists("get_disable_item_discount_settings") && get_disable_item_discount_settings() ){
		?>
		<style type="text/css">
		.line-discount{ display:none; }
		</style>
		<?php
		}

		if( $receipt_printout_settings ){
			$data["skip_timeout_script"] = 1;
		}
		if( isset( $_GET["skip_print"] ) && $_GET["skip_print"] ){
			$data["skip_timeout_script"] = 1;
		}
		
		$rh = __get_receiptS( $data, $tmp, $cs );
		$receipt_html = $rh["html"];
		$returned_data = $rh["returned_data"];
		
		if( $receipt_printout_settings ){
			if( $receipt_per_page ){
				?>
				<div class="row">
					<div class="col-xs-12">
						<?php echo $receipt_html; ?>
					</div>
				</div>
				<div class="row" style="page-break-before:always; margin-top:20px;">
					<div class="col-xs-12">
						<?php echo $receipt_html; ?>
					</div>
				</div>
				<?php
			}else{
				?>
				<div class="row">
					<div class="col-xs-6">
						<?php echo $receipt_html; ?>
					</div>
					<div class="col-xs-6">
						<?php echo $receipt_html; ?>
					</div>
				</div>
				<?php
			}
			?>
			<script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
			<?php
		}else{
			if( isset( $receipt_html["html"] ) ){
				echo $receipt_html["html"];
			}else if( is_array( $receipt_html ) ){
				print_r( $receipt_html );
			}else{
				 echo $receipt_html;
			}
		}
	?>
		
	
</div>
</div>
</div>