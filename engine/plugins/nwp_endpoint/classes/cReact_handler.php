<?php
/**
 * react_handler Class
 *
 * @used in  				react_handler Function
 * @created  				Hyella Nathan | 18:39 | 11-Jul-2023
 * @database table name   	react_handler
 */
	
	class cReact_handler extends cCustomer_call_log{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'react_handler';
		
		public $default_reference = '';
		
		public $label = 'React Handler';
		
		private $associated_cache_keys = array(
			'react_handler',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $table_fields = array();
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 0,
				
				'utility_buttons' => array(
					'comments' => 1,
					//'tags' => 1,
					'view_details' => 1,
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/react_handler.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/react_handler.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
				if( isset( $return[ "table_clone" ] ) )$this->table_clone = $return[ "table_clone" ];
			}
			
		}
	
		function react_handler(){
			
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
			case 'delete_from_popup':
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_frontend_history':
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
			case 'display_all_records_frontend':
				$returned_value = $this->_display_all_records_full_view2();
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
			case 'view_details2':
			case 'view_details':
				$returned_value = $this->_view_details2();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'save_line_items':
				$returned_value = $this->_save_line_items();
			break;
			case 'manage_fileload':
			case 'manage_spreadsheet':
			case 'manage_fileload_frame':
			case 'manage_spreadsheet_frame':
				$returned_value = $this->_load_data_view();
			break;
			case 'login':
				$returned_value = $this->_login();
			break;
			case 'get_sidebar':
			case 'manage_dashboard':
				$returned_value = $this->_manage_r_request();
			break;
			case 'display_data_access_view':
				$returned_value = $this->_display_application_update_view();
			break;
			case 'create_index':
			case 'save_create_index':
			case 'save_refresh_index':
				$returned_value = $this->_custom_capture_form_version_2();
			break;
			case 'import_csv_tutorial_view':
			case 'refresh_file_upload_template':
				$returned_value = $this->_import_csv_data_tutorial();
			break;
			case 'delete_fields':
			case 'add_new_fields':
			case 'edit_new_fields':
			case 'save_add_new_fields':
			case 'save_edit_new_fields':
				$returned_value = $this->_manage_fields();
			break;
			case 'kibana_iframe':
			case 'rabbit_mq_iframe':
				$returned_value = $this->_kibana_iframe();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		protected function _kibana_iframe( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			switch( $action_to_perform ){
				case 'kibana_iframe':
				case 'rabbit_mq_iframe':
					$filename = $action_to_perform . ".php";
					$this->class_settings["html"] = array( $this->view_path . $filename );
					$html = $this->_get_html_view();
					return array(
						'status' => 'new-status',
						'html_replacement' => $html,
						'html_replacement_selector' => '#'. $handle,
						'javascript_functions' => $js
					);
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		protected function _manage_fields( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];

			$dt = isset($_GET['dt']) && $_GET['dt'] ? $_GET['dt'] : '';

			switch ($action_to_perform) {
				case 'add_new_fields':
					$dt = isset( $_POST['id'] ) && $_POST['id'] ? $_POST['id'] : '';
				break;
				case 'save_add_new_fields':
				case 'save_edit_new_fields':
					$dt = isset( $_POST['dt'] ) && $_POST['dt'] ? $_POST['dt'] : '';
				break;
			}

			if( !$dt ){
				$error_msg = "<h4><b>Invalid Reference</b></h4><p>Select a valid table</p>";
			}else{
				$tbd = explode( ":::", $dt );
				$tba = array();
				if( ! empty( $tbd ) ){
					foreach( $tbd as $tbv ){
						$tba2 = explode( '=', $tbv );
						if( isset( $tba2[1] ) && $tba2[1] ){
							$tba[ $tba2[0] ] = $tba2[1];
						}
					}
					
					$tb_type = isset( $tba["type"] )?$tba["type"]:'';
					$dtable = isset( $tba["value"] )?$tba["value"]:'';

					if( $dtable ){
						switch( $tb_type ){
							case "plugin":						
								if( isset( $tba["key"] ) && $tba["key"] ){
									$clp = 'c' . ucwords( strtolower( $tba["key"] ) );						
									if( class_exists( $clp ) ){
										$ptb = new $clp();						
										$in = $ptb->load_class( array( 'class' => array( $dtable ), 'initialize' => 1 ) );
										if( isset( $in[ $dtable ]->table_name ) ){
											$cl = $in[ $dtable ];
										}else{
											$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $dtable ."' is invalid or missing in the project.</p>";
										}						
									}else{
										$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";						
									}
								}else{
									$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
								}						
							break;
							default:						
								$cls = 'c' . ucwords( $dtable );
								if( class_exists( $cls ) ){
									$cl = new $cls();
								}else{
									$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";						
								}
							break;
						}

						if( !$error_msg ){

							if( isset( $cl ) && function_exists( $dtable ) ){

								$c = [];

								$file = $this->class_settings['calling_page'].'plugins/'. $cl->plugin .'/classes/dependencies/'. $dtable .'.json';

								switch ( $action_to_perform ) {
									case 'edit_new_fields':
									case 'delete_fields':
									case 'save_add_new_fields':
									case 'save_edit_new_fields':
										if( file_exists( $file ) ){
											$c = json_decode( file_get_contents( $file ), true );
											if( class_exists('cNwp_orm') ){
												$nwp = new cNwp_orm();
												$nwp->class_settings = $this->class_settings;
												$tb = "orm_elasticsearch";
												$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
												$client = cOrm_elasticsearch::$esClient;
												if( isset(cOrm_elasticsearch::$esClientError) && cOrm_elasticsearch::$esClientError ){
													$error_msg = cOrm_elasticsearch::$esClientError;
												}
												if( !$error_msg ){
													$exists = $client->indices()->exists([ 'index' => $dtable ])->asBool();
													if( !$exists ){
														$error_msg = "<h4><b>Invalid Data Source Index</b></h4><p>Data index for selected report doesn't seem to exist. Kindly contact technical team</p>";
													}
												}
											}
										}else{
											$error_msg = "<h4><b>Invalid Table Field Data</b></h4><p>There was an error in referencing fields</p>";
										}
									break;
								}

								if( !$error_msg ){
									switch ( $action_to_perform ) {
										case 'edit_new_fields':
										case 'delete_fields':
											$field_key = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';
											if( $field_key ){
												if( isset( $c['fields'][ $field_key ]) && $c['fields'][ $field_key ] ){
													$field_id = $c['fields'][ $field_key ];
													if( isset($c['labels'][ $field_id ]) && $c['labels'][ $field_id ] ){
														switch( $action_to_perform ){
															case 'delete_fields':
																unset( $c[ 'fields' ][ $field_key ] );
																unset( $c[ 'labels' ][ $field_id ] );
															break;
														}
													}
												}else{
													$error_msg = "<h4><b>A. Invalid Field</b></h4><p>Select a valid field</p>";
												}										
											}else{
												$error_msg = "<h4><b>B. Invalid Field</b></h4><p>Select a valid field</p>";
											}
										break;
									}									
								}

								if( !$error_msg ){
									switch ( $action_to_perform ) {
										case 'add_new_fields':
										case 'edit_new_fields':
											$this->class_settings['modal_title'] = 'Add New Field: '. $cl->label;
											switch ( $action_to_perform ) {
												case 'edit_new_fields':
													$this->class_settings['modal_title'] = 'Edit Field: '. $cl->label;
												break;
											}
										break;
									}

									$data = array(
										'dt' => $dt,
										'action_to_perform' => $action_to_perform,
									);

									switch( $action_to_perform ){
										case 'edit_new_fields':
											if( isset( $field_id ) && $field_id ){
												$data[ 'form_data' ] = array(
													'field_key' => $field_key,
													'field_label' => isset( $c[ 'labels' ][ $field_id ]['field_label']) && $c[ 'labels' ][ $field_id ]['field_label'] ? $c[ 'labels' ][ $field_id ][ 'field_label' ] : '',
													'display_field_label' => isset( $c[ 'labels' ][ $field_id ]['display_field_label']) && $c[ 'labels' ][ $field_id ]['display_field_label'] ? $c[ 'labels' ][ $field_id ][ 'display_field_label' ] : '',
													'field_type' => isset( $c[ 'labels' ][ $field_id ]['form_field']) && $c[ 'labels' ][ $field_id ]['form_field'] ? $c[ 'labels' ][ $field_id ][ 'form_field' ] : '',
													'form_field_option' => isset( $c[ 'labels' ][ $field_id ]['form_field_options']) && $c[ 'labels' ][ $field_id ]['form_field_options'] ? $c[ 'labels' ][ $field_id ][ 'form_field_options' ] : '',
												);
												$data['show_mod'] = 1;
											}else{
												$error_msg = "<h4><b>Invalid Field</b></h4><p>Field Details not available</p>";
												break;
											}
										case 'add_new_fields':
											
											$filename = 'add-new-fields.php';
											$this->class_settings['data'] = $data;
											$this->class_settings['modal_callback'] = "(a => nwReloadTableFunction('". $dt ."'))";
											$this->class_settings['html'] = array( $this->view_path . $filename );
											
											$html = $this->_get_html_view();
											
											return $this->_launch_popup( $html, '#'. $handle, $js );

										break;
										case 'delete_fields':

											try{
												
												$r = $in[ $tb ]->_load_query( array(
													'query' => array( 'labels' => $c['labels']),
													'query_type' => 'CREATE_TABLE',
													'tables' => array( $dtable ),
												) );
												
												if( isset($r['error']) && $r['error'] ){
													$error_msg = $r['error'];
													$e_type = 'error';
												
												}else{

													$e_type = 'success';

													$error_msg = "<h4><b>Field Deleted Successfully</b></h4><p>Field has been deleted successfully</p>";

													$n_params['callback'][] = "(a => nwReloadTableFunction('". $dt ."'))";
												}

											}catch(Exception $e){
												$error_msg = $e->getMessage();
											}catch(Throwable $e){
												$error_msg = $e->getMessage();
											}

										break;
										case 'save_edit_new_fields':
											$field_key = isset($_POST['field_key']) && $_POST['field_key'] ? $_POST['field_key'] : '';
											if( $field_key && isset($c[ 'fields' ][ $field_key ]) && $c[ 'fields' ][ $field_key ] ){
												$field_id = $c[ 'fields' ][ $field_key ];
												if( isset($c[ 'labels' ][ $field_id ]) && $c[ 'labels' ][ $field_id ] ){

													$nff = isset( $_POST['field_type'] ) && $_POST['field_type'] ? $_POST['field_type'] : $c['labels'][ $field_id ]['form_field'];
													$oldMap = get_elastic_field_mappings( $c[ 'labels' ][ $field_id ]['form_field'] );
													$newMap = get_elastic_field_mappings( $nff );

													// print_r($oldMap);
													// print_r($newMap);exit;

													$c[ 'labels' ][ $field_id ][ 'field_label' ] = isset( $_POST['field_label'] ) && $_POST['field_label'] ? $_POST['field_label'] : $c['labels'][ $field_id ]['field_label'];
													$c[ 'labels' ][ $field_id ][ 'display_field_label' ] = isset( $_POST['display_field_label'] ) && $_POST['display_field_label'] ? $_POST['display_field_label'] : $c['labels'][ $field_id ]['display_field_label'];
													$c[ 'labels' ][ $field_id ][ 'form_field' ] = $nff;
													$c[ 'labels' ][ $field_id ][ 'form_field_option' ] = isset( $_POST['form_field_option'] ) && $_POST['form_field_option'] ? $_POST['form_field_option'] : $c['labels'][ $field_id ]['form_field_options'];

													$error_msg = "<h4><b>Field Edited Successfully</b></h4><p>Field has been edited Successfully</p>";

													$n_params['html_replacement_selector'] = '#'.$handle;

													$e_type = 'success';

												}else{
													$error_msg = "<h4><b>Invalid Reference</b></h4><p>Invalid Field Label Selected</p>";
												}
											}else{
												$error_msg = "<h4><b>Invalid Reference</b></h4><p>Invalid Field Selected</p>";
											}
										break;
										case 'save_add_new_fields':
											
											if( $c ){
												
												$field_key = isset( $_POST[ 'field_label' ] ) && $_POST[ 'field_label' ] ? clean3( strtolower( $_POST[ 'field_label' ] ) ) : '';
												
												$lserialcache = array(
													'cache_key' => $this->table_name . '-last-generated-serial',
													'directory_name' => $this->table_name,
													'permanent' => true
												);

												$serial_num = get_cache_for_special_values( $lserialcache );

												if( !$serial_num ){

													$df = new cDatabase_fields();
													
													$df->class_settings = $this->class_settings;
													
													$df->class_settings['overide_select'] = " MAX(`serial_num`) AS 'serial_num' ";
													
													$serial_num = $df->_get_records();
													
													$serial_num = isset( $serial_num[0]['serial_num'] ) && $serial_num[0]['serial_num'] ? (int)$serial_num[0]['serial_num'] : rand();
												}

												$serial_num += 10;													
												
												$field_id = $dtable . $serial_num;

												if( isset( $c[ 'labels' ][ $field_id ]) && $c[ 'labels' ][ $field_id ] ){
													$serial_num += 100;
													$field_id = $dtable . $serial_num;
												}

												$date = date("U");

												if( $field_key && !(isset($c['labels'][ $field_id ]) && $c['labels'][ $field_id ]) ){

													$c[ 'fields' ][ $field_key ] = $field_id;

													$c[ 'labels' ][ $field_id ] = array(
														"table_name" => $dtable . '_reports',
														"serial_number" =>  strval( ( count( $c['labels'] ) + 10) * 10),
														"field_type" => "undefined",
														"filed_length" => "undefined",
														"field_label" => $_POST['field_label'],
														"display_field_label" => isset( $_POST['display_field_label'] ) && $_POST['display_field_label'] ? $_POST['display_field_label'] : '',
														"form_field" => isset( $_POST['field_type'] ) && $_POST['field_type'] ? $_POST['field_type'] : '',
														"form_field_options" => isset( $_POST['form_field_option'] ) && $_POST['form_field_option'] ? $_POST['form_field_option'] : '',
														"required_field" => "no",
														"database_objects" => "",
														"attributes" => "",
														"class" => "",
														"data" => array( "added_field" => 1 ),
														"default_appearance_in_table_fields" => "hide",
														"display_position" => "display-in-table-row",
														"acceptable_files_format" => "",
														"field_id" => $field_id,
														"id" => $field_id,
														"serial_num" => $serial_num,
														"created_by" => $this->class_settings['user_id'],
														"modification_date" => $date,
														"creation_date" => $date,
														"modified_by" => $this->class_settings['user_id'],
														"text" => $_POST['field_label'],
														"field_key" => $field_id,
														"table" => $dtable,
														"field_identifier" => $field_key
													);

													$lserialcache['cache_values'] = $serial_num;

													set_cache_for_special_values( $lserialcache );

													switch ( $c[ 'labels' ][ $field_id ][ 'form_field' ] ) {
														case 'select':
															if( $c[ "labels" ][ $field_id ][ "form_field_options" ] ){
																$c[ "labels" ][ $field_id ][ "data" ][ "form_field_options_source" ] = 2;
															}
														break;
													}
													
													$params = [ 
														'index' => $dtable,
														'body' => [
															'properties' => [ 
																$field_id => get_elastic_field_mappings( $c['labels'][ $field_id ][ 'form_field' ] ),
															]
														]
													];

													try{
														
														$response = $client->indices()->putMapping( $params );

														if( isset($response['acknowledged']) && $response['acknowledged'] ){

															$error_msg = "<h4><b>Field Added Successfully</b></h4><p>Field has been added Successfully</p>";

															$n_params['html_replacement_selector'] = '#'.$handle;

															$e_type = 'success';																
														}else{
															$error_msg = "<h4><b>Elastic Index Operation Failed</b></h4><p>There was an error when saving the added field</p>";
														}
													}catch(Exception $e){
														$error_msg = $e->getMessage();
													}catch(Throwable $e){
														$error_msg = $e->getMessage();
													}

												}else{
													$error_msg = "<h4><b>Invalid Field Label</b></h4><p>Kindly fill in a field label</p>";
												}
											}else{
												$error_msg = "<h4><b>Invalid Table Field Data</b></h4><p>There was an error in referencing fields</p>";
											}
										break;
									}
								
									switch ( $action_to_perform ) {
										case 'delete_fields':
										case 'save_add_new_fields':
										case 'save_edit_new_fields':
											if( $e_type == 'success' ){
												switch ($action_to_perform) {
													case 'save_edit_new_fields':
														if( isset( $oldMap ) && isset( $newMap ) && $oldMap != $newMap ){
															$r = $in[ $tb ]->_load_query( array(
																'query' => array( 'labels' => $c['labels']),
																'query_type' => 'CREATE_TABLE',
																'tables' => array( $dtable ),
															) );
															if( isset($r['error']) && $r['error'] ){
																$error_msg = $r['error'];
																$e_type = 'error';
															}
														}
													break;
												}

												if( $e_type == 'success' ){
													file_put_contents( $file, json_encode( $c ) );
												}
											}
										break;
									}
								}
							}
						}
					}
				}
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		protected function _import_csv_data_tutorial( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			$dt = isset($_POST['table']) && $_POST['table'] ? $_POST['table'] : ( isset($_GET['dt']) && $_GET['dt'] ? $_GET['dt'] : '' );

			if( $dt ){
				$tbd = explode( ":::", $dt );
				$tba = array();
				if( ! empty( $tbd ) ){
					foreach( $tbd as $tbv ){
						$tba2 = explode( '=', $tbv );
						if( isset( $tba2[1] ) && $tba2[1] ){
							$tba[ $tba2[0] ] = $tba2[1];
						}
					}
					
					$tb_type = isset( $tba["type"] )?$tba["type"]:'';
					$dtable = isset( $tba["value"] )?$tba["value"]:'';

					if( $dtable ){
						switch( $tb_type ){
							case "plugin":
								if( isset( $tba["key"] ) && $tba["key"] ){
									$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
									if( class_exists( $clp ) ){
										$ptb = new $clp();
										$in = $ptb->load_class( array( 'class' => array( $dtable ), 'initialize' => 1 ) );
										if( isset( $in[ $dtable ]->table_name ) ){
											$cl = $in[ $dtable ];
										}else{
											$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $dtable ."' is invalid or missing in the project.</p>";
										}
									}else{
										$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
									}
								}else{
									$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
								}
						
							break;
							default:
								$cls = 'c' . ucwords( $dtable );
								if( class_exists( $cls ) ){
									$cl = new $cls();
								}else{
									$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
								}
							break;
						}

						if( !$error_msg ){

							if( isset( $cl ) && function_exists( $dtable ) ){

								$filename = 's-import.php';

								if( isset( $t->basic_data["import_template"]["fields"] ) ){
									$d["import_fields"] = $cl->basic_data["import_template"]["fields"];
								}else{
									$d["import_fields"] = $cl->table_fields;
								}
								$d[ "dt" ] = $dt;
								$d[ "fields" ] = $cl->table_fields;
								$d[ "labels" ] = $dtable();

								$dir = "files/resource_library/";
								$fName = $dtable . "-import-template";
								$file_url = $dir . $fName.".csv";

								switch ($action_to_perform) {
									case 'refresh_file_upload_template':
										$d['refreshed'] = 1;
										if( file_exists( $this->class_settings['calling_page'] . $file_url ) ){
											unlink( $this->class_settings['calling_page'] . $file_url );
										}
									break;
								}

								if( file_exists( $this->class_settings['calling_page'] . $file_url ) ){
									$d[ "file_url" ] = $file_url;
								}else{
									foreach ( $d[ 'import_fields' ] as $akey => $avalue) {
										if( isset($d['labels'][ $avalue ]["data"][ "hide_on_import_table" ]) && $d['labels'][ $avalue ]["data"][ "hide_on_import_table" ] ){
											unset($d['import_fields'][ $akey ]);
										}										
									}

									$data[] = array_keys( $d['import_fields'] );
									for ($i=0; $i < rand(10, 15) ; $i++) {
										$arr = [];
										foreach ( $d[ 'import_fields' ] as $akey => $avalue) {
											$val = '';
											if( isset($d['labels'][ $avalue ]['form_field']) && $d['labels'][ $avalue ]['form_field'] ){
												switch ( $d['labels'][ $avalue ]['form_field'] ) {
													case 'number':
													case 'decimal_long':
													case 'decimal':
													case 'currency':
														$val = rand();
													break;
													case 'date-5':
													case 'date':
														$val = date("Y-m-d");
													break;
													case 'date-5time':
														$val = date("Y-m-d h:m:i");
													break;
													case 'select':
													case 'multi-select':
														$val = '';
														if( isset( $d['labels'][ $avalue ][ 'form_field_options' ] ) && $d['labels'][ $avalue ][ 'form_field_options' ] ){
															$f = $d['labels'][ $avalue ][ 'form_field_options' ];
															if( function_exists($f) ){
																$f = $f();
																$key = array_keys($f);
																$val = $key[ array_rand( $key ) ];
															}
														}
	
														if( !$val ){
															if( isset( $d['labels'][ $avalue ][ 'options' ] ) && $d['labels'][ $avalue ][ 'options' ] ){
																$ex = explode(";", $d['labels'][ $avalue ][ 'options' ] );
																if( is_array( $ex ) && ! empty( $ex ) ){
																	foreach( $ex as $ek => $ev ){
																		if( $ev ){
																			$es2 = explode(":", $ev );
																			if( isset( $es2[0] ) && $es2[0] ){
																				$key[] = $es2[0];
																			}
																		}
																	}

																	$val = $key[ array_rand( $key ) ];
																}
															}
														}

														if( !$val ){
															if( isset( $d['labels'][ $avalue ][ 'data' ]["form_field_options_source"] ) && ( $d['labels'][ $avalue ][ 'data' ]["form_field_options_source"] == 2 || $d['labels'][ $avalue ][ 'data' ]["form_field_options_source"] == 'list_box_class' ) ){
																$ex = get_list_box_options( $d['labels'][ $avalue ][ 'form_field_options' ], array( "return_type" => 1 ) );
																if( isset( $ex["keys"] ) ){
																	$val = $ex["keys"][ array_rand( $ex['keys'] ) ];
																}
															}															
														}

													break;
													case 'calculated':
														if( isset( $d['labels'][ $avalue ]['calculations']['reference_table'] ) && $d['labels'][ $avalue ]['calculations']['reference_table'] ){
															$ktb = $d['labels'][ $avalue ]['calculations']['reference_table'];
															$values = pull_cache_values( array( 'permanent' => 1, 'directory_name' => $ktb, 'return_cache' => [ 'count' => 4 ] ) );
															$values = array_keys( itemize_by_key($values, 'id') );
															if( $values ){
																$val = $values[ array_rand( $values ) ];
															}
														}
													break;
													case 'textarea':
														$val = 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Consectetur nemo explicabo iusto facilis dignissimos unde id sint! Dolorum odit, vitae animi consequuntur fugit qui deleniti soluta cum, repudiandae, laudantium, nam.';
													break;
													default:
														$val = 'Lorem ipsum dolor sit.';
													break;
												}
											}

											$arr[] = $val;
										}

										$data[] = $arr;
									}
									download_csv($data, $this->class_settings['calling_page'], $this->class_settings['calling_page'] . $dir . $fName, array( 'transformed' => 1 ));
									if( file_exists( $this->class_settings['calling_page'] . $file_url ) ){
										$d[ "file_url" ] = $file_url;
									}
								}
								
								$d[ "title" ] = isset( $cl->label ) ? $cl->label : $cl->table_name;
								$d[ "table" ] = $cl->table_name;

								$this->class_settings[ 'data' ] = $d;
								$this->class_settings['html'] = array( $this->view_path . $filename );
								$html = $this->_get_html_view();

								return array(
									'status' => 'new-status',
									'html_replacement' => $html,
									'html_replacement_selector' => '#'.$handle,
									'javascript_functions' => $js
								);
							}
						}

					}else{
						$error_msg = "<h4><b>Invalid Data Source</b></h4><p>Error Parsing Data Source Parameter</p>";
					}
				}
			}else{
				$error_msg = "<h4><b>Invalid Reference</b></h4><p>Select a valid data entry source</p>";
			}

			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}

		protected function _custom_capture_form_version_2(){

			$action_to_perform = $this->class_settings['action_to_perform'];

			$filename = str_replace( '_', '-', $action_to_perform );
			
			$modal = 0;
			$params = '';
			$html = '';
			$close_modal = '';
			$error = '';
			$etype = 'error';
			$error_container = '';
			$manual_close = 0;
			$mjs = array( 'set_function_click_event', 'prepare_new_record_form_new' );

			$id = isset( $_POST[ 'id' ] ) ? $_POST[ 'id' ] : '';
			
			$data = array();
			
			$handle = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			switch ( $action_to_perform ){
			case 'create_index':

				$data = [];
				$ad = new cAudit();
				$opt = [];
				$opt['group_plugin'] = 1;
				$opt['no_labels'] = 1;
				$opt['no_fields'] = 1;
				$opt['for_database_table_name'] = 1;
				$opt['selected_class_only'] = [
					'cNwp_reports' => 1,
					'cNwp_grm' => 1,
					'cNwp_device_management' => 1,
					'cNwp_enrolment_data' => 1
				];

				$opt['excluded_plugin_classes'] = array(
					"frequent_changes" => 1,
					"non_frequent_changes" => 1,
					"frequent_changes_sr" => 1,
					"non_frequent_changes_sr" => 1,
					// "device_mgt_sr" => 1
					// ==============================
					// ""
					'report_config' => 1,
					'enrolment_device' => 1,
					'enrolment_agent_activity' => 1,
					'enrolment_fingerprint' => 1,
					'enrolment_photo' => 1,
					'enrolment_demographic' => 1,
					"enrolment_document" => 1,
					"enrolment_signature" => 1,
					"enrolment_preview" => 1,
					"enrolment_liveness" => 1,
					"enrolment_iris" => 1,
					"enrolment_metadata" => 1
				);

				$data["databases"] = $ad->_get_project_classes( $opt );
				
			break;
			case 'save_create_index':
				if( isset( $_POST[ 'dtable' ] ) && is_array( $_POST[ 'dtable' ] ) && ! empty( $_POST[ 'dtable' ] ) ){

					$error_msg = "";
					$success = [];
					$fail = [];

					foreach( $_POST[ 'dtable' ] as $dtable ){

						$tbd = explode(":::", $dtable);
						$tba = array();
						if( ! empty( $tbd ) ){
							foreach( $tbd as $tbv ){
								$tba2 = explode( '=', $tbv );
								if( isset( $tba2[1] ) && $tba2[1] ){
									$tba[ $tba2[0] ] = $tba2[1];
								}
							}
						}

						$tb_type = isset( $tba["type"] )?$tba["type"]:'';
						$table2 = isset( $tba["value"] )?$tba["value"]:'';
						$dtable = $table2;

						switch( $tb_type ){
						case "plugin":
							if( isset( $tba["key"] ) && $tba["key"] ){
								$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
								if( class_exists( $clp ) ){
									$ptb = new $clp();
									$in = $ptb->load_class( array( 'class' => array( $dtable ), 'initialize' => 1 ) );
									if( isset( $in[ $dtable ]->table_name ) ){
										$cl = $in[ $dtable ];
									}else{
										$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $dtable ."' is invalid or missing in the project.</p>";
									}
								}else{
									$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
								}
							}else{
								$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
							}
						break;
						default:
							$cls = 'c' . ucwords( $dtable );
							if( class_exists( $cls ) ){
								$cl = new $cls();
							}else{
								$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
							}
						break;
						}

						if( $error_msg ){
							$fail[] = $dtable;
							$error_msg = "";
						}else{

							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => array(
									'labels' => $dtable(),
								),
								'query_type' => 'CREATE_TABLE',
								'db_mode' => 'elasticsearch',
								'set_memcache' => 0,
								'parent_tb' => $this->table_name,
								'plugin' => $this->plugin,
								'action_to_perform' => $this->class_settings[ 'action_to_perform' ],
								'record_plugin' => isset( $tba["key"] ) ? $tba["key"] : '',
								'tables' => array( $dtable ),
							);
							
							$dxd = execute_sql_query( $query_settings );

							if( isset( $dxd[ 'error' ] ) && $dxd[ 'error' ] ){
								$fail[] = $dtable . $dxd[ 'error' ];
							}else{
								$success[] = $cl->label;
							}
						}
					}

					if( ! empty( $fail ) ){
						$error = '<h4><strong>Failed Tables</strong></h4>'.implode( '<br>', $fail );
					}

					if( ! empty( $success ) ){
						$error = '<h4><strong>Successful Tables</strong></h4>'.implode( '<br>', $success );
						$etype = 'success';
					}

				}else{
					$error = '<h4><strong>Invalid Data Source</strong></h4>Please select at least one data source';
				}
			break;
			case 'save_refresh_index':
				if( isset( $_POST[ 'dtable' ] ) && is_array( $_POST[ 'dtable' ] ) && ! empty( $_POST[ 'dtable' ] ) ){

					$error_msg = "";
					$success = [];
					$fail = [];

					foreach( $_POST[ 'dtable' ] as $dtable ){

						$tbd = explode(":::", $dtable);
						$tba = array();
						if( ! empty( $tbd ) ){
							foreach( $tbd as $tbv ){
								$tba2 = explode( '=', $tbv );
								if( isset( $tba2[1] ) && $tba2[1] ){
									$tba[ $tba2[0] ] = $tba2[1];
								}
							}
						}

						$tb_type = isset( $tba["type"] )?$tba["type"]:'';
						$table2 = isset( $tba["value"] )?$tba["value"]:'';
						$dtable = str_replace("device_mgt", "device_mgt_store", $table2 );

						switch( $tb_type ){
							case "plugin":
								if( isset( $tba["key"] ) && $tba["key"] ){
									$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
									if( class_exists( $clp ) ){
										$ptb = new $clp();
										$in = $ptb->load_class( array( 'class' => array( $dtable ), 'initialize' => 1 ) );
										if( isset( $in[ $dtable ]->table_name ) ){
											$cl = $in[ $dtable ];
										}else{
											$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $dtable ."' is invalid or missing in the project.</p>";
										}
									}else{
										$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
									}
								}else{
									$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
								}
							break;
							default:
								$cls = 'c' . ucwords( $dtable );
								if( class_exists( $cls ) ){
									$cl = new $cls();
								}else{
									$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
								}
							break;
						}

						if( $error_msg ){
							$fail[] = $dtable;
							$error_msg = "";
						}else{

							$cl->class_settings['action_to_perform'] = 'refresh_cache';

							$cl->class_settings['run_filter'] = array( 'date_filter' => array( 'call_end_time' => date("U") ) );

							$es = $cl->$dtable();

							if( $es && is_array( $es ) ){
								$fail[] = $dtable . ( isset( $es['msg'] ) && $es['msg'] ? $es['msg'] : 'Unknown Error' );
							}else{
								$success[] = str_replace(" - Store", "", $cl->label);
							}
						}
					}

					if( ! empty( $fail ) ){
						$error = '<h4><strong>Failed Tables</strong></h4>'.implode( '<br>', $fail );
					}

					if( ! empty( $success ) ){
						$error = '<h4><strong>Successful Tables</strong></h4>'.implode( '<br>', $success );
						$etype = 'success';
					}

				}else{
					$error = '<h4><strong>Invalid Data Source</strong></h4>Please select at least one data source';
				}
			break;
			}

			if( $error ){
				$ex = array( "type" => $etype, "message" => $error );
				if( $error_container ){
					$ex[ 'do_not_display' ] = 1;
					$ex[ 'html_replacement_selector' ] = $error_container;
				}
				if( $close_modal ){
					$ex[ 'callback' ][] = array( $close_modal );
				}
				if( $manual_close ){
					$ex[ 'manual_close' ] = 1;
				}
				return $this->_display_notification( $ex );
			}

			$data[ 'params' ] = '&html_replacement_selector=' . $handle . $params;
				
			if( ! $html && $filename ){
				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( $this->view_path . $filename );
				$html = $this->_get_html_view();
			}
			
			if( isset( $after_html["html_replacement"] ) ){
				$html .= $after_html["html_replacement"];
			}
			
			if( $modal ){
				return $this->_launch_popup( $html, "#" . $handle, $mjs );
			}
			
			if( isset( $_GET["close_modal"] ) && $_GET["close_modal"] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/' . $this->table_name_static . '/zero-out-negative-budget-removal-script.php' );
				$html .= $this->_get_html_view();
			}
			
			$return = array(
				// 'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => "#" . $handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $mjs,
			);
			
			if( isset( $_GET["menu_title"] ) && $_GET["menu_title"] ){
				// $return["javascript_functions"][] = '$.fn.cCallBack.displayTabTitle';
				// $return["data"]["tab_title"] = rawurldecode( $_GET["menu_title"] );
			}
			return $return;
		}

		protected function _display_application_update_view(){
			$running_in_background = 0;
			if( $this->class_settings['running_in_background'] && $this->class_settings['running_in_background'] ){
				$running_in_background = ( isset( $_POST['id'] ) && $_POST['id'] )?$_POST['id']:1;
			}
			
			$js = array( 'set_function_click_event' );
			$html_q = '';
			$html = '';
			$error_msg = '';
			$handle = '#dash-board-main-content-area';
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ]["html_replacement_selector"] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			$ifil = array();
			
			switch( $action_to_perform ){
			case 'display_data_access_view':
				$duplicates = 0;
								
				if( isset( $_POST[ 'data_source' ] ) && $_POST[ 'data_source' ] ){
					$s_option = isset( $_POST[ 's_option' ] )?$_POST[ 's_option' ]:'';
					$tbd = explode( ':::' , $_POST[ 'data_source' ] );
					$tba = array();
					if( ! empty( $tbd ) ){
						foreach( $tbd as $tbv ){
							$tba2 = explode( '=', $tbv );
							if( isset( $tba2[1] ) && $tba2[1] ){
								$tba[ $tba2[0] ] = $tba2[1];
							}
						}
					}
					$tb_type = isset( $tba["type"] )?$tba["type"]:'';
					$tb = isset( $tba["value"] )?$tba["value"]:'';
					
					$todo = 'display_all_records_frontend';
					$skip = 0;
					
					switch( $s_option ){
					case "spreadsheet":
						$todo = 'display_spreadsheet_view';
						$todo = 'display_spreadsheet_new';
					break;
					case "datatable_trash":
						$todo = 'display_all_deleted_records';
					break;
					}
					
					switch( $tb_type ){
					case "plugin":
						
						if( isset( $tba["key"] ) && $tba["key"] ){
							$clp = 'c' . ucwords( strtolower( $tba["key"] ) );
							if( class_exists( $clp ) ){
								$ptb = new $clp();
								$in = $ptb->load_class( array( 'class' => array( $tb ), 'initialize' => 1 ) );
								if( isset( $in[ $tb ]->table_name ) ){
									$cl = $in[ $tb ];
								}else{
									$error_msg = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $tb ."' is invalid or missing in the project.</p>";
								}
							}else{
								$error_msg = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
							}
						}else{
							$error_msg = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
						}
						
					break;
					default:
						$cls = 'c' . ucwords( $tb );
						if( class_exists( $cls ) ){
							$cl = new $cls();
						}else{
							$error_msg = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
						}
					break;
					}
					
					if( ! $error_msg ){
						
						if( ! $skip ){
							$cc = new cCustomer_call_log();
							$cc->class_settings = $this->class_settings;
							
							$tb2 = $cc->table_name;
							$cc->table_fields = $cl->table_fields;
							$cc->table_name = $cl->table_name;
							
							if( isset( $cl->db_table_name ) && $cl->db_table_name ){
								$cc->table_name = $cl->db_table_name;
							}
							$cc->label = ( isset( $cl->label )?$cl->label:$cl->table_name );
							
							if( isset( $cl->spreadsheet_options ) && $cl->spreadsheet_options ){
								$cc->spreadsheet_options = $cl->spreadsheet_options;
							}
							
							if( isset( $cl->plugin ) && $cl->plugin ){
								$cc->plugin = $cl->plugin;
							}
							
							$where = "";
							if( ! empty( $ifil ) ){
								foreach( $ifil as $ifk => $ifv ){
									if( isset( $cc->table_fields[ $ifk ] ) ){
										$where .= " AND `".$cc->table_name."`.`".$cc->table_fields[ $ifk ]."` = '".$ifv."' ";
									}
								}
								
								switch( $cc->table_name ){
									case "banks":
										if( isset( $ifil["type"] ) ){
											$cc->class_settings[ 'datatable_settings' ]["button_params"] = '&type=' . $ifil["type"];
										}
									break;
								}
							}

							switch( $cc->table_name ){
								case "users":
								case "access_roles":
								case "parish_transaction":
								case "vendor_bills":
								case "vendors":
								case "production":
								case "expenditure":
								case "sales":
								case "customers":
								case "void_transactions":
								case "transactions":
								case "general_settings":
								case "online_payment":
								case "payment":
								case "revision_history":
								case "debit_and_credit":
								case "inventory_ent":
									$hide = 0;
									if( ! get_hyella_development_mode() ){
										$hide = 1;
										if( $this->class_settings[ 'user_id' ] == '35991362173x' ){
											$hide = 0;
										}
									}
									if( $hide ){
										$cc->class_settings[ 'datatable_settings' ]['show_add_new'] = 0;
										$cc->class_settings[ 'datatable_settings' ]['show_edit_button'] = 0;
										$cc->class_settings[ 'datatable_settings' ]['show_delete_button'] = 0;
									}
								break;
							}
							
							if( $where )$cc->class_settings["filter"][ 'where' ] = $where;
							$cc->class_settings[ 'datatable_settings' ]['utility_buttons']['view_details'] = 1;
							if( $duplicates ){
								$cc->class_settings[ 'full_table' ] = 1;
								$cc->class_settings[ 'datatable_settings' ]['show_add_new'] = 0;
								$cc->class_settings[ 'datatable_settings' ]['show_edit_button'] = 0;
								
							}
							$cc->class_settings[ 'base_call' ] = 1;
							$cc->class_settings[ 'action_to_perform' ] = $todo;

							return $cc->$tb2();
						}
					}
					
				}else{
					$error_msg = "<h4><strong>Undefined/Missing Table Name </strong></h4><p>The table name is not found. Please define a table name</p>";
				}
			break;
			}
			
			if( $html ){
				if( $html_q ){
					$html = '<h4>Database Queries</h4><div contenteditable="true" style="background:#222; color:#eee; overflow:auto; max-height:300px;">'. $html_q .'</div>' . $html;
				}
				
				return array(
					'html_replacement_selector' => $handle,
					'html_replacement' => $html,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					'javascript_functions' => $js 
				);
			}
			
			if( $error_msg ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cAudit.php';
				$err->method_in_class_that_triggered_error = '_sync_progress';
				$err->additional_details_of_error = $error_msg;
				return $err->error();
			}
		}

		protected function _manage_r_request(){

			$data = array();

			$action_to_perform = $this->class_settings['action_to_perform'];

			switch ($action_to_perform) {
				case 'manage_dashboard':
					$nwp = new cNwp_reports();
					$nwp->class_settings = $this->class_settings;
					$tb = "reports_bay";
					$in = $nwp->load_class( array( 'class' => [ $tb ], 'initialize' => 1 ) );
					$in[ $tb ]->class_settings['action_to_perform'] = 'report_dashboard';
					$in[ $tb ]->class_settings['return_data'] = 1;
					$data = $in[ $tb ]->$tb();
					// $data = [
					//     array(
					//       "title" => "Total Enrolment",
					//       "value" => 10364,
					//       "percent" => 12,
					//       "icon" => array(
					//         "class" => "bg-primary",
					//         "feather" => "user"
					//       )    
					//     ),
					//     array(
					//       "title" => "Total Adult Enrolment",
					//       "value" => 1546,
					//       "percent" => 14.9,
					//       "icon" => array(
					//         "class" => "bg-primary",
					//         "feather" => "user"
					//       )    
					//     ),
					//     array(
					//       "title" => "Total Child Enrolment",
					//       "value" => 8818,
					//       "percent" => 85.1,
					//       "icon" => array(
					//         "class" => "bg-primary",
					//         "feather" => "user"
					//       )    
					//     ),
					//     array(
					//       "title" => "Total Male Enrolment",
					//       "value" => 5043,
					//       "percent" => 48,
					//       "icon" => array(
					//         "class" => "bg-primary",
					//         "feather" => "user"
					//       )    
					//     ),
					//     array(
					//       "title" => "Total NIN Generated",
					//       "value" => 10093,
					//       "percent" => -36,
					//       "icon" => array(
					//         "class" => "bg-primary",
					//         "feather" => "user"
					//       )    
					//     ),
					//   ];
				break;
				case 'get_sidebar':
					$data = get_dashboard_sidemenu();
				break;
			}

			return array(
				'status' => 'new-status',
				'data' => $data
			);

			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
			
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
			
			return $this->_display_notification( $n_params );
		}

		protected function _load_data_view( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
		
			$this->class_settings['data'] = array(
				'plugin' => $this->plugin,
				'table' => $this->table_name
			);

			switch( $action_to_perform ){
			case 'manage_spreadsheet':
			case 'manage_spreadsheet_frame':
				$filename = 'e-spreadsheet.php';
			break;
			case 'manage_fileload':
			case 'manage_fileload_frame':
				$filename = 'fileload.php';
			break;
			}
			
			$data = [];

			$data["databases"] = get_all_report_types();
			if( isset( $data['databases'][ 'nwp_reports' ][ 'classes' ]['indicators'] ) && $data['databases'][ 'nwp_reports' ][ 'classes' ]['indicators'] ){
				unset( $data['databases'][ 'nwp_reports' ][ 'classes' ]['indicators'] );
			}
			
			if( !get_hyella_development_mode() && isset( $data['databases']['nwp_device_management'] ) && $data['databases']['nwp_device_management'] ){
				unset( $data['databases'][ 'nwp_device_management' ] );
			}
			// $data["databases"][ 'nwp_reports' ]['classes'] = get_report_source_types();
			// $data["databases"][ 'nwp_reports' ]['type'] = 'plugin';
			// $data["databases"][ 'nwp_reports' ]['label'] = 'Reports';
			
			$this->class_settings[ 'data' ] = $data;

			if( !$error_msg ){
				$this->class_settings['html'] = array( $this->view_path . $filename );
				$html = $this->_get_html_view();

				switch ( $action_to_perform ) {
					case 'manage_fileload_frame':
					case 'manage_spreadsheet_frame':
						return $this->_display_html_only( [ 'html' => $html ] );
					break;
				}
			
				return array(
					'status' => 'new-status',
					'html_replacement' => $html,
					'html_replacement_selector' => '#'.$handle,
					'javascript_functions' => $js
				);
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['type'] = $e_type;
			$n_params['message'] = $error_msg;
		
			return $this->_display_notification( $n_params );
		}
		
		protected function _login( $o = array() ){
			$error_msg = '';
			$e_type = 'error';
			$n_params = array();
			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );
		
			$handle = isset($this->class_settings['html_replacement_selector']) && $this->class_settings['html_replacement_selector'] ? $this->class_settings['html_replacement_selector'] : 'inventories-asset-center-sub';
		
			$action_to_perform = $this->class_settings['action_to_perform'];
			$_POST = json_decode(file_get_contents('php://input'), true);
			switch( $action_to_perform ){
				case 'login':
					$_POST['username_key'] = 'email';
					$a = new cAuthentication();
					$a->class_settings = $this->class_settings;
					$a->class_settings['action_to_perform'] = 'authenticate_app';
					$r = $a->authentication();
					return $r;
				break;
			}
		
			$error_msg = $error_msg ? $error_msg : '<h4><b>Unknown Error</b></h4><p>Please try again later</p>';
		
			$n_params['status'] = $e_type;
			$n_params['message'] = $error_msg;
			
			return $n_params;		
		}
		
		protected function _view_details2(){
			
			$filename = '';
				
			$handle = "#dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
				
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			
			$action_to_perform = $this->class_settings['action_to_perform'];
			$error_msg = '';
			$hide_buttons = isset( $_GET[ 'hide_buttons' ] ) ? $_GET[ 'hide_buttons' ] : '';
			$e_type = 'error';
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$error_msg = 'Invalid Reference';
			}
			
			switch( $action_to_perform ){
			case 'view_details2':
				if( ! $error_msg ){
					$this->class_settings["current_record_id"] = $_POST[ 'id' ];
					$e = $this->_get_record();
					if( isset( $e["id"] ) ){
						
					}else{
						$error_msg = 'Unable to retrieve patient details';
					}
				}
			break;
			}
			
			switch( $action_to_perform ){
			case 'control_panel':
				if( ! $error_msg ){
					$opt = array();
					if( isset( $_GET[ 'click_btn' ] ) && $_GET[ 'click_btn' ] )$opt[ 'click_btn' ] = $_GET[ 'click_btn' ];
					$id = $e['id'];
					$opt[ 'id' ] = $id;

					/* $opt[ 'general_tasks' ][ 'administer_vaccine_btn' ] = array(
						'action' => 'antenatal_care_progress',
						'todo' => 'show_capture_options&use_get_customer=1&customer='.$e[ 'customer' ],
						'title' => 'New Care Progress',
					); */

					$opt[ 'general_actions' ][ 'view_details' ] = array(
						'action' => $this->table_name,
						'todo' => 'view_details',
						'title' => 'View Details',
					);

					$opt[ 'details_todo' ] = 'view_details2';
					$opt[ 'title_text' ] = get_name_of_referenced_record( array( 'id' => $e[ 'customer' ], 'table' => 'customers' ) );
					$_GET[ 'hide_buttons' ] = 1;

					$this->class_settings[ 'open_module' ] = $opt;

					//set_current_customer( array( "customer" => $id ) );
					return $this->_open_module();
				}
			break;
			case 'view_details2':
				$_GET["modal"] = 1;
				$filename = "view-details.php";
					
				if( ! $error_msg ){
					if( isset( $e["id"] ) ){
						$this->_apply_basic_data_control( $e );
						
						/* $stb = array();
						if( isset( $e["status2"] ) ){
							switch( $e["status2"] ){
							case "sent_to_nurse":
								$stb = array( 'an_vi' => 1 );
							break;
							}
						}
						if( empty( $stb ) ){
							$stb = array( 'of' => 1 );
						} 
						
						$this->class_settings[ 'data' ][ 'standalone_buttons' ] = $stb;
						*/
						
						//$this->class_settings[ 'data' ][ 'more_data' ][ 'additional_params' ] = '&reference=' . $e["id"] . '&reference_table=' . $this->table_name;
						
						
						$this->class_settings[ 'data' ][ 'selected_record' ] = $e["id"];
						$this->class_settings[ 'data' ][ 'table' ] = $this->table_name;
						
						
						$this->class_settings[ 'data' ][ 'utility_buttons' ] = $this->datatable_settings["utility_buttons"];
						$this->class_settings[ 'data' ][ 'more_actions' ] = $this->basic_data["more_actions"];
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/globals/custom-buttons.php' );
						
						$b["more_actions"] = $this->_get_html_view();
						$b["id"] = $e["id"];
						//$b["auth_data"] = $qd;
						
						$this->class_settings["data_items"] = array( $e["id"] => $e );
						$this->class_settings["other_params"] = $b;
					}else{
						$error_msg = 'Unable to Retrieve Record';
					}
				}
			break;
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => $e_type, "message" => $error_msg ) );
			}
			
			if( $hide_buttons )$this->class_settings[ 'data' ][ 'hide_buttons' ] = $hide_buttons;
			
			$this->class_settings[ 'data' ][ 'no_columns' ] = isset( $_GET["no_column"] )?$_GET["no_column"]:'';
			if( isset( $this->plugin ) && $this->plugin ){
				$this->class_settings[ 'data' ][ 'plugin' ] = $this->plugin;
				if( $filename ){
					$this->class_settings["html_filename"] = array( $this->view_path . $filename );
				}
			}else{
				if( $filename ){
					$this->class_settings["html_filename"] = array( 'html-files/templates-1/package/'.HYELLA_PACKAGE.'/'.$this->table_name.'/'.$filename );
				}
			}
			
			$this->class_settings["modal_dialog_style"] = "width:40%;";
				
			$return = $this->_view_details();
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'view_details2':
				if( isset( $return["html_replacement_selector"] ) && $return["html_replacement_selector"] ){
					$return["html_replacement_selector"] = $handle;
				}
			break;
			}
			
			return $return;
		}
		
		protected function _search_form2(){
			$where = "";
			
			foreach( array( 'customer', 'staff_responsible' ) as $key ){
				if( isset( $this->table_fields[ $key ] ) && isset( $_POST[ $this->table_fields[ $key ] ] ) && $_POST[ $this->table_fields[ $key ] ] ){
					//$this->class_settings[ $key ] = $_POST[ $this->table_fields[ $key ] ];
					$where .= " AND `".$this->table_fields[ $key ]."` = '".$_POST[ $this->table_fields[ $key ] ]."' ";
				}
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
			$where = '';
			$disable_btn = 1;
			$spilt_screen = 0;
			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			$show_form = 0;
			
			switch( $action_to_perform ){
			case "display_all_records_full_view_search":
			case "display_all_records_frontend_history":
				$show_form = 1;
				unset( $this->class_settings[ "full_table" ] );
			break;
			case "display_all_records_frontend":
				$spilt_screen = 1;
			break;
			}
			
			if( $disable_btn ){
				$this->datatable_settings[ "show_add_new" ] = 0;
				$this->datatable_settings[ "show_edit_button" ] = 1;
				$this->datatable_settings[ "show_delete_button" ] = 0;
				unset( $this->basic_data['more_actions'] );
			}
			
			switch( $action_to_perform ){
			case "display_all_records_frontend_history":
				$this->datatable_settings[ "show_edit_button" ] = 1;
				unset( $this->basic_data['more_actions'] );
			break;
			}
			
			if( $show_form ){
				$this->class_settings[ "show_form" ] = 1;
				$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
				$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
				$this->class_settings[ 'add_empty_select_option' ] = 1;
				
				foreach( $this->table_fields as $key => $val ){
					switch( $key ){
					case "date":
						$this->class_settings["form_values_important"][ $val ] = date("U");
						$this->class_settings["attributes"]["show_date_range"][ $val ] = 1;
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					case "staff_responsible":
					case "customer":
						$this->class_settings["attributes"]["disable_required_field"][ $val ] = 1;
					break;
					default:
						$this->class_settings["hidden_records"][$val] = 1;
					break;
					}
				}
			}

			if( $spilt_screen ){
				$this->class_settings[ "full_table" ] = 1;
				$this->class_settings[ "show_popup_form" ] = 1;
				$this->class_settings[ 'frontend' ] = 1;
				
				$this->datatable_settings["split_screen"] = array();
				
				$this->datatable_settings["datatable_split_screen"] = array(
					'action' => '?action='. $this->plugin .'&todo=execute&nwp_action=' . $this->table_name . '&nwp_todo=view_details2&no_column=1',
					'col' => 4,
					'content' => get_quick_view_default_message_settings(),
				);
			}
			
			return $this->_display_all_records_full_view();
		}

		protected function _save_app_changes2(){
			
			$ddx = array();
			$error = '';
			$e = array();
			$etype = 'error';
			$save_app = 1;
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'save_app_changes':
			break;
			}
			
			if( $error ){
				return $this->_display_notification( array( "type" => $etype, "message" => $error ) );
			}
			
			switch( $save_app ){
			case 1:
				$return = $this->_save_app_changes();
			break;
			case 2:
				$return = $this->_save_changes();
			break;
			case 3:
				$return = $this->_update_table_field();
			break;
			case 4:
				$return = $this->_save_line_items();
				if( $return )$return = array( 'saved_record_id' => 1 );
			break;
			}
			
			// print_r( $return );exit;
			if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
				
				$container = "#dash-board-main-content-area";
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$container = "#" . $this->class_settings[ 'html_replacement_selector' ];
				}
				
				switch( $action_to_perform ){
				case 'save_app_changes':
				break;
				}
				
			}
			
			// Send notification to assigned persons
			switch( $action_to_perform ){
			case 'save_new_popup':
			break;
			}
			
			return $return;
		}
		
		protected function _new_popup_form2(){
			$action_to_perform = $this->class_settings['action_to_perform'];
			//$this->class_settings["skip_link"] = 1;
			
			switch ( $action_to_perform ){
			case 'edit_popup_form_in_popup':
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
			break;
			default:
			break;
			}
			
			//$this->class_settings["modal_callback"] = 'nwCustomers.loadForm';
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
		
		protected function _apply_basic_data_control( $e = array() ){
			/* if( ! isset( $e["status"] ) ){
				return;
			} */
			
			//check if method is defined in custom-buttons
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return;
				}
			}
			
			/* 
			$show_cancel = 0;
			switch( $e["status"] ){
			case "cancelled":
			case "in_active":
				$show_cancel = 1;
			break;
			}

			if( ! $show_cancel ){
				unset( $this->basic_data['more_actions']['actions']['data']['p1'] );
			}
			 */
			 
			if( isset( $e["action_to_perform"] ) && $e["action_to_perform"] ){
				$pos = strpos( json_encode( $this->basic_data['more_actions'] ), $e["action_to_perform"] );
				if ($pos === false) {
					return $this->_display_notification( array( "type" => "error", "message" => "<h4>Access Denied</h4>The status of the " . $this->label . " does not permit this action" ) );
				}
			}
		}
			
		public $basic_data = array(
			'access_to_crud' => 0,	//all crud functions (add, edit, view, delete) will be available in access control
			//'exclude_from_crud' => array(),

			'more_actions' => array(),
		);
	}
	
	function react_handler(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/react_handler.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/react_handler.json' ), true );
			if( isset( $return[ "labels" ] ) )return $return[ "labels" ];
		}
	}
?>