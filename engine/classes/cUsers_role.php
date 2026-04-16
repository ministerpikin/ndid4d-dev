<?php
	/**
	 * User Roles Class
	 *
	 * @used in  				User Roles Function
	 * @created  				10:11 | 09-07-2013
	 * @database table name   	users_roles
	 */

	/*
	|--------------------------------------------------------------------------
	| Manages Users Roles and Privileges in Users Role Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cUsers_role{
		public $calling_page = '../';
		
		public $class_name = 'users_role';
		public $database_connection = '';
		public $database_name = '';
		public $action_to_perform = '';
		
		public $user = '';
		public $user_id = '';
		public $priv_id = '';
		
		private $finish_msg = '';
		private $record_id = '';
		private $form_action_todo = 'save';
		
		public $form_action = '';
		public $form_html_id = '';
		public $form_submit_button = 'Save';
		
		private $table = 'users_role';
		public $current_module = '';
		
		//Used to set options of base method
		private $temporary_options_for_current_method = array();
		
		function users_role(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			//CHECK FOR CURRENT MODULE
			if(isset($_GET['module']))$this->current_module = $_GET['module'];
			
			//CHECK IF STATE OF VERIFIED AND UNVERIFIED RECORDS DISPLAY
			if(isset($_SESSION['current_state'])){
				foreach($_SESSION['current_state'] as $k_sess => $v_sess){
					$result_of_sql_queryetain_state[$k_sess] = $v_sess;
				}
			}
			
			//CLEAR TEMPORARY SETTINGS
			unset($_SESSION['current_state']);
				
			switch ($this->action_to_perform){
			case 'new':
			case 'edit':
				//CHECK VERIFIED AND UNVERIFIED DISPLAY STATUS
				if(isset($result_of_sql_queryetain_state)){
					foreach($result_of_sql_queryetain_state as $k_ret => $v_ret){
						if(in_array($k_ret, array('show_creator', 'show_my_records', 'show_modifier'))){
							$_SESSION['current_state'][$k_ret] = $v_ret;
							$this->temporary_options_for_current_method[$k_ret] = $v_ret;
						}
					}
				}
				
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'view':
				$this->temporary_options_for_current_method['show_modifier'] = 1;
				
				$_SESSION['current_state']['show_modifier'] = 1;
				
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$this->temporary_options_for_current_method['show_modifier'] = 1;
				
				$_SESSION['current_state']['show_modifier'] = 1;
				
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				//CHECK VERIFIED AND UNVERIFIED DISPLAY STATUS
				if(isset($result_of_sql_queryetain_state)){
					foreach($result_of_sql_queryetain_state as $k_ret => $v_ret){
						if(in_array($k_ret, array('show_creator', 'show_my_records', 'show_modifier'))){
							$_SESSION['current_state'][$k_ret] = $v_ret;
							$this->temporary_options_for_current_method[$k_ret] = $v_ret;
						}
					}
				}
				
				$returned_value = $this->_save_changes();
				
				//Add ID of Newly Created Record
				if( $this->record_id ){
					$returned_value['saved_record_id'] = $this->record_id;
				}
			break;
			case 'import_excel_table':
				//CHECK VERIFIED AND UNVERIFIED DISPLAY STATUS
				if(isset($result_of_sql_queryetain_state)){
					foreach($result_of_sql_queryetain_state as $k_ret => $v_ret){
						if(in_array($k_ret, array('show_creator', 'show_my_records', 'show_action_buttons'))){
							$_SESSION['current_state'][$k_ret] = $v_ret;
							$this->temporary_options_for_current_method[$k_ret] = $v_ret;
						}
					}
				}
				
				$returned_value = $this->_generate_excel_table_import_form();
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = '';
			
			//Check if edit mode is on
			$values = array();
			
			
			if( isset($_GET['id']) && $_GET['id'] && (! isset($_POST['id']) ) ){
				$_POST['id'] = $_GET['id'];
			}
			
			if( isset($_POST['id']) ){
				$query = "SELECT * FROM `".$this->database_name."`.`".$this->table."` WHERE `".$this->table."`.`id`='".$_POST['id']."'";
				$query_settings = array(
					'database'=>$this->database_name,
					'connect'=>$this->database_connection,
					'query'=>$query,
					'query_type'=>'SELECT',
					'set_memcache'=>1,
					'tables'=>array($this->table),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if(isset($sql_result[0])){
					$values = $sql_result[0];
				}else{
					if(  isset($_POST['mod']) && $_POST['mod']=='edit-'.md5($this->table) ){
						//REPORT INVALID TABLE ERROR
						$err = new cError('000001');
						$err->action_to_perform = 'notify';
						$err->class_that_triggered_error = 'cUsers_role.php';
						$err->method_in_class_that_triggered_error = '_generate_new_data_capture_form';
						$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 127';
						
						return $err->error();
					}
				}
				
				switch( $this->action_to_perform ){
				case "new":
					$values[0] = '';
				break;
				}
			}
			
			//1. SET HEADING TITLE
			$form_heading_caption_title = 'Users Role Manager';
			$returning_html_data .= get_add_new_record_form_heading_title($form_heading_caption_title);
			
			//2. PREPARE FORM OPTIONS
			//GET ALL FIELDS IN TABLE
			$fields = array();
			$query = "DESCRIBE `".$this->database_name."`.`".$this->table."`";
			$query_settings = array(
				'database'=>$this->database_name,
				'connect'=>$this->database_connection,
				'query'=>$query,
				'query_type'=>'DESCRIBE',
				'set_memcache'=>1,
				'tables'=>array($this->table),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval)
					$fields[] = $sval[0];
			}else{
				//REPORT INVALID TABLE ERROR
				$err = new cError('000001');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cUsers_role.php';
				$err->method_in_class_that_triggered_error = '_generate_new_data_capture_form';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 126';
				return $err->error();
			}
			
			$option = array();
			
			
			//SET FORM SELECT COMBO VALUES
			$option[2] = convert_array_to_key_value_pair_for_selectbox("get_accessible_functions");
			
			$hidden_records = array();
			
			//3. INHERIT FORM CLASS TO GENERATE FORM
			/**************************************************************************/
			/**************************SELECT FORM GENERATOR***************************/
			/**************************************************************************/
			$form = new cForms();
			$form->setDatabase($this->database_connection,$this->table);
			$form->setFormActionMethod('?action='.$this->table.'&todo='.$this->form_action_todo,'post');
			$form->uid = $this->user_id; //Currently logged in user id
			$form->pid = $this->priv_id; //Currently logged in user privilege
			$form->maxstep = 1;
			$form->step = 1;
			
			$form->butclear = 0;
			$form->submit = 'Save';
			$form->but_theme = 'b';
			
			$form->placeholder = get_placeholders($this->table);
			//$form->tooltips = get_tooltips($this->table);
			
			$form->hide_record = $hidden_records;
			
			//4. RETURN GENERATED FORM
			$returning_html_data .= $form->myphp_form($fields,$values,'no.ofcolumns: default = 1',$option);
			
			return array('html' => $returning_html_data);
		}
		
		private function _delete_records(){
			$returning_html_data = '';
			
			
			if( isset($_POST['mod']) && $_POST['mod']=='delete-'.md5($this->table) && ( isset($_POST['id']) || isset($_POST['ids']) ) ){
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
								
								if($select_clause_for_query)$select_clause_for_query .= " OR `".$this->table."`.`ID`='".$ids."'";
								else $select_clause_for_query = "`".$this->table."`.`ID`='".$ids."'";
							}
						}
					}
				}
				
				if( ! ($fields_to_delete && $values_to_delete) ){
					$fields_to_delete = 'ID';
					$values_to_delete = $_POST['id'];
					
					$select_clause_for_query = "`".$this->table."`.`ID`='".$_POST['id']."'"; 
				}
				
				//delete items
				$save = update( $this->database_name, $this->table, $this->database_connection, 'record_status,modification_date,modified_by', '0<>'.date('U').'<>'.$this->user_id, $fields_to_delete, $values_to_delete, $condition );
				
				if($save){
					//Auditor
					auditor($this->calling_page,$this->priv_id,$this->user_id,$this->user,$this->database_connection,$this->database_name,'delete',$this->table, 'deleted record with '.$fields_to_delete.' '.$values_to_delete.' in the table');
				
					//Return Successful write operation to database
					$err = new cError('010001');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cUsers_role.php';
					$err->method_in_class_that_triggered_error = '_delete';
					$err->additional_details_of_error = 'updated record with '.$fields_to_delete.' '.$values_to_delete.' on line 265';
					
					return $err->error();
				}
			}
			
			//Return unsuccessful update operation
			$err = new cError('000006');
			$err->action_to_perform = 'notify';
			
			$err->class_that_triggered_error = 'cUsers_role.php';
			$err->method_in_class_that_triggered_error = '_delete';
			$err->additional_details_of_error = 'could not update record on line 265';
			return $err->error();
		}
		
		private function _save_changes(){
			//SET TABLE
			$save = 0;
			
			/**************************************************************************/
			/*****************RECIEVE USER INPUT FROM FILLED FORM**********************/
			/**************************************************************************/
			//1. Determine if form data was submitted
			if(isset($_POST['table']) && $_POST['table']==$this->table){
				//GET ALL FIELDS IN TABLE
				$fields = array();
				$query = "DESCRIBE `".$this->database_name."`.`".$this->table."`";
				$query_settings = array(
					'database'=>$this->database_name,
					'connect'=>$this->database_connection,
					'query'=>$query,
					'query_type'=>'DESCRIBE',
					'set_memcache'=>1,
					'tables'=>array($this->table),
				);
				$sql_result = execute_sql_query($query_settings);
				
				if($sql_result && is_array($sql_result)){
					foreach($sql_result as $sval)
						$fields[] = $sval[0];
				}else{
					//REPORT INVALID TABLE ERROR
					$err = new cError('000001');
					$err->action_to_perform = 'notify';
					
					$err->class_that_triggered_error = 'cUsers_role.php';
					$err->method_in_class_that_triggered_error = '_save';
					$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 230';
					return $err->error();
				}
				
				/**************************************************************************/
				/**************************SELECT FORM GENERATOR***************************/
				/**************************************************************************/
				$form = new cForms();
				$form->setDatabase($this->database_connection,$this->table);
				$form->setFormActionMethod('','post');
				$form->uid = $this->user_id; //Currently logged in user id
				$form->pid = $this->priv_id; //Currently logged in user privilege
				$form->maxstep = 1;
				$form->step = 1;
				$form->salter = $this->websalter;	//Set Password Salter
				
				/**************************************************************************/
			
				//2. Transform posted form data into array
				$field_values_pair = $form->myphp_post($fields);
				
				//3. Update the current step
				$form->step = $form->nextstep;
				
				//4. Pick current record id
				$this->record_id = $form->record_id;
				$values[0] = $form->record_id;
				
				//5. Insert array into database
				if(isset($field_values_pair) && is_array($field_values_pair)){
					//6. Update existing record
					if($field_values_pair['update']){
						$save = update($this->database_name,$this->table,$this->database_connection,$field_values_pair['field'],$field_values_pair['value'],'id',$field_values_pair['id']);
						
						if($save){
							//Auditor
							auditor($this->calling_page,$this->priv_id,$this->user_id,$this->user,$this->database_connection,$this->database_name,'modify',$this->table,'updated record with id '.$this->record_id.' in the table with values '.$field_values_pair['value']);
						}else{
							//RETURN ERROR IN RECORD UPDATE PROCESS
							$err = new cError('000006');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cUsers_role.php';
							$err->method_in_class_that_triggered_error = '_save';
							$err->additional_details_of_error = 'could not update record on line 283';
			
							return $err->error();
						}
					//7. Create new record
					}else{
						$save = create($this->database_name,$this->table,$this->database_connection,$field_values_pair['field'],$field_values_pair['value']);
						
						if($save){
							//Auditor
							auditor($this->calling_page,$this->priv_id,$this->user_id,$this->user,$this->database_connection,$this->database_name,'insert',$this->table,'added new record with id '.$this->record_id.' into the table');
						}else{
							//RETURN ERROR IN RECORD CREATION PROCESS
							$err = new cError('000007');
							$err->action_to_perform = 'notify';
							
							$err->class_that_triggered_error = 'cUsers_role.php';
							$err->method_in_class_that_triggered_error = '_save';
							$err->additional_details_of_error = 'could not create record on line 301';
							
							return $err->error();
						}
					}
				}else{
					if($field_values_pair == '-1'){
						//RETURN INVALID TOKEN ERROR
						$err = new cError('000002');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cUsers_role.php';
						$err->method_in_class_that_triggered_error = '_save';
						$err->additional_details_of_error = 'invalid token on line 270 during transformation';
							
						return $err->error();
					}else{
						//RETURN ERROR IN SUBMITTED DATA STRUCTURE
						$err = new cError('000101');
						$err->action_to_perform = 'notify';
						
						$err->class_that_triggered_error = 'cUsers_role.php';
						$err->method_in_class_that_triggered_error = '_save';
						$err->additional_details_of_error = 'invalid data submitted via data capture form';
						
						return $err->error();
					}
				}
			}
			
			if($save){
				//RETURN SUCCESS NOTIFICATION
				$err = new cError('010002');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cUsers_role.php';
				$err->method_in_class_that_triggered_error = '_save';
				$err->additional_details_of_error = 'successful write operation to database';
					
				return $err->error();
			}
			
			return $save;
		}
		
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
			$fields = array();
			$query = "DESCRIBE `".$this->database_name."`.`".$this->table."`";
			$query_settings = array(
				'database'=>$this->database_name,
				'connect'=>$this->database_connection,
				'query'=>$query,
				'query_type'=>'DESCRIBE',
				'set_memcache'=>1,
				'tables'=>array($this->table),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval)
					$fields[] = $sval;
			}else{
				//REPORT INVALID TABLE ERROR
				$err = new cError('000001');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cUsers_role.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 362';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase($this->database_connection,$this->table,$this->database_name);
			$form->uid = $this->user_id; //Currently logged in user id
			$form->pid = $this->priv_id; //Currently logged in user privilege
			
			$form->datatables_settings = array(
				'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
					'show_add_new' => 1,			//Determines whether or not to show add new record button
					'show_advance_search' => 0,		//Determines whether or not to show advance search button
					'show_column_selector' => 0,	//Determines whether or not to show column selector button
					'show_units_converter' => 0,	//Determines whether or not to show units converter
					'show_import_excel_table' => 1,		//Determines wether or not to show import excel table button
						'show_units_converter_volume' => 0,
						'show_units_converter_currency' => 0,
						'show_units_converter_currency_per_unit_kvalue' => 0,
						'show_units_converter_kvalue' => 0,
						'show_units_converter_time' => 0,
						'show_units_converter_pressure' => 0,
					'show_edit_button' => 1,		//Determines whether or not to show edit button
					'show_delete_button' => 1,		//Determines whether or not to show delete button
					
				'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
					'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
				
				'show_details' => 1,				//Determines whether or not to show details
				'show_serial_number' => 1,			//Determines whether or not to show serial number
				
				'show_verification_status' => 0,	//Determines whether or not to show verification status
				'show_creator' => 0,				//Determines whether or not to show record creator
				'show_modifier' => 1,				//Determines whether or not to show record modifier
				'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
				
				'current_module_id' => $this->current_module,	//Set id of the currently viewed module
			);
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array('html' => $returning_html_data);
		}
		
		
		private function _generate_excel_table_import_form(){
			$returning_html_data = '';
			
			//1. SET HEADING TITLE
			$form_heading_caption_title = 'Import Excel Data';
			$returning_html_data .= get_add_new_record_form_heading_title($form_heading_caption_title);
			
			//GET ALL FIELDS IN TABLE
			$fields = array();
			$option = array();
			$values = array();
			
			$fields = array(
				'ID',
				'USERS_ROLE1000_DT10_DT1_DT6',
			);
			
			$this->form_hidden_records = array(
				1=>1,
			);
			
			$this->form_hidden_records_css = array();
			
			$this->form_submit_button = 'Import File';
			
			$this->form_action_todo = 'save_imported_excel_data';
			$this->form_action = '?action=myexcel&todo='.$this->form_action_todo;
			
			//Get form action
			if(!$this->form_action)
				$this->form_action = '?action='.$this->table.'&todo='.$this->form_action_todo;
			
			//3. INHERIT FORM CLASS TO GENERATE FORM
			/**************************************************************************/
			/**************************SELECT FORM GENERATOR***************************/
			/**************************************************************************/
			$form = new cForms();
			$form->setDatabase($this->database_connection,$this->table);
			$form->setFormActionMethod($this->form_action,'post');
			$form->uid = $this->user_id; //Currently logged in user id
			$form->pid = $this->priv_id; //Currently logged in user privilege
			$form->maxstep = 1;
			$form->step = 1;
			
			$form->butclear = 0;
			$form->submit = $this->form_submit_button;
			$form->but_theme = 'b';
			$form->html_id = $this->form_html_id;
			
			$form->placeholder = get_placeholders($this->table);
			//$form->tooltips = get_tooltips('authentication');
			
			//4. RETURN GENERATED FORM
			$returning_html_data .= $form->myphp_form($fields,$values,'no.ofcolumns: default = 1',$option);
			
			return array('html' => $returning_html_data);
		}
		
	}
?>