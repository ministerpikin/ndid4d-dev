<?php
	/**
	 * empowerment Class
	 *
	 * @used in  				empowerment Function
	 * @created  				08:22 | 06-06-2016
	 * @database table name   	empowerment
	 */
	
	class cDatabase_table extends cCustomer_call_log{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $customer_source = '';
		public $table_name = 'database_table';
		
		public $default_reference = '';
		
		public $label = 'Database Table';
		
		private $associated_cache_keys = array(
			'database_table',
			'operators-tree-view' => 'operators-tree-view',
		);
		
		public $refresh_cache_after_save_line_items = 1;
		
		public $basic_data = array(
			// 'access_to_crud' => 1,
			'exclude_from_crud' => array( 'create_new_record' => 1, 'edit' => 1, 'delete' => 1, 'generate_class' => 1 ),
			'more_actions' => array(
				'generate_class' => array(
					'todo' => 'generate_class',
					'title' => 'Generate Class',
					'text' => 'Generate Class',
				),
				'actions' => array(
					'title' => 'More Actions',
					'data' => array(
						'd1' => array(
							'bmb' => array(
								'multiple' => 1,
								'todo' => 'generate_backend_menu_button',
								'title' => 'Generate Backend Menu Button',
								'text' => 'Generate Backend Menu Button',
							),
							'uts' => array(
								'todo' => 'upgrade_table_structure',
								'title' => 'Upgrade Table Structure',
								'text' => 'Upgrade Table Structure',
							),
							'raf' => array(
								'multiple' => 1,
								'todo' => 'refresh_all_fields',
								'title' => 'Refresh All Table Fields',
								'text' => 'Refresh All Table Fields',
							),
							'rdf' => array(
								// 'multiple' => 1,
								'selected_record' => '-',
								'todo' => 'regenerate_classes',
								'title' => 'Re-create Database Fields',
								'text' => 'Re-create Database Fields',
							),
						),
					),
				),
			),
		);
		
		public $table_fields = array(
			'table_name' => 'database_table001',
			'table_label' => 'database_table002',
			'table_type' => 'database_table003',
			'table_data' => 'database_table004',
			
			'table_package' => 'database_table005',
			'table_classification' => 'database_table006',
		);
		
		
		protected $datatable_settings = array(
			'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				'show_add_new' => 1,			//Determines whether or not to show add new record button
				'show_advance_search' => 1,		//Determines whether or not to show advance search button
				'show_column_selector' => 1,	//Determines whether or not to show column selector button
				'show_edit_button' => 1,		//Determines whether or not to show edit button
				'show_delete_button' => 1,		//Determines whether or not to show delete button
				'show_refresh_cache' => 1,
				
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
			
		}
	
		function database_table(){
			//LOAD LANGUAGE FILE
			if( ! defined( strtoupper( $this->table_name ) ) ){
				if( ! ( load_language_file( array( 
					'id' => $this->table_name , 
					'pointer' => $this->class_settings['calling_page'], 
					'language' => $this->class_settings['language'] 
				) ) && defined( strtoupper( $this->table_name ) ) ) ){
					//REPORT INVALID TABLE ERROR
					$err = new cError('000017');
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
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
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
			case 'display_all_records_frontend':
			case 'display_all_records_full_view':
				$returned_value = $this->_display_all_records_full_view2();
			break;
			case 'display_app_view':
				$returned_value = $this->_display_app_view();
			break;
			case 'save_new_popup':
			case 'save_app_changes':
				$returned_value = $this->_save_app_changes2();
			break;
			case 'delete_app_record':
				$returned_value = $this->_delete_app_record();
			break;
			case 'new_popup_form_in_popup':
			case 'new_popup_form':
			case 'edit_task':
				$returned_value = $this->_new_popup_form2();
			break;
			case 'search_project_call_log2':
			case 'search_project_call_log':
			case 'search_customer_call_log':
				$returned_value = $this->_search_customer_call_log2();
			break;
			case 'view_details':
				$returned_value = $this->_view_details();
			break;
			case "get_available_database_tables":
				$returned_value = $this->_get_available_database_tables();
			break;
			case "upgrade_table_structure":
			case "generate_class":
				$returned_value = $this->_generate_class();
			break;
			case "open_class":
			case "open_file":
				$returned_value = $this->_open_file();
			break;
			case "save_line_items":
				$returned_value = $this->_save_line_items();
			break;
			case "get_data":
			case "get_list":
				$returned_value = $this->_get_all_customer_call_log();
			break;
			case 'update_table_field':
				$returned_value = $this->_update_table_field();
			break;
			case 'upgrade_old_class':
			case 'regenerate_classes':
				$returned_value = $this->_upgrade_old_class();
			break;
			case "refresh_all_fields":
			case 'generate_backend_menu_button':
				$returned_value = $this->_generate_backend_menu_button();
			break;
			case 'get_record':
				$returned_value = $this->_get_record();
			break;
			case 'get_form_fields_from_table_name':
			case 'get_form_fields':
				$returned_value = $this->_get_form_fields();
			break;
			case 'refresh_cache':
				$returned_value = $this->_refresh_cache();
			break;
			case 'create_centralized_database':
				$returned_value = $this->_create_centralized_database();
			break;
			}
			
			if( isset( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ) && $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] ){
				$callback = get_callback_functions( $this->class_settings["callback"][ $this->table_name ][ $this->class_settings['action_to_perform'] ] );
				if( is_array( $callback ) && ! empty( $callback ) )$returned_value = array_merge( $callback, $returned_value );
			}
			
			return $returned_value;
		}
		
		protected function _get_form_fields(){
			$return = array();
			$return["javascript_functions"] = array();
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'get_form_fields_from_table_name3':
				if( $table )$e[ 'table_name' ] = $table;
				if( $plugin ){
					$e[ 'table_classification' ] = 'plugin_table';
					$e[ 'table_package' ] = str_replace( "nwp_", "", $plugin );
				}
			break;
			case 'get_form_fields_from_table_name':
			case 'get_form_fields_from_table_name2':
				$e["table_name"] = isset( $_POST["table_name"] )?$_POST["table_name"]:'';
				
				if( isset( $_POST["plugin"] ) && $_POST["plugin"] ){
					$p_cl = "c".ucwords( $_POST["plugin"] );
					
					if( class_exists( $p_cl ) ){
						$p_cls = new $p_cl();
						$pc = $p_cls->load_class( array( "class" => array( $e["table_name"] ) ) );
					}else{
						$error_msg = 'Unregistered Plugin';
					}
				}
			break;
			default:
				if( isset( $_POST[ 'tableID' ] ) && $_POST[ 'tableID' ] ){
					$id = $_POST[ 'tableID' ];

					$this->class_settings["current_record_id"] = $id;
					$e = $this->_get_record();
				}
			break;
			}
			
			if( isset( $e["table_name"] ) && $e["table_name"] ){
				$table_name = strtolower( clean3( trim( $e["table_name"] ) ) );
				
				if( isset( $e[ 'table_classification' ] ) ){
				switch( $e[ 'table_classification' ] ){
				case 'plugin_table':
					$tb = $e[ 'table_package' ];
					$cl = 'c' . ucwords( 'nwp_'.$tb );
					$pc = new $cl();
					$x = $pc->load_class( array( 'class' => array( $table_name ) ) );
				break;
				}
				}
				
				//$table_package = strtolower( $e["table_package"] );
				//$table_label = $e[ 'table_label' ];
				if( function_exists( $table_name ) ){
					//nw5
					$e["db_table_filter"] = isset( $_POST["db_table_filter"] )?$_POST["db_table_filter"]:'';
					if( $e["db_table_filter"] ){
						$labels = $table_name( $e["db_table_filter"] );
					}else{
						$labels = $table_name();
					}
				
					if( isset( $this->class_settings[ 'data' ][ 'filter' ] ) && ! empty( $this->class_settings[ 'data' ][ 'filter' ] ) ){
						$filter = $this->class_settings[ 'data' ][ 'filter' ];
						//print_r( $filter );exit;
						foreach( $labels as $key => $value ){
							if( ! ( isset( $filter[ $value[ 'form_field' ] ] ) && $filter[ $value[ 'form_field' ] ] ) ){
								unset( $labels[ $key ] );
							}
						}
					}
					
					foreach( $labels as $key => & $value ){
						if( ! isset( $value["display_field_label"] ) ){
							$value["display_field_label"] = $value["field_label"];
						}
						if( ! isset( $value["text"] ) ){
							$value["text"] = $value["field_label"];
							$value["table"] = $table_name;
							$value["field_key"] = $key;
						}
					}
					
					switch( $action_to_perform ){
					case 'get_form_fields_from_table_name2':
					case 'get_form_fields_from_table_name':
						$return[ 'data' ]["labels"] = $labels;
						$return[ 'data' ]["table"] = $table_name;
						
						switch( $action_to_perform ){
						case 'get_form_fields_from_table_name2':
							$cl = "c".ucwords( $table_name );
							
							if( class_exists( $cl ) ){
								$cl = new $cl();
								$return[ 'data' ]["fields"] = $cl->table_fields;
								$return[ 'data' ]["table_label"] = $cl->label;
							}
						break;
						}

					break;
					default:
						$return[ 'data' ] = $labels;
					break;
					}
					

					if( isset($_POST[ 'callback' ]) && $_POST[ 'callback' ] ){
						$return[ 'javascript_functions' ][] = $_POST[ 'callback' ];
					}
				}
			}
			
			// print_r( $return );exit;
			$return["status"] = "new-status";

			return $return;
		}
		
		protected function _get_form_fields_old(){
			$return = array();
			$return["javascript_functions"] = array( 'prepare_new_record_form_new' );
			$return["javascript_functions"] = array();
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			switch( $action_to_perform ){
			case 'get_form_fields_from_table_name':
				$e["table_name"] = isset( $_POST["table_name"] )?$_POST["table_name"]:'';
			break;
			default:
				if( isset( $_POST[ 'tableID' ] ) && $_POST[ 'tableID' ] ){
					$id = $_POST[ 'tableID' ];

					$this->class_settings["current_record_id"] = $id;
					$e = $this->_get_record();
				}
			break;
			}
			
			
			if( isset( $e["table_name"] ) && $e["table_name"] ){
				$table_name = strtolower( clean3( trim( $e["table_name"] ) ) );
				//$table_package = strtolower( $e["table_package"] );
				//$table_label = $e[ 'table_label' ];
				if( function_exists( $table_name ) ){
					$labels = $table_name();
					//print_r( $labels );exit;

					if( isset( $this->class_settings[ 'data' ][ 'filter' ] ) && ! empty( $this->class_settings[ 'data' ][ 'filter' ] ) ){
						$filter = $this->class_settings[ 'data' ][ 'filter' ];
						//print_r( $filter );exit;
						foreach( $labels as $key => $value ){
							if( ! ( isset( $filter[ $value[ 'form_field' ] ] ) && $filter[ $value[ 'form_field' ] ] ) ){
								unset( $labels[ $key ] );
							}
						}
					}
					
					foreach( $labels as $key => & $value ){
						if( ! isset( $value["display_field_label"] ) ){
							$value["display_field_label"] = $value["field_label"];
						}
						if( ! isset( $value["text"] ) ){
							$value["text"] = $value["field_label"];
							$value["table"] = $table_name;
							$value["field_key"] = $key;
						}
					}
					
					switch( $action_to_perform ){
					case 'get_form_fields_from_table_name':
						$return[ 'data' ]["labels"] = $labels;
						$return[ 'data' ]["table"] = $table_name;
					break;
					default:
						$return[ 'data' ] = $labels;
					break;
					}

					if( isset($_POST[ 'callback' ]) && $_POST[ 'callback' ] ){
						$return[ 'javascript_functions' ][] = $_POST[ 'callback' ];
					}
				}
			}
			
			//print_r( $return );exit;
			$return["status"] = "new-status";

			return $return;
		}
		
		protected function __before_save_changes(){
			
			if( isset( $_POST[ $this->table_fields[ "table_name" ] ] ) && $_POST[ $this->table_fields[ "table_name" ] ] ){
				$_POST[ $this->table_fields[ "table_name" ] ] = strtolower( clean3( trim( $_POST[ $this->table_fields[ "table_name" ] ] ) ) );
			}
			
		}
		
		protected function _generate_backend_menu_button(){
			$error_msg = '';
			
			$action_to_perform = $this->class_settings["action_to_perform"];
			
			if( ! ( ( isset( $_POST["id"] ) && $_POST["id"] ) || ( isset( $_POST["ids"] ) && $_POST["ids"] ) ) ){
				$error_msg = '<h4>No Record Selected</h4>Please select records by clicking on them';
			}
			
			$ids = array();
			
			if( ! $error_msg ){
				if( isset( $_POST["id"] ) && $_POST["id"] ){
					$ids = array( $_POST["id"] );
				}
				
				if( isset( $_POST["ids"] ) && $_POST["ids"] ){
					$ids = explode( ":::", $_POST["ids"] );
				}
			}
			
			if( empty( $ids ) ){
				$error_msg = '<h4>Invalid Selection</h4>Please select records';
			}
			
			if( ! $error_msg ){
				
				foreach( $ids as & $idv ){
					$idv = "'". $idv ."'";
				}
				
				$this->class_settings["where"] = " AND `id` IN (". implode(",", $ids) .") ";
				$d = $this->_get_all_customer_call_log();
				
				$html = '';
				
				if( isset( $d[0]["id"] ) && $d[0]["id"] ){
					
					switch( $action_to_perform ){
					case "refresh_all_fields":
						
						foreach( $d as $dv ){
							$_POST["id"] = $dv["id"];
							$this->class_settings["action_to_perform"] = 'upgrade_table_structure';
							
							$r2 = $this->_generate_class();
							$html .= isset( $r2["html"] )?$r2["html"]:'';
						}
							
					break;
					case "generate_backend_menu_button":
						
						$this->class_settings[ 'data' ][ 'items' ] = $d;
						$this->class_settings[ 'html' ] = array( 'html-files/templates-1/development/'.$this->table_name.'/backend-menu-button.php' );
						$html = $this->_get_html_view();
					
					break;
					}
				
					if( $html ){
						
						$js = array();
						$this->class_settings["modal_dialog_style"] = " width:50%; ";
						$this->class_settings["modal_title"] = 'Backend Menu Buttons';
						
						return $this->_launch_popup( $html, '#dash-board-main-content-area', $js );
						
					}else{
						$error_msg = '<h4>Unable to Retrieve Records from Database</h4>';
					}
					
				}else{
					$error_msg = '<h4>Unable to Retrieve Records from Database</h4>';
				}
				
			}
			
			if( ! $error_msg ){
				$error_msg = '<h4>Unknown Error</h4>';
			}
			
			$err = new cError('010014');
			$err->action_to_perform = 'notify';
			$err->html_format = 2;
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $error_msg;
			return $err->error();
		}
		
		protected function _create_centralized_database( $opt = array() ){

			$class_path = $this->class_settings[ 'calling_page' ] . 'classes/';

			$d = $this->_check_class_folder( array( 'class_path' => $class_path ) );
			$changed = array();

			$pclass_path = $class_path . 'package/';
		    foreach( scandir( $pclass_path ) as $file ){
		    	if( is_dir( $pclass_path . $file ) ){
		    		switch ( pathinfo( $file )[ 'basename' ] ) {
	    			case '.':
	    			case '..':
    				break;
    				case 'hospital':
						$d[ 'classes' ] = array();
						$d1 = $this->_check_class_folder( array( 'class_path' => $pclass_path . $file . '/', 'has_package' => $file , 'existing_classes' => $d[ 'classes' ] ) );
						if( isset( $d1[ 'classes' ] ) && ! empty( $d1[ 'classes' ] ) ){
							$d[ 'classes' ] = array_merge( $d[ 'classes' ], $d1[ 'classes' ] );
						}
						if( isset( $d1[ 'changed' ] ) && ! empty( $d1[ 'changed' ] ) ){
							$changed = $d1[ 'changed' ];
						}
						// 	exit;
    				break;
		    		}
			    }
		    }
			$aa = array( 'category', 'delivery_register-x', 'icd', 'treatment', 'post_operative_note-a', 'post_operative_order-1', 'post_operative_note-1', 'customers', 'all_reports', 'customers-old', 'items', 'inventory', 'base_class', 'parent_data', 'customer_call_log', 'error' );
			
			foreach( $aa as $av ){
				$key = $av;
				if( isset( $d[ 'classes' ][ $key ] ) ){
					$x = $d[ 'classes' ][ $key ];
					unset( $d[ 'classes' ][ $key ] );
					//array_unshift( $d[ 'classes' ], $x );
				}
			}
			//print_r( $d );exit;
			
		    if( ! empty( $d ) ){
		    	$dd = $this->_upgrade_old_class( array( 'classes' => $d[ 'classes' ], 'skip_generate' => 1 ) );
				// print_r( $dd );exit;
		    }


		}

		protected function _check_class_folder( $opt = array() ){
			echo '<pre>';
			$return = array();
			$existing_classes = isset( $opt[ 'existing_classes' ] ) ? $opt[ 'existing_classes' ] : array();

			if( isset( $opt[ 'class_path' ] ) && $opt[ 'class_path' ] ){
				$classes_path = $opt[ 'class_path' ];
				$fields_path = $opt[ 'class_path' ] . 'dependencies/';

		    	foreach( scandir( $classes_path ) as $file ){
	    			if( strpos( $file, ' - Copy.php' ) > 1 )continue;
	    			
			    	switch( substr( $file, 0, 1 ) ){
			    	case 'c':
			        	$info = pathinfo( $classes_path . '/'. $file );

			    		switch( $info[ 'extension' ] ){
			    		case 'php':
			    		break;
			    		default:
			    			continue 3;
			    		break;
			    		}
		    	
		    			$package = '';
		    			if( isset( $opt[ 'has_package' ] ) && $opt[ 'has_package' ] ){
		    				$package = $opt[ 'has_package' ];	
		    			}

						$db_functions_path = $this->class_settings[ 'calling_page' ] . 'settings/'. ( $package ? 'package/'. $package .'/' : '' ) . 'Database_table_field_names_interpretation_functions.php';

		    			$lbl_fn = strtolower( substr( $info[ 'filename' ], 1) );
		    			$class_name = $lbl_fn;
		    			$c_file_name = $info[ 'filename' ];

			    		if( isset( $existing_classes[ $lbl_fn ] ) && ! empty( $existing_classes[ $lbl_fn ] ) ){

			    			$class_name = $lbl_fn . '_' . str_replace( "-", "_", $package );
			    			$cname = 'c'.ucwords( $class_name );
			    			
				    		switch( $package ){
				    		case 'visitors':
				    		break;
				    		}

			    			// Edit files to suite the new name
							$cfile = $classes_path . $file;
							$file_contents = file_get_contents( $cfile );

							$check_include = strpos( $file_contents,	'include' );
							if( gettype( $check_include ) == 'integer' && $check_include <= 10 ){
								$a = strpos( $file_contents, '"' );
								$b = strpos( substr( $file_contents, strpos( $file_contents, '"' )+1 ), '"' ) + strpos( $file_contents, '"' );
								$xpath = substr( $file_contents, $a+1, $b-$a );

								$count = substr_count( $file_contents, 'dirname' );
								$xpackage = trim( substr( $xpath, 1, strpos( $xpath, "/c" )-1 ) );

								if( $xpackage ){
									$xfields_path = $opt[ 'class_path' ] . '../' . $xpackage;
								}else{
									$xfields_path = $opt[ 'class_path' ] . '../..' . $xpackage;
								}

								$dp = $lbl_fn.'.json';
								// print_r( pathinfo( '../classes/package/custom/../../dependencies/' )  );exit;
								if( file_exists( $xfields_path.'/dependencies/'.$dp ) )copy( $xfields_path.'/dependencies/'.$dp, $fields_path. $dp );

								switch( substr_count( $file_contents, 'dirname' ) ){
								case 2:
									$xpath = '..' . $xpath;
								break;
								case 3:
								case 4:
									$xpath = '../..' . $xpath;
								break;
								}

								if( file_exists( $classes_path.$xpath ) ){

									$file_contents = file_get_contents( $classes_path.$xpath );

									if( $xpackage ){
										$file_contents = str_replace( 'class '. $info[ 'filename' ]. '_' . str_replace( "-", "_", $xpackage ).'{', 'class '. $info[ 'filename' ].'{', $file_contents ); 
										$file_contents = str_replace( 'class '. $info[ 'filename' ].'_' . str_replace( "-", "_", $xpackage ).' extends', 'class '. $info[ 'filename' ].' extends', $file_contents ); 
										$file_contents = str_replace( 'function '. $lbl_fn.'_' . str_replace( "-", "_", $xpackage ) .'(){', 'function '. $lbl_fn .'(){', $file_contents );
									}
								}
							}

							$file_contents = str_replace( 'class '. $info[ 'filename' ].'{', 'class '. $cname.'{', $file_contents ); 
							$file_contents = str_replace( 'class '. $info[ 'filename' ].' extends', 'class '. $cname.' extends', $file_contents ); 
							$file_contents = str_replace( 'function '. $lbl_fn .'(){', 'function '. $class_name .'(){', $file_contents );
							file_put_contents( $cfile, $file_contents );
		        			// rename( $classes_path . $file, $classes_path . $cname.'.php' );
 
							$return[ 'changed' ][ $cfile ][ 'path' ] = $cfile;
							$return[ 'changed' ][ $cfile ][ 'old_name' ] = $lbl_fn;
							$return[ 'changed' ][ $cfile ][ 'new_name' ] = $class_name;

							if( file_exists( $db_functions_path ) ){
								$cfile = $db_functions_path;
								$file_contents = file_get_contents( $cfile );
								$file_contents = str_replace( 'function '. $lbl_fn .'(){', 'function '. $class_name .'(){', $file_contents );
								file_put_contents( $cfile, $file_contents );
	 
								$return[ 'changed' ][ $db_functions_path ][ 'path' ] = $db_functions_path;
								$return[ 'changed' ][ $db_functions_path ][ 'old_name' ] = $lbl_fn;
								$return[ 'changed' ][ $db_functions_path ][ 'new_name' ] = $class_name;
							}
			    		}

		    			$fp = $fields_path . $lbl_fn . '.json';
		    			$set_class = 0;

		    			if( ! file_exists( $fp ) ){
    						$return[ 'classes' ][ $class_name ][ 'old_class' ] = 1;
			    			$set_class = 1;
	    				}else{
			    			$set_class = 1;
	    				}

	    				if( $set_class ){
    						$return[ 'classes' ][ $class_name ][ 'package' ] = $package;
    						$return[ 'classes' ][ $class_name ][ 'tb_name' ] = $lbl_fn;
    						$return[ 'classes' ][ $class_name ][ 'class' ] = $class_name;
    						$return[ 'classes' ][ $class_name ][ 'c_file_name' ] = $c_file_name;
	    				}else{
			    			$return[ 'not_defined' ][] = $info[ 'filename' ];
			    		}
			    	break;
			    	}
			    }
		    }
		    return $return;
		}
		
		private $clx = array();

		protected function _upgrade_old_class(){
			$return = array();

			$handle = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
				$handle = $this->class_settings[ 'html_replacement_selector' ];
			}
			$classes = array();

			if( isset( $opt[ 'classes' ] ) && ! empty( $opt[ 'classes' ] ) ){
				$classes = $opt[ 'classes' ];
			}else{
				if( isset( $_POST[ 'classes' ] ) && ! empty( $_POST[ 'classes' ] ) ){
					$classes = $_POST[ 'classes' ];
				}
			}

			$filename = '';
			$data = array();
			
			$modal = 0;
			$params = '';
			$title = '';
			$html = '';

			$etype = 'error';
			$error = '';
			
			$line_table = array();
			$line_field = array();
			
			$line_table_id = array();
			
			$package = 'mis';
			// if( defined( 'HYELLA_PACKAGE' ) ){
			// 	$package = HYELLA_PACKAGE;
			// }

			$this->class_settings[ 'modal_dialog_style' ] = 'width:50%;';

			$js = array( 'set_function_click_event', 'prepare_new_record_form_new' );

			$action_to_perform = $this->class_settings['action_to_perform'];

			switch ( $action_to_perform ){
			case 'regenerate_classes':
				$title = 'Re-Generate Classes';
				$filename = str_replace( "_", "-", $action_to_perform);
				$modal = 1;

				$ad = new cAudit();
				$ad->class_settings = $this->class_settings;

				$data[ 'classes' ] = $ad->_get_project_classes();
			break;
			default:
				if( ! empty( $classes ) ){
					$msg = '';
					$sn = 0;
					$require = 0;

					// print_r( $classes );exit;
					foreach( $classes as $tb1 ){
						$tbd = explode( ':::' , $tb1 );
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
						
						$set_field_id = 0;
						$tb_name = $tb;
						
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
										$error = "<h4><strong>Invalid Plugin Table</strong></h4><p>The Plugin Table '". $clp ."::". $tb ."' is invalid or missing in the project.</p>";
									}
								}else{
									$error = "<h4><strong>Invalid Plugin</strong></h4><p>The Plugin '". $clp ."' is invalid or missing in the project.</p>";
								}
							}else{
								$error = "<h4><strong>Undefined Plugin</strong></h4><p>The Plugin is missing in the project.</p>";
							}
							
						break;
						default:
							$cls = 'c' . ucwords( $tb );
							if( class_exists( $cls ) ){
								$cl = new $cls();
							}else if( is_array( $tb ) && isset( $tb[ 'class' ] ) ){
								$tb_name = isset( $tb[ 'tb_name' ] ) ? $tb[ 'tb_name' ] : $tb;
								if( isset( $tb[ 'package' ] ) && $tb[ 'package' ] ){
									$package = $tb[ 'package' ];

									if( ! $require ){
										// $db_fn_path = $this->class_settings[ 'calling_page' ].'settings/Database_table_field_names_interpretation_functions.php';
										// if( file_exists( $db_fn_path ) )include_once $db_fn_path;

										$all_packages = array_combine( array_column( $classes, 'package' ), array_column( $classes, 'package' ) );
										foreach( $all_packages as $p ){
											$db_fn_path = $this->class_settings[ 'calling_page' ].'settings/package/'.$package .'/Database_table_field_names_interpretation_functions.php';
											if( file_exists( $db_fn_path ) )include_once $db_fn_path;
										}

										$require = 1;
									}
								}
								
								$c_file_name = isset( $tb[ 'c_file_name' ] ) ? $tb[ 'c_file_name' ] : $tb[ 'class' ];
								$x = $tb[ 'class' ];
								unset( $tb );
								$tb = $x;
								
								$tb = strtolower( $tb );
								// $tb = strtolower( $tb ) . $sn++;
								$ctb = 'c'.ucwords( $tb );
								if( ! isset( $c_file_name ) )$c_file_name = $ctb;

								$path = $this->class_settings[ 'calling_page' ].'classes/';
								if( isset( $package ) && $package )$path .= 'package/'.$package . '/';
								$path .= $c_file_name.'.php';
								
								if( file_exists( $path ) ){
									if( ! class_exists( $ctb ) )require $path;
								}
								if( class_exists( $ctb ) ){
									// echo 'Converting Class: ' . $ctb . ' '. ( $package ? 'in '. $package : '' ) .'<br />';
									$cl = new $ctb();
								}else{
									
									$error = "<h4><strong>Class '". $ctb ."' Not Found 2</strong></h4>";
								}
							}else{
							
								$error = "<h4><strong>Class '". $cls ."' Not Found</strong></h4>";
							}
						break;
						}
					
						

						// print_r( class_exists( $ctb ) );exit;
						
							
						if( ! $error ){
							if( isset( $cl ) && isset( $cl->table_name ) ){
								// echo 'Converting Class: ' . $ctb . ' '. ( $package ? 'in '. $package : '' ) .'<br />';
								$xc = $cl;
								$tb =  $cl->table_name;
								

								if( function_exists( $tb ) && isset( $xc->table_fields ) && ! empty( $xc->table_fields ) ){
									
									$label = ucwords( str_replace( '_', ' ', $tb ) );
									if( isset( $xc->label ) ){
										$label = $xc->label;
									}
									// echo 'Function Exists: ' . $ctb . ' '. ( $package ? 'in '. $package : '' ) .'<br /><br />';
									//create table
									$line_table_id[] = "'". $tb ."'";
									
									$lt = array(
										"new_id" => $tb,
										"table_name" => $tb_name,
										"table_label" => $label,
										"table_type" => '',
										"table_status" => '',
										"table_package" => $package,
										"table_classification" => 'table',
									);
									if( isset( $cl->plugin ) && $cl->plugin ){
										$lt["new_id"] .= '_' . str_replace( 'nwp_', '', $cl->plugin );
										$lt["table_package"] = str_replace( 'nwp_', '', $cl->plugin );
										$lt["table_classification"] = "plugin_table";
										
										
										$line_table_id[] = "'". $lt["new_id"] ."'";
									}
									$line_table[] = $lt;
									
									$ptb = $tb();
									$serial_num = 10;
									
									foreach( $xc->table_fields as $k => $v ){
										if( isset( $ptb[ $v ] ) ){
											
											$vf = $ptb[ $v ];
											foreach( $vf as & $vvf ){
												if( is_array( $vvf ) ){
													$vvf = json_encode( $vvf );
												}
											}
											$vf["table_name"] = $lt["new_id"];
											$vf["new_id"] = $v;
											$vf["serial_number"] = $serial_num;
											
											$vf["display_field_label"] = $vf["field_label"];
											$vf["field_label"] = $k;

											$vf["field_id"] = $v;
											if( $set_field_id ){
											}
											
											$serial_num += 10;
											
											$line_field[] = $vf;
										}
									}
								}else{
									$error = 'Unable to retrieve fields:'. $tb .', '. $package .'<br>';
								}
							}
							
						}
					}
					// print_r( $line_table );exit;
					//print_r( $line_field );exit;
					if( ! empty( $line_table ) ){
						$this->class_settings[ 'reference' ] = 1;
						$this->class_settings[ 'reference_table' ] = 1;
						$this->class_settings[ "clear_ids" ] = $line_table_id;
						$this->class_settings["line_items"] = $line_table;
						// if( $this->class_settings[ 'reference' ] ){
						if( $this->_save_line_items() ){
							$msg =  'Tables Created <br />';
							
							$database_fields = new cDatabase_fields();
							
							
							$query = "DELETE FROM `" . $this->class_settings['database_name'] . "`.`".$database_fields->table_name."` where `".$database_fields->table_fields['table_name']."` = '". $tb ."' ";	//LIMIT 1 				
							$query_settings = array(
								'database' => $this->class_settings['database_name'] ,
								'connect' => $this->class_settings['database_connection'] ,
								'query' => $query,
								'query_type' => 'UPDATE',
								'set_memcache' => 1,
								'tables' => array( $database_fields->table_name ),
							);
							execute_sql_query($query_settings);
							
							
							$database_fields->class_settings = $this->class_settings;
							$database_fields->class_settings["line_items"] = $line_field;
							$database_fields->class_settings["reference"] = $tb;
							$database_fields->class_settings["reference_table"] = $tb;
							$database_fields->class_settings["action_to_perform"] = 'save_line_items';
							$database_fields->database_fields();
							
							// print_r( $line_field );exit;
							// echo 'Fields Created <br />';
						}
						if( ! class_exists( 'cAudit' ) ){
							include $this->class_settings[ 'calling_page' ].'/classes/cAudit.php';
						}
						// exit;
						// if( ! ( isset( $opt[ 'skip_generate' ] ) && $opt[ 'skip_generate' ] ) ){
							foreach( $line_table as $sval ){
								$_POST["id"] = $sval["table_name"];
								// $this->_generate_class();
							}
						// }
							
						$error = $msg . 'Class(es) Regenerated Successfully';
						$etype = 'success';

					}else{
						$error = 'Class(es) Regeneration Failed';
					}
				}
			break;
			}

			if( $error ) {
				$s_msg = $this->_display_notification( array( "type" => $etype, "message" => $error ) );
				$html = isset( $s_msg[ 'html' ] ) ? $s_msg[ 'html' ] : '';
			}

			if( ! $html && $filename ){
				$this->class_settings[ 'data' ] = $data;
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/development/'.$this->table_name.'/'.$filename );
				$html = $this->_get_html_view();
			}
			
			if( isset( $after_html["html_replacement"] ) ){
				$html .= $after_html["html_replacement"];
			}
			
			if( $modal ){
				$this->class_settings["modal_title"] = $title;
				return $this->_launch_popup( $html, '#'.$handle, array( 'set_function_click_event', 'prepare_new_record_form_new' ) );
			}
			
			if( isset( $_GET["close_modal"] ) && $_GET["close_modal"] ){
				$this->class_settings[ 'html' ] = array( 'html-files/templates-1/' . $this->table_name_static . '/zero-out-negative-budget-removal-script.php' );
				$html .= $this->_get_html_view();
			}
			
			$return = array(
				'do_not_reload_table' => 1,
				'html_replacement' => $html,
				'html_replacement_selector' => '#'.$handle,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'new-status',
				'javascript_functions' => $js,
			);
			
			return $return;
			
		}
		
		protected function _open_file(){
			//create files here
			$error = '';
			$class = '';
			$filename = '';
			$line_number = 0;
			$method = '';
			$search_string = '';
			
			switch( $this->class_settings["action_to_perform"] ){
			case "open_class":
				
				if( ( isset( $_POST["class"] ) && $_POST["class"] ) ){
					$cls1 = strtolower( trim( $_POST["class"] ) );
					
					$cls = 'c' . ucwords( $cls1 );
					if( class_exists( $cls ) ){
						$class = $cls;
					}else{
						if( class_exists( $cls1 ) ){
							$class = $cls;
						}
					}
					
					if( $class ){
						$method = strtolower( trim( isset( $_POST["method"] )?$_POST["method"]:'' ) );
						
						$reflector = new \ReflectionClass( $class );
						$filename = $reflector->getFileName();
						
						if( $method && file_exists( $filename ) ){
							$f1 = realpath( $filename );
							
							$a = file_get_contents( $f1 );
							$as = explode( $method, $a );
							if( isset( $as[1] ) && $as[1] ){
								unset( $as[0] );
								$a = implode( $method, $as );
								
								$as = explode( '$this->', $a );
								if( isset( $as[1] ) && $as[1] ){
									$as = explode( '(', $as[1] );
									if( isset( $as[0] ) && $as[0] ){
										$search = 'n ' . $as[0];
										
										unset( $a );
										unset( $as );
										
										$line_number = $this->_get_line_number( $search , $f1 );
									}
								}
							}
							
						}
						
					}
					
					if( ! $class ){
						$error = '<h4>Class Not Found</h4>Please refresh & try again';
					}
				}else{
					$error = '<h4>No Class Specified</h4>Please refresh & try again';
				}
				
			break;
			default:
				
				if( ( isset( $_POST["file"] ) && $_POST["file"] ) ){
					$filename = $_POST["file"];
				}else{
					$error = '<h4>No File Specified</h4>Please refresh & try again';
				}
				
			break;
			}
			
			if( ! $error && ! $filename ){
				$error = '<h4>File Path Not Found</h4>Please refresh & try again';
			}
			
			if( $error ){
				$err = new cError('010014');
				$err->action_to_perform = 'notify';
				$err->html_format = 2;
				$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = $error;
				return $err->error();
			}
			
			if( $filename ){
				run_in_background( "openfile", 0, 
					array( 
						"text" => $filename, 
						"argument3" => $line_number,
					) 
				);
			}
			
			$err = new cError('010011');
			$err->action_to_perform = 'notify';
			$err->html_format = 2;
			$err->class_that_triggered_error = 'c'.$this->table_name.'.php';
			$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
			$err->additional_details_of_error = $error;
			return $err->error();
		}
			
		protected function _get_line_number( $search = '', $filename = '' ){
			$line_number = false;
			
			if( file_exists( $filename ) && $search ){
				if ($handle = fopen( $filename , "r")) {
				   $count = 0;
				   while (($line = fgets($handle, 4096)) !== FALSE and !$line_number) {
					  $count++;
					  $line_number = (strpos($line, $search) !== FALSE) ? $count : $line_number;
				   }
				   fclose($handle);
				}
			}
			
			return $line_number;
		}
		
		public function _generate_class( $opt = array() ){
			//create files here
			$action_to_perform = $this->class_settings["action_to_perform"];
			$error_msg = '';
			
			if( ! ( isset( $_POST["id"] ) && $_POST["id"] ) ){
				$error_msg =  '<h4>No Record Found</h4>Please select a record';
			}
			
			if( ! $error_msg ){
				clear_load_time_cache();
				
				$id = $_POST["id"];
				$this->class_settings["current_record_id"] = $_POST["id"];	
				$e = $this->_get_record();
				
				if( ! ( isset( $e["id"] ) && $e["id"] ) ){
					$error_msg =  '<h4>Unable to Retrieve Record</h4>Please try again';
				}
			}
			
			if( $error_msg ){
				return $this->_display_notification( array( "type" => "error", "message" => $error_msg ) );
			}
			
			$classification = 'table';
			
			$upgrade_table = 0;
			$create_table = 1;
			$create_class = 1;
			$include_table_data = 0;
			$get_table_fields = 1;
			$create_package = 0;
			$create_plugin = '';
			$plugin_name = '';
			$main_class = 1;
			$create_class_dependency = 1;
			
			switch( $action_to_perform ){
			case "upgrade_table_structure":
				$upgrade_table = 1;
				$create_table = 0;
				$include_table_data = 1;
			break;
			}
			
			$table_name = strtolower( clean3( trim( $e["table_name"] ) ) );
			$table_package = strtolower( $e["table_package"] );
			$table_label = $e[ 'table_label' ];
			
			// print_r( $e );exit;
			switch( $e['table_classification'] ){
			case "single_json_data":
			case "multiple_json_data_horizontal":
			case "multiple_json_data_vertical":
				$create_table = 0;
				$create_class = 0;
				$include_table_data = 1;
			break;
			case "package":
				$create_table = 0;
				$upgrade_table = 0;
				
				$create_class = 1;
				$include_table_data = 1;
				$get_table_fields = 0;
				$create_package = 1;
			break;
			case "plugin":
				$create_table = 0;
				$upgrade_table = 0;
				$create_class = 1;
				$include_table_data = 1;
				$get_table_fields = 0;
				$main_class = 0;
				$create_class_dependency = 0;
				
				$plugin_name = $table_name;
				
				$table_name = 'nwp_'.$table_name;
				$create_plugin = $table_name;
				
			break;
			case "plugin_table":
				$main_class = 0;
				
				$this->class_settings["overide_select"] = " `".$this->table_fields["table_name"]."` as 'table_name' ";
				$this->class_settings["where"] = " AND `".$this->table_fields["table_name"]."` = '". $e["table_package"] ."' ";
				$p = $this->_get_records();
				
				// print_r( $p );exit;
				if( isset( $p[0]["table_name"] ) && $p[0]["table_name"] ){
					$plugin_name = $p[0]["table_name"];
					$create_plugin = 'nwp_' . $p[0]["table_name"];
				}else{
					if (isset($e['table_name']) && $e['table_name']) {
						$plugin_name = strtolower( $e['table_package'] );
						$create_plugin = 'nwp_' . $plugin_name;
					}
				}
				
			break;
			case "table":
				if( $e[ 'table_package' ] )$create_package = 1;
			break;
			}

			if( isset( $opt[ 'cloud_app_db' ] ) && $opt[ 'cloud_app_db' ] ){
				$upgrade_table = 0;
				$create_package = 0;
				$create_plugin = 0;
				$create_class = 0;
				$create_table = 1;
			}
			
			if( $include_table_data && isset( $e["data"] ) && $e["data"] ){
				$e_data = json_decode( $e["data"], true );
				unset( $e["data"] );
				
				$e["data"] = $e_data;
				unset( $e_data );
			}
			
			$table = 'database_fields';
			$action = 'search_list';
			$data = array();
			$data1 = array();
			
			$this->class_settings['return_data'] = 1;
			
			if( $get_table_fields ){
				$rt = "c" . ucwords( $table );
				$class = new $rt();
				$class->class_settings = $this->class_settings;
				
				$db_mode = '';
				if( defined("DB_MODE") ){
					$db_mode = DB_MODE;
				}
				
				switch( $db_mode ){
				case 'mongoDB':
					$class->class_settings[ 'mongo_where' ] = array(
						$class->table_fields["table_name"] => $_POST["id"],
						// "record_status" => 1,
						$class->table_fields["serial_number"] => array(
							'$gt' => '0',
						),
					);

					$class->class_settings["action_to_perform"] = $action;
					$data = $class->$table();
					// print_r( $data );exit;

					$class->class_settings[ 'mongo_where' ] = array(
						// "record_status" => 1,
						$class->table_fields["table_name"] => $_POST["id"],
						$class->table_fields["serial_number"] => array(
							'$lt' => '1',
						),
					);

					$class->class_settings["action_to_perform"] = $action;
					$data1 = $class->$table();
					
				break;
				default:	//mysql

					$class->class_settings['where'] = " AND `".$class->table_fields["table_name"]."` = '".$_POST["id"]."' AND `".$class->table_name."`.`".$class->table_fields["serial_number"]."` > 0 ";
					$class->class_settings["order_by"] = " ORDER BY `".$class->table_name."`.`".$class->table_fields["serial_number"]."` ASC ";
					
					$class->class_settings["action_to_perform"] = $action;
					$data = $class->$table();

					$class->class_settings['where'] = " AND `".$class->table_fields["table_name"]."` = '".$_POST["id"]."' AND `".$class->table_name."`.`".$class->table_fields["serial_number"]."` < 1 ";
					$class->class_settings["order_by"] = " ORDER BY `".$class->table_name."`.`serial_num` ASC ";
					$data1 = $class->$table();

				break;
				}
				// print_r( $data ); exit;
			
				$data = array_merge( $data, $data1 );
			}
			
			
			
			$table_clones = array( $table_name => $table_name );
			
			if( isset( $e["table_type"] ) && $e["table_type"] ){
				$suffix = explode( ",", $e["table_type"] );
				if( ! empty( $suffix ) ){
					foreach( $suffix as $suv ){
						$table_clones[ $table_name . "_" . $suv ] = $table_name . "_" . $suv;
					}
				}
			}
			
			$remove_fields = array();
			$new_fields = array();
			$actual_fields = array();
			
			if( $upgrade_table ){
				$query = "DESCRIBE `" . $this->class_settings['database_name'] . "`.`" . $table_name . "`";
				switch( DB_MODE ){
				case 'mssql':
					$query = $table_name;
				break;
				}
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query ,
					'query_type' => 'DESCRIBE' ,
					'set_memcache' => 0,
					'tables' => array( $table_name ),
				);
				$sql_r = execute_sql_query($query_settings);
				
				if( is_array( $sql_r ) && ! empty( $sql_r ) ){
					
					foreach( $sql_r as $v1 ){
						
						switch( $v1["Field"] ){
						case "creator_role":
						case "modified_by":
						case "modification_date":
						case "creation_date":
						case "created_source":
						case "modified_source":
						case "created_by":
						case "device_id":
						case "ip_address":
						case "serial_num":
						case "record_status":
						case "id":
						break;
						default:
						
							$actual_fields[ $v1["Field"] ] = $v1["Field"];
							
						break;
						}
						
					}
					
				}
			}
			
			$d["table_fields"] = $data;

			$table_data = array();
			
			if( count( $table_clones ) > 1 ){
				$table_data["table_clone"] = $table_clones;
			}
			
			if( $include_table_data ){
				$table_data["table"] = $e;
			}
			
			$change_field_types = array();
			
			$database_fields = "";
			$previous_field = "id";
			$database_fields2 = array();
			$index_this_fields = array();

			$ffds = array();
			$fn = 'c'.$table_name;
			if( class_exists( $fn ) ){
				$fn = new $fn;
				$ffds = $fn->table_fields;
			}

			if( ! empty( $data ) ){
				foreach( $data as $field ){
					// echo $field[ "field_number" ];
					$field_key = strtolower( $table_name . $field[ "serial_num" ] );
					$field_identifier = clean3( strtolower( $field[ 'field_label' ] ) );
					// $field_identifier = $field[ 'field_label' ];
					if( isset( $ffds[ $field_identifier ] ) && $ffds[ $field_identifier ] ){
						$field_key = $ffds[ $field_identifier ];
					}

					if( isset( $field[ 'field_id' ] ) && $field[ 'field_id' ] && $field[ 'field_id' ] !== 'undefined' ){
						$field_key = $field[ 'field_id' ];
					}

					if( defined( 'USE_FIELD_KEYS_IN_DB' ) && USE_FIELD_KEYS_IN_DB ){
						$field_key = $field_identifier;
					}

					$table_data[ "fields" ][ $field_identifier ] = $field_key;
					
					if( isset( $field["display_field_label"] ) && $field["display_field_label"] && $field["display_field_label"] != "undefined" ){
						$field[ 'field_label' ] = $field["display_field_label"];
					}

					if( isset( $field["abbreviation"] ) && $field["abbreviation"] ){
						$field["text"] = $field["abbreviation"];
					}else{
						$field["text"] = $field[ 'field_label' ];
					}
					
					$field["field_key"] = $field_key;
					$field["table"] = $table_name;
					$field["field_identifier"] = $field_identifier;
					
					if( isset( $field["data"] ) && $field["data"] ){
						$fx = json_decode( $field["data"], true );
						if( is_array( $fx ) ){
							$field["data"] = $fx;
							//$field = array_merge( $field, $fx );
						}
					}
					
					$table_data[ "labels" ][ $field_key ] = $field;
					
					$data_type = "varchar(200)";
					
					switch( $field[ 'form_field' ] ){
					case "date":
					case "date-5time":
					case "date-5":
					case "number":
						$data_type = "int(11)";
						switch( DB_MODE ){
						case 'mssql':
							$data_type = "bigint";
						break;
						}
					break;
					case "decimal_long":
						$data_type = "decimal(20,10)";
					break;
					case "decimal":
					case "currency":
						$data_type = "decimal(20,4)";
					break;
					case "multi-select":
						$data_type = "varchar(4000)";
					break;
					case "calculated":
					case "select":
						$data_type = "varchar(100)";
						switch( DB_MODE ){
						case 'mssql':
							if( $upgrade_table || $create_table ){
								$index_this_fields[ $field_key ] = $field_key;
							}
						break;
						default:
							if( ( $upgrade_table && ! isset( $actual_fields[ $field_key ] ) ) || $create_table ){
								$index_this_fields[ $field_key ] = $field_key;
							}
						break;
						}
					break;
					case "file":
					case "textarea":
					case "field_group":
					case "html":
					case "textarea-unlimited":
						$data_type = "text";
					break;
					case "textarea-unlimited-med":
						$data_type = "mediumtext";
						switch( DB_MODE ){
						case 'mssql':
							$data_type = "VARCHAR(MAX)";
						break;
						}
					break;
					}

					$defnull = 'DEFAULT NULL ';
					switch( DB_MODE ){
					case 'mssql':
						if( isset( $index_this_fields[ $field_key ] ) && $index_this_fields[ $field_key ] ){
							$defnull = ' CONSTRAINT <replace_tablename>_'. $field_key .'_constraint '.$defnull;
						}else{
							$defnull = '';
						}
					break;
					}

					//get new fields
					if( isset( $actual_fields[ $field_key ] ) ){
						switch( DB_MODE ){
						case 'mssql':
							$change_field_types[] = " ALTER COLUMN `".$field_key."` ".$data_type." ";
							// $change_field_types[] = " ADD CONSTRAINT `".$field_key."` ".$data_type." DEFAULT NULL ";
						break;
						default:
							$change_field_types[] = " CHANGE `".$field_key."` `".$field_key."` ".$data_type." NULL DEFAULT NULL ";
						break;
						}
						
						unset( $actual_fields[ $field_key ] );
					}else{
						switch( DB_MODE ){
						case 'mssql':
							$new_fields[] = " ADD `".$field_key."` ".$data_type." ";
						break;
						default:
							$new_fields[] = " ADD `".$field_key."` ".$data_type." DEFAULT NULL AFTER `".$previous_field."` ";
						break;
						}
					}
					$previous_field = $field_key;
					
					$fpx = " `".$field_key."` ".$data_type." ".$defnull;
					$database_fields .= ", ".$fpx;
					$database_fields2[ $field_key ] = $fpx;
				}
			}
			// print_r( $new_fields );exit;

			foreach( [ 'record_status' ] as $fieldkey ){
				switch( DB_MODE ){
				case 'mssql':
					if( $upgrade_table || $create_table ){
						$index_this_fields[ $fieldkey ] = $fieldkey;
					}
				break;
				default:
					if( ( $upgrade_table && ! isset( $actual_fields[ $fieldkey ] ) ) || $create_table ){
						$index_this_fields[ $fieldkey ] = $fieldkey;
					}
				break;
				}
			}

			if( ! empty( $actual_fields ) ){
				foreach( $actual_fields as $af ){
					switch( DB_MODE ){
					case 'mssql':
						$remove_fields[] = " DROP COLUMN `".$af."` ";
					break;
					default:
						$remove_fields[] = " DROP `".$af."` ";
					break;
					}
				}
			}
			
			//check for the files
			$development_path = $this->class_settings["calling_page"] . "classes/dev/";
			$class_path = $this->class_settings["calling_page"] . 'classes/';
			$class_template = 'demo_class.php';
			
			$package_path = '';
			if( $table_package ){
				$package_path = 'package/'.$table_package.'/';
			}
			
			if( $create_package ){
				create_folder( $this->class_settings["calling_page"] . 'classes/'. $package_path , '', '' );
				create_folder( $this->class_settings["calling_page"] . 'settings/'. $package_path , '', '' );
				create_folder( $this->class_settings["calling_page"] . 'html-files/templates-1/'. $package_path , '', '' );
			}
			
			
			if( $create_plugin ){
				$package_path = '';
				
				create_folder( $this->class_settings["calling_page"] . 'plugins/'. $create_plugin , '', '' );
				create_folder( $this->class_settings["calling_page"] . 'plugins/'. $create_plugin . '/classes' , '', '' );
				create_folder( $this->class_settings["calling_page"] . 'plugins/'. $create_plugin . '/views' , '', '' );
				
				$class_path = $this->class_settings["calling_page"] . 'plugins/'. $create_plugin . '/';
				
				if( file_exists( $class_path . $create_plugin.'.json' ) ){
					$config_data = json_decode( file_get_contents( $class_path . $create_plugin.'.json' ), true );
				}else{
					file_put_contents( $class_path . $package_path.'functions.php' , '' );
					$config_data = json_decode( file_get_contents( $development_path . "demo_plugin_config.json" ), true );
				}
				
				if( ! is_array( $config_data ) ){
					$config_data = array();
				}
				
				//get all plugin tables & register them
				$this->class_settings["overide_select"] = " `".$this->table_fields["table_name"]."` as 'table_name', `".$this->table_fields["table_label"]."` as 'table_label', `".$this->table_fields["table_type"]."` as 'table_type' ";
				$this->class_settings["where"] = " AND `".$this->table_fields["table_package"]."` = '". $plugin_name ."' AND `".$this->table_fields["table_classification"]."` = 'plugin_table' ";
				$p = $this->_get_records();
				
				if( isset( $p[0]["table_name"] ) && $p[0]["table_name"] ){
					foreach( $p as $pv ){
						$config_data["classes"][ $pv["table_name"] ] = $pv;
						if( isset( $pv["table_type"] ) && $pv["table_type"] ){
							$suffix = explode( ",", $pv["table_type"] );
							if( ! empty( $suffix ) ){
								foreach( $suffix as $suv ){
									$pv[ 'table_name' ] .= "_" . $suv;
									$pv[ 'table_label' ] .= "-" . $suv;
									$config_data["classes"][ $pv["table_name"] ] = $pv;
								}
							}
						}
					}
				}
				
				$config_data["updated"]["by"] = $this->class_settings["user_full_name"];
				$config_data["updated"]["at"] = date("d-M-Y H:i:s");
				
				if( isset( $table_data["table"] ) ){
					$config_data["table"] = $table_data["table"];
				}
				
				file_put_contents( $class_path . $package_path . $create_plugin.'.json' , json_encode( $config_data ) );
				
				if( $create_table || $upgrade_table ){
					$package_path = 'classes/';
				}
				
				if( $create_table ){
					create_folder( $this->class_settings["calling_page"] . 'plugins/'. $create_plugin . '/views/' . $table_name , '', '' );
					
					$class_template = 'demo_plugin_class.php';
				}else{
					//when the main plugin class is being created
					$class_template = 'demo_plugin.php';
				}
				
			}
			
			if( $create_class && $create_class_dependency ){
				create_folder( $class_path . $package_path .'dependencies', '', '' );
				create_folder( $class_path . $package_path .'dependencies-query', '', '' );
				
				file_put_contents( $class_path . $package_path .'dependencies/' . $table_name.'.json' , json_encode( $table_data ) );
			}
			
			foreach( $table_clones as $stb ){
				if( $create_class && ! file_exists( $class_path . $package_path ."c" . ucfirst($stb) . ".php" ) ){
					
					// the class file
					$demo_class = $development_path . $class_template;
					$the_new_class = file_get_contents($demo_class);

					$new_table_name = str_replace(' ', '_', $stb); 

					$thereplace = str_replace('demo_class', $stb, $the_new_class ); 
					$thereplace = str_replace('cDemo_class', 'c'.ucfirst($stb), $thereplace );
					$thereplace = str_replace('Demo Class', $table_label, $thereplace ); 
					$thereplace = str_replace('@timeofcreation@', $this->class_settings["user_full_name"] . date(" | H:i | d-M-Y") , $thereplace ); 
					
					file_put_contents( $class_path . $package_path ."c" . ucfirst($stb) . ".php", $thereplace );
					
				}
				
				/* 
				if( $create_class && $main_class && ! file_exists( $this->class_settings["calling_page"] . "ajax_server/".$stb."_server.php" ) ){
					// the ajax server
					$theajaxfile = $development_path . "demo_server.php";
					copy( $theajaxfile, $this->class_settings["calling_page"] . "ajax_server/".$stb."_server.php" );
				}
				 */
				 
				if( $create_class && $main_class && ! file_exists( $this->class_settings["calling_page"] . "locale/US/" . strtoupper( $stb ) . ".php" ) ){
					//language file
					$languageFile = "<?php define( '".strtoupper( $stb )."' , 'TRUE' ); ?>";
					file_put_contents( $this->class_settings["calling_page"] . "locale/US/" . strtoupper( $stb ) . ".php", $languageFile );
				}
			}
			
			
			$success_msg = '<h4>Oops! Something Happened</h4>' . $create_plugin . json_encode( $table_clones );
			
			if( $create_plugin ){
				$success_msg = '<h4>Successful Plugin Generation</h4><strong>'. strtoupper( $create_plugin ) .'</strong> has been generated';
			}
			
			if( $create_table ){
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query_type' => 'EXECUTE', //CREATE, SELECT
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);

				$db_mode = '';
				if( defined("DB_MODE") ){
					$db_mode = DB_MODE;
				}
				
				switch( $db_mode ){
				case 'mongoDB':
					file_put_contents( $class_path . $package_path .'dependencies-query/' . $table_name.'.txt', json_encode( $query_settings ) );

					$success_msg = '<h4>Successful Class Generation</h4><strong>'. ucwords( $table_name ) .'</strong> has been generated';
				break;
				default: //case 'mysql':
					$stb_created = '';
					
					foreach( $table_clones as $stb ){
						$stb_created .= $stb . ', ';
						
						$query = "DROP TABLE IF EXISTS `" . $this->class_settings['database_name'] . "`.`".$stb."` ";
						$query_settings[ "query" ] = $query;
						execute_sql_query( $query_settings );
						
						$all_query = '';
						$df = str_replace( '<replace_tablename>', $stb, $database_fields );
						
						switch( DB_MODE ){
						case 'mssql':
							$query = " CREATE TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` ( `id` varchar(33) NOT NULL UNIQUE ".$df.", `serial_num` int NOT NULL IDENTITY(1,1) PRIMARY KEY, `creator_role` varchar(100) DEFAULT NULL, `created_source` varchar(100) DEFAULT NULL, `created_by` varchar(100) DEFAULT NULL, `creation_date` int DEFAULT NULL, `modified_source` varchar(100) DEFAULT NULL, `modified_by` varchar(100) DEFAULT NULL, `modification_date` int DEFAULT NULL, `ip_address` varchar(100) DEFAULT NULL, `device_id` text NOT NULL, `record_status` varchar(100) DEFAULT NULL 
								) ";
							
							$all_query = $query . "; \n";
							// print_r( $query );exit;
							$query_settings[ "query" ] = $query;
							execute_sql_query( $query_settings );
						
							$idx = 'record_status_index';
							$query = "IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = '". $idx ."' AND object_id = OBJECT_ID('". $stb ."'))
								BEGIN
								CREATE INDEX ". $idx ." ON `" . $this->class_settings['database_name'] . "`.`".$stb."` (`record_status`) 
								END
								";
							$query_settings[ "query" ] = $query;
							execute_sql_query( $query_settings );
							$all_query .= $query . "; \n";
						
							$idx = 'modification_date_index';
							$query = "IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = '". $idx ."' AND object_id = OBJECT_ID('". $stb ."'))
								BEGIN
								CREATE INDEX ". $idx ." ON `" . $this->class_settings['database_name'] . "`.`".$stb."` (`modification_date`) 
								END
								";
							$query_settings[ "query" ] = $query;
							execute_sql_query( $query_settings );
							$all_query .= $query . "; \n";
							
						break;
						default:
							$query = " CREATE TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` ( `id` varchar(33) NOT NULL ".$df.", `serial_num` int(11) NOT NULL, `creator_role` varchar(100) DEFAULT NULL, `created_source` varchar(100) DEFAULT NULL, `created_by` varchar(100) DEFAULT NULL, `creation_date` int(11) DEFAULT NULL, `modified_source` varchar(100) DEFAULT NULL, `modified_by` varchar(100) DEFAULT NULL, `modification_date` int(11) DEFAULT NULL, `ip_address` varchar(100) DEFAULT NULL, `device_id` text NOT NULL, `record_status` varchar(2) DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ";
							
							$all_query = $query . "; \n";
							// print_r( $query );exit;
							$query_settings[ "query" ] = $query;
							execute_sql_query( $query_settings );
							
							$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` ADD PRIMARY KEY (`serial_num`), ADD UNIQUE KEY `id` (`id`) ";
							$query_settings[ "query" ] = $query;
							execute_sql_query( $query_settings );
							$all_query .= $query . "; \n";
							
							$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` MODIFY `serial_num` int(11) NOT NULL AUTO_INCREMENT ";
							$query_settings[ "query" ] = $query;
							execute_sql_query( $query_settings );
							$all_query .= $query . "; \n";
							
						break;
						}
						
						file_put_contents( $class_path . $package_path .'dependencies-query/' . $stb.'.sql', $all_query );
					}
					
					$success_msg = '<h4>Successful Class Generation</h4><strong>'. ucwords( $stb_created ) .'</strong> has been generated';
				break;
				}
			}
			
			if( $upgrade_table ){
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query_type' => 'EXECUTE', //CREATE, SELECT
					'set_memcache' => 1,
					'tables' => array( $this->table_name ),
				);
				
				$all_query = '';
				$stb_created = '';
				
				foreach( $table_clones as $stb ){
					$stb_created .= $stb . ', ';
					
					if( ! empty( $new_fields ) ){
						switch( DB_MODE ){
						case 'mssql':
							foreach( $new_fields as $nf ){
								$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` " . $nf;
								
								$query_settings[ "query" ] = $query;
								$all_query .= $query . "; \n";
								
								// echo $query . "\r\n";
								execute_sql_query( $query_settings );
							}
						break;
						default:
							$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` " . implode( ", ", $new_fields );
							
							$query_settings[ "query" ] = $query;
							$all_query .= $query . "; \n";
							
							execute_sql_query( $query_settings );
						break;
						}
						
					}
					
					if( ! empty( $change_field_types ) ){
						switch( DB_MODE ){
						case 'mssql':
							foreach( $change_field_types as $nf ){
								$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` " . $nf;
								
								$query_settings[ "query" ] = $query;
								
								// echo $query . "\r\n";
								execute_sql_query( $query_settings );
							}
						break;
						default:
							$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` " . implode( ", ", $change_field_types );
							
							$query_settings[ "query" ] = $query;
							
							execute_sql_query( $query_settings );
						break;
						}
					}
					
					if( ! empty( $remove_fields ) ){
						switch( DB_MODE ){
						case 'mssql':
							foreach( $remove_fields as $nf ){
								if( preg_match( "/`([^`]*)`/", $nf, $matches ) ){
									// print_r( $matches );
									$idx = $matches[1].'_index';
									$query = "IF EXISTS (SELECT * FROM sys.indexes WHERE name = '". $idx ."' AND object_id = OBJECT_ID('". $stb ."'))
										BEGIN
											DROP INDEX $idx ON $stb;
										END
										";
									$query_settings[ "query" ] = $query;
									execute_sql_query( $query_settings );

									$idx = $stb.'_'.$matches[1].'_constraint';
									$query = "IF EXISTS (SELECT 1 FROM sys.objects WHERE name = '$idx')
									    ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` DROP CONSTRAINT [$idx];
										";
									$query_settings[ "query" ] = $query;
									// echo $query."\r\n";
									execute_sql_query( $query_settings );
								}

								$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` " . $nf;
								
								$query_settings[ "query" ] = $query;
								$all_query .= $query . "; \n";
								
								// echo $query . "\r\n";
								execute_sql_query( $query_settings );
							}
						break;
						default:
							$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` " . implode( ", ", $remove_fields );
							
							$query_settings[ "query" ] = $query;
							$all_query .= $query . "; \n";
							
							execute_sql_query( $query_settings );
						break;
						}
					}
					
					$past_query = '';
					
					if( file_exists( $class_path . $package_path .'dependencies-query/' . $stb.'.sql' ) ){
						$past_query = file_get_contents( $class_path . $package_path .'dependencies-query/' . $stb.'.sql' );
					}
					
					file_put_contents( $class_path . $package_path .'dependencies-query/' . $stb.'.sql', $past_query . $all_query );
					
				}
				$success_msg = '<h4>Successful Table Upgrade</h4><strong>'. ucwords( $stb_created ) .'</strong> has been upgraded';
			}
			
			if( $upgrade_table || $create_table ){
				if( ! empty( $index_this_fields ) ){
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query_type' => 'EXECUTE', //CREATE, SELECT
						'set_memcache' => 1,
						'tables' => array( $this->table_name ),
					);
					

					foreach( $index_this_fields as $index_field ){
						foreach( $table_clones as $stb ){
							switch( DB_MODE ){
							case 'mssql':
								$idx = $index_field.'_index';
								$query = "IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = '". $idx ."' AND object_id = OBJECT_ID('". $stb ."'))
									BEGIN
									CREATE INDEX ". $idx ." ON `" . $this->class_settings['database_name'] . "`.`".$stb."` (`". $index_field ."`) 
									END
									";
							break;
							default:
								$query = "ALTER TABLE `" . $this->class_settings['database_name'] . "`.`".$stb."` ADD INDEX(`". $index_field ."`) ";
							break;
							}
							$query_settings[ "query" ] = $query;
							
							$all_query .= $query . "; \n";
							execute_sql_query( $query_settings );
						}
					}
					
					
					file_put_contents( $class_path . $package_path .'dependencies-query/' . $stb.'.sql', $all_query );
				}
			}
			
			return $this->_display_notification( array( "type" => "success", "message" => $success_msg ) );
		}

		protected function _get_available_database_tables(){
			$select = "";
			$table_fields = array();
			$mongo_filter = array();
			foreach( $this->table_fields as $key => $val ){
				if( $select ){
					$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				}
				else{ 
					$select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
					$mongo_filter[ 'projection' ][ '_id' ] = 0;
					$mongo_filter[ 'projection' ][ 'id' ] = 1;
					$mongo_filter[ 'projection' ][ 'serial_num' ] = 1;
					$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
				}
				$table_fields[ $val ] = $key;
			}

			$where = '';
			$all_items = '';
			$mongo_criteria = array();
			// print_r( $mongo_filter );exit;
					
			if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
				$_POST["term"] = trim( $_POST["term"] );
				$where = " AND ( `".$this->table_name."`.`".$this->table_fields["table_label"]."` regexp '".$_POST["term"]."' OR `".$this->table_name."`.`".$this->table_fields["table_name"]."` regexp '".$_POST["term"]."'  ";
				
				if( doubleval( $_POST["term"] ) )$where .= " OR `".$this->table_name."`.`serial_num` = ".doubleval( $_POST["term"] )." ";
				
				$where .= " OR `".$this->table_name."`.`id` = '".$_POST["term"]."' ) ";

				$mongo_criteria[ '$or' ][0][ $this->table_fields["table_label"] ][ '$regex' ] = '^'.$_POST["term"];
				$mongo_criteria[ '$or' ][0][ $this->table_fields["table_label"] ][ '$options' ] = 'i';
				$mongo_criteria[ '$or' ][1][ $this->table_fields["table_name"] ][ '$regex' ] = '^' . $_POST["term"];
				$mongo_criteria[ '$or' ][1][ $this->table_fields["table_name"] ][ '$options' ] = 'i';
				$mongo_criteria[ '$or' ][2][ "serial_num" ][ '$regex' ] = '^' . $_POST["term"];
				$mongo_criteria[ '$or' ][2][ "serial_num" ][ '$options' ] = 'i';
			}
			
			$limit = '';
			/*
			if( isset( $_POST["page_limit"] ) && intval( $_POST["page_limit"] ) ){
				$limit = "LIMIT 0, " . intval( $_POST["page_limit"] );
			}
			*/
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `".$this->table_name."`.`record_status` = '1' ".$where." ORDER BY `".$this->table_name."`.`".$this->table_fields["table_name"]."` ".$limit;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),

				//MongoDB
				'table' => $this->table_name,
				'where' => $mongo_criteria,
				'select' => $mongo_filter,
				'others' => array( 'use_aggregate' => 1 ),
			);
			
			$all_items = execute_sql_query($query_settings);
			// print_r( $all_items );exit;
			$return = array( "items" => array() );
			
			foreach( $all_items as $sval ){
				if( isset( $sval["table_type"] ) && $sval["table_type"] ){
					$return["items"][] = array( "id" => $sval["id"] . "_" . $sval["table_type"], "text" => $sval["table_label"] . ' ['.$sval["table_name"].'] - ' . strtoupper( $sval["table_type"] ), "name" => $sval["table_name"] . "_" . $sval["table_type"] );
				}
				$return["items"][] = array( "id" => $sval["id"], "text" => $sval["table_label"] . ' ['.$sval["table_name"].']', "name" => $sval["table_name"] );
			}
			$return["do_not_reload_table"] = 1;
			
			return $return;
		}

		protected function _display_all_records_full_view2(){
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'display_all_records_frontend':
			break;
			default:
				$this->class_settings[ "return_new_format" ] = 1;
			break;
			}
			
			/* $development = get_hyella_development_mode();
			if( ! $development ){
				$this->datatable_settings["show_add_new"] = 0;
				$this->datatable_settings["show_edit_button"] = 0;
				$this->datatable_settings["show_delete_button"] = 0;
			}
			
			$this->class_settings[ 'data' ][ 'development' ] = $development;
			
			//return html view
			$this->class_settings[ 'html' ] = array( 'html-files/templates-1/development/'.$this->table_name.'/custom-buttons.php' );
			$returning_html_data = $this->_get_html_view();
			
			//configure settings for additional buttons
			$this->class_settings[ "custom_edit_button" ] = $returning_html_data; */
			
			//call initial method
			return $this->_display_all_records_full_view();
		}
	
	
	}
?>