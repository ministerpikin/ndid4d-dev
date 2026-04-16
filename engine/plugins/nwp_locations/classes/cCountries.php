<?php
/**
 * countries Class
 *
 * @used in  				countries Function
 * @created  				18:12 | 10-Sep-2019
 * @database table name   	countries
 */
	
	class cCountries extends cBase_class{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'countries';
		
		public $default_reference = '';
		
		public $label = 'Country List';
		
		private $associated_cache_keys = array(
			'countries',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $basic_data = array(
			'access_to_crud' => 1,	//all crud functions (add, eidt, view, delete) will be available in access control
			'api_custom_fields' => array( 'id' => array( 'custom' => 1 ) ),
			//'exclude_from_crud' => array(),
		);
		
		public $table_fields = array();
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,
				
			'utility_buttons' => array(
				'comments' => 'comments',
				'share' => 'share',
				'attach_file' => 'attach_file',
				'tags' => 'tags',
				'view_details' => 'view_details',
				'revision_history' => 'revision_history',
			),
				
			'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
				//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
			
			'show_details' => 1,				//Determines whether or not to show details
			'show_serial_number' => 1,			//Determines whether or not to show serial number
			
			'show_verification_status' => 0,	//Determines whether or not to show verification status
			'show_creator' => 0,				//Determines whether or not to show record creator
			'show_modifier' => 0,				//Determines whether or not to show record modifier
			'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
		);
			
		function __construct(){
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/countries.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/countries.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function countries(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					return $this->_display_notification( array( "type" => 'no_language', "message" => 'no language file' ) );
				}
			}
			
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			$this->class_settings[ 'project_task_data' ] = get_project_data();
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_all_records':
				unset( $_SESSION[$this->table_name]['filter']['show_my_records'] );
				unset( $_SESSION[$this->table_name]['filter']['show_deleted_records'] );
				
				$returned_value = $this->_display_data_table();
			break;
			case 'display_deleted_records':
				$_SESSION[$this->table_name]['filter']['show_deleted_records'] = 1;
				
				$returned_value = $this->_display_data_table();
			break;
			case 'delete_from_popup2':
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_frontend_nsr':
			case 'display_all_records_frontend_ssr':
			case 'display_all_records_frontend':
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'display_app_manager':
			case 'display_app_view':
				$returned_value = $this->_display_app_view2();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes2();
			break;
			case 'delete_app_manager':
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'edit_popup_form_in_popup':
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_popup_form':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'search_list2_datatable':
			case 'search_list2_handsontable':
			case 'search_list2':
			case 'search_list':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log();
			break;
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			case 'change_country':
				$returned_value = $this->_change_country();
			break;
			case 'update_currency_conversion_rate':
				$returned_value = $this->_update_currency_conversion_rate();
			break;
			case 'get_countries':
				//$returned_value = $this->_get_countries();
			break;
			case 'get_select2':
			case 'get_currency_select2':
				$returned_value = $this->_get_select2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'update_exchange_rates':
				$returned_value = $this->_update_exchange_rates();
			break;
			}	
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected function _get_select2(){

			$_GET[ 'status' ] = 'active';

			switch( $this->class_settings['action_to_perform'] ){
			case 'get_currency_select2':
				$this->class_settings['select2_where'] = "  AND ( `". $this->table_name ."`.`". $this->table_fields['currency'] ."` IS NOT NULL AND `". $this->table_name ."`.`". $this->table_fields['currency'] ."` != '' ) ";
				$this->class_settings['group'] = " GROUP BY `".$this->table_fields['currency']."` ";
				$this->class_settings['custom_select'] = " `".$this->table_fields['currency']."` as 'id', `".$this->table_fields['currency']."` as 'text' ";

				$this->table_fields["name"] = $this->table_fields['currency'];
				//$this->class_settings['select_as_name'] = $this->table_fields['currency'];
			break;
			}

			return $this->plugin_instance->_get_default_select2( [ 'table_name' => $this->table_name, 'table_fields' => $this->table_fields ] );
		}
		
        protected function _display_app_view2(){
			
			$this->class_settings[ 'data' ][ "show_last_records_option" ] = 1;
			
			$return = $this->_display_app_view();
			
			$return["javascript_functions"] =  array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.loadForm' );
			
			return $return;
		}
		
        private function _update_currency_conversion_rate(){
            //get counrties with currency iso code
			$query = "SELECT `serial_num`, `".$this->table_fields['currency_iso_code']."` as 'currency_iso_code' FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` where `record_status`='1' AND `modification_date` < ".(date("U") - (3600*24));
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
            
            //get exchange rate
            $all_countries = execute_sql_query($query_settings);
			$return = array();
            
			if( is_array( $all_countries ) && ! empty( $all_countries ) ){
				$query_settings['query_type'] = 'UPDATE';
                
                $first = true;
                foreach( $all_countries as $val ){
					if( $val['currency_iso_code'] ){
                        $json = file_get_contents('http://rate-exchange.appspot.com/currency?from=USD&to='.strtoupper($val['currency_iso_code']) );
                        if($json)$cur = json_decode($json, true);
                        if( isset( $cur['rate'] ) && $cur['rate'] ){
                            $return[] = $cur;
                            
                            $query_settings['query'] = "UPDATE `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` SET `".$this->table_fields['conversion_rate']."`= '".$cur['rate']."' WHERE `serial_num`='".$val['serial_num']."' ";
                            
                            execute_sql_query($query_settings);
                            
                            if( $first ){
                                $query_settings['tables'] = array();
                                $query_settings['set_memcache'] = 0;
                                $first = false;
                            }
                        }
                    }
				}
                
                $this->class_settings[ 'do_not_check_cache' ] = 1;
                $this->_get_countries();
            }
            
            return $return;
        }
        
        private function _change_country(){
            if( isset( $_GET['record_id'] ) && $_GET['record_id'] ){
                $country = doubleval( $_GET['record_id'] );
                
                //check country exists
                if( $country == '1' ){
                    $country_data = get_default_country_details();
                }else{
                    $country_data = get_countries_details( array( 'id' => $country ) );
                }
                
                if( isset( $country_data['id'] ) && $country_data['id'] == $country ){
                    $_SESSION['country'] = $country_data;
                    
                    //REPORT INVALID TABLE ERROR
					$err = new cError('010012');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = '_change_country';
					$err->additional_details_of_error = 'changing country';
					$return = $err->error();
                    
					$return['status'] = 'country-changed';
                    
					return $return;
                }
            }
            
            //INVALID COUNTRY
            $err = new cError('000022');
            $err->action_to_perform = 'notify';
            
            $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
            $err->method_in_class_that_triggered_error = '_change_country';
            $err->additional_details_of_error = 'Oops, we could not detect the selected country';
            return $err->error();
        }
        
		protected function _search_form2(){
			$where = "";
			
			$key = 'customer';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
				$where .= " AND `".$this->table_fields[ $key ]."` = '".$this->class_settings[ $key ]."' ";
			}
			
			$key = 'date';
			if( isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
				$this->class_settings[ $key ] = convert_date_to_timestamp( $_POST[ $this->table_fields[ $key ] ] , 1 );
				$where .= " AND `".$this->table_fields[ $key ]."` >= " . $this->class_settings[ $key ];
			}
			
			$key = 'date';
			$skey = "_range";

			if( isset( $_POST[ $this->table_fields[ $key ] . $skey ] ) && $_POST[ $this->table_fields[ $key ] . $skey ] ){
				$this->class_settings[ $key ] = convert_date_to_timestamp( $_POST[ $this->table_fields[ $key ] . $skey ] , 2 );

				$where .= " AND `".$this->table_fields[ $key ]."` <= " . $this->class_settings[ $key ];
			}
			
			$this->class_settings[ "where" ] = $where;
			return $this->_search_form();
		}

		protected function _display_all_records_full_view2(){
			$this->class_settings[ "show_form" ] = 1;
			$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
			$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
			$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
			$this->class_settings[ "add_empty_select_option" ] = 1;

			foreach( $this->table_fields as $key => $val ){
				switch( $key ){
				case "date":
					$this->class_settings["form_values_important"][ $val ] = date("U");
					$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
				break;
				case "customer":
					$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
				break;
				default:
					$this->class_settings["hidden_records"][$val] = 1;
				break;
				}
			}
			return $this->_display_all_records_full_view();
		}

		protected function _save_app_changes2(){
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				//check for project details & update
				
			break;
			}
			
			$return = $this->_save_app_changes();
			
			switch( $this->class_settings["action_to_perform"] ){
			case 'save_new_popup':
				//display payment info
				/*
				if( isset( $return["data"]["id"] ) && $return["data"]["id"] ){
					$_POST["id"] = '';
					$_POST["tmp"] = $return["data"]["id"];
					
					$stores = new cStores();
					$stores->class_settings = $this->class_settings;
					$stores->class_settings["action_to_perform"] = "update_table_field";
					$stores->class_settings["update_fields"] = array(
						"name" => $return["data"]["name"],
						"address" => $return["data"]["location"],
						"comment" => $return["data"]["comment"],
					);
					$return1 = $stores->stores();
					
					if( ! ( isset( $return1["saved_record_id"] ) && $return1["saved_record_id"] ) ){
						$err = new cError('010014');
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
						$err->method_in_class_that_triggered_error = '_resend_verification_email';
						$err->additional_details_of_error = 'Project creation failed due to business units issues';
						return $err->error();
					}
				}
				*/
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			$this->class_settings["skip_link"] = 1;
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
			break;
			}
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}

		protected function _update_exchange_rates(){
			$handle = isset( $this->class_settings['html_replacement_selector'] ) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
			$url = 'https://openexchangerates.org/api/latest.json?app_id=cc858c351e554b64b7e4a83506862a81';

			$options = array(
			  'http' => array(
			    'header'  => "Content-type: application/json\r\n",
			    'method'  => 'GET'
			  ),
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			$data = json_decode( $result, true );
			if( isset($data['rates']) && $data['rates'] ){
				$this->class_settings['where'] = " AND !(`". $this->table_name ."`.`". $this->table_fields['currency'] ."` IS NULL OR `". $this->table_name ."`.`".$this->table_fields['currency']."` = ' ') ";
				$rcrds = $this->_get_records();
				foreach ($rcrds as $record) {
					// if( !(isset( $record['usd_exchange_rate'] ) && $record['usd_exchange_rate']) ){
						if( isset( $data['rates'][ $record['currency'] ] ) && $data['rates'][ $record['currency'] ] ){
							// $s = array();
							// $s["where"] = " `id` = '".$record['id']."' ";
							// $s["field_and_values"][ $this->table_fields["usd_exchange_rate"] ] = array(
							// 	"key" => 'usd_exchange_rate',
							// 	"value" => $data['rates'][ $record['currency'] ],
							// );
							// $y = $this->_update_records( $s );
							// print_r($y);
							$_POST['id'] = $record['id'];
							$this->class_settings['update_fields'] = array( 'usd_exchange_rate' => $data['rates'][ $record['currency'] ] );
							$y = $this->_update_table_field();
						}
					// }
				}
			}
			$this->class_settings['data'] = $data;
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/package/'. HYELLA_PACKAGE.'/'.$this->table_name.'/'.'rates.php' );
			$ht = $this->_get_html_view();
			return array(
				'status' => 'new-status',
				'html_replacement' => $ht,
				'html_replacement_selector' => '#'.$handle,
			);
		}
		
		
	}
	
	function countries(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/countries.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/countries.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				
				if( isset( $return[ "fields" ] ) ){
				
					$key = $return[ "fields" ][ "language" ];
					$return[ "labels" ][ $key ][ 'calculations' ] = array(
						'type' => 'record-details',
						'reference_table' => 'banks',
						'reference_keys' => array( 'name' ),
						'form_field' => 'text',
						'variables' => array( array( $key ) ),
					);
					$return[ "labels" ][ $key ][ 'form_field' ] = 'calculated';
					$return[ "labels" ][ $key ][ 'attributes' ] = ' action="?action=banks&todo=get_select2&hide_type=1&type=language" minlength="0" ';
					$return[ "labels" ][ $key ][ 'class' ] = ' select2 ';
					$return[ "labels" ][ $key ][ 'default_appearance_in_table_fields' ] = 'hide';
					
					
					$key = $return[ "fields" ][ "currency_iso_code" ];
					if( ! is_array( $return[ "labels" ][ $key ]['data'] ) ){
						$return[ "labels" ][ $key ]['data'] = [];
					}
					$return[ "labels" ][ $key ][ 'form_field' ] = 'select';
					$return[ "labels" ][ $key ][ 'form_field_options' ] = 'get_global_region';
					$return[ "labels" ][ $key ]['data'][ 'form_field_options_source' ] = 2;
					$return[ "labels" ][ $key ][ 'field_label' ] = 'Region';
					$return[ "labels" ][ $key ][ 'text' ] = 'Region';
					$return[ "labels" ][ $key ][ 'display_field_label' ] = 'Region';
					
					
				}
				
				return $return[ "labels" ];
			}
		}
	}
?>