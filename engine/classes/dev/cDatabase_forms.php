<?php
/**
 * database_forms Class
 *
 * @used in  				database_forms Function
 * @created  				15:28 | 05-Sep-2019
 * @database table name   	database_forms
 */
	
	class cDatabase_forms extends cBase_class{
		
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'database_forms';
		
		public $default_reference = '';
		
		public $label = 'Database Forms';
		
		private $associated_cache_keys = array(
			'database_forms',
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
				'show_refresh_cache' => 1,
				
			'utility_buttons' => array(
				'comments' => 1,
				'share' => 1,
				'attach_file' => 1,
				'tags' => 1,
				'view_details' => 1,
				// 'ub_caption_text' => 'More Actions',
			),	//Determines whether or not to show delete button
				
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
			
			if( file_exists( dirname( __FILE__ ).'/dependencies/database_forms.json' ) ){
				$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/database_forms.json' ), true );
				if( isset( $return[ "fields" ] ) )$this->table_fields = $return[ "fields" ];
			}
			
		}
	
		function database_forms(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError(000017);
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
					$err->method_in_class_that_triggered_error = '_language_initialization';
					$err->additional_details_of_error = 'no language file';
					return $err->error();
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
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
			break;
			case 'restore':
				$returned_value = $this->_restore_records();
			break;
			case 'display_all_records_full_view_search':
			case 'display_all_records_full_view':
			case 'display_all_records_frontend':
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
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'search_list2_datatable':
			case 'search_list2_handsontable':
			case 'search_list2':
			case 'search_list':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log();
			break;
			case 'view_details2':
			case 'view_details':
				$returned_value = $this->_view_details2();
			break;
			case "search_form":
				$returned_value = $this->_search_form2();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			case 'update_table_field':
				$returned_value = $this->_update_table_field();
			break;
			case 'display_form_fields_and_labels':
			case 'get_form_preview':
			case 'get_form_fields3':
			case 'get_form_fields2':
			case 'get_form_field4':
			case 'get_form_fields_select2':
			case 'display_form2':
			case 'display_form':
				$returned_value = $this->_display_form();
			break;
			case 'display_canvas':
			case 'edit_popup_form':
			case 'edit_form':
				$returned_value = $this->_display_canvas();
			break;
			case 'drag_drop_save':
				$returned_value = $this->_drag_drop_save();
			break;
			case 'drag_drop_create_table':
				$returned_value = $this->_drag_drop_create_table();
			break;
			case 'get_select2':
				$returned_value = $this->_get_select2();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'refresh_form':
				$returned_value = $this->_refresh_form();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}

		protected function _refresh_form(){
			if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
				$skip_refresh_fields = array(
					'id',
					'reuse',
					'position',
				);

				$this->class_settings[ 'current_record_id' ] = $_POST[ 'id' ];
				$e = $this->_get_record();

				if( ! empty( $e ) ){
					$fields = json_decode( $e[ 'data' ], true );

					if( ! empty( $fields ) && isset( $fields[ 'fields' ] ) ){
						$tbx = '';

						$plugins =  array();
						foreach( $fields[ 'fields' ] as $fx1 => $fx2 ){
							if( isset( $fx2[ 'plugin' ] ) && $fx2[ 'plugin' ] && isset( $fx2[ 'table' ] ) && $fx2[ 'table' ] ){
								$plugins[ $fx2[ 'plugin' ] ][ $fx2[ 'table' ] ] = $fx2[ 'table' ];
							}
						}

						if( ! empty( $plugins ) ){
							foreach( $plugins as $px => $pz ){
								$pl = 'c'.ucwords( $px );
								if( class_exists( $pl ) ){
									$pl = new $pl();
									$pl->load_class( array( 'class' => $pz ) );
								}
							}
						}

						$tbx = array();

						// print_r( $fields );exit;
						foreach( $fields[ 'fields' ] as $f1 => &$f2 ){
							if( ! empty( $f2 ) ){
								if( isset( $f2[ 'table' ] ) && $f2[ 'table' ] ){
									$table = $f2[ 'table' ];

									if( ! isset( $tbx[ $table ] ) ){
										$tbx[ $table ] = $table();
									}

									foreach( $tbx[ $table ] as $tx ){
										if( $f1 == $tx[ 'id' ] ){
											$f2 = array_merge( $f2, $tx );
										}
									}
								}

							}

						}
					}
						// print_r( $fields );exit;

					$this->class_settings[ 'update_fields' ][ 'data' ] = json_encode( $fields );
					$x = $this->_update_table_field();

					if( $x ){
						return $this->_display_notification( array( "type" => 'success', "message" => "Form Refreshed Successfully" ) );
					}
				}
			}
			return $this->_display_notification( array( "type" => 'error', "message" => "Unable to Refresh Form" ) );
		}

		protected function _view_details2(){
			$return = '';
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			$this->class_settings["action_to_perform"] = 'display_form';
			
			switch( $action_to_perform ){
			case "view_details2":
				$this->class_settings["action_to_perform"] = 'display_form2';
				$this->class_settings["current_record_id"] = isset( $_POST["id"] )?$_POST["id"]:'';
			break;
			}
			
			$return = $this->_display_form();
				
			switch( $action_to_perform ){
			case "view_details2":
				$container = "#modal-replacement-handle";
				$handle = "";
			
				if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
					$handle = "#" . $this->class_settings[ 'html_replacement_selector' ];
					$this->class_settings[ 'data' ][ 'params' ] = '&html_replacement_selector=' . $this->class_settings[ 'html_replacement_selector' ];
					$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				}
				
				if( $handle ){
					$container = $handle;
				}
				
				return array(
					'do_not_reload_table' => 1,
					'html_replacement' => $return,
					'html_replacement_selector' => $container,
					'method_executed' => $this->class_settings['action_to_perform'],
					'status' => 'new-status',
					//'javascript_functions' => $js,
				);

			break;
			}
			
			
			return $return;
		}

		protected function _get_select2(){
			
			$where = '';
			$limit = '';
			$search_term = '';
			
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$search_term = trim( $_POST["term"] );
				
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["name"]."` regexp '".$search_term."' ) ";
				
			}else{
				$limit = "LIMIT 0, " . get_default_select2_limit();
			}
			
			$select = "";
			foreach( $this->table_fields as $key => $val ){
			
				switch( $key ){
				case "name":
					if( $select )$select .= ", `".$val."` as '".$key."'";
					else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
				break;
				}
				
				if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
					$where .= " AND `".$val."` = '".$_GET[ $key ]."' ";
				}
			}
			
			$select .= ", concat( `".$this->table_fields["name"]."`, ' - ', `serial_num` ) as 'text'";
			
			
			$this->class_settings["overide_select"] = $select;
			$this->class_settings["where"] = $where;
			$this->class_settings["limit"] = $limit;
			
			$data1 = $this->_get_all_customer_call_log();
			return array( "items" => $data1, "do_not_reload_table" => 1 );
		}

		protected function _drag_drop_create_table(){
			$return = array();
			$return["javascript_functions"] = array( 'prepare_new_record_form_new' );

			if( (isset( $_POST ) && $_POST ) && isset( $_POST[ 'tableDetails' ] ) && $_POST[ 'tableDetails' ] ){
				$table_name = $_POST[ 'tableDetails' ][ 'table_name' ];
				$table_label = $_POST[ 'tableDetails' ][ 'table_label' ];

				$database_table = new cDatabase_table();
				$_POST[ 'id' ] = '';

				$database_table->class_settings = $this->class_settings;
				$database_table->class_settings[ 'update_fields' ] = $_POST[ 'tableDetails' ];
				$database_table->class_settings[ 'action_to_perform' ] = 'update_table_field';

				$returned_data = $database_table->database_table();

				if( isset($_POST[ 'callback' ]) && $_POST[ 'callback' ] ){
					$return[ 'javascript_functions' ][] = $_POST[ 'callback' ];
				}
			}
			
			$return["status"] = "new-status";

			$return["html_replacement_selector"] = '';
			
			$return["html_replacement"] = '';

			return $return;
		}

		protected function _drag_drop_save(){
			$error_msg = '';
			$splitted_data = array();
			$return = array();

			$data = $_POST;
			$_POST = array();

			$table = isset( $data[ 'table_name' ] )?$data[ 'table_name' ]:'';
			$name = isset($data[ 'form_name' ])?$data[ 'form_name' ]:'';

			if( isset( $data[ 'form_data' ] ) && $data[ 'form_data' ] ){
				$splitted_data = $this->split_drag_drop_data( $data[ 'form_data' ] );
			}

			if( ( isset( $data[ 'new_table_name' ] ) && $data[ 'new_table_name' ] ) && isset( $data[ 'table_label' ] ) && $data[ 'table_label' ] ){
				$table_name = $data[ 'new_table_name' ];
				$table_label = $data[ 'table_label' ];
				$name = $data[ 'table_label' ];
				
				if( isset( $splitted_data[ 'fields' ] ) && is_array( $splitted_data[ 'fields' ] ) && ! empty( $splitted_data[ 'fields' ] ) ){
					
					$database_table = new cDatabase_table();
					$_POST[ 'id' ] = '';

					$database_table->class_settings = $this->class_settings;
					$database_table->class_settings[ 'update_fields' ][ 'table_name' ] = $data[ 'new_table_name' ];
					$database_table->class_settings[ 'update_fields' ][ 'table_label' ] = $data[ 'table_label' ];
					$database_table->class_settings[ 'action_to_perform' ] = 'update_table_field';

					$returned_data = $database_table->database_table();


					if( isset( $returned_data["saved_record_id"] ) && $returned_data["saved_record_id"] ){
						$table = $returned_data["saved_record_id"];
					}else{
						$error_msg = '<h4>Unable to Save New Table</h4>';
						if( isset( $returned_data["html"] ) ){
							$error_msg .= $returned_data["html"];
						}else if( isset( $returned_data["msg"] ) ){
							$error_msg .= $returned_data["msg"];
						}
					}
				}else{
					//$error_msg = '<h4>Invalid Table Fields</h4>';
				}
				
				if( ! $error_msg ){
					
					$db_fields = new cDatabase_fields();
					$db_fields->class_settings = $this->class_settings;
					$db_fields->class_settings["action_to_perform"] = "save_line_items";
					$line_items = array();
					
					foreach( $splitted_data[ 'fields' ] as $key => $value){

						switch( $value[ 'form_field' ] ){
						case 'date':
							$value[ 'form_field' ] = 'date-5';
						break;
						case 'input':
							$value[ 'form_field' ] = 'text';
						break;
						case 'header':
							$value[ 'form_field' ] = '';
							$value[ 'value' ] = '';
							$value[ 'field_label' ] = $value['header'];
							$value[ 'name' ] = '';
							$value[ 'required_field' ] = '';
							$value[ 'class' ] = '';
							$value[ 'default_appearance_in_table_fields' ] = '';
						break;
						}

						switch( $value[ 'default_appearance_in_table_fields' ] ){
						case 'yes':
							$value[ 'default_appearance_in_table_fields' ] = 'show';
						break;
						case 'no':
							$value[ 'default_appearance_in_table_fields' ] = 'hide';
						break;
						}

						switch( $value[ 'display_position' ] ){
						case 'yes':
							$value[ 'display_position' ] = 'display-in-table-row';
						break;
						case 'no':
							$value[ 'display_position' ] = 'do-not-display-in-table';
						break;
						}
						// print_r( $value ); exit;

						$_POST["id"] = '';

						$line_items[] = array(
							"table_name" => $table,
							"serial_number" => '',
							"field_label" => $value[ 'name' ],
							"form_field" => $value[ 'form_field' ],
							"required_field" => $value[ 'required_field' ],
							"attributes" => $value[ 'attributes' ],
							"class" => $value[ 'class' ],
							"display_position" => isset( $value[ 'display_position' ] ) ? $value[ 'display_position' ] : '',
							"default_appearance_in_table_fields" => $value[ 'default_appearance_in_table_fields' ],
							"acceptable_files_format" => '',
							"display_field_label" => isset( $value[ 'field_label' ] ) ? $value[ 'field_label' ] : '',
						);


					}
					
					// print_r( $line_items );exit;
					// $db_fields->class_settings["reference_table"] = $this->table_name;
					// $db_fields->class_settings["reference"] = $program_id;
					
					$db_fields->class_settings["line_items"] = $line_items;
					$return = $db_fields->database_fields();
					
					if( ! $return ){
						$error_msg = '<h4>Unable to Save Table Fields</h4>';
						//@mike: write code to delete the table
					}
					
				}
			}
			
			if( ! $error_msg ){
				if( is_array( $splitted_data ) && ! empty( $splitted_data ) ){

					if( isset( $data[ 'form_script' ] ) && $data[ 'form_script' ] ){
						$splitted_data["script"] = rawurlencode( $data[ 'form_script' ] );
						
						if( isset( $data[ 'form_script_header' ] ) && $data[ 'form_script_header' ] ){
							$splitted_data["script_header"] = rawurlencode( $data[ 'form_script_header' ] );
						}
						
					}

					$_POST = array();
					if( ( isset( $data[ 'id' ] ) && $data[ 'id' ] ) ){
						$_POST[ 'id' ] = $data[ 'id' ];
					}else{
						$_POST[ 'id' ] = "";
					}
					// print_r( $splitted_data );exit();

					$this->class_settings["update_fields"] = array(
						"table" => $table,
						"name" => $name,
						"data" => json_encode( $splitted_data ),
					);
					$return = $this->_update_table_field();
					
					if( isset( $return["saved_record_id"] ) && $return["saved_record_id"] ){
						//display newly saved form
						$this->class_settings["action_to_perform"] = "display_form2";
						$this->class_settings["current_record_id"] = $return["saved_record_id"];
						$html = $this->_display_form();
						
						$this->class_settings[ 'data' ]["name"] = $name;
						$this->class_settings[ 'data' ]["form"] = $html;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/sucessfully-saved-form.php' );

						$h = $this->_get_html_view();
			
						// return 1;
						return array(
							"status" => "new-status",
							"html_replacement" => $h,
							"html_replacement_selector" => '#form-canvas-container',
						);
						
					}else{
						$error_msg = '<h4>Unable to Save Form</h4>';
						if( isset( $return["html"] ) ){
							$error_msg .= $return["html"];
						}else if( isset( $return["msg"] ) ){
							$error_msg .= $return["msg"];
						}
					}
				}else{
					$error_msg = '<h4>Empty Form</h4>Please drag fields to the canvas';
				}
			}
			
			if( ! $error_msg ){
				$error_msg = '<h4>Unknown Error</h4>Unexpected Exception';
			}
			
			return $this->_display_notification( array( "type" => 'error', "message" => $error_msg ) );
		}

		protected function split_drag_drop_data( $form_data ){
			$data = array();
			$fd = json_decode( $form_data, true );
			
			if( is_array( $fd ) && ! empty( $fd ) ){
				foreach( $fd as $key => $value ){
					if( isset( $value[ 'form_field' ] ) && $value[ 'form_field' ] ){
						switch( $value[ 'form_field' ] ){
						case 'row':
							$data[ 'row_col' ][ $value[ 'id' ] ][ 'id' ] = $value[ 'id' ];
							$data[ 'row_col' ][ $value[ 'id' ] ][ 'form_field' ] = $value[ 'form_field' ];
							if( isset( $value[ 'position' ] ) )$data[ 'row_col' ][ $value[ 'id' ] ][ 'position' ] = $value[ 'position' ];

							if( isset( $value[ 'columns' ] ) && $value[ 'columns' ] ){

								if( is_array( $value[ 'columns' ] ) ){
									foreach( $value[ 'columns' ] as $k => $v ){
										$data[ 'row_col' ][ $value[ 'id' ] ][ 'columns' ][ $v[ 'id' ] ][ 'id' ] = $v[ 'id' ];
										$data[ 'row_col' ][ $value[ 'id' ] ][ 'columns' ][ $v[ 'id' ] ][ 'form_field' ] = $v[ 'form_field' ];

										if( isset( $v[ 'fields' ] ) && $v[ 'fields' ] ){
											if( !( isset( $data[ 'row_col' ][ $value[ 'id' ] ][ 'columns' ][ $v[ 'id' ] ][ 'fields' ] ) && is_array( $data[ 'row_col' ][ $value[ 'id' ] ][ 'columns' ][ $v[ 'id' ] ][ 'fields' ] ) ) ){
												$data[ 'row_col' ][ $value[ 'id' ] ][ 'columns' ][ $v[ 'id' ] ][ 'fields' ] = array();
											}

											if( is_array( $v[ 'fields' ] ) ){
												foreach( $v[ 'fields' ] as $field_id => $field_details ){
													array_push( $data[ 'row_col' ][ $value[ 'id' ] ][ 'columns' ][ $v[ 'id' ] ][ 'fields' ], $field_id);

													$data[ 'fields' ][ $field_id ] = $field_details;
												}
											}
										}
									}
								}
							}
						break;
						default:
							$data[ 'fields' ][ $value[ 'id' ] ] = $value;
						break;
						}
					}
				}
			}

			return $data;
		}
		
		protected function _display_form(){
			$html = '';
			$id = '';
			
			$select2_fields_list = array();
			$fields_and_labels = array();
			$js = array();
			$action_to_perform = $this->class_settings["action_to_perform"];
			$callback = isset( $_GET["callback"] )?$_GET["callback"]:'';
			
			$form_settings = isset( $this->class_settings[ 'form_settings' ] )?$this->class_settings[ 'form_settings' ]:array();
			$params = isset( $this->class_settings[ 'params' ] )?$this->class_settings[ 'params' ]:array();
			$saved_data = isset( $this->class_settings[ 'saved_data' ] )?$this->class_settings[ 'saved_data' ]:array();
			
			$preview = isset( $_GET["preview"] )?$_GET["preview"]:0;
			
			if( $callback ){
				$js[] = $callback;
			}
			
			$calculated_actions = array();
			$sql = array();
			$options = array();
			
			switch( $action_to_perform ){
			case "get_form_fields2":
			case "display_form_fields_and_labels":
			case "display_form2":
				$id = isset( $this->class_settings[ 'current_record_id' ] )?$this->class_settings[ 'current_record_id' ]:'';
			break;
			case "get_form_fields_select2":
			case "get_form_field4":
				$id = isset( $_POST[ 'form_id' ] )?$_POST[ 'form_id' ]:'';
			break;
			case "get_form_fields3":
				$ids = isset( $this->class_settings[ 'ids' ] )?$this->class_settings[ 'ids' ]:array();
				
				if( ! empty( $ids ) ){
					$this->class_settings[ 'overide_select' ] = " `id`, `".$this->table_fields["data"]."` as 'data' ";
					$this->class_settings[ 'where' ] = " AND `id` IN ( ".implode( ",", $ids )." ) ";
					$sql = $this->_get_records();
				}
			break;
			default:
				if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
					$id = $_POST[ 'id' ];
				}
			break;
			}
			
			if( $id || isset( $sql[0]["id"] ) ){
				
				if( $id ){
					$this->class_settings[ 'current_record_id' ] = $id;
					$sql[0] = $this->_get_record();
				}else{
					$id = $sql[0]["id"];
				}
				
				if( isset( $sql[0][ "data" ] ) && $sql[0][ "data" ] ){
					
					foreach( $sql as $sval ){
						
						$data = json_decode( $sval[ "data" ], true );

						if( isset( $sval[ "options" ] ) && $sval[ "options" ] ){
							$options = json_decode( $sval[ "options" ], true );
						}
						
						
						if( isset( $data[ 'row_col' ] ) && $data[ 'row_col' ] ){
							foreach( $data[ 'row_col' ] as $key => $value ){
								if( $data[ 'row_col' ][ $key ][ 'columns' ] ){
									foreach( $value[ 'columns' ] as $k => $v ){
										if( isset( $v[ 'fields' ] ) && $v[ 'fields' ] ){
											foreach( $v[ 'fields' ] as $f_key => $f_value ){
												if( isset( $data[ 'fields' ][ $f_value ] ) && $data[ 'fields' ][ $f_value ] ){
													$data[ 'row_col' ][ $key ][ 'columns' ][ $k ][ 'fields' ][ $f_value ] = $data[ 'fields' ][ $f_value ];

													unset( $data[ 'row_col' ][ $key ][ 'columns' ][ $k ][ 'fields' ][ $f_key ] );
													unset( $data[ 'fields' ][ $f_value ] );
												}
											}
										}
									}
								}
							}
						}

						switch( $sval[ 'id' ] ){
						case 'ds24550389049':
							// print_r( $data ); exit;
						break;
						}

						if( isset($data[ 'fields' ]) && $data[ 'fields' ] ){
							foreach( $data[ 'fields' ] as $key => $value ){
								if( !( isset( $data[ 'row_col' ] ) ) ){
									$data[ 'row_col' ] = array();
								}
								$data[ 'row_col' ][ $key ] = $value;
							}
						}
						
						switch( $action_to_perform ){
						case "get_form_fields_select2":
						case "get_form_fields3":
						case "get_form_fields2":
						case "display_form_fields_and_labels":
							if( isset( $data[ 'row_col' ] ) && is_array( $data[ 'row_col' ] ) && ! empty( $data[ 'row_col' ] ) ){
								foreach( $data[ 'row_col' ] as $key => $value ){
									
									if( isset( $value[ 'columns' ] ) && is_array( $value[ 'columns' ] ) && ! empty( $value[ 'columns' ] ) ){
										
									}else{
										$value[ 'columns' ][ 1 ] = array(
											'fields' => array( $key => $value ),
										);
									}
									
									foreach( $value[ 'columns' ] as $k => $v ){
										if( isset( $v[ 'fields' ] ) && $v[ 'fields' ] ){
											foreach( $v[ 'fields' ] as $f_key => $f_val ){
												
												switch( $f_val["form_field"] ){
												case "date":
													$f_val["form_field"] = 'date-5';
												break;
												case "calculated":
													
													if( isset( $f_val["calculations"]["action"] ) && isset( $f_val["calculations"]["todo"] ) ){
														
														if( isset( $f_val['calculations']['key'] ) && $f_val['calculations']['key'] ){
															$mt = $f_val['calculations']['key'];
														}else{
															$mt = $f_val["calculations"]["action"] . $f_val["calculations"]["todo"];
															
															if( isset( $f_val['calculations']['todo2'] ) ){
																$mt .= $f_val['calculations']['todo2'];
															}
															$mt = md5( $mt );
															
															$f_val["calculations"]["key_type"] = $mt;
															$f_val["calculations"]["key"] = $mt;
														}
														
														$calculated_actions[ $mt ] = $f_val["calculations"];
													}
													
													//remove this later
													
													if( isset( $f_val["calculations"]["variables"] ) ){
														$f_val["calculations"]["variables"] = array( array( $f_key ) );
													}
													//print_r( $f_val["calculations"] ); exit;
													
													//remove this later
												break;
												}
												
												$fields_and_labels[ $sval["id"] ][ 'fields' ][ $f_key ] = $f_key;
												$fields_and_labels[ $sval["id"] ][ 'labels' ][ $f_key ] = $f_val;
												
												
												$sdf = array();
												
												if( isset( $f_val["table"] ) && $f_val["table"] ){
													$fields_and_labels[ $sval["id"] ][ 'tables' ][ $f_val["table"] ]['fields'][ $f_key ] = isset( $f_val[ 'field_key_actual' ] ) ? $f_val[ 'field_key_actual' ] : $f_val["field_key"];
													
													$sdf["table"] = $f_val["table"];
													$sdf["table"] = $f_val["field_key"];
												}

												if( isset( $this->class_settings["params"][ 'return_plugins' ] ) && $this->class_settings["params"][ 'return_plugins' ] && isset( $f_val["plugin"] ) ){
													if( ! isset( $f_val["plugin"] ) ){
														// print_r( $f_val ); exit;
													}
													$fields_and_labels[ $sval["id"] ][ 'plugins' ][ $f_val["plugin"] ][ $f_val[ 'table' ] ] = $f_val["table"];
												}
												
												$sdf["id"] = $f_key;
												
												if( isset( $f_val["abbreviation"] ) && $f_val["abbreviation"] ){
													$sdf["text"] = $f_val["abbreviation"];
												}else{
													$sdf["text"] = ( isset( $f_val["display_field_label"] ) && $f_val["display_field_label"] )?$f_val["display_field_label"]:$f_val["field_label"];
												}
												
												
												$select2_fields_list[] = $sdf;
												
											}
										}
									}
									
									
								}
							}
						break;
						}
					
					}
					
				}
				
			}	
			
			switch( $action_to_perform ){
			case "get_form_preview":
				//$preview = 1;
			break;
			}
			
			// print_r( $fields_and_labels );exit;
			switch( $action_to_perform ){
			case "get_form_fields_select2":
			case "get_form_fields3":
			case "get_form_fields2":
				if( isset( $_GET[ 'return_field' ] ) && $_GET[ 'return_field' ] ){
					$field = $data[ 'fields' ][ $_POST[ 'id' ] ];

					if( is_array( $field ) && ! empty( $field ) ){
						$v = isset( $field[ 'table' ] ) ? 'c' . ucwords( $field[ 'table' ] ) : '';
						$table_name = isset( $field[ 'table' ] ) ? $field[ 'table' ] : '';

						$fields_and_labels = $table_name();

						$gbal = array(
							'labels' => array(
								$_POST[ 'id' ] => $fields_and_labels[ $field[ 'field_key' ] ],
							),
							'fields' => array(
								$_POST[ 'id' ] => $_POST[ 'id' ],
							),
						);

						$x = __get_value( '', '', array( 'form_fields' => array( $_POST[ 'id' ] => array( 'value' => '' ) ), 'globals' => $gbal ) );
						
						return array(
							"status" => "new-status",
							"data" => $x[ $_POST[ 'id' ] ],
							"javascript_functions" => isset( $_GET["callback"] ) ? array( $_GET["callback"] ) : array(),
						);
					}
				}
				if( isset( $_GET[ 'condition' ] ) && $_GET[ 'condition' ] == 'filter_number' ){
					$select2_fields_list = array();	

					foreach( $data[ 'fields' ] as $key => $value ){
						if( $value[ 'form_field' ] == 'number' ){
							$select2_fields_list[] = array(
								'table' => $value[ 'table' ],
								'id' => $value[ 'id' ],
								'text' => $value[ 'text' ] ? $value[ 'text' ] : ( isset( $value[ 'abbreviation' ] ) ? $value[ 'abbreviation' ] : ( $value[ 'display_field_label' ] ? $value[ 'display_field_label' ] : $value[ 'field_label' ] ) ),
							);

						}
					}
				}
				// print_r( $select2_fields_list );exit;
			break;
			case "get_form_field4":
				// $x = __get_value( '', '', array( 'form_fields' => $ips, 'globals' => $gbal ) );
			break;
			default:
				
				if( isset( $data[ 'row_col' ] ) ){
					$tb = $this->table_name;
					if( isset( $params["table"] ) && $params["table"] ){
						$tb = $params["table"];
					}

					if( isset( $this->class_settings[ 'default_form_values' ] ) && ! empty( $this->class_settings[ 'default_form_values' ] ) ){
						$form_settings[ 'form_values' ] = $this->class_settings[ 'default_form_values' ];
					}

					if( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ){
						$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
					}
					
					$this->class_settings[ 'data' ][ 'form_options' ] = $options;
					$this->class_settings[ 'data' ][ 'form_id' ] = $id;
					$this->class_settings[ 'data' ][ 'form_settings' ] = $form_settings;
					$this->class_settings[ 'data' ][ 'table_id' ] = $id;
					$this->class_settings[ 'data' ][ 'table' ] = $tb;
					$this->class_settings[ 'data' ][ 'saved_data' ] = $saved_data;
					$this->class_settings[ 'data' ][ 'params' ] = $params;
					$this->class_settings[ 'data' ][ 'form_data' ] = $data[ 'row_col' ];
					$this->class_settings[ 'data' ][ 'form_script_header' ] = isset( $data[ 'script_header' ] )?$data[ 'script_header' ]:'';
					$this->class_settings[ 'data' ][ 'form_script' ] = isset( $data[ 'script' ] )?$data[ 'script' ]:'';
					$this->class_settings[ 'data' ][ 'preview' ] = $preview;

					$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'. $this->table_name .'/display-form.php' );

					// print_r( $this->class_settings[ 'data' ] );exit;
					$html = $this->_get_html_view();

					if( isset( $this->class_settings[ 'return_html' ] ) && $this->class_settings[ 'return_html' ] ){
						return $html;
					}
					//echo $html; exit;
				}
			break;
			}
			// print_r( '$fields_and_labels' );exit;
			switch( $action_to_perform ){
			case "get_form_preview":
				$website = new cWebsite();
				$website->class_settings = $this->class_settings;
				$website->class_settings["do_not_show_header"] = 1;
				$website->class_settings["action_to_perform"] = 'setup_website';
				$r2 = $website->website();
				
				if( isset( $r2["javascript"] ) && isset( $r2["stylesheet"] ) ){
					$html = $r2["stylesheet"] . $html;
				}
				//print_r($r2); exit;
				
				return array(
					"status" => "new-status",
					"html_replacement" => $html,
					"javascript_functions" => $js,
				);
			break;
			case "display_form2":
				return $html;
			break;
			case "get_form_fields2":
				if( isset( $this->class_settings[ 'add_form_record' ] ) && $this->class_settings[ 'add_form_record' ] ){
					$fields_and_labels[ $id ][ 'form_data' ] = $sql[0];
				}
				if( isset( $fields_and_labels[ $id ] ) ){
					return $fields_and_labels[ $id ];
				}
				
				return array();
			break;
			case "display_form_fields_and_labels":
				$return = array();
				if( isset( $fields_and_labels[ $id ] ) ){
					$return = $fields_and_labels[ $id ];
				}
				$return["html_form"] = $html;
				
				$return["calculations"] = $calculated_actions;
				
				return $return;
			break;
			case "get_form_fields3":
				return $fields_and_labels;
			break;
			case "get_form_fields_select2":
				
				return array( "items" => $select2_fields_list, "do_not_reload_table" => 1 );
			break;
			}
			
			if( $html ){
				$js = array();
				$this->class_settings["modal_dialog_style"] = " width:50%; ";
				$this->class_settings["modal_title"] = 'Backend Menu Buttons';
				
				return $this->_launch_popup( $html, '#dash-board-main-content-area', $js );
			}
		}

		protected function _display_canvas(){
			$error = '';
			$data = array();
			$js = array( 'prepare_new_record_form_new', '$.fn.dragDropCanvas.init' );

			$handle2 = "inventories-asset-center-sub";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
			}

			$action_to_perform = $this->class_settings[ 'action_to_perform' ];
			switch( $action_to_perform ){
			case 'display_canvas':
			break;
			case 'edit_form':
			case 'edit_popup_form':
				$js[] = '$.fn.dragDropCanvas.displayFormData';
				if( isset( $_POST[ 'id' ] ) && $_POST[ 'id' ] ){
				$id = $_POST[ 'id' ];

				$this->class_settings[ 'current_record_id' ] = $id;
				$sql = $this->_get_record();
				// print_r( $sql );exit;

				$return = array();

				if( isset( $sql["id"] ) && $sql["id"] ){
					$data = $sql;
					$data["form_name"] = $data["name"];

					$data['data'] = json_decode( $data['data'], true );
					
					if( isset( $data[ 'data' ][ 'row_col' ] ) && $data[ 'data' ][ 'row_col' ] ){
						foreach( $data[ 'data' ][ 'row_col' ] as $key => $value ){
							if( $data[ 'data' ][ 'row_col' ][ $key ][ 'columns' ] ){
								foreach( $value[ 'columns' ] as $k => $v ){
									if( isset( $v[ 'fields' ] ) && $v[ 'fields' ] ){
										foreach( $v[ 'fields' ] as $f_key => $f_value ){
											if( isset( $data[ 'data' ][ 'fields' ][ $f_value ] ) && $data[ 'data' ][ 'fields' ][ $f_value ] ){
												$data[ 'data' ][ 'row_col' ][ $key ][ 'columns' ][ $k ][ 'fields' ][ $f_value ] = $data[ 'data' ][ 'fields' ][ $f_value ];

												unset( $data[ 'data' ][ 'row_col' ][ $key ][ 'columns' ][ $k ][ 'fields' ][ $f_key ] );
												unset( $data[ 'data' ][ 'fields' ][ $f_value ] );
											}
										}
									}
								}
							}
						}
					}

					if( isset($data[ 'data' ][ 'fields' ]) && $data[ 'data' ][ 'fields' ] ){
						foreach( $data[ 'data' ][ 'fields' ] as $key => $value ){
							if( !( isset( $data[ 'data' ][ 'row_col' ] ) ) ){
								$data[ 'data' ][ 'row_col' ] = array();
							}
							$data[ 'data' ][ 'row_col' ][ $key ] = $value;
						}
					}
					
					//print_r( $data );exit;
					$data[ 'record_id' ] = $id;

				}
				}else{
					$error = 'Invalid ID';
				}
			break;
			}

			if( $error ){
				return $this->_display_notification( array( "type" => 'error', "message" => $error ) );
			}
				
			$this->class_settings[ 'data' ] = $data;

			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/'. $this->table_name .'/get-html-canvas.php' );

			$returning_html_data = $this->_get_html_view();

			$return["javascript_functions"] = $js;
		
			$return["status"] = "new-status";

			$return["html_replacement_selector"] = '#' . $handle2;
			
			$return["html_replacement"] = $returning_html_data;

			return $return;
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
			
			$this->class_settings[ 'html' ] =  array( 'html-files/templates-1/'.$this->table_name.'/custom-buttons.php' );
			$this->class_settings[ "custom_edit_button" ] = $this->_get_html_view();
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case "display_all_records_frontend":
				$this->class_settings[ "frontend" ] = 1;
				$this->datatable_settings["show_add_new"] = 0;
				//$this->datatable_settings["show_edit_button"] = 0;
				$this->datatable_settings["show_advance_search"] = 0;
				$this->datatable_settings["show_advance_search"] = 0;
			break;
			default:
				
				$this->class_settings[ "show_form" ] = 1;
				$this->class_settings[ "form_heading_title" ] = 'Search ' . $this->label; 
				$this->class_settings[ 'form_submit_button' ] = 'Search &rarr;';
				$this->class_settings[ 'form_action' ] = '?action='.$this->table_name.'&todo=search_form';
				$this->class_settings[ "add_empty_select_option" ] = 1;
				$this->class_settings[ "edit_form" ] = 1;
				//$this->class_settings[ "display_form" ] = 1;
				
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
			break;
			}
			
			
			$this->datatable_settings["split_screen"] = array();
			
			$this->datatable_settings["datatable_split_screen"] = array(
				'action' => '?action=' . $this->table_name . '&todo=view_details2',
				'col' => 4,
				'content' => get_quick_view_default_message_settings(),
			);
			
			return $this->_display_all_records_full_view();
		}

		protected function _display_app_view2(){
			
			$this->class_settings[ 'data' ][ "show_last_records_option" ] = 1;
			
			$return = $this->_display_app_view();
			
			$return["javascript_functions"] =  array( 'set_function_click_event', 'prepare_new_record_form_new', 'nwCustomers.loadForm' );
			
			return $return;
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
						$err = new cError(010014);
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
			switch ( $this->class_settings['action_to_perform'] ){
			case 'edit_popup_form':
				$_POST["mod"] = 'edit-'.md5($this->table_name);
				$this->class_settings["modal_title"] = 'Edit ' . $this->label;
				$this->class_settings["override_defaults"] = 1;
				
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["task_type"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["amount_paid"] ] = 1;
				//$this->class_settings[ "hidden_records" ][ $this->table_fields["payment_method"] ] = 1;
			break;
			}
			
			$this->class_settings['form_submit_button'] = 'Save '.$this->label.' &rarr;';
			return $this->_new_popup_form();
		}
		
	}
	
	function database_forms(){
		if( file_exists( dirname( __FILE__ ).'/dependencies/database_forms.json' ) ){
			$return = json_decode( file_get_contents( dirname( __FILE__ ).'/dependencies/database_forms.json' ), true );
			if( isset( $return[ "labels" ] ) ){
				return $return[ "labels" ];
			}
		}
	}
?>