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
			case 'delete_records':
				$returned_value = $this->_delete_records();
			break;
			case 'restore_records':
				$returned_value = $this->_restore_records();
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
			
			if( isset( $this->class_settings[ 'form_values' ] ) && is_array( $this->class_settings[ 'form_values' ] ) )
				$values = $this->class_settings[ 'form_values' ];
			else
				$values = array();
				
			if( ( isset($_POST['mod']) && $_POST['mod']=='edit-'.md5( $this->class_settings[ 'database_table' ] ) && isset($_POST['id']) ) || ( isset( $_GET[ 'id' ] ) && $_GET[ 'id' ] ) ){
				
				if( isset( $_GET[ 'id' ] ) && $_GET[ 'id' ] && ( ! ( isset( $_POST[ 'id' ] ) )  ) ){
					$_POST[ 'id' ] = $_GET[ 'id' ];
				}
				
				$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`" . $this->class_settings[ 'database_table' ] . "` WHERE `".$this->class_settings[ 'database_table' ]."`.`id`='".$_POST['id']."'";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query,
					'query_type' => 'SELECT',
					'set_memcache' => 1,
					'tables' => array( $this->class_settings[ 'database_table' ] ),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if(isset($sql_result[0])){
					$values = $sql_result[0];
					
					if( isset( $_GET[ 'id' ] ) && $_GET[ 'id' ] ){
						$values[ 'id' ] = '';
						$values[ 0 ] = '';
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
			
			//2. PREPARE FORM OPTIONS
			//GET ALL FIELDS IN TABLE
			$fields = array();
			$query = "DESCRIBE `" . $this->class_settings['database_name'] . "`.`" . $this->class_settings[ 'database_table' ] . "`";
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query ,
				'query_type' => 'DESCRIBE' ,
				'set_memcache' => 1 ,
				'tables' => array( $this->class_settings[ 'database_table' ] ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				
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
			
			$file_url = $this->class_settings[ 'calling_page' ] . $this->directory_of_process_handlers . $this->class_settings[ 'database_table' ] . '/data_capture_form_process_before.php';
			if( file_exists( $file_url ) ){
				include $file_url;
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
			
			$form->butclear = 0;
				
			//$form->form_class = 'form-horizontal';
			
			if( isset( $this->class_settings[ 'form_submit_button' ] ) )
				$form->submit = $this->class_settings[ 'form_submit_button' ];
			
			if( isset( $this->class_settings[ 'form_clear_button' ] ) ){
				$form->clear = $this->class_settings[ 'form_clear_button' ];
                $form->butclear = 1;
			}
			$form->but_theme = 'b';
			
			if( isset( $this->class_settings[ 'form_html_id' ] ) )
				$form->html_id = $this->class_settings[ 'form_html_id' ];
			
			if( isset( $this->class_settings[ 'form_class' ] ) )
				$form->form_class = $this->class_settings[ 'form_class' ];
			
			//Determine whether or not to hide / show agreement button
			if( isset( $this->class_settings[ 'agreement_text' ] ) && $this->class_settings[ 'agreement_text' ] ){
				$form->show_agreement = 1;
				$form->agreement_text = $this->class_settings[ 'agreement_text' ];
			}
			
			//Determine whether or not to hide / show recaptcha
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
			if( isset( $this->class_settings[ 'hidden_records' ] ) )
				$form->hide_record = $this->class_settings[ 'hidden_records' ];
			
			//Determine if not to generate specific form elements
			if( isset( $this->class_settings[ 'hidden_records_function' ] ) )
				$form->hidden_records_function = $this->class_settings[ 'hidden_records_function' ];
			
			//Determine if Search Operation is being Executed
			if( isset( $this->class_settings[ 'searching' ] ) )
				$form->searching = $this->class_settings[ 'searching' ];
			
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
			
			//Determine whether to enforce max value limits
			if( isset( $this->class_settings[ 'form_maximum_value_limit' ] ) )
				$form->form_maximum_value_limit = $this->class_settings[ 'form_maximum_value_limit' ];
			
			if( isset( $this->class_settings['inline_edit_form'] ) )
				$form->inline_edit_form = $this->class_settings['inline_edit_form'];
			
			if( isset( $this->class_settings['hide_form_labels'] ) )
				$form->hide_form_labels = $this->class_settings['hide_form_labels'];
			
			if( isset( $this->class_settings['skip_form_field_validation'] ) )
				$form->skip_form_field_validation = $this->class_settings['skip_form_field_validation'];
			
			$form->select_box_opions_type = 1;	//Serial option to populate select boxes
			/**************************************************************************/
			
			$returning_html_data .= $form->myphp_form( $fields , $values , 'no.ofcolumns: default = 1' , $option , $values );
			
			if( ! isset( $this->class_settings['user_email'] ) )
				$this->class_settings['user_email'] = '';
				
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
			$file_url = $this->class_settings[ 'calling_page' ] . $this->directory_of_process_handlers . $this->class_settings[ 'database_table' ] . '/delete_process_before.php';
			if( file_exists( $file_url ) ){
				include $file_url;
			}
			
			$returning_html_data = '';
			
			$controller_table = '';
			if( isset( $this->class_settings[ 'database_controller_table' ] ) )
				$controller_table = $this->class_settings[ 'database_controller_table' ];
			
			if( isset($_POST['mod']) && ( $_POST['mod']=='delete-'.md5( $this->class_settings[ 'database_table' ] ) || $_POST['mod']=='delete-'.md5( $controller_table )  ) && ( isset( $_POST['id'] ) || isset($_POST['ids']) ) ){
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
								
								if($select_clause_for_query)$select_clause_for_query .= " OR `" . $this->class_settings[ 'database_table' ] . "`.`id`='".$ids."'";
								else $select_clause_for_query = "`" . $this->class_settings[ 'database_table' ] . "`.`id`='".$ids."'";
							}
						}
					}
				}
				
				if( ! ($fields_to_delete && $values_to_delete) ){
					$fields_to_delete = 'id';
					$values_to_delete = $_POST['id'];
					
					$select_clause_for_query = "`" . $this->class_settings[ 'database_table' ] . "`.`id`='".$_POST['id']."'"; 
				}
				
				//delete items
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $this->class_settings[ 'database_table' ] ,
					'field_and_values' => array(
						'record_status' => array(
							'value' => '0',
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
				);
				$save = update( $settings_array );
				
				if($save){
					//Auditor
					//auditor( $this->class_settings , 'delete' , $this->class_settings[ 'database_table' ] , 'deleted record with '.$fields_to_delete.' '.$values_to_delete.' in the table' );
					
					//Process to Execute After Successful Delete Process
					$file_url = $this->class_settings[ 'calling_page' ] . $this->directory_of_process_handlers . $this->class_settings[ 'database_table' ] . '/delete_process_success.php';
					if( file_exists( $file_url ) ){
						include $file_url;
					}
					
					//Return Successful write operation to database
					$err = new cError('010001');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cregistered_users.php';
					$err->method_in_class_that_triggered_error = '_delete';
					$err->additional_details_of_error = 'updated record with '.$fields_to_delete.' '.$values_to_delete.' on line 284';
					
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
			
			//Process to Execute After Failed Delete Process
			$file_url = $this->class_settings[ 'calling_page' ] . $this->directory_of_process_handlers . $this->class_settings[ 'database_table' ] . '/delete_process_failed.php';
			if( file_exists( $file_url ) ){
				include $file_url;
			}
			
			//Return unsuccessful update operation
			$err = new cError('000006');
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'cProcess_handler.php';
			$err->method_in_class_that_triggered_error = '_delete_records';
			if( isset( $fields_to_delete ) && isset( $values_to_delete ) ){
				$err->additional_details_of_error = 'could not update record on line 284 with fields ' . $fields_to_delete . ' and values '.$values_to_delete;
			}else{
				$err->additional_details_of_error = '$_POST variable not set, thus could not update record on line 284';
			}
			return $err->error();
		}
		
		private function _restore_records(){
			//Process to Execute Prior to Restore Process
			$file_url = $this->class_settings[ 'calling_page' ] . $this->directory_of_process_handlers . $this->class_settings[ 'database_table' ] . '/restore_process_before.php';
			if( file_exists( $file_url ) ){
				include $file_url;
			}
			
			$returning_html_data = '';
			
			$controller_table = '';
			if( isset( $this->class_settings[ 'database_controller_table' ] ) )
				$controller_table = $this->class_settings[ 'database_controller_table' ];
			
			if( isset($_POST['mod']) && ( $_POST['mod']=='restore-'.md5( $this->class_settings[ 'database_table' ] ) || $_POST['mod']=='restore-'.md5( $controller_table )  ) && ( isset($_POST['id']) || isset($_POST['ids']) ) ){
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
								
								if($fields_to_delete)$fields_to_delete .= ',ID';
								else $fields_to_delete = 'ID';
								
								if($select_clause_for_query)$select_clause_for_query .= " OR `" . $this->class_settings[ 'database_table' ] . "`.`ID`='".$ids."'";
								else $select_clause_for_query = "`" . $this->class_settings[ 'database_table' ] . "`.`ID`='".$ids."'";
							}
						}
					}
				}
				
				if( ! ($fields_to_delete && $values_to_delete) ){
					$fields_to_delete = 'ID';
					$values_to_delete = $_POST['id'];
					
					$select_clause_for_query = "`" . $this->class_settings[ 'database_table' ] . "`.`ID`='".$_POST['id']."'"; 
				}
				
				//delete items
				$settings_array = array(
					'database_name' => $this->class_settings['database_name'] ,
					'database_connection' => $this->class_settings['database_connection'] ,
					'table_name' => $this->class_settings[ 'database_table' ] ,
					'field_and_values' => array(
						'record_status' => array(
							'value' => '1',
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
				);
				$save = update( $settings_array );
				
				
				if($save){
					//Auditor
					//auditor( $this->class_settings , 'restore' , $this->class_settings[ 'database_table' ] , 'restored record with '.$fields_to_delete.' '.$values_to_delete.' in the table' );
					
					//Process to Execute After Successful Delete Process
					$file_url = $this->class_settings[ 'calling_page' ] . $this->directory_of_process_handlers . $this->class_settings[ 'database_table' ] . '/restore_process_success.php';
					if( file_exists( $file_url ) ){
						include $file_url;
					}
					
					//Return Successful write operation to database
					$err = new cError('010001');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cProcess_handler.php';
					$err->method_in_class_that_triggered_error = '_restore_records';
					$err->additional_details_of_error = 'updated record with '.$fields_to_delete.' '.$values_to_delete.' on line 284';
					
					return $err->error();
				}
			}
			
			//Process to Execute After Failed Delete Process
			$file_url = $this->class_settings[ 'calling_page' ] . $this->directory_of_process_handlers . $this->class_settings[ 'database_table' ] . '/restore_process_failed.php';
			if( file_exists( $file_url ) ){
				include $file_url;
			}
			
			//Return unsuccessful update operation
			$err = new cError('000006');
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'cProcess_handler.php';
			$err->method_in_class_that_triggered_error = '_restore_records';
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
			//SET TABLE
			$save = 0;
			
			/**************************************************************************/
			/*****************RECIEVE USER INPUT FROM FILLED FORM**********************/
			/**************************************************************************/
			//1. Determine if form data was submitted
			if(isset($_POST['table']) && $_POST['table'] == $this->class_settings[ 'database_table' ] ){
				//GET ALL FIELDS IN TABLE
				$fields = array();
				$query = "DESCRIBE `" . $this->class_settings['database_name'] . "`.`" . $this->class_settings[ 'database_table' ] . "`";
				$query_settings = array(
					'database' => $this->class_settings['database_name'] ,
					'connect' => $this->class_settings['database_connection'] ,
					'query' => $query ,
					'query_type' => 'DESCRIBE',
					'set_memcache' => 1,
					'tables' => array( $this->class_settings[ 'database_table' ] ),
				);
				$sql_result = execute_sql_query( $query_settings );
				
				
				if($sql_result && is_array($sql_result)){
					foreach($sql_result as $sval)
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
				
				/**************************************************************************/
				/**************************SELECT FORM GENERATOR***************************/
				/**************************************************************************/
				$form = new cForms();
				$form->setDatabase( $this->class_settings['database_connection'] , $this->class_settings[ 'database_table' ] );
				$form->setFormActionMethod('','post');
				$form->uid = $this->class_settings['user_id']; //Currently logged in user id
				$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
				
				if( isset( $this->class_settings[ 'attributes' ] ) )
					$form->attributes = $this->class_settings[ 'attributes' ];
				/**************************************************************************/
			
				//2. Transform posted form data into array
				$field_values_pair = $form->myphp_post($fields);
				
				if( isset( $this->class_settings['return_form_data_only'] ) && $this->class_settings['return_form_data_only'] ){
					if( isset($field_values_pair) && is_array($field_values_pair) ){
						return $field_values_pair;
					}else{
						if($field_values_pair == '-1'){
							//RETURN INVALID TOKEN ERROR
							$err = new cError('000002');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cregistered_users.php';
							$err->method_in_class_that_triggered_error = '_save';
							$err->additional_details_of_error = 'invalid token on line 325 during transformation';
								
							return $err->error();
						}else{
							//RETURN ERROR IN SUBMITTED DATA STRUCTURE
							$err = new cError('000101');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cregistered_users.php';
							$err->method_in_class_that_triggered_error = '_save';
							$err->additional_details_of_error = $form->error_msg_title;
							
							return $err->error();
						}
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
							'table_name' => $this->class_settings[ 'database_table' ] ,
							'field_and_values' => $field_values_pair['form_data'] ,
							'where_fields' => 'id' ,
							'where_values' => $field_values_pair['id'] ,
						);
						$save = update( $settings_array );
						
						if($save){
							//Auditor
							//auditor( $this->class_settings , 'modify' , $this->class_settings[ 'database_table' ] , 'updated record with id '.$this->current_record_id.' in the table with values ' );
						}else{
							//RETURN ERROR IN RECORD UPDATE PROCESS
							$err = new cError('000006');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cregistered_users.php';
							$err->method_in_class_that_triggered_error = '_save';
							$err->additional_details_of_error = 'could not update record on line 338';
			
							return $err->error();
						}
					//7. Create new record
					}else{
						$settings_array = array(
							'database_name' => $this->class_settings['database_name'] ,
							'database_connection' => $this->class_settings['database_connection'] ,
							'table_name' => $this->class_settings[ 'database_table' ] ,
							'field_and_values' => $field_values_pair['form_data'] ,
						);
						$save = create( $settings_array );
						
						if($save){
							$new_record_created = 1;
							
							//Auditor
							//auditor( $this->class_settings, 'insert' , $this->class_settings[ 'database_table' ] , 'added new record with id '.$this->current_record_id.' into the table' );
						}else{
							//RETURN ERROR IN RECORD CREATION PROCESS
							$err = new cError('000007');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cregistered_users.php';
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
						
						$err->class_that_triggered_error = 'cregistered_users.php';
						$err->method_in_class_that_triggered_error = '_save';
						$err->additional_details_of_error = 'invalid token on line 325 during transformation';
							
						return $err->error();
					}else{
						//RETURN ERROR IN SUBMITTED DATA STRUCTURE
						$err = new cError('000101');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cregistered_users.php';
						$err->method_in_class_that_triggered_error = '_save';
						$err->additional_details_of_error = $form->error_msg_title;
						
						return $err->error();
					}
				}
			}
			
			if($save){
				//RETURN SUCCESS NOTIFICATION
				$err = new cError('010002');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cregistered_users.php';
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