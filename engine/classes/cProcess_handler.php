<?php
	/**
	 * Process Handler Class
	 *
	 * @used in  				Al Classes that use default operational 
	 * 							proccesses
	 * @created  				20:25 | 29-12-2013
	 * @database table name   	-
	 */

	/*
	|--------------------------------------------------------------------------
	| Allows automation of create, search, delete, update, view processess
	|--------------------------------------------------------------------------
	|
	| Interfaces with the cForms - Form Generator Class
	|
	*/
	
	class cProcess_handler{
		public $class_settings = array();
		
		private $current_record_id = '';
		private $directory_of_process_handlers = 'classes/process_handlers/';
		
		function process_handler(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'generate_data_capture_form':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_data_table':
				$returned_value = $this->_display_data_table();
			break;
			case 'restore_records':
			case 'delete_records':
			case 'void_records':
				$returned_value = $this->_delete_records();
			break;
			case 'save_changes_to_database':
				$returned_value = $this->_save_changes();
				
				//Add ID of Newly Created Record
				if( $this->current_record_id ){
					$returned_value['saved_record_id'] = $this->current_record_id;
				}
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = '';
			$hbc = '';
			$hsbc = 'New';	
			
			$tb = $this->class_settings[ 'database_table' ];
			if( isset( $this->class_settings[ 'db_table' ] ) && $this->class_settings[ 'db_table' ] ){
				$tb = $this->class_settings[ 'db_table' ];
			}
			
			//13-mar-23
			$def_values = array();
			$values = array();
			if( isset( $this->class_settings[ 'form_values' ] ) && is_array( $this->class_settings[ 'form_values' ] ) ){
				$values = $this->class_settings[ 'form_values' ];
			}
			
			if( isset( $_GET[ 'sel_id' ] ) && $_GET[ 'sel_id' ] )$_GET[ 'id' ] = $_GET[ 'sel_id' ];
			if( isset( $_GET["selected_record"] ) && $_GET["selected_record"] ){
				$_GET[ 'id' ] = $_GET["selected_record"];
			}
				
			if( ( isset($_POST['mod']) && $_POST['mod']=='edit-'.md5( $this->class_settings[ 'database_table' ] ) && isset($_POST['id']) ) || ( isset( $_GET[ 'id' ] ) && $_GET[ 'id' ] ) ){
				
				if( isset( $_GET[ 'id' ] ) && $_GET[ 'id' ] && ( ! ( isset( $_POST[ 'id' ] ) )  ) ){
					$_POST[ 'id' ] = $_GET[ 'id' ];
				}
				
				$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`" . $tb . "` WHERE `".$tb."`.`id`='".$_POST['id']."'";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					
					'where_table' => $tb,
					'where_fields' => array(
						array( "field" => "id", "value" => $_POST['id'] )
					),
					
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $tb ),
				);
				
				if( isset( $this->class_settings["data_connection"] ) && $this->class_settings["data_connection"] ){
					$query_settings["data_connection"] = $this->class_settings["data_connection"];
				}
				
				$sql_result = execute_sql_query($query_settings);
				
				if(isset($sql_result[0])){
					
					if( isset( $this->class_settings["data_connection"] ) && $this->class_settings["data_connection"] ){
						switch( $this->class_settings["data_connection"] ){
						case "odoo":
							if( function_exists( "connect_to_odoo" ) ){
								$tb = "c" . ucwords( $this->class_settings[ 'database_table' ] );
								$tbb = new $tb();
								foreach( $tbb->table_fields as $key => $val ){
									if( isset( $sql_result[0][ $key ] ) ){
										$sql_result[0][ $val ] = $sql_result[0][ $key ];
									}
								}
							}
						break;
						}
					}
					
					$values = $sql_result[0];
					$def_values = $sql_result[0];
					
					if( isset( $_GET[ 'id' ] ) && $_GET[ 'id' ] ){
						$values[ 'id' ] = '';
						$values[ 0 ] = '';
						$def_values[ 0 ] = '';
						$def_values[ 'id' ] = '';
					}
					
					$this->current_record_id = $values[ 'id' ];
				}else{
					//report error for edit mode only
					if( ! ( isset( $_GET[ 'id' ] ) && $_GET[ 'id' ] ) ){
						//REPORT INVALID TABLE ERROR
						$err = new cError('000001');
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'cProcess_handler.php';
						$err->method_in_class_that_triggered_error = '_generate_new_data_capture_form';
						$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 67';
						
						return $err->error();
					}
				}
			}
			
			if( isset( $this->class_settings["custom_fields_data"] ) && isset( $values[ $this->class_settings["custom_fields_data"] ] ) ){
				$dx = json_decode( $values[ $this->class_settings["custom_fields_data"] ], true );
				if( isset( $dx["custom_fields"] ) && is_array( $dx["custom_fields"] ) && ! empty( $dx["custom_fields"] ) ){
					$values = array_merge( $values, $dx["custom_fields"] );
					$def_values = array_merge( $def_values, $dx["custom_fields"] );
				}
			}
			
			if( isset( $this->class_settings[ 'form_values_important' ] ) && is_array( $this->class_settings[ 'form_values_important' ] ) ){
				foreach( $this->class_settings[ 'form_values_important' ] as $k => $v ){
					$values[ $k ] = $v;
				}
			}

			if( isset( $this->class_settings[ 'form_values_replace' ] ) && is_array( $this->class_settings[ 'form_values_replace' ] ) ){
				foreach( $this->class_settings[ 'form_values_replace' ] as $k => $v ){
					if( !( isset( $values[ $k ] ) && $values[ $k ] ) )
						$values[ $k ] = $v;
				}
			}
			
			//1. SET HEADING TITLE
			if( ! ( isset( $this->class_settings['do_not_show_headings'] ) && $this->class_settings['do_not_show_headings'] ) ){
				
				if( isset( $this->class_settings['form_heading_title'] ) )
					$form_heading_caption_title = $this->class_settings['form_heading_title'];
				else
					$form_heading_caption_title = $hsbc.' '.ucwords(str_replace('_',' ',$this->class_settings[ 'database_table' ]));
					
				$returning_html_data .= get_add_new_record_form_heading_title($form_heading_caption_title);
				
			}
			// print_r( $values );exit;
			
			//2. PREPARE FORM OPTIONS
			//GET ALL FIELDS IN TABLE
			$fields = array();
			
			$get_fields = 1;
			if( isset( $GLOBALS["table_fields"] ) && is_array( $GLOBALS["table_fields"] ) && $GLOBALS["table_fields"] ){
				$fields = $GLOBALS["table_fields"];
				$get_fields = 0;
			}
			
			if( $get_fields ){
				$query = "DESCRIBE `" . $this->class_settings['database_name'] . "`.`" . $tb . "`";
				
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query ,
					'query_type' => 'DESCRIBE' ,
					'set_memcache' => 1 ,
					'tables' => array( $tb ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if( $sql_result && is_array($sql_result) ){
					
					$fields_sequence_count = 0;
					if( isset( $this->class_settings[ 'fields_sequence' ] ) && is_array( $this->class_settings[ 'fields_sequence' ] ) ){
						$fields_sequence_count = count( $this->class_settings[ 'fields_sequence' ] );
					}
					
					$increment_counter = 0;
					foreach($sql_result as $sval){
					
						//Customize Arrangement of Fields for ordering form elements
						if( $fields_sequence_count ){
							if( isset( $this->class_settings[ 'fields_sequence' ][ $sval[0] ] ) ){
								$fields[ $this->class_settings[ 'fields_sequence' ][ $sval[0] ] ] = $sval[0];
							}else{
								$fields[ $increment_counter + $fields_sequence_count ] = $sval[0];
							}
						}else{
							$fields[] = $sval[0];
						}
						
						++$increment_counter;
					}
					
				}else{
					//REPORT INVALID TABLE ERROR
					$err = new cError('000001');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cregistered_users.php';
					$err->method_in_class_that_triggered_error = '_generate_new_data_capture_form';
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 99';
					return $err->error();
				}
			}

			/**************************************************************************/
			/**********************SET SELECT BOX OPTIONS LIST*************************/
			/**************************************************************************/
			$option = array();
			
			//Get form action
			if( ! isset( $this->class_settings[ 'form_action' ] ) || ( isset( $this->class_settings[ 'form_action' ] ) && ! $this->class_settings[ 'form_action' ] ) ){
				
				$this->class_settings[ 'form_action' ] = '';
				
				if( isset( $this->class_settings[ 'database_table' ] ) && isset( $this->class_settings[ 'form_action_todo' ] ) )
					$this->class_settings[ 'form_action' ] = '?action='.$this->class_settings[ 'database_table' ].'&todo='.$this->class_settings[ 'form_action_todo' ];
					
			}
			
			if( isset( $this->class_settings[ 'plugin' ] ) && $this->class_settings[ 'plugin' ] ){
				$fa = str_replace('action=', 'nwp_action=', $this->class_settings[ 'form_action' ] );
				$fa = str_replace('todo=', 'nwp_todo=', $fa );
				$this->class_settings[ 'form_action' ] = $fa . '&todo=execute&action=' . $this->class_settings[ 'plugin' ];
			}

			if( isset( $this->class_settings[ 'overide_form_action' ] ) && $this->class_settings[ 'overide_form_action' ] ){
				$this->class_settings[ 'form_action' ] = $this->class_settings[ 'overide_form_action' ];
			}
			
			/**************************************************************************/
			/**************************SELECT FORM GENERATOR***************************/
			/**************************************************************************/
			$form = new cForms();
			$form->class_settings = $this->class_settings;
			$form->setDatabase( $this->class_settings['database_connection'] , $this->class_settings[ 'database_table' ] );
			$form->setFormActionMethod( $this->class_settings[ 'form_action' ] , 'post' );
			$form->uid = $this->class_settings['user_id'] ; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id'] ; //Currently logged in user privilege

			if( isset( $this->current_record_id ) ){
				$form->class_settings["edit_record"] = $this->current_record_id;
			}
			
			$form->butclear = 0;
				
			//$form->form_class = 'form-horizontal';
			
			if( isset( $this->class_settings[ 'disable_form' ] ) && $this->class_settings[ 'disable_form' ] ){
				$form->enabled = 0;
			}
			
			if( isset( $this->class_settings[ 'form_submit_button' ] ) )
				$form->submit = $this->class_settings[ 'form_submit_button' ];
			
			if( isset( $this->class_settings[ 'form_clear_button' ] ) ){
				$form->clear = $this->class_settings[ 'form_clear_button' ];
	            $form->butclear = 1;
			}
			$form->but_theme = 'b';
			
			if( isset( $this->class_settings[ 'form_html_id' ] ) )
				$form->html_id = $this->class_settings[ 'form_html_id' ];
			
			
			if( isset( $this->class_settings[ 'form_class' ] ) ){
				$form->form_class = $this->class_settings[ 'form_class' ];
			}else{
				$form->form_class = 'activate-ajax';
			}
			
			//Determine whether or not to hide / show agreement button
			if( isset( $this->class_settings[ 'agreement_text' ] ) && $this->class_settings[ 'agreement_text' ] ){
				$form->show_agreement = 1;
				$form->agreement_text = $this->class_settings[ 'agreement_text' ];
			}
			
			//Determine whether or not to hide / show recaptcha
			if( isset( $this->class_settings[ 'show_recaptcha' ] ) && $this->class_settings[ 'show_recaptcha' ] ){
				$form->show_recaptcha = 1;
			}
			
			//Determine if to hide(make invisible) specific form elements
			if( isset( $this->class_settings[ 'database_table_temp' ] ) )
				$form->table_field_temp = $this->class_settings[ 'database_table_temp' ];
			
			if( isset( $this->class_settings[ 'table_temp' ] ) )
				$form->table_temp = $this->class_settings[ 'table_temp' ];
			
			//Determine if to hide(make invisible) specific form elements
			if( isset( $this->class_settings[ 'hidden_records_css' ] ) )
				$form->hide_record_css = $this->class_settings[ 'hidden_records_css' ];
			
			//Determine if to hide(make invisible) specific form elements
			if( isset( $this->class_settings['form_extra_field_data'] ) )
				$form->form_extra_field_data = $this->class_settings['form_extra_field_data'];
			
			//Determine if to hide(make invisible) specific form elements
			if( isset( $this->class_settings['form_extra_field_elements'] ) )
				$form->form_extra_field_elements = $this->class_settings['form_extra_field_elements'];
			
			//Determine if to hide(make invisible) specific form elements
			if( isset( $this->class_settings[ 'attributes' ] ) )
				$form->attributes = $this->class_settings[ 'attributes' ];
			
			//Determine if not to generate specific form elements
			if( isset( $this->class_settings[ "field_label" ] ) )
				$form->field_label = $this->class_settings[ "field_label" ];
			
			//Determine if not to generate specific form elements
			if( isset( $this->class_settings[ 'hidden_records' ] ) )
				$form->hide_record = $this->class_settings[ 'hidden_records' ];
			
			//Determine if not to generate specific form elements
			if( isset( $this->class_settings[ 'hidden_records_function' ] ) )
				$form->hidden_records_function = $this->class_settings[ 'hidden_records_function' ];
			
			//Determine if Search Operation is being Executed
			if( isset( $this->class_settings[ 'searching' ] ) )
				$form->searching = $this->class_settings[ 'searching' ];
			
			if( isset( $this->class_settings[ "add_empty_select_option" ] ) )
				$form->add_empty_select_option = $this->class_settings[ "add_empty_select_option" ];
			
			//Determine if whether to display forgot password link
			if( isset( $this->class_settings[ 'forgot_password_link' ] ) )
				$form->forgot_password_link = $this->class_settings[ 'forgot_password_link' ];
			
			//Determine whether to add special classes
			if( isset( $this->class_settings[ 'special_element_class' ] ) )
				$form->special_element_class = $this->class_settings[ 'special_element_class' ];
			
			//Determine whether to disable elements
			if( isset( $this->class_settings[ 'disable_form_element' ] ) )
				$form->disable_form_element = $this->class_settings[ 'disable_form_element' ];
			
			//Determine whether to disable elements
			if( isset( $this->class_settings[ 'form_display_not_editable_value' ] ) )
				$form->form_display_not_editable_value = $this->class_settings[ 'form_display_not_editable_value' ];
			
			//Determine whether to disable elements
			if( isset( $this->class_settings[ 'form_extra_options' ] ) )
				$form->form_extra_options = $this->class_settings[ 'form_extra_options' ];
			
			//Determine whether to enforce max value limits
			if( isset( $this->class_settings[ 'form_maximum_value_limit' ] ) )
				$form->form_maximum_value_limit = $this->class_settings[ 'form_maximum_value_limit' ];
			
			if( isset( $this->class_settings['inline_edit_form'] ) )
				$form->inline_edit_form = $this->class_settings['inline_edit_form'];
			
			if( isset( $this->class_settings['hide_form_labels'] ) )
				$form->hide_form_labels = $this->class_settings['hide_form_labels'];
			
			if( isset( $this->class_settings['search_form'] ) )
				$this->class_settings['search_form'] = $this->class_settings['search_form'];
			
			if( isset( $this->class_settings['skip_form_field_validation'] ) )
				$form->skip_form_field_validation = $this->class_settings['skip_form_field_validation'];
			
			if( isset( $this->class_settings['form_settings'] ) && is_array( $this->class_settings['form_settings'] ) ){
				$form->form_settings = $this->class_settings['form_settings'];
			}
			
			$form->select_box_opions_type = 1;	//Serial option to populate select boxes
			/**************************************************************************/
			
			// print_r( $form );exit;
			
			//13-mar-23: correction
			//$returning_html_data .= $form->myphp_form( $fields , $values , 'no.ofcolumns: default = 1' , $option , $values );
			$option["def_values"] = $def_values;
			
			$returning_html_data .= $form->myphp_form( $fields , $values , 'no.ofcolumns: default = 1' , $option );
			
			
			if( ! isset( $this->class_settings['user_email'] ) ){
				$this->class_settings['user_email'] = '';
			}
				
			//Auditor
			//auditor( $this->class_settings , 'read' , $this->class_settings[ 'database_table' ] , 'displayed new record form from the table' );
			
			return array(
				'html' => $returning_html_data,
				'typ' => $this->class_settings['action_to_perform'],
				'record_id' => $this->current_record_id,
				'values' => $values,
			);
		}
		
		private function _delete_records(){
			//Process to Execute Prior to Delete Process
			if( defined("MIS_READ_ONLY") && MIS_READ_ONLY ){
				$err = new cError( '010014' );
				$err->html_format = 2;
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cProcess_handler.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Read Only Mode Active</h4>You are not allowed to modify these records';
				return $err->error();
			}
			
			$record_status = '0';
			$ca = '010001';
			switch ( $this->class_settings['action_to_perform'] ){
			case 'void_records':
				$record_status = '2';
			break;
			case 'restore_records':
				$record_status = '1';
				$ca = '010001A';
			break;
			}
			
			$returning_html_data = '';
			
			$controller_table = '';
			if( isset( $this->class_settings[ 'database_controller_table' ] ) )
				$controller_table = $this->class_settings[ 'database_controller_table' ];
			
			$tb = $this->class_settings[ 'database_table' ];
			if( isset( $this->class_settings[ 'db_table' ] ) && $this->class_settings[ 'db_table' ] ){
				$tb = $this->class_settings[ 'db_table' ];
			}
			
			//echo $tb;
			if( isset($_POST['mod']) && ( $_POST['mod']=='delete-'.md5( $tb ) || $_POST['mod']=='delete-'.md5( $controller_table )  ) && ( isset( $_POST['id'] ) || isset($_POST['ids']) ) ){
				$condition = "";
				$fields_to_delete = "";
				$values_to_delete = "";
				$select_clause_for_query = "";
				
				if( isset($_POST['ids']) && $_POST['ids'] ){
					$condition = "OR";
					
					$array_of_ids = explode(':::' , $_POST['ids']);
					if( is_array($array_of_ids) ){
						foreach( $array_of_ids as $ids ){
							if( $ids ){
								if($values_to_delete)$values_to_delete .= '<>'.$ids;
								else $values_to_delete = $ids;
								
								if($fields_to_delete)$fields_to_delete .= ',id';
								else $fields_to_delete = 'id';
								
								if($select_clause_for_query)$select_clause_for_query .= " OR `" . $tb . "`.`id`='".$ids."'";
								else $select_clause_for_query = "`" . $tb . "`.`id`='".$ids."'";
							}
						}
					}
				}
				
				if( ! ($fields_to_delete && $values_to_delete) ){
					$fields_to_delete = 'id';
					$values_to_delete = $_POST['id'];
					
					$select_clause_for_query = "`" . $tb . "`.`id`='".$_POST['id']."'"; 
				}
				
				//delete items
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $tb,
					'field_and_values' => array(
						'record_status' => array(
							'value' => $record_status,
						),
						'modification_date' => array(
							'value' => date("U"),
						),
						'modified_by' => array(
							'value' => $this->class_settings['user_id'] ,
						),
					) ,
					'where_fields' => $fields_to_delete ,
					'where_values' => $values_to_delete ,
					'condition' => $condition ,
					'delete' => 1,
				);
				
				if( isset( $this->class_settings["action_called"] ) && $this->class_settings["action_called"] ){
					$settings_array["action_to_perform"] = $this->class_settings["action_called"];
				}
				
				if( isset( $this->class_settings["parent_tb"] ) && $this->class_settings["parent_tb"] ){
					$settings_array["parent_tb"] = $this->class_settings["parent_tb"];
				}
				
				if( isset( $this->class_settings["plugin"] ) && $this->class_settings["plugin"] ){
					$settings_array["plugin"] = $this->class_settings["plugin"];
				}
				
				$save = update( $settings_array );
				
				if($save){
					//Auditor
					//auditor( $this->class_settings , 'delete' , $this->class_settings[ 'database_table' ] , 'deleted record with '.$fields_to_delete.' '.$values_to_delete.' in the table' );
					
					
					//Return Successful write operation to database
					$err = new cError( $ca );
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
					$err->method_in_class_that_triggered_error = '_delete';
					$err->additional_details_of_error = 'updated record with '.$fields_to_delete.' '.$values_to_delete.' on line 284';
					$err->additional_details_of_error = '';
					
                    $returning = $err->error();
                    
                    $returning['deleted_records_select_query'] = $select_clause_for_query;
                    if( isset($_POST['ids']) && $_POST['ids'] ){
                        $returning['deleted_record_id'] = $_POST['ids'];
                    }else{
                        if( isset($_POST['id']) && $_POST['id'] )$returning['deleted_record_id'] = $_POST['id'];
                    }
                    
                    return $returning;
				}
			}
			
			//Return unsuccessful update operation
			$err = new cError('000006');
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
			$err->method_in_class_that_triggered_error = '_delete_records';
			if( isset( $fields_to_delete ) && isset( $values_to_delete ) ){
				$err->additional_details_of_error = 'could not update record on line 284 with fields ' . $fields_to_delete . ' and values '.$values_to_delete;
			}else{
				$err->additional_details_of_error = '$_POST variable not set, thus could not update record on line 284';
			}
			return $err->error();
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
			$fields = array();
			$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$this->class_settings[ 'database_table' ]."`";
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query ,
				'query_type' => 'DESCRIBE' ,
				'set_memcache' => 1 ,
				'tables' => array( $this->class_settings[ 'database_table' ] ) ,
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval)
					$fields[] = $sval;
			}else{
				//REPORT INVALID TABLE ERROR
				$err = new cError('000001');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'c'.ucwords($this->class_settings[ 'database_table' ]).'.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->class_settings[ 'database_table' ] , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$form->datatables_settings = $this->class_settings[ 'datatables_settings' ];
			
			if( defined("MIS_READ_ONLY") && MIS_READ_ONLY ){
				$form->datatables_settings["show_add_new"] = 0;
				$form->datatables_settings["show_edit_button"] = 0;
				$form->datatables_settings["show_delete_button"] = 0;
			}
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			$inline_edit_form = '';
			if( isset( $form->datatables_settings['user_can_edit'] ) && $form->datatables_settings['user_can_edit'] ){
				//get inline edit form
				
				$this->class_settings['do_not_show_headings'] = 1;
				$this->class_settings['inline_edit_form'] = 1;
				$this->class_settings[ 'form_html_id' ] = $this->class_settings[ 'database_table' ] . '-inline-edit';
				$this->class_settings[ 'form_action_todo' ] = 'save';
				
				unset( $_GET[ 'id' ] );
				
				$generated_form = $this->_generate_new_data_capture_form();
				$inline_edit_form = $generated_form[ 'html' ];
				
			}
			
			return array(
				'html' => $returning_html_data,
				'inline_edit_form' => $inline_edit_form,
				'typ' => $this->class_settings['action_to_perform'],
			);
		}
		
		private function _save_changes(){
			if( defined("MIS_READ_ONLY") && MIS_READ_ONLY ){
				$err = new cError( '010014' );
				$err->html_format = 2;
				$err->action_to_perform = 'notify';
				$err->class_that_triggered_error = 'cProcess_handler.php';
				$err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
				$err->additional_details_of_error = '<h4>Read Only Mode Active</h4>You are not allowed to modify these records';
				return $err->error();
			}
			
			//SET TABLE
			$save = 0;
			
			$options = array();
			
			$tb = $this->class_settings[ 'database_table' ];
			if( isset( $this->class_settings[ 'db_table' ] ) && $this->class_settings[ 'db_table' ] ){
				$tb = $this->class_settings[ 'db_table' ];
			}
			/**************************************************************************/
			/*****************RECIEVE USER INPUT FROM FILLED FORM**********************/
			/**************************************************************************/
			//1. Determine if form data was submitted
			if(isset($_POST['table']) && $_POST['table'] == $this->class_settings[ 'database_table' ] ){
				//GET ALL FIELDS IN TABLE
				$fields = array();
				
				$get_fields = 1;
				if( isset( $GLOBALS["table_fields"] ) && is_array( $GLOBALS["table_fields"] ) && $GLOBALS["table_fields"] ){
					$fields = $GLOBALS["table_fields"];
					$get_fields = 0;
				}
				if( ! $get_fields ){
					if( ! isset( $fields[ 'serial_num' ] ) )$fields[ 'serial_num' ] = 'serial_num';
					if( ! isset( $fields[ 'id' ] ) )$fields[ 'id' ] = 'id';
					if( ! isset( $fields[ 'creator_role' ] ) )$fields[ 'creator_role' ] = 'creator_role';
					if( ! isset( $fields[ 'created_by' ] ) )$fields[ 'created_by' ] = 'created_by';
					if( ! isset( $fields[ 'created_source' ] ) )$fields[ 'created_source' ] = 'created_source';
					if( ! isset( $fields[ 'creation_date' ] ) )$fields[ 'creation_date' ] = 'creation_date';
					if( ! isset( $fields[ 'modified_source' ] ) )$fields[ 'modified_source' ] = 'modified_source';
					if( ! isset( $fields[ 'modified_by' ] ) )$fields[ 'modified_by' ] = 'modified_by';
					if( ! isset( $fields[ 'modification_date' ] ) )$fields[ 'modification_date' ] = 'modification_date';
					if( ! isset( $fields[ 'ip_address' ] ) )$fields[ 'ip_address' ] = 'ip_address';
					if( ! isset( $fields[ 'device_id' ] ) )$fields[ 'device_id' ] = 'device_id';
					if( ! isset( $fields[ 'record_status' ] ) )$fields[ 'record_status' ] = 'record_status';
				}
				
				// print_r( $get_fields );exit;
				if( $get_fields ){
					$query = "DESCRIBE `" . $this->class_settings['database_name'] . "`.`" . $tb . "`";
					$query_settings = array(
						'database' => $this->class_settings['database_name'] ,
						'connect' => $this->class_settings['database_connection'] ,
						'query' => $query ,
						'query_type' => 'DESCRIBE',
						'set_memcache' => 1,
						'tables' => array( $tb ),

						//MongoDB
						'table' => $tb,
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
					$result = execute_sql_query( $query_settings );
					
					// print_r( $result );exit;
					if($result && is_array($result)){
						foreach($result as $sval)
							$fields[] = $sval[0];
					}else{
						//REPORT INVALID TABLE ERROR
						$err = new cError('000001');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cregistered_users.php';
						$err->method_in_class_that_triggered_error = '_save';
						$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 246';
						return $err->error();
					}
				}
				
				if( isset( $this->class_settings[ 'table_labels' ] ) && $this->class_settings[ 'table_labels' ] ){
					$options["form_label"] = $this->class_settings[ 'table_labels' ];
				}
				
				/**************************************************************************/
				/**************************SELECT FORM GENERATOR***************************/
				/**************************************************************************/
				$form = new cForms();
				$form->setDatabase( $this->class_settings['database_connection'] , $this->class_settings[ 'database_table' ] );
				$form->setFormActionMethod('','post');
				$form->utid = isset( $this->class_settings['user_table'] )?$this->class_settings['user_table']:''; //Currently logged in user id
				$form->upid = isset( $this->class_settings['user_plugin'] )?$this->class_settings['user_plugin']:''; //Currently logged in user id
				$form->uid = isset( $this->class_settings['user_id'] )?$this->class_settings['user_id']:''; //Currently logged in user id
				$form->pid = isset( $this->class_settings['priv_id'] ) ? $this->class_settings['priv_id'] : ''; //Currently logged in user privilege
				
				if( isset( $this->class_settings[ 'searching' ] ) && $this->class_settings[ 'searching' ] ){
					$form->searching = $this->class_settings[ 'searching' ];
				}

				if( isset( $this->class_settings[ 'table_temp' ] ) )
					$form->table_temp = $this->class_settings[ 'table_temp' ];
				
				if( isset( $this->class_settings[ 'attributes' ] ) )
					$form->attributes = $this->class_settings[ 'attributes' ];
				/**************************************************************************/

				//2. Transform posted form data into array
						// print_r( $_POST );
				$field_values_pair = $form->myphp_post( $fields, $options );
						// print_r( $field_values_pair );exit;
				$this->class_settings['more_form_data'] = $form->more_data;
				
				if( isset( $this->class_settings['return_form_data_only'] ) && $this->class_settings['return_form_data_only'] ){
					if( isset($field_values_pair) && is_array($field_values_pair) ){
						return $field_values_pair;
					}
				}
				
				// print_r( $field_values_pair );exit;
				if( ! ( isset($field_values_pair) && is_array($field_values_pair) ) ){
					if($field_values_pair == '-1'){
						//RETURN INVALID TOKEN ERROR
						$err = new cError('000002');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
						$err->method_in_class_that_triggered_error = '_save';
						$err->additional_details_of_error = 'invalid token on line 325 during transformation';
							
						return $err->error();
					}else{
						//RETURN ERROR IN SUBMITTED DATA STRUCTURE
						$err = new cError('000101');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
						$err->method_in_class_that_triggered_error = '_save';
						$err->additional_details_of_error = $form->error_msg_title;
						
						return $err->error();
					}
				}
				
				$new_record_created = 0;
				//4. Pick current record id
				$this->current_record_id = $form->record_id;
				
				//5. Insert array into database
				if( isset($field_values_pair) && is_array($field_values_pair) ){
					//6. Update existing record
					if($field_values_pair['update']){
						
						$settings_array = array(
							'database_name' => $this->class_settings['database_name'] ,
							'database_connection' => $this->class_settings['database_connection'] ,
							'table_name' => $tb ,
							'field_and_values' => $field_values_pair['form_data'] ,
							'where_fields' => 'id' ,
							'where_values' => $field_values_pair['id'] ,
						);
						
						if( isset( $this->class_settings["action_called"] ) && $this->class_settings["action_called"] ){
							$settings_array["action_to_perform"] = $this->class_settings["action_called"];
						}
						
						if( isset( $this->class_settings["parent_tb"] ) && $this->class_settings["parent_tb"] ){
							$settings_array["parent_tb"] = $this->class_settings["parent_tb"];
						}
						
						if( isset( $this->class_settings["data_connection"] ) && $this->class_settings["data_connection"] ){
							$settings_array["data_connection"] = $this->class_settings["data_connection"];
						}
						
						// print_r( $this->class_settings["plugin"] );exit;
						if( isset( $this->class_settings["plugin"] ) && $this->class_settings["plugin"] ){
							$settings_array["plugin"] = $this->class_settings["plugin"];
						}
						
						//13-mar-23: pass old values
						if( isset( $field_values_pair["old_values"] ) && $field_values_pair["old_values"] ){
							$settings_array["old_values"] = $field_values_pair["old_values"];
						}
						
						// print_r( $settings_array );exit;
						$save = update( $settings_array );
						
						if($save){
							//Auditor
							//auditor( $this->class_settings , 'modify' , $this->class_settings[ 'database_table' ] , 'updated record with id '.$this->current_record_id.' in the table with values ' );
						}else{
							//RETURN ERROR IN RECORD UPDATE PROCESS
							$err = new cError('000006');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
							$err->method_in_class_that_triggered_error = '_save';
							$err->additional_details_of_error = 'could not update record on line 338';
			
							return $err->error();
						}
					//7. Create new record
					}else{
						$settings_array = array(
							'database_name' => $this->class_settings['database_name'] ,
							'database_connection' => $this->class_settings['database_connection'] ,
							'table_name' => $tb ,
							'field_and_values' => $field_values_pair['form_data'] ,
						);
						
						if( isset( $this->class_settings["action_called"] ) && $this->class_settings["action_called"] ){
							$settings_array["action_to_perform"] = $this->class_settings["action_called"];
						}
						
						if( isset( $this->class_settings["parent_tb"] ) && $this->class_settings["parent_tb"] ){
							$settings_array["parent_tb"] = $this->class_settings["parent_tb"];
						}
						
						if( isset( $this->class_settings["data_connection"] ) && $this->class_settings["data_connection"] ){
							$settings_array["data_connection"] = $this->class_settings["data_connection"];
						}
						
						if( isset( $this->class_settings["plugin"] ) && $this->class_settings["plugin"] ){
							$settings_array["plugin"] = $this->class_settings["plugin"];
						}
						
						if( isset( $this->class_settings["use_replace"] ) && $this->class_settings["use_replace"] ){
							$settings_array["use_replace"] = $this->class_settings["use_replace"];
						}
						
						// print_r( $settings_array );exit;
						$save = create( $settings_array );
						
						if($save){
							$new_record_created = 1;
							
							//Auditor
							//auditor( $this->class_settings, 'insert' , $this->class_settings[ 'database_table' ] , 'added new record with id '.$this->current_record_id.' into the table' );
						}else{
							//RETURN ERROR IN RECORD CREATION PROCESS
							$err = new cError('000007');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
							$err->method_in_class_that_triggered_error = '_save';
							$err->additional_details_of_error = 'could not create record on line 356';
							
							return $err->error();
						}
					}
				}else{
					
					if($field_values_pair == '-1'){
						//RETURN INVALID TOKEN ERROR
						$err = new cError('000002');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
						$err->method_in_class_that_triggered_error = '_save';
						$err->additional_details_of_error = 'invalid token on line 325 during transformation';
							
						return $err->error();
					}else{
						//RETURN ERROR IN SUBMITTED DATA STRUCTURE
						$err = new cError('000101');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
						$err->method_in_class_that_triggered_error = '_save';
						$err->additional_details_of_error = $form->error_msg_title;
						
						return $err->error();
					}
				}
			}else{
				//RETURN ERROR IN SUBMITTED DATA STRUCTURE
				$err = new cError('000101');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
				$err->method_in_class_that_triggered_error = '_save';
				$err->additional_details_of_error = '<h4>Undefined Database Table</h4>Form was not processed';
				
				return $err->error();
			}
			
			if($save){
				//13-mar-23: clear checksum
				if( isset( $form ) && isset( $field_values_pair["nw_checksum"] ) && $field_values_pair["nw_checksum"] ){
					$form->clear_checksum( $field_values_pair );
				}
				
				//RETURN SUCCESS NOTIFICATION
				$err = new cError('010002');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = $this->class_settings[ 'database_table' ];
				$err->method_in_class_that_triggered_error = '_save';
				$err->additional_details_of_error = 'successful write operation to database';
					
				$returning = $err->error();
				$returning['saved_record_id'] = $this->current_record_id;
				$returning['new_record_created'] = $new_record_created;
				
				return $returning;
			}
			
			return $save;
		}
		
	}
?>