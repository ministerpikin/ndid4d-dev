<?php
	/**
	 * Form Generating Class
	 *
	 * @used in  				Internally in all Classes
	 * @created  				-
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Forms / Datatable Generation Class
	|--------------------------------------------------------------------------
	|
	| Generates forms, dataTables by interpreting database table field names,
	| also process and transforms data of submitted forms.
	|
	*/
	
	class cForms{
		//Database name
		private $database = '';
		
		//Table name
		private $table = '';
		
		//Where form data will be sent to
		private $action = '';
		
		//The http method that will be used to send data
		private $method = 'post';
		
		//Password Salter
		public $salter = '';
		
		//Current user id
		public $uid = '';
		
		//Current user privilege id
		public $pid = '';
		
		//Current Step in a Stepwise process
		public $step = 1;
		
		//Next Step in a Stepwise process
		public $nextstep = 1;
		
		//Max step in a stepwise process
		public $maxstep = 1;
		
		//Return current record id
		public $record_id = '';
		
		//Return current state of operation record creation / update
		private $update_state = 0;
		
		//All Main Level Categories
		public $all_categories = "";
		
		//All Sub Ccategories
		public $all_cat = "";
		
		//Values of columns that contain FK id of other record
		public $id2val = '';
		
		//Determine if required box will be displayed next to label or input element
		//# $label=0 ;display beside input element
		//# $label=1 ;display beside label
		private $label = 1;
		
		//Categories selection key and value pair
		private $catkey;
		private $catval;
		private $catend;
		
		//Define current unique class
		private $lbl = '';
		
		//Determine if the agrement button should be displayed
		public $show_agreement = 0;
		
		public $show_recaptcha = 0;
		
		//Determine if the budget balance should be displayed
		public $show_budget_balance = array();
		
		//Agreement text
		public $agreement_text = '';
		
		//Determine if the edit button should be displayed
		public $show_edit = 1;
		
		//Determine if the edit button should be displayed
		public $show_delete = 1;
		
		//Determine the maximum nuber of columns to display
		public $grid_max_column = 0;
		
		//Default submit button text
		public $submit = 'Save';
		
		//Default clear button text
		public $clear = 'Clear';
		
		//jQuery Mobile Color theme for button
		public $but_theme= '';
		
		//show x buttons - 0 = hide, 1 = show button
		public $butsubmit = 1;
		public $butclear = 1;
		
		//determines the location of the calling page relative to index file
		public $calling_page = '../';
		
		//destination of edit and submit button
		public $editto = '';
		public $deleteto = '';
		public $result_of_sql_queryadioto = '';
		public $buttonto = '';
		
		//Tie the values of a radio button to particular field
		public $tie_radio = '';
		
		//Tie the values of a button to particular field
		public $tie_button = '';
		
		//upload directory
		public $upload_dir = "../";
		
		//Set Caption for Actions label
		public $action_lbl = 'Actions';
		
		//Set Minimum year for date selector
		public $date_min_year = 0;
		
		//Error message indicating what went wrong
		public $error_msg_title = '';
		public $error_msg_body = '';
		
		//Id incremental
		private $id_increment = 0;
		
		//display record but create new id upon saving
		public $oldid = 1;
		
		//hide record row completely
		public $hidden_records_function = '';
		
		//hide record row completely
		public $hide_record = array();
		
		//hide record row with css
		public $hide_record_css = array();
		
		//disable element with html
		public $disable_form_element = array();
		
		//Display non-editable value
		public $form_display_not_editable_value = array();
		
		//Include max value limit on field
		public $form_maximum_value_limit = array();
		
		//Include special element class
		public $special_element_class = array();
		
		//Set FORGOT PASSWORD link
		public $forgot_password_link = '';
		
		//Set HTML ID of Form
		public $html_id = '';
		
		//Set Form Class
		public $form_class = '';
		
		//Generate inline edit form for datatables
		public $inline_edit_form = 0;
		public $hide_form_labels = 0;
		
		//Determine if form being generated will be use for searching
		public $searching = 0;
		
		//Determine the rule that will be applied in populating select boxes options	[1 = 'serial' , 0 = 'index_number']
		public $select_box_opions_type = 0;
		
		//Table Field Temp Name
		public $table_field_temp = '';
		
		//Determine wether form field validation should be skipped
		public $skip_form_field_validation = false;
		
		//Hold All Variables Used to Set DataTables Values
		public $datatables_settings = array(
			'show_toolbar' => 0,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
				
				'show_navigation_pane' => 0,		//Determines whether or not show navigation pane
				
				'select_audit_trail' => 0,		//Determines whether or not show select audit trail
				
				'show_add_new' => 0,			//Determines whether or not to show add new record button
				'show_import_excel_table' => 0,		//Determines wether or not to show import excel table button
				
				'show_add_new_memo_report_letter' => 0,			//Determines whether or not to show add new memo, report, letter button 
				'show_add_new_scanned_file' => 0,			//Determines whether or not to show add new scanned file
				'show_add_new_label' => 0,			//Determines whether or not to show add new label
				'show_advance_search' => 0,		//Determines whether or not to show advance search button
				'show_column_selector' => 0,	//Determines whether or not to show column selector button
				'show_units_converter' => 0,	//Determines whether or not to show units converter
					'show_units_converter_volume' => 1,
					'show_units_converter_currency' => 1,
					'show_units_converter_currency_per_unit_kvalue' => 1,
					'show_units_converter_kvalue' => 1,
					'show_units_converter_time' => 1,
					'show_units_converter_pressure' => 1,
					
					'show_units_converter_volume_per_day' => 1,
					'show_units_converter_heating_value' => 1,
					
				'show_records_view_options_selector' => 0,	//Determines whether or not to custom record view options selector
				'array_of_view_options' => array(),	//View options list
				
				'show_get_images_button' => 0,
				'show_synchronization_button' => 0,
				
				'show_attach_files_to_gas_sales_agreements' => 0,		//Determines whether or not to show attach files to gas sales agreements
				'show_edit_password_button' => 0,		//Determines whether or not to show edit password button
				
				'show_edit_button' => 0,		//Determines whether or not to show edit button
				'user_can_edit' => 0,		//Determines whether or not a user can edit a record
				
				'show_delete_button' => 0,		//Determines whether or not to show delete button
				'show_status_update' => 0,		//Determines whether or not to show status update button
				'show_record_assign' => 0,		//Determines whether or not to show status record assign button
				
				'show_delete_forever' => 0,		//Determines whether or not to show delete forever button
				'show_restore_button' => 0,		//Determines whether or not to show restore selected button
				
				'show_generate_report' => 0,	//Determines whether or not to show restore selected button
				
			'show_timeline' => 0,			//Determines whether or not to show timeline will be shown
				'timestamp_action' => '',	//Set Action of Timestamp
				
			'show_details' => 0,				//Determines whether or not to show details
			'show_serial_number' => 0,			//Determines whether or not to show serial number
			
			'show_verification_status' => 0,	//Determines whether or not to show verification status
			'show_creator' => 0,				//Determines whether or not to show record creator
			'show_modifier' => 0,				//Determines whether or not to show record modifier
			'show_action_buttons' => 1,			//Determines whether or not to show record action buttons
			
			'table_class' => 'display-no-scroll',			//Set id of the currently viewed module
			'current_module_id' => '',			//Set id of the currently viewed module
			
			'multiple_table_header' => 0,		//Determine whether or not dataTable will have multiple table header
			'multiple_table_header_cells' => '', //Table Cells for multiple header columns
		);
		
		//Set Search Conditions
		/*
		<option value="LIKE %...%">LIKE %...%</option>
		<option value="NOT LIKE">NOT LIKE</option>
		<option value="LIKE">LIKE</option>
		*/
		public $search_conditions = '
			<option value="=">EQUALS (=)</option>
			<option value="!=">NOT EQUALS (!=)</option>
			<option value=">">GREATER THAN (>)</option>
			<option value="<">LESS THAN (<)</option>';
		
		public $text_fields_search_conditions = '
			<option value="=">EQUALS (=)</option>
			<option value="!=">NOT EQUALS (!=)</option>
			<option value="REGEXP">CONTAINS</option>
			<option value="NOT REGEXP">DOES NOT CONTAIN</option>';
		
		//Password Confirmation
		private $password_confirmation = '';
		
		public $attributes = array();
		
		public $form_extra_field_data = array();
		public $form_extra_field_elements = "";
		
		function setDatabase($database_connection,$table,$database=""){
			//SET DATABASE
			$this->database = $database;
			//SET DATABASE
			$this->database_connection = $database_connection;
			//SET TABLE
			$this->table = $table;
		}
		
		function setFormActionMethod($action,$method){
			//SET FORM ACTION
			$this->action_to_perform = $action;
			//SET FORM METHOD
			$this->method = $method;
		}
		
		private function textarea( $field_name , $ctrl='' , $serial_number , $field_details ){
			//CREATES A TEXTAREA ELEMENT
			//$t = DATATYPE - integer; FUNCTION - defines the tab index as well as the element name
			//$ctrl = DATATYPE - string; FUNCTION - sets the default value of the element
			
			//Set unique id for elements
			if($this->id_increment == 0)$input_id = 'kd';
			else $input_id = 'kd'.$this->id_increment;
			
			++$this->id_increment;
			
			$required = ( ( isset( $field_details[ 'required_field' ]) && $field_details[ 'required_field' ] == 'yes' ) ? 'form-element-required-field ' : '' );
			
			if( $required ){
				if( isset( $field_details['attributes'] ) )$field_details['attributes'] .= ' required="required" ';
				else $field_details['attributes'] = ' required="required" ';
			}
			$returning_html_data =  '<textarea data-mini="true" tabindex="' . $serial_number . '" class="form-control form-gen-element '.(isset($this->special_element_class[$field_name])?$this->special_element_class[ $field_name ]:'').' '.$this->lbl.' ' . $required . ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' ) .'" type="text" name="' . $field_name . '" rows="1" cols="26" tip="' . ( isset ( $field_details['tooltip'] ) ? $field_details['tooltip'] : '' ) . '" placeholder="' . ( isset ( $field_details['placeholder'] ) ? $field_details['placeholder'] : '' ) . '" '.(isset($this->disable_form_element[ $field_name ])?$this->disable_form_element[ $field_name ]:'').' ' . ( isset ( $field_details['attributes'] ) ? $field_details['attributes'] : '' ) . ' alt1="' . $field_details['form_field'] . '" alt="textarea" data-type="textarea" >'.stripslashes($ctrl).'</textarea>';
			
			return $returning_html_data;
		}
		
		private function upload( $field_details , $field_id, $value, $serial_number ){
		
			//CREATES FILE UPLOAD BOX
			//$t = DATATYPE - integer; FUNCTION - defines the tab index as well as the element name
			//$ctrl = DATATYPE - string; FUNCTION - sets the default value of the element
			$returning_html_data =  '<input type="file" tabindex="'.$serial_number.'" class="form-gen-element '. ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' ) .' '.$this->lbl.'" name="'.$field_id.'" '.(isset($this->disable_form_element[$field_id])?$this->disable_form_element[$field_id]:'').' acceptable-files-format="'.(isset( $field_details['acceptable_files_format'] )?$field_details['acceptable_files_format']:'').'" ' . ( isset ( $field_details['attributes'] ) ? $field_details['attributes'] : '' ) . ' data-value="'.$value.'" />';
			
			return $returning_html_data;
		}
		
		private function group_boxes($select_rows,$n,$type,$t,$value='',$form=true){
			//FUNCTION USED TO DISPLAY GROUP OF CHECK BOXES / OPTION BUTTON
			//$select_rows = DATATYPE - array; FUNCTION - defines number of groups of boxes
			$typ = array('13'=>'radio','12'=>'checkbox');
			$returning_html_data = '<div class="box-wrap">';
			$group = null;
			
			if($value){
				$se = explode(';;',$value);
				foreach($se as $s){
					$sel = explode('::',$s);
					$n_r[$sel[0]] = $sel[1];
				}
			}
			$xx=0;
			foreach($select_rows as $v){
				$returning_html_data .= '<fieldset><legend>'.ucwords(str_replace('_',' ',$v)).'</legend>';
				
				if($form){
					foreach($n as $vv){
						//if(isset($n_r[$v]) && $n_r[$v]==code_privilege($vv))$chk = 'checked="checked"';
						$chk = '';
						$returning_html_data .= '<div class="'.$this->lbl.'-main"><div class="'.$this->lbl.'-sub">'.ucwords($vv).'</div>';
						$returning_html_data .= '<input type="'.$typ[$type].'" '.$chk.' class="form-gen-element '.$this->lbl.'" name="'.$v.'" value="'.$vv.'" /></div>';
					}
				}
				$returning_html_data .= '</fieldset>';
				
				if($group)$group .= '::'.$v;
				else $group .= $v;
			}
			if($form)$returning_html_data .= '<input type="hidden" name="q'.$t.'" value="'.$group.'" />';
			$returning_html_data .= '</div>';
			return $returning_html_data;
		}

		private function group_upload($select_rows,$n,$t,$value='',$form=true){
			//FUNCTION USED TO DISPLAY GROUP OF CHECK BOXES / OPTION BUTTON
			//$select_rows = DATATYPE - array; FUNCTION - defines number of groups of boxes

			$returning_html_data = '<div class="box-wrap">';
			$group = null;
			
			if($value){
				$se = explode(';;',$value);
				foreach($se as $s){
					$sel = explode('::',$s);
					$n_r[$sel[0]] = $sel[1];
				}
			}
			$xx=0;
			foreach($select_rows as $v){
				$returning_html_data .= '<fieldset><legend>'.ucwords(str_replace('_',' ',$v)).'</legend>';
				
				if($form){
					foreach($n as $vv){
						//if(isset($n_r[$v]) && $n_r[$v]==code_privilege($vv))$chk = 'checked="checked"';
						$chk = '';
						$returning_html_data .= $vv.'<input type="'.$typ[$type].'" '.$chk.' class="form-gen-element '.$this->lbl.'" name="'.$v.'" value="'.$vv.'" />';
					}
				}
				$returning_html_data .= '</fieldset>';
				
				if($group)$group .= '::'.$v;
				else $group .= $v;
			}
			if($form)$returning_html_data .= '<input type="hidden" name="q'.$t.'" value="'.$group.'" />';
			$returning_html_data .= '</div>';
			return $returning_html_data;
		}
		
		private function form_value( $val , $populate_form_with_values , $field_id , $form_details  ){
			//FUNCTION THAT DETERMINES WHETHER TO LOAD VALUES INTO A FORM OR NOT
			if($populate_form_with_values && isset($val) && $val!='b' && $val){
				
				switch( $form_details ){
				case 'date':
					return date( "Y-m-d" , doubleval( $val ) );
				break;
				case 'time':
					return format_time( $val );
				break;
				case 'old-password':
				case 'password':
					return '';
				break;
				}
				
				return stripslashes($val);	
			}
			
			if( ( ! $populate_form_with_values ) && isset( $_POST[ $field_id ] ) ){
				return $_POST[ $field_id ];
			}
		}
		
		private function date( $t , $value = '' , $field_details , $field_id ){
			$timestamp = doubleval( $value );
			
			//DISPLAY DATE SELECTOR
			if($value && is_numeric($value)){
				$date = date("j-M-Y",$value);
				$value = explode('-',$date);
			}else{
				$date = date("j-M-Y");
				$value = explode('-',$date);
			}
			
			//If Search Mode is active ensure first select element is null
			if($this->searching){
				$value[0] = "";
				$value[1] = "";
				$value[2] = "";
			}
			
			//$returning_html_data = '<div class="date">';
			$returning_html_data = '<fieldset data-role="controlgroup" data-type="horizontal" class="date" ' . ( isset ( $field_details['attributes'] ) ? $field_details['attributes'] : '' ) . '>';
			//$returning_html_data .= '<legend>Date</legend>';
				//$returning_html_data .= '<div class="date-lbl">Day</div>';
				for($x=1;$x<32;$x++)$key[] = $x;
				$returning_html_data .= $this->select( 9 , $key , $key , $field_id , 'cus88day' , $value[0] , '' , '' , '' , $t );
				//$returning_html_data .= '<div class="date-lbl">Month</div>';
				$key = explode("<>","jan<>feb<>mar<>apr<>may<>jun<>jul<>aug<>sep<>oct<>nov<>dec");
				$returning_html_data .= $this->select( 9 , $key , $key , $field_id , 'cus88month' , strtolower($value[1]), '' , '' , '' , $t );
				//$returning_html_data .= '<div class="date-lbl">Year</div>';
				$key = '';
				for($x=((date('Y')+30) - $this->date_min_year);$x>1900;$x--)$key[] = $x;
				$returning_html_data .= $this->select( 9 , $key , $key , $field_id , 'cus88year' , $value[2] , '' , '' , '' , $t );
				$returning_html_data .= '<input type="hidden" name="'.$field_id.'cus88timestamp" value="'.$timestamp.'" />';
				$returning_html_data .= '<input type="hidden" name="'.$field_id.'" value="date" />';
			//$returning_html_data .= '</div>';
			$returning_html_data .= '</fieldset>';
			return $returning_html_data;
		}
		
		private function category($ctrl_form,$t,$value='',$form=true){
			$returning_html_data = '<div class="select-category">';
				//GET FIRST LEVEL CATEGORY
				$x = 0;
				foreach($this->all_categories as $kk => $vv){
					$this->catkey[$x][] = $kk;
					$this->catval[$x][] = $vv;
					$this->catend[$x][] = '';
					//GET SUBSEQUENT LEVELS OF CATEGORY
					$x = $this->recurse_categories($kk,$x);				
				}
				
				foreach($this->catkey as $kk => $vv){
					$dis = 'base-level-category';
					$base = '';
					if($kk){
						$dis='subsequent-level-category';
						$base = 'category-level-holder';
					}
					
					$returning_html_data .= '<div class="'.$base.'">'.$this->select( 11 , $vv , $this->catval[$kk] , 3000 , '' , '' , $dis , 'category'.$kk,$this->catend[$kk] ).'</div>';
				}
				
				//CONFIRM SELECTED CATEGORY 
				$returning_html_data .= '<div id="end-category" class="category-level-holder"></div>';
				
				//HIDDEN FIELD CONTAINING SELECTED CATEGORY
				$returning_html_data .= '<input type="hidden" id="text-end-category" name="q'.$t.'" />';
			$returning_html_data .= '</div>';
			return $returning_html_data;
		}
		
		private function recurse_categories($key,$x){
			//GET SUBSEQUENT LEVELS OF CATEGORIES
			if(isset($this->all_cat[$key]) && is_array($this->all_cat[$key])){
				++$x;
				foreach($this->all_cat[$key] as $kk1 => $vv1){
					$k1[$x][] = $kk1;
					$v1[$x][] = $vv1;
					
					//GET SUBSEQUENT LEVELS OF CATEGORY
					if(isset($this->all_cat[$kk1]) && is_array($this->all_cat[$kk1])){
						$e1[$x][] = '';
						$x = $this->recurse_categories($kk1,$x);
					}else $e1[$x][] = 'end-category';
					//$this->catend[$kk1][] = 'end-category';
				}
				if(isset($k1[$x])){
				$this->catkey[$key] = $k1[$x];
				$this->catval[$key] = $v1[$x];
				$this->catend[$key] = $e1[$x];
				}
				return (--$x);
			}else{
				$this->catend[$key] = 'end-category';
			}		
		}
		
		private function select( $field_details , $key , $value , $field_id , $cus_name = '' , $val = '' , $display = '' , $elementid = '' , $end = '' , $serial_number ){
			//DISPLAY OPTIONS SELECT ELEMENT
			$returning_html_data = null;
			
			$ctrl_form = $field_details[ 'form_field' ];
			
			$array = '';
			$multi = null;
			
			//Multi-select Menu
			$data_role = '';
			
			$autocomplete_select = '';
			
			$select_option_tooltip = '';
			
			$array_of_accessible_functions_tooltips = array();
			
			if( count($key) > 16 ){
				//$data_role = 'data-role="none"';
				//$autocomplete_select = 'pautoselect';
			}
			
			$sel1 = array();
			if( $ctrl_form == 'multi-select' ){
				//Multi-select Menu
				$data_role = 'data-role="none"';
				
				$multi='multiple="multiple" size="11"'; 
				$array = '[]';
				
				if($val){
					$val = explode(':::',$val);
					foreach($val as $val1)$sel1[$val1] = 'selected="selected"';
				}
				
				$autocomplete_select = '';
				
				//Get Accessible Functions Tooltips
				
				switch( $this->table ){
				case "ACCESS_ROLE":
				case "access_role":
					$array_of_accessible_functions_tooltips = get_accessible_functions_tooltips();
				break;
				}
			}
			
			if($cus_name=='cus88day' || $cus_name=='cus88month' || $cus_name=='cus88year'){
				$data_native_menu = 'true';
				$autocomplete_select = '';
				$data_role = '';
			}else{
				$data_native_menu = 'false';
			}
			
			
			/*------------------------------------------*/
			//Remove after resolving pop-up issue in forms
			$data_native_menu = 'true';
			/*------------------------------------------*/
			
			$h_not_editable = '';
			
			$returning_html_data .= '<select '.$multi.' data-mini="true" data-native-menu="'.$data_native_menu.'" tabindex="' . $serial_number . '" class="form-gen-element form-control '.$autocomplete_select.' '.$display.' '.$this->lbl.' '.(isset($this->special_element_class[$field_id])?$this->special_element_class[ $field_id ]:'') . ' ' . ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' ) . ( ( isset( $field_details[ 'required_field' ]) && $field_details[ 'required_field' ] == 'yes' ) ? 'form-element-required-field" required="required' : '' ) . '" name="' . $field_id . $cus_name . $array . '" id="' . $field_id . $cus_name . $array . '" '.$data_role.' '.(isset($this->disable_form_element[ $field_id ])?$this->disable_form_element[ $field_id ]:'').' '.(isset($this->form_display_not_editable_value[ $field_id ])?'style="display:none;"':'').' ' . ( isset ( $field_details['attributes'] ) ? $field_details['attributes'] : '' ) . ' alt="' . $field_details['form_field'] . '" data-type="' . $field_details['form_field'] . '">';
				////if($multi)$returning_html_data .= '<option>'.ucwords(str_replace('-',' ',$this->lbl)).'</option>';
				
				if( isset( $field_details["form_field_options_group"] ) && function_exists( $field_details["form_field_options_group"] )  ){
					
					$options = $field_details["form_field_options_group"]();
					foreach( $options as $opt => $opts ){
						$returning_html_data .= '<optgroup label="'.$opt.'">';
						foreach( $opts as $k => $v ){
							$sel = '';
							if( ( $val == $k && $val != null ) || ( isset( $sel1[$k] ) && $sel1[$k] ) )
								$sel = 'selected="selected"';
							
							$returning_html_data .= '<option '.$sel.' value="'.$k.'">'.$v.'</option>';
						}
						$returning_html_data .= '</optgroup>';
					}
					$returning_html_data .= '</select>';
					return $returning_html_data;
				}
				
				//If Search Mode is active ensure first select element is null
				if($this->searching)$returning_html_data .= '<option value=""></option>';
				
				$n=0;
				if( is_array( $key ) && ! empty( $key ) ){
					foreach($key as $k){
					$select_option_tooltip = '';
					if( $ctrl_form == 'multi-select' ){
					
						if( isset($array_of_accessible_functions_tooltips) && is_array($array_of_accessible_functions_tooltips) && !empty($array_of_accessible_functions_tooltips) ){
							
							if( isset($array_of_accessible_functions_tooltips[ $k ]) && $array_of_accessible_functions_tooltips[ $k ] )
								$select_option_tooltip = 'tip="'. $array_of_accessible_functions_tooltips[ $k ] . '" class="select-box-tooltip-option"';
							
						}
						
					}
					
					if(($val==$k && $val!=null) || (isset($sel1[$k]) && $sel1[$k])){
						$sel = 'selected="selected"';
						
						if(isset($this->form_display_not_editable_value[ $field_id ])){
							//if($data_native_menu != 'true')
							$h_not_editable = '<label class="not-editable-form-element-value">'.ucwords(str_replace("_"," ",$value[$n])).'</label>';
						}
						
					}else $sel = null;
					
					$returning_html_data .= '<option '.$sel.' alt="'.(isset($end[$n])?$end[$n]:'').'" title="'. ucwords(str_replace("_"," ",$value[$n])) .'" '.$select_option_tooltip.' value="'.$k.'">'.ucwords(str_replace("_"," ",$value[$n])).'</option>';
					
					++$n;
				}
				}
			$returning_html_data .= '</select>';
			
			return $h_not_editable.$returning_html_data;
		}
		
		function myphp_form($fields,$values='',$columns=2,$options){
			//Search Combo Container
			$search_combo_option = '';
			$search_combo_option_text = '';
			
			//Set HTML ID of Form
			$html_id = $this->table;
			if($this->html_id)
				$html_id = $this->html_id;
				
			$h_content = '';
			
			$returning_html_data = '<div id="form-panel-wrapper">';
			
			if( $this->inline_edit_form ){
				$returning_html_data = '<div id="inline-edit-form-wrapper">';
			}
			
			
			$returning_html_data .= '<form name="'.$this->table.'" id="'.$html_id.'-form" method="'.$this->method.'" action="'.$this->action_to_perform.'" enctype="multipart/form-data" data-ajax="false" class="login-form '.$this->form_class.'">';
			//NB: $ctrl_form - ARRAY USED TO DETERMINE TYPE OF FORM COMPONENT TO BE PLACED
			//		$uid holds the key field of a record that should be displayed
			
			
			//GET ARRAY OF VALUES FOR FORM LABELS
			$database_table_field_intepretation_function_name = $this->table;
			
			if( $this->table_field_temp && function_exists( $database_table_field_intepretation_function_name . $this->table_field_temp ) )
				$database_table_field_intepretation_function_name .= $this->table_field_temp;
			
			if(isset($this->datatables_settings['multiple_table_header']) && $this->datatables_settings['multiple_table_header'] ){
				//CHECK FOR MULTI-ROW TABLE HEADER
				$func = $this->table.'_multi_table_header';
			}
			
			if( function_exists( $database_table_field_intepretation_function_name ) ){
				$form_label = $database_table_field_intepretation_function_name();
				
				//ALTERNATE FIELD CONTROLLER FUNCTION
				if( isset( $this->hidden_records_function ) && $this->hidden_records_function && function_exists( $this->hidden_records_function ) ){
					$function_name = $this->hidden_records_function;
					
					$form_label = $function_name();
				}
				
				//SET CONSTANTS		
				$returning_html_data .= '<input type="hidden" name="module" value="c'.ucfirst($this->table).'" />';
				$returning_html_data .= '<input type="hidden" name="table" value="'.$this->table.'" />';
				$returning_html_data .= '<input type="hidden" id="id" name="id" value="';
				if($this->oldid)$returning_html_data .= (isset($values['id'])?$values['id']:'');
				$returning_html_data .= '" />';
				$returning_html_data .= '<input type="hidden" id="uid" name="uid" value="'.$this->uid.'" />';
				$returning_html_data .= '<input type="hidden" name="stepmaxstep" value="'.$this->step.'::'.$this->maxstep.'" />';
				$returning_html_data .= '<input type="hidden" name="skip_validation" value="'.$this->skip_form_field_validation.'" />';
				$returning_html_data .= '<input type="hidden" id="user_priv" name="user_priv" value="'.$this->pid.'" />';
				
				if( isset( $_POST["tmp_id"] ) && $_POST["tmp_id"] )
					$returning_html_data .= '<input type="hidden" name="tmp" value="'.$_POST["tmp_id"].'" />';
				
				if( isset( $this->form_extra_field_data ) && $this->form_extra_field_data )
					$returning_html_data .= '<input type="hidden" id="extra_fields" name="extra_fields" value="'.$this->form_extra_field_data.'" />';
				
				$or = explode('?',$_SERVER['REQUEST_URI']);
				if(isset($or[1]))
					$origin = '?'.$or[1];
				else
					$origin = '';
					
				$returning_html_data .= '<input type="hidden" id="orign" name="origin" value="'.$origin.'" />';
				
				//Generate Form Token
				$token = generate_token( 
					array(
						'table' => $this->table,
					) 
				);
				$returning_html_data .= '<input type="hidden" id="processing" name="processing" value="'.$token.'" />';
				
				$returning_html_data .= '<div class="form-body">';
				
				//$n=1;
				$t = 0;
				$stop = 1;
				
				$css_hide = '"';
				
				//USED TO DETERMINE IF FORM FILLED VALUE SHOULD BE FILLED
				if( is_array( $values ) && !empty( $values ) ){
					$populate_form_with_values = true;
					$aa = $values;
				}else{
					$populate_form_with_values = false;
				}
				
				foreach( $fields as $field_id ){
					$h_content_loop = '';
					
					//$field_id = $field_ids[0];
					
					$field_details = array();
					
					if( isset( $form_label[$field_id] ) && is_array( $form_label[$field_id] ) )
						$field_details = $form_label[ $field_id ];
					
					if( !empty( $field_details ) ){
						
						$this->lbl = $field_id;
						
						//Check if field is not hidden
						if(!(isset($this->hide_record[ $field_id ]) && $this->hide_record[ $field_id ])){
							
							//Check if field is hidden with css
							$css_hide = '"';
							if((isset($this->hide_record_css[ $field_id ]) && $this->hide_record_css[ $field_id ]))
								$css_hide = ' default-hidden-row" style="display:none;" ';
							
							//Check in search mode -- hide all fields with css
							if($this->searching){
								$css_hide = ' default-hidden-row" style="display:none;" ';
								
								if( isset( $field_details[ 'required_field' ] ) )
									unset( $field_details[ 'required_field' ] );
								
								if( isset( $field_details[ 'class' ] ) )
									unset( $field_details[ 'class' ] );
							}
							
							if( isset( $field_details['display_position'] ) || $this->searching){
								$h_content_loop .= '<div class="form-group control-group input-row '.( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' ).'-row '.($this->lbl).'-row'.$css_hide.'>';
								
								//Search Combo Options -- if field is not hidden
								if( $field_details['form_field']!='group-file' && $this->searching ){
									//DISPLAY LABEL
									$h_content_loop .= '<label class="control-label cell searching-label">';
										$h_content_loop .= 'Condition';
									$h_content_loop .= '</label>';
									
									//Display Search Condition Control
									
									$h_content_loop .= '<div class="controls cell-element ">';
										$h_content_loop .= '<select name="sq' . $field_id .'" class="form-control">';
											switch( $field_details['form_field'] ){
											case 'currency':
											case 'decimal':
											case 'time':
											case 'date-5':
											case 'date':
											case 'date_time':
											case 'number':
												$h_content_loop .= $this->search_conditions;
											break;
											default:
											case 'text':
											case 'tag':
                                            case 'calculated':
											case 'password':
											case 'checkbox':
											case 'radio':
											case 'hidden':
											case 'select':
											case 'multi-select':
											case 'textarea':
											case 'textarea-unlimited':
                                            case 'textarea-norestriction':
												$h_content_loop .= $this->text_fields_search_conditions;
											break;
											}
										$h_content_loop .= '</select>';
									$h_content_loop .= '</div>';
									
								}
								
								if( $field_details['form_field'] && $field_details['form_field']!='hidden' && !( $this->inline_edit_form || $this->hide_form_labels ) ){
									//DISPLAY LABEL
									$h_content_loop .= '<label class="control-label cell '.($this->lbl).'-label form-element-required-label-' . ( isset( $field_details[ 'required_field' ] )?$field_details[ 'required_field' ] : '' ) .'">';
										
										$this_field_label = $field_details[ 'field_label' ]; 
										$h_content_loop .=  $this_field_label;
										
										//Set Text to be displayed in search combo
										$search_combo_option_text = $this_field_label;
										
									$h_content_loop .= '</label>';
								}
								
								if( $field_details['form_field'] == 'text-file' || $field_details['form_field'] == 'calculated' ){
									$field_details['form_field'] = 'text';
									if( isset( $field_details['calculations']['form_field'] ) && $field_details['calculations']['form_field'] ){
										$field_details['form_field'] = $field_details['calculations']['form_field'];
									}	
								}
									
								//text,password,hidden,single radio, single check
								switch( $field_details['form_field'] ){
								case 'text':
								case 'time':
								case 'tag':
                                case 'calculated':
								case 'password':
								case 'old-password':
								case 'hidden':
								case 'checkbox':
								case 'radio':
								case 'email':
								case 'tel':
								case 'number':
								case 'currency':
								case 'decimal':
								case 'date-5':
								case 'date_time':
								case 'text-file':
									
									$input_value_step = 'step="any"';
									$max_length = 200;
									
									if( $field_details['form_field'] == 'number' ){
										$input_value_step = 'step="1"';
									}
									
									if( $field_details['form_field'] == 'currency' || $field_details['form_field'] == 'decimal' ){
										$field_details['form_field'] = 'number';
										$input_value_step = 'step="any"';
                                        
                                        if( isset( $aa[ $field_id ] ) && $aa[ $field_id ] ){
                                            $aa[ $field_id ] = convert_currency( $aa[ $field_id ] ,'' , 1 );
                                        }
                                        if( isset( $_POST[ $field_id ] ) && $_POST[ $field_id ] ){
                                            $_POST[ $field_id ] = convert_currency( $_POST[ $field_id ] ,'' , 1 );
                                        }
									}
									
									if( $field_details['form_field'] == 'old-password' ){
										$field_details['form_field'] = 'password';
									}
									
									if( $field_details['form_field'] == 'tag' ){
										$field_details['form_field'] = 'text';
									}
									
									if( $field_details['form_field'] == 'date-5' ){
										$field_details['form_field'] = 'date';
										
										$input_value_step = ' current-date="' . date("Y-M-d") . '"';
										
										if( isset( $field_details[ 'custom_data' ][ 'min-age-limit' ] ) && $field_details[ 'custom_data' ][ 'min-age-limit' ] ){
											$input_value_step .= ' min-year="'.$field_details[ 'custom_data' ][ 'min-age-limit' ].'" ';
										}
										
										if( isset( $field_details[ 'custom_data' ][ 'max-age-limit' ] ) && $field_details[ 'custom_data' ][ 'max-age-limit' ] ){
											$input_value_step .= ' max-year="'.$field_details[ 'custom_data' ][ 'min-age-limit' ].'" ';
										}
									}
									
								$h_content_loop .= '<div class="controls cell-element '.( ( isset( $field_details['icon'] ) && $field_details['icon'] )?'input-icon':'').'">';
									
									//Not Editable Value
									if( isset( $this->form_display_not_editable_value[ $field_id ] ) ){
										$h_content_loop .= '<label class="not-editable-form-element-value">'.$this->form_value( isset($aa[$t])?$aa[$t]:'b' , $populate_form_with_values , $field_id , $field_details['form_field'] ).'</label>';
									}else{
                                        if( isset( $field_details['icon'] ) )$h_content_loop .= $field_details['icon'];
                                        
										$h_content_loop .=  '<input value="'.$this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'b' , $populate_form_with_values , $field_id , $field_details['form_field'] ).'" tabindex="'.$t.'" class="form-control form-gen-element ' . $field_id . ' demo-input-local '.(isset($this->special_element_class[$field_id])?$this->special_element_class[ $field_id ]:' ').' ' . ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : ' ' ) . ( ( isset( $field_details[ 'required_field' ]) && $field_details[ 'required_field' ] == 'yes' ) ? ' form-element-required-field" required="required' : ' ' ) . '" type="' . $field_details['form_field'] . '" id="' . $field_id . '" name="' . $field_id . '" tip="' . ( isset ( $field_details['tooltip'] ) ? $field_details['tooltip'] : '' ) . '" placeholder="' . ( isset ( $field_details['placeholder'] ) ? $field_details['placeholder'] : '' ) . '"  max="'.(isset($this->form_maximum_value_limit[ $field_id ])?$this->form_maximum_value_limit[ $field_id ]:'').'" '.(isset($this->disable_form_element[ $field_id ])?$this->disable_form_element[ $field_id ]:'').' title="' . ( isset ( $field_details['title'] ) ? $field_details['title'] : '' ) . '" ' . ( isset ( $field_details['attributes'] ) ? $field_details['attributes'] : '' ) . ' alt="' . $field_details['form_field'] . '" data-type="' . $field_details['form_field'] . '" data-validate="' . $field_details['form_field'] . '" maxlength="'.$max_length.'" ' . $input_value_step . ' min="0" /><span class="input-status"></span>';
									
									}
								$h_content_loop .= '</div>';
								break;
								case 'date':
								case 'date_time':
								case 'date-time':
								$h_content_loop .= '<div class="controls cell-element">';
								
									$h_content_loop .= $this->date( $t, $this->form_value( isset($aa[$t])?$aa[$t]:'b' , $populate_form_with_values , $field_id , $field_details['form_field'] ) , $field_details , $field_id );
									
								$h_content_loop .= '</div><span class="input-status"></span>';
								break;
								case 'select':
								case 'multi-select':
								$h_content_loop .= '<div class="controls cell-element">';
									//Initialize key value pair
									$key = array();
									$value = array();
									
									if( isset( $field_details['form_field_options'] ) && $field_details['form_field_options'] ){
										$options = convert_array_to_key_value_pair_for_selectbox( $field_details['form_field_options'] );
										
										$key = $options[0];
										$value = $options[1];
									}
										
									$h_content_loop .= $this->select( $field_details , $key , $value , $field_id , '' , $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'b' , $populate_form_with_values , $field_id , $field_details['form_field'] ) , '' , '' , '' , $t );
										
								$h_content_loop .= '<span class="input-status"></span></div>';
								break;
								case 'file':
								$h_content_loop .= '<input alt="'.$field_details['form_field'].'" type="hidden" class="'.$field_id.'-replace" /><img id="'.$field_id.'-img" class="form-gen-element-image-upload-preview" style="display:none;" /><div class="controls cell-element upload-box " id="upload-box-'.$t.'">';
								//'. ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' ) .'
									$h_content_loop .= $this->upload( $field_details , $field_id , $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'b' , $populate_form_with_values , $field_id , $field_details['form_field'] ), $t );
								$h_content_loop .= '<span class="input-status"></span></div>';
								
								$h_content_loop .= '<div class="file-content">'.get_uploaded_files( $this->class_settings["calling_page"] , $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'b' , $populate_form_with_values , $field_id , $field_details['form_field'] ), $field_details[ 'field_label' ] , $field_id ).'</div>';
								break;
								case 'textarea':
								case 'textarea-unlimited':
								case 'textarea-norestriction':
								$h_content_loop .= '<div class="controls cell-element">';
									if(isset($this->form_display_not_editable_value[$t])){
										$h_content_loop .= '<label class="not-editable-form-element-value">'.$this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'b' , $populate_form_with_values , $field_id , $field_details['form_field'] ).'</label>';
									}else{
										$h_content_loop .= $this->textarea( $field_id , $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'b' , $populate_form_with_values , $field_id , $field_details['form_field'] ) , $t , $field_details );
									}
									
								$h_content_loop .= '<span class="input-status"></span></div>';
								break;
								case 'group-radio':
								case 'group-checkbox':
								$h_content_loop .= '<div class="controls cell-element">';
									$all_tables = $options[$t][0];
									$boxes = $options[$t][1];
									
									$h_content_loop .= $this->group_boxes($all_tables,$boxes,$field_details['form_field'],$t,$this->form_value(isset($aa[$t])?$aa[$t]:'b' , $populate_form_with_values , $field_id , $field_details['form_field'] ));
									
								$h_content_loop .= '<span class="input-status"></span></div>';
								break;
								case 'category':
								$h_content_loop .= '<div class="controls cell-element">';
									$h_content_loop .= $this->category($field_details['form_field'],$t,$this->form_value( isset($aa[$t])?$aa[$t]:'b' ,$populate_form_with_values , $field_id , $field_details['form_field'] ));
								$h_content_loop .= '</div>';
								break;
								case 'group-file':
								$h_content_loop .= '<div class="controls cell-element">';
									$h_content_loop .= $this->group_upload('','','',$t);
								$h_content_loop .= '<span class="input-status"></span></div>';
								break;
								}
								
								//Search Combo Options -- if field is not hidden
								if($field_details['form_field']!='file' && $field_details['form_field']!='group-file' && $this->searching){
									$search_combo_option .= '<option value="'.($this->lbl).'-row">'.$search_combo_option_text.'</option>';
								}
								
								//CLOSE THE ROW
								$h_content_loop .=  '</div>';
								
							}
							
						}
							
						
					}
					++$t;
					
					if( isset( $this->attributes["show_date_range"][ $field_id ] ) && $this->attributes["show_date_range"][ $field_id ] ){
						//duplicate field
						$duplicate = str_replace( $field_id, $field_id . "_range", $h_content_loop );
						$h_content_loop = str_replace( $this_field_label, "Start " . $this_field_label, $h_content_loop );
						$duplicate = str_replace( $this_field_label, "End " . $this_field_label, $duplicate );
						$h_content_loop .= $duplicate;
					}
					
					$h_content .= $h_content_loop;
				}	
					
					//RECAPTCHA BUTTON
					if( $this->show_recaptcha ){
						$h_content .= '<div class="input-row recaptcha">';
							$h_content .= '<div class="cell cell-recaptcha">';
								$publickey = '6LdxqewSAAAAAL6H03WfZsUmD_ztU00cSxmxHLLm';
								
								$h_content .= recaptcha_get_html($publickey,'',true);
								$h_content .= '<input type="hidden" name="test-recaptcha" value="yes" />';
							$h_content .= '</div>';
						$h_content .= '</div>';
					}
				
					//AGREEMENT BUTTON
					if( $this->show_agreement ){
						$h_content .= '<div class="input-row agreement" style="float:left; width:100%; margin-bottom:20px;">';
							
							$h_content .= '<div class="cell">';
								$h_content .= '<label class="checkbox" for="agreement-checkbox">';
                                $h_content .= '<input type="checkbox" tabindex="'.($t+1).'" class="form-gen-agreement pull-left" id="agreement-checkbox" name="agreement" value="agreement" />';
								$h_content .= '<input type="hidden" name="test-agreement" value="yes" />';
                                //$h_content .= '<div class="agreement-text checkbox">';
									$h_content .= $this->agreement_text;
								//$h_content .= '</div></label>';
								$h_content .= '</label>';
								
							$h_content .= '</div>';
						$h_content .= '</div>';
					}
					
					//SEARCH COMBO
					if( $this->searching ){
						
						//DISPLAY LABEL
						$returning_html_data .= '<div class="form-group control-group searching-row input-row">';
						
							$returning_html_data .= '<label class="control-label cell searching-label">';
								$returning_html_data .= 'Select Field(s)';
							$returning_html_data .= '</label>';
						
						
							$returning_html_data .= '<div class="controls cell">';
								$returning_html_data .= '<select name="search_field" id="search-field-select-combo" class="form-control">';
									$returning_html_data .= '<option>Select Field to Search</option>';
									$returning_html_data .= $search_combo_option;
								$returning_html_data .= '</select>';
							$returning_html_data .= '</div>';
						$returning_html_data .= '</div>';
					}
					
					//Allow Search Select Option Field to Remain at the top of the form
					$returning_html_data .= $h_content;
					
					//MULTIPLE SEARCH CONDITIONS
					if( $this->searching ){
						//DISPLAY LABEL
						$returning_html_data .= '<div class="form-group control-group searching-row input-row">';
						
							$returning_html_data .= '<label class="control-label cell searching-label">';
								$returning_html_data .= 'Single Search Condition';
							$returning_html_data .= '</label>';
						
							$returning_html_data .= '<div class="controls cell">';
								$returning_html_data .= '<select name="single_search_condition" class="form-control">';
									$returning_html_data .= '<option value="AND">AND</option>';
									$returning_html_data .= '<option value="OR">OR</option>';
								$returning_html_data .= '</select>';
							$returning_html_data .= '</div>';
						$returning_html_data .= '</div>';
						
						$returning_html_data .= '<div class="form-group control-group searching-row input-row">';
							$returning_html_data .= '<label class="control-label cell searching-label">';
								$returning_html_data .= 'Multiple Search Condition';
							$returning_html_data .= '</label>';
						
							$returning_html_data .= '<div class="controls cell">';
								$returning_html_data .= '<select name="multiple_search_condition" class="form-control">';
									$returning_html_data .= '<option value="AND">AND</option>';
									$returning_html_data .= '<option value="OR">OR</option>';
								$returning_html_data .= '</select>';
							$returning_html_data .= '</div>';
						$returning_html_data .= '</div>';
					}
					
					//5. CREATE BUTTONS THAT WILL SUBMIT FORM AND CLEAR FIELDS
					//***EVENTS OF SUCH BUTTONS TO BE HANDLED BY FORM JQUERY CLASS
					if( $this->forgot_password_link ){
					$returning_html_data .= '<div class="control-group input-row forgot-password-row" >';
						$returning_html_data .= '<div class="cell small controls">';
							$returning_html_data .= $this->forgot_password_link;
						$returning_html_data .= '</div>';
					$returning_html_data .= '</div>';
					}
					
					
					if( $this->form_extra_field_elements )
						$returning_html_data .= $this->form_extra_field_elements;
					
					$returning_html_data .= '<div class="control-group input-row bottom-row" style="margin-bottom:20px; clear:both;">';
						$returning_html_data .= '<div class="controls cell">';
						if($this->butsubmit)$returning_html_data .= '<input tabindex="'.($t+2).'" id="form-gen-submit" data-loading-text="processing..." class="form-gen-button btn btn-primary blue data-theme="'.$this->but_theme.'" value="'.$this->submit.'" type="submit"/> ';
						
						if($this->searching)$this->clear .= ' Search Form';
						
						if($this->butclear || $this->searching)$returning_html_data .= '<input id="form-gen-clear" class="form-gen-button btn btn-primary" data-theme="'.$this->but_theme.'" value="'.$this->clear.'" type="reset" tabindex="'.($t+3).'"/>';
						$returning_html_data .= '</div>';
					$returning_html_data .= '</div>';
					
					//CLOSE THE BOX
					$returning_html_data .= '</div>';
				$returning_html_data .= '</form>';
				$returning_html_data .= '</div>';
					
			}
			
            
			return $returning_html_data;
		}
		
		function myphp_post($fields){
		
			if( isset( $_POST[ 'test-recaptcha' ] ) && $_POST[ 'test-recaptcha' ]=='yes' ){
				if( isset( $_POST["recaptcha_challenge_field"] ) && isset( $_POST["recaptcha_response_field"] ) ){
				  $privatekey = "6LdxqewSAAAAAOsV3q7nYIbLTX9J3C4F1vH_6Ll_";
				  $resp = recaptcha_check_answer ($privatekey,
												$_SERVER["REMOTE_ADDR"],
												$_POST["recaptcha_challenge_field"],
												$_POST["recaptcha_response_field"]);

				  if (!$resp->is_valid) {
					// What happens when the CAPTCHA was entered incorrectly
					//INVALID RECAPTCHA
					$this->error_msg_title = "The <b>reCAPTCHA</b> wasn't entered correctly. Refresh the reCAPTCHA and try it again.";
					$this->error_msg_body = "reCAPTCHA said: " . $resp->error;
					
					return false;
					//die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
						// "(reCAPTCHA said: " . $resp->error . ")");
				  } else {
					// Your code here to handle a successful verification
				  }
				}
			}
			
			//VALIDATE TOKEN
			if(isset($_SESSION['key'])){
				$frmtok = md5('form_token'.$_SESSION['key']);
				$skip_token = false;
                
                if( defined('SKIP_USE_OF_FORM_TOKEN') ){
                    $skip_token = true;
                }
                
				if( $skip_token || ( isset($_SESSION[$frmtok]) && is_array($_SESSION[$frmtok]) && isset($_POST['processing']) && in_array($_POST['processing'],$_SESSION[$frmtok]) ) ){
					
					//CLEAR TOKEN
                    if( ! $skip_token ){
                        foreach($_SESSION[$frmtok] as $ki => $vi){
                            if($_POST['processing']==$vi){
                                unset($_SESSION[$frmtok][$ki]);
                            }
                        }
					}
                    
					//RECONSTRUCT FORM DATA FROM TABLE NAME
					
					if( isset( $_POST[ 'test-agreement' ] ) && $_POST[ 'test-agreement' ]=='yes' ){
						if( ! ( isset( $_POST[ 'agreement' ] ) && $_POST[ 'agreement' ] == 'agreement' ) ){
							
							$this->error_msg_title = 'You must agree to the <b>Terms of Service</b>';
							$this->error_msg_body = 'Please ensure that you checked the agreement checkbox';
							
							return false;
						}
					}
					
					if( isset( $_POST[ 'skip_validation' ] ) && $_POST[ 'skip_validation' ] == 'true' ){
						$this->skip_form_field_validation = true;
					}
					
                    if( file_exists( $this->calling_page."classes/cSimple_image.php" ) )require_once( $this->calling_page."classes/cSimple_image.php" );
                    
					//GET FIELD NAMES
					$t = 0;
					$returning_html_data = null;
					$transformed_form_data = array();
					$vr = null;
					$sr = null;
					$update = false;
					
					$system_values = $this->system_values();
					
					//GET ARRAY OF VALUES FOR FORM LABELS
					$database_table_field_intepretation_function_name = $this->table;
					
					if( $this->table_field_temp && function_exists( $database_table_field_intepretation_function_name . $this->table_field_temp ) )
						$database_table_field_intepretation_function_name .= $this->table_field_temp;
					
					if( function_exists( $database_table_field_intepretation_function_name ) ){
						$form_label = $database_table_field_intepretation_function_name();
			
						if( is_array( $fields ) ){
							foreach( $fields as $field_id ){
								
								$field_details = array();
					
								if( isset( $form_label[$field_id] ) && is_array( $form_label[$field_id] ) )
									$field_details = $form_label[ $field_id ];
								
								if( ! empty( $field_details ) ){
									
									//GET POST KEY
									$key = $field_id;
									$validate_checker = '';
									
									//TEST POST VALUE				New AJAX Upload - $ctrl_form==10
									$up = $this->get_upload_session_id();
									//if( isset($_POST[$key]) || (isset($_FILES[$key]) && $_FILES[$key]) || ( isset( $field_details[ 'form_field' ] ) && $field_details[ 'form_field' ]=='file' ) ){
									if( isset($_POST[$key]) || (isset($_FILES[$key]) && $_FILES[$key]) || ( isset( $_SESSION[$up][$key] ) ) ){
										
										//PASS VALUE TO INSERT CLASS ARRAY
										$transformed_form_data[ $field_id ] = array(
											'search_condition' => 'LIKE',
										);
										
										//CHECK FOR SEARCH COMPARATOR
										if(isset($_POST['sq'.$key]) && $_POST['sq'.$key]){
											$transformed_form_data[ $field_id ]['search_condition'] = $_POST['sq'.$key];
										}
										
										switch( $field_details[ 'form_field' ] ){
										case 'calculated':
											if( isset( $field_details[ 'calculations' ][ 'form_field' ] ) ){
												$field_details[ 'form_field' ] = $field_details[ 'calculations' ][ 'form_field' ];
											}
										break;
										}
										
										//$vr .= '<>'.$this->user_defined_values($ctrl_form,$key);
										switch( $field_details[ 'form_field' ] ){
										case 'date':
										case 'date-5':
										case 'time':
										case 'date_time':
										case 'multi-select':
											$validate_checker = $this->validate( $this->user_defined_values( $field_details[ 'form_field' ] , $key ), $field_details );
										break;
										case 'file':
											
											$validate_checker = $this->validate( $this->user_defined_values( $field_details[ 'form_field' ] , $key ), $field_details );
											
											if( ! $validate_checker ){
												continue 2;
											}
										break;
										default:
											$validate_checker = $this->validate( $_POST[$key] , $field_details );
										break;
										}
										
										//TRIGGER ERROR
										if( ! $validate_checker && ( $this->error_msg_title && $this->error_msg_body ) && ! $this->searching ){
											return false;
										}
										
										$transformed_form_data[ $field_id ]['value'] = $validate_checker;
									}else{
										//PASS DEFAULT VALUE TO THE FIELD
										/*
										$transformed_form_data[ $field_id ] = array(
											'search_condition' => 'LIKE',
										);
										
										//CHECK FOR SEARCH COMPARATOR
										if(isset($_POST['s'.$key]) && $_POST['s'.$key])
											$transformed_form_data[ $field_id ]['search_condition'] = $_POST['sq'.$key];
												
										$transformed_form_data[ $field_id ]['value'] = '';
										*/
									}
								}else{
								//SYSTEM DEFINED VALUES
									//SKIP SYSTEM DEFINITION IF SEARCH MODE IS ON
									if($this->searching){
										$id = 'searching';
									}else{
										//SET OTHER SYSTEM VALUES
										if( isset( $system_values[ $field_id ] ) ){
											$vvv = $system_values[ $field_id ];
										}else{
											$vvv = 'undefined';
										}
										
										if($vvv || $system_values[ 'update' ] ){
											$transformed_form_data[ $field_id ] = array(
												'search_condition' => 'LIKE',
												'value' => $vvv,
											);
										}
									}
								}
								++$t;
								
							}
						}
						return array(
							'form_data' => $transformed_form_data,
							'update' => $system_values[ 'update' ],
							'id' => $system_values[ 'id' ],
						);
						
					}else{
						//SOMETHING WENT WRONG
						return false;
					}
				}else{
					//INVALID TOKEN
					return -1;
				}
			}
			
			
		}
		
		function myphp_DTtables( $fields ){
			//DISPLAY SELECTED RECORDS FROM A DATABASE QUERY IN TABULAR FORM
			$returning_html_data = '';
			
			$t = 'View ';
			
			$returning_html_data = '';
			$hide_show_col = '';
			$hsbc = '';	
			$header_left = '';
			$header_right = '';
			$header_bottom = '';
			
			//GET DETAILS OF CURRENTLY LOGGED IN USER
			$current_user_session_details = array();
			$current_user_session_details['id'] = '';
			$current_user_session_details['privilege'] = '';
			
			//Get user certificate session variable
			$current_user_details_session_key = md5('ucert'.$_SESSION['key']);
			if(isset($_SESSION[$current_user_details_session_key]))$current_user_session_details = $_SESSION[$current_user_details_session_key];
            
            if( isset( $current_user_session_details['role'] ) && isset( $current_user_session_details['id'] ) ){
                switch( strtolower( $current_user_session_details['role'] ) ){
                case 'seller':
                case 'buyer':
                case 'admin_seller':
                break;
                default:
                    //admin users
                    $this->admin_user = 1;
                break;
                }
            }
            
			//Check for New record privilege
			$classname = $this->table;
			
			$row_span = "";
			if(isset($this->datatables_settings['multiple_table_header']) && $this->datatables_settings['multiple_table_header'] && isset($this->datatables_settings['multiple_table_header_cells'])){
				$row_span = 'rowspan="'.$this->datatables_settings['multiple_table_header'].'"';
			}
			
			$table_name = $classname . "-datatable";
			$this->data_table_id = $table_name;
			$table_name_container = "dynamic";
			if( isset( $this->datatables_settings[ 'data_table_name' ] ) && $this->datatables_settings[ 'data_table_name' ] ){
				$table_name_container = $table_name . "-container";
			}
			$this->data_table_container = $table_name_container;
			
				$returning_html_data .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table table-striped table-bordered table-hover dataTable display '.( isset( $this->datatables_settings['table_class'] )?$this->datatables_settings['table_class']:'display-no-scroll' ).' '.$classname.'" id="'.$table_name.'" class-name="'.$classname.'">';
				$returning_html_data .= '<thead>';
				
				//CHECK WHETHER OR NOT TO SHOW DETAILS
				if(isset($this->datatables_settings['show_details']) && $this->datatables_settings['show_details']){
					$header_left .= '<th data-priority="persit" class="table-header remove-before-export" style=" max-width:65px;" '.$row_span.' >';
					$header_left .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					//$header_left .= '<div><a href="#" data-role="button" data-theme="e" data-icon="arrow-d" data-mini="true" data-iconpos="notext" data-inline="true" id="all-datatables-details" title="Click to View All Details" >v</a></div>';
					$header_left .= '</th>';
				}
				
				//CHECK WHETHER OR NOT TO SHOW SERIAL NUMBER
				if(isset($this->datatables_settings['show_serial_number']) && $this->datatables_settings['show_serial_number']){
					//Show Serial Number
					$header_left .= '<th data-priority="persit" class="table-header" style="min-width:40px; max-width:65px;" '.$row_span.'>';
					$header_left .= 'S/N';
					$header_left .= '</th>';
				}
				
				//$hide_show_col .= '<label data-corners="false"><input type="checkbox" name="sn_DT0" checked="checked">S/N</label>';
				
				$t = 1;
				$cols = 0;
				
				//GET ARRAY OF VALUES FOR FORM LABELS
				$database_table_field_intepretation_function_name = $this->table;
				if(isset($this->datatables_settings['real_table']) && $this->datatables_settings['real_table'])
                    $database_table_field_intepretation_function_name = $this->datatables_settings['real_table'];
                
				if(isset($this->datatables_settings['multiple_table_header']) && $this->datatables_settings['multiple_table_header'] ){
					//CHECK FOR MULTI-ROW TABLE HEADER
					$database_table_field_intepretation_function_name = $this->table.'_multi_table_header';
				}
				
				if( function_exists( $database_table_field_intepretation_function_name ) ){
					$form_label = $database_table_field_intepretation_function_name();
					
                    $fields = reorder_fields_based_on_serial_number( $fields , $form_label );
                    
					//ALTERNATE FIELD CONTROLLER FUNCTION
					if( isset( $this->datatables_settings['field_controller_function'] ) && $this->datatables_settings['field_controller_function'] && function_exists( $this->datatables_settings['field_controller_function'] ) ){
						$form_label = $this->datatables_settings['field_controller_function']();
					}
					
					foreach( $fields as $field_ids ){
						
						$field_id = $field_ids[0];
						
						$field_details = array();
						
						if( isset( $form_label[$field_id] ) && is_array( $form_label[$field_id] ) )
							$field_details = $form_label[ $field_id ];
						
						$show_field = false;
						
						switch($field_id){
						case 'created_by':
						case 'creation_date':
						case 'modified_by':
						case 'modification_date':
							//$show_field = false;
							$show_field = true;
						break;
						}
						
						if( ! empty( $field_details ) || $show_field ){
							
							if( ( isset( $field_details['display_position'] ) && ( $field_details['display_position'] == 'display-in-table-row' || ($field_details['display_position'] == 'display-in-admin-table' && isset($this->admin_user)) ) ) || $show_field ){
								
								if( isset($this->datatables_settings['multiple_table_header_columns_to_span']) && is_array($this->datatables_settings['multiple_table_header_columns_to_span']) && in_array( $field_id , $this->datatables_settings['multiple_table_header_columns_to_span']) ){
									
									$temp_header = '<th class="'.$field_id.'" '.$row_span.'>';
										
										$temp_header .=  $field_details['field_label'];
										
										$hide_show_col .= get_column_toggler_checkboxes( $field_id , $this->table , $this->datatables_settings['current_module_id'] , $field_details );
										
									$temp_header .= '</th>';
									
									if( isset( $this->datatables_settings[ 'multiple_table_header_columns_to_span_right' ] ) && is_array( $this->datatables_settings[ 'multiple_table_header_columns_to_span_right' ] ) && in_array( $field_id , $this->datatables_settings[ 'multiple_table_header_columns_to_span_right' ] ) ){
										$header_right .= $temp_header;
									}else{
										$header_left .= $temp_header;
									}
									
								}else{
										
									$header_bottom .= '<th class="'.$field_id.'">';
										
										if( isset( $field_details['field_label'] ) && $field_details['field_label'] ){
											$header_bottom .=  $field_details['field_label'];
											
											$hide_show_col .= get_column_toggler_checkboxes( $field_id , $this->table , $this->datatables_settings['current_module_id'] , $field_details );
										}else{
										
											$header_bottom .= ucwords( str_replace( '_', ' ', $field_id ) );
											
											$hide_show_col .= get_column_toggler_checkboxes( $field_id , $this->table , $this->datatables_settings['current_module_id'] , $field_details );
										}
										
									$header_bottom .= '</th>';
									
								}
							
								++$cols;
								
								if( isset( $field_details[ 'form_field' ] ) )
									$display[$t++] = $field_details[ 'form_field' ];
								else
									$display[$t++] = $field_id;
							}
							
						}
					}
					
				}
				
				//CHECK WHETHER OR NOT TO SHOW VERIFICATION STATUS
				if(isset($this->datatables_settings['show_verification_status']) && $this->datatables_settings['show_verification_status']){
					$header_right .= '<th data-priority="persit" class="header remove-before-export" style="min-width:30px;" '.$row_span.'>';
					$header_right .= 'Status';
					$header_right .= '</th>';
				}
				
				//DETERMINES WHETHER OR NOT TO SHOW RECORD CREATOR
				if(isset($this->datatables_settings['show_creator']) && $this->datatables_settings['show_creator']){
					$header_right .= '<th data-priority="persit" class="header remove-before-export" '.$row_span.'>';
					$header_right .= 'Created By';
					$header_right .= '</th>';
					$header_right .= '<th data-priority="persit" class="header remove-before-export" '.$row_span.'>';
					$header_right .= 'Created On';
					$header_right .= '</th>';
				}
				
				//DETERMINES WHETHER OR NOT TO SHOW RECORD MODIFIER
				if(isset($this->datatables_settings['show_modifier']) && $this->datatables_settings['show_modifier']){
					$header_right .= '<th data-priority="persit" class="header remove-before-export" '.$row_span.'>';
					$header_right .= 'Modified By';
					$header_right .= '</th>';
					$header_right .= '<th data-priority="persit" class="header remove-before-export" '.$row_span.'>';
					$header_right .= 'Modified On';
					$header_right .= '</th>';
				}
				
				//DETERMINES WHETHER OR NOT TO SHOW RECORD ACTION BUTTONS
				if( isset( $this->datatables_settings['show_action_buttons'] ) && $this->datatables_settings['show_action_buttons'] ){
					$header_right .= '<th data-priority="2" class="header remove-before-export" style="min-width:100px;" '.$row_span.'>';
						$header_right .= 'Actions';
					$header_right .= '</th>';
					
					//Set State to Hidden by Default
					$sq = md5('column_toggle'.$_SESSION['key']);
					$_SESSION[$sq][$this->table]['action_buttons'] = 1;
					$hide_show_col .= get_column_toggler_checkboxes('action_buttons',$this->table,$this->datatables_settings['current_module_id'],'Actions');
				}
				
				
				//CHECK FOR MULTIPLE TABLE HEADERS
				if(isset($this->datatables_settings['multiple_table_header']) && $this->datatables_settings['multiple_table_header'] && isset($this->datatables_settings['multiple_table_header_cells'])){
					
					if(($this->datatables_settings['multiple_table_header']-1)<2){
						$returning_html_data .= '<tr>';
							$returning_html_data .= $header_left.$this->datatables_settings['multiple_table_header_cells'].$header_right;
						$returning_html_data .= '</tr>';
					}else{
						if(is_array($this->datatables_settings['multiple_table_header_cells'])){
							$returning_html_data .= '<tr>';
								$returning_html_data .= $header_left.$this->datatables_settings['multiple_table_header_cells'][0].$header_right;
							$returning_html_data .= '</tr>';
							
							for($heading_row_count=1; $heading_row_count<count($this->datatables_settings['multiple_table_header_cells']);  $heading_row_count++){
								$returning_html_data .= '<tr>';
									$returning_html_data .= $this->datatables_settings['multiple_table_header_cells'][$heading_row_count];
								$returning_html_data .= '</tr>';
							}
						}
					}
					$returning_html_data .= '<tr>';
						$returning_html_data .= $header_bottom;
					$returning_html_data .= '</tr>';
				}else{
					$returning_html_data .= '<tr>';
						$returning_html_data .= $header_left.$header_bottom.$header_right;
					$returning_html_data .= '</tr>';
				}
				
				$returning_html_data .= '</thead>';
				
				$returning_html_data .= '<tbody>';
					$returning_html_data .= '<tr>';
						$returning_html_data .= '<td colspan="'.$cols.'" class="dataTables_empty">Loading data from server</td>';
					$returning_html_data .= '</tr>';
				$returning_html_data .= '</tbody>';
				$returning_html_data .= '</table>';
				
				$returning_html_data = '<div id="'.$table_name_container.'" class="dynamic" data-table="'.$classname.'">' . $this->toolbar( $hide_show_col ) . $returning_html_data;
				$returning_html_data .= '</div>';
				
			return $returning_html_data;
		}
		
		private function get_radio_value($val){
			//GET CURRENT VALUE OF OPTION BUTTON
			if($val==1){
				return 'checked="checked"';
			}else{
				return '';
			}
		}
		
		function toolbar($hide_show_col){
			$returning_html_data = '';
			
			//GET DETAILS OF CURRENTLY LOGGED IN USER
			$current_user_session_details = array();
			$current_user_session_details['id'] = '';
			$current_user_session_details['privilege'] = '';
			
			//Get user certificate session variable
			$current_user_details_session_key = md5('ucert'.$_SESSION['key']);
			if(isset($_SESSION[$current_user_details_session_key]))$current_user_session_details = $_SESSION[$current_user_details_session_key];
			
			//CHECK FOR BUTTONS ACTION PROCESSING CLASS
			$temp_table_name = $this->table;
			if( isset( $this->datatables_settings['buttons_action_processing_class'] ) && $this->datatables_settings['buttons_action_processing_class'] ){
				$this->table = $this->datatables_settings['buttons_action_processing_class'];
			}
			
			//Check for New record privilege
			$classname = $this->table;
			$allow_new = permission( $current_user_session_details , 'create_new_record' , $classname , $this->database_connection , $this->database );
			
			$allow_import_excel_table = permission($current_user_session_details,'import_excel_table',$classname,$this->database_connection,$this->database);
			
			$allow_editing_password = permission($current_user_session_details,'edit_password',$classname,$this->database_connection,$this->database);
			
			$allow_editing_records = 0;
			if(isset($this->datatables_settings['show_edit_button']) && $this->datatables_settings['show_edit_button']){
				$allow_editing_records = permission($current_user_session_details,'edit',$classname,$this->database_connection,$this->database);
				
				$this->datatables_settings['user_can_edit'] = $allow_editing_records;
			}
			
			$allow_deleting_records = permission($current_user_session_details,'delete',$classname,$this->database_connection,$this->database);
			
			$allow_restore_button = permission($current_user_session_details,'restore',$classname,$this->database_connection,$this->database);
			
			$allow_generate_report = permission($current_user_session_details,'generate_report',$classname,$this->database_connection,$this->database);
			
			//CHECK WHETHER OR NOT TO SHOW TOOLBAR
			if(isset($this->datatables_settings['show_toolbar']) && $this->datatables_settings['show_toolbar']){
					
				  $returning_html_data .= get_export_and_print_popup( "#".$this->data_table_id , "#".$this->data_table_container );
				  
				  //$returning_html_data .= '<a href="#" class="btn btn-mini default btn-sm btn-sm quick-print" target="#'.$this->data_table_container.'" target-table="#'.$this->data_table_id.'" merge-and-clean-data="true" title="Print"><i class="icon-print fa fa-print fa-fw"></i> Print</a>';
				  
				  if( isset($this->datatables_settings['show_summary_view']) && $this->datatables_settings['show_summary_view']){
					$returning_html_data .= '<button class="btn btn-mini btn-sm btn-sm" id="summary-view" title="Show Summary View" toggle-text="Details View"><i class="icon-minus"></i> Summary View</button>';
				  }
				  /*
				  $returning_html_data .= '<button href="#" class="btn btn-mini btn-sm dropdown-toggle" data-toggle="dropdown">';
					$returning_html_data .= '&nbsp;<span class="budgetet"></span>&nbsp;';
				  $returning_html_data .= '</a>';
				  $returning_html_data .= '<ul class="dropdown-menu">';
					$returning_html_data .= '<li>Settings</li>';
				  $returning_html_data .= '</ul>';
				  */
				$returning_html_data .= '</div>&nbsp;';
				
				//Toolbar
				$returning_html_data .= '<div data-role="controlgroup" class="btn-group" data-type="horizontal" data-mini="true">';	
					
					//CHECK WHETHER OR NOT TO SHOW ADD NEW RECORD BUTTON
					if(isset($this->datatables_settings['show_add_new']) && $this->datatables_settings['show_add_new']){
						if($allow_new){
							
							$function_name = 'create_new_record';
							if( isset( $this->datatables_settings['show_add_new']['function-name'] ) )
								$function_name = $this->datatables_settings['show_add_new']['function-name'];
								
							$function_text = 'Add New Record';
							if( isset( $this->datatables_settings['show_add_new']['function-text'] ) )
								$function_text = $this->datatables_settings['show_add_new']['function-text'];
								
							$function_title = 'Add new record to the dataTable';
							if( isset( $this->datatables_settings['show_add_new']['function-title'] ) )
								$function_title = $this->datatables_settings['show_add_new']['function-title'];
							
							
							$returning_html_data .= '<a href="#" id="add-new-record" class="btn btn-mini btn-sm btn-success" data-role="button" data-mini="true" data-inline="true" data-theme="e" data-corners="false" data-icon="plus" data-iconpos="notext" function-id="'.$allow_new.'" search-table="" function-class="'.$classname.'" function-name="'.$function_name.'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="'.$function_title.'">'.$function_text.'</a>';
						}
					}
					
					//CHECK WHETHER OR NOT TO SHOW IMPORT EXCEL TABLE BUTTON
					if(isset($this->datatables_settings['show_import_excel_table']) && $this->datatables_settings['show_import_excel_table']){
						if($allow_import_excel_table){
							$returning_html_data .= '<a href="#" id="import-excel-table" data-role="button" data-mini="true" data-inline="true" class="btn btn-mini btn-sm btn-success" data-theme="e" data-corners="false" data-icon="back" function-id="'.$allow_import_excel_table.'" search-table="'.$classname.'" function-class="myexcel" function-name="import_excel_table" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Import data from excel file">Import File</a>';
						}
					}
					
					//CHECK WHETHER OR NOT TO SHOW EDIT BUTTON
					if(isset($this->datatables_settings['show_edit_button']) && $this->datatables_settings['show_edit_button']){
						$caption = 'Edit Record';
						$title_text = 'Edit Record the Selected Record';
						
						if( strlen( $this->datatables_settings['show_edit_button'] ) > 1 ){
							$caption = $this->datatables_settings['show_edit_button'];
							$atitle_text = strip_tags( $this->datatables_settings['show_edit_button'] );
						}
                        if( isset( $atitle_text ) && $atitle_text)$title_text = $atitle_text;
                        
						if($allow_editing_records){
							$returning_html_data .= '<a href="#" id="edit-selected-record" class="btn btn-mini btn-sm btn-success" data-role="button" function-id="'.$allow_editing_records.'" search-table="" function-class="'.$classname.'" function-name="edit" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'].'&action='.$this->table.'&todo=edit" mod="edit-'.md5($this->table).'" todo="edit" title="'.$title_text.'">'.$caption.'</a>';
						}
					}
					
					if(isset($this->datatables_settings['custom_edit_button']) && $this->datatables_settings['custom_edit_button']){
						$returning_html_data .= $this->datatables_settings['custom_edit_button'];
					}
					
					//CHECK WHETHER OR NOT TO SHOW EDIT PASSWORD BUTTON
					if(isset($this->datatables_settings['show_edit_password_button']) && $this->datatables_settings['show_edit_password_button']){
						if($allow_editing_password){
							$returning_html_data .= '<a href="#" id="edit-selected-record-password" class="btn btn-mini btn-sm btn-success" data-role="button" data-mini="true" data-inline="true" data-theme="e" data-corners="false" data-icon="edit" function-id="'.$allow_editing_password.'" search-table="" function-class="'.$classname.'" function-name="edit_password" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'].'&action='.$this->table.'&todo=edit_password" mod="edit-'.md5($this->table).'" todo="edit_password" title="Change Password">Change Password</a>';
						}
                        
					}
					
					//CHECK WHETHER OR NOT TO SHOW DELETE BUTTON
					if(isset($this->datatables_settings['show_delete_button']) && $this->datatables_settings['show_delete_button']){
						if($allow_deleting_records){
							$function_name = 'delete';
							if( isset( $this->datatables_settings['show_delete_button']['function-name'] ) )
								$function_name = $this->datatables_settings['show_delete_button']['function-name'];
								
							$function_text = 'Delete Record';
							if( isset( $this->datatables_settings['show_delete_button']['function-text'] ) )
								$function_text = $this->datatables_settings['show_delete_button']['function-text'];
								
							$function_title = 'Delete selected record(s)';
							if( isset( $this->datatables_settings['show_delete_button']['function-title'] ) )
								$function_title = $this->datatables_settings['show_delete_button']['function-title'];
								
							$returning_html_data .= '<a href="#" id="delete-selected-record" class="btn btn-mini btn-sm btn-success pop-up-button" data-role="button"  data-iconpos="notext" data-mini="true" data-inline="true" data-theme="e" data-corners="false" data-icon="delete" function-id="'.$allow_deleting_records.'" search-table="" function-class="'.$classname.'" function-name="'.$function_name.'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'].'&action='.$this->table.'&todo='.$function_name.'" mod="delete-'.md5($this->table).'" todo="'.$function_name.'" data-toggle="popover" data-trigger="manual" data-placement="bottom" title="'.$function_title.'">'.$function_text;
							$returning_html_data .= '</a>';
							
							$returning_html_data .= '<div class="pop-up-content" style="display:none;">';
							$returning_html_data .= 'Are you sure you want to delete the selected record(s)<br /><br /><input type="button" class="btn btn-mini btn-sm btn-primary" value="Yes" id="delete-button-yes" />&nbsp;<input type="button" value="No" class="btn btn-mini" id="delete-button-no" />';
							$returning_html_data .= '</div>';
						}
					}
					
					//CHECK WHETHER OR NOT TO SHOW DELETE BUTTON
					if(isset($this->datatables_settings['show_restore_button']) && $this->datatables_settings['show_restore_button']){
						
						if( $allow_restore_button ){
							$returning_html_data .= '<a href="#" id="restore-selected-record" class="btn btn-mini btn-sm btn-success pop-up-button" data-role="button"  data-iconpos="notext" data-mini="true" data-inline="true" data-theme="e" data-corners="false" data-icon="plus" function-id="'.$allow_restore_button.'" search-table="" function-class="'.$classname.'" function-name="new" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'].'&action='.$this->table.'&todo=restore" mod="restore-'.md5($this->table).'" todo="restore" data-toggle="popover" data-trigger="manual" data-placement="bottom" title="Restore Records">Restore Record(s)';
								$returning_html_data .= '<div class="pop-up-content" style="display:none;">';
									$returning_html_data .= 'Are you sure you want to restore the selected record(s)<br /><br /><input type="button" class="btn btn-mini btn-sm btn-primary" value="Yes" id="restore-button-yes" />&nbsp;<input type="button" value="No" class="btn btn-mini" id="restore-button-no" />';
									$returning_html_data .= '</div>';
							$returning_html_data .= '</a>';
						}
					}
					
				$returning_html_data .= '</div>&nbsp;';
				
                if(isset($this->datatables_settings['custom_single_select_button']) && $this->datatables_settings['custom_single_select_button']){
                    $returning_html_data .= $this->datatables_settings['custom_single_select_button'];
                }
                    
                if(isset($this->datatables_settings['custom_multi_select_button']) && $this->datatables_settings['custom_multi_select_button']){
                    $returning_html_data .= $this->datatables_settings['custom_multi_select_button'];
                }
                    
				$returning_html_data .= '<div data-role="controlgroup" class="btn-group" data-type="horizontal" data-mini="true">';	
					
					//CHECK WHETHER OR NOT TO SHOW GENERATE REPORT BUTTON
					if(isset($this->datatables_settings['show_generate_report']) && $this->datatables_settings['show_generate_report']){
						
						$caption = 'Generate Reports';
						$title_text = 'Generate Reports';
						
						if( strlen( $this->datatables_settings['show_generate_report'] ) > 1 ){
							$caption = $this->datatables_settings['show_generate_report'];
							$title_text = $this->datatables_settings['show_generate_report'];
						}
						
						$month = '';
						if( isset( $this->datatables_settings['month'] ) ){
							$month = $this->datatables_settings['month'];
						}
						
						$year = '';
						if( isset( $this->datatables_settings['year'] ) ){
							$year = $this->datatables_settings['year'];
						}
						
						if($allow_generate_report){
							$returning_html_data .= '<a href="#" id="generate-report" class="btn btn-mini btn-sm btn-success dropdown-toggle" data-toggle="dropdown" data-role="button" data-mini="true" data-inline="true" data-theme="e" data-corners="false" data-icon="forward" title="'.$title_text.'" month-id="'.$month.'" budget-id="'.$year.'" function-id="'.$allow_generate_report.'" search-table="" function-class="'.$classname.'" function-name="generate_report" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="">'.$caption.'</a>';
							/*
							$returning_html_data .= '<ul class="dropdown-menu">';
								$returning_html_data .= '<li><a href="#" id="generate-report-first-term" function-id="'.$allow_generate_report.'" search-table="" function-class="'.$classname.'" function-name="generate_report" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'].'&action='.$this->table.'&todo=generate_report" mod="generate-report-first-term-'.md5('report_budgetds_generator').'" todo="generate_report">First Term</a></li>';
								$returning_html_data .= '<li><a href="">Second Term</a></li>';
								$returning_html_data .= '<li><a href="">Third Term</a></li>';
							$returning_html_data .= '</ul>';
							*/
						}
					}
				
					//CHECK WHETHER OR NOT TO SHOW ADVANCE SEARCH BUTTON
					if(isset($this->datatables_settings['show_advance_search']) && $this->datatables_settings['show_advance_search']){
						//Advance Search Button
						$returning_html_data .= '<a href="#" id="advance-search" class="btn btn-mini btn-sm btn-success dropdown-toggle" data-role="button" data-mini="true" data-inline="true" data-theme="f" data-corners="false" data-icon="search" function-id="search" function-class="search" search-table="'.$this->table.'" function-name="search" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Perform advance search query">Advance Search</a>';
						
						//Advance Clear Search Button
						$returning_html_data .= '<a href="#" id="clear-search"class="btn btn-mini btn-sm btn-success dropdown-toggle"  data-role="button" data-mini="true" data-inline="true" data-theme="f" data-corners="false" data-icon="delete" function-id="clear_search" function-class="search" search-table="'.$this->table.'" function-name="clear_search" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Clear the advance search query">Clear Search</a>';
						
						//check for search query
						$sq = md5('search_query'.$_SESSION['key']);
						if( isset( $_SESSION[$sq][ $this->table ]['query'] ) && $_SESSION[$sq][ $this->table ]['query'] ){
							define("SEARCH_QUERY", $_SESSION[$sq][ $this->table ]['query'] );
						}
					}
					
					if(isset($this->datatables_settings['custom_view_button']) && $this->datatables_settings['custom_view_button']){
						$returning_html_data .= $this->datatables_settings['custom_view_button'];
					}
					
				$returning_html_data .= '</div>&nbsp;';
				
				$returning_html_data .= '<div data-role="controlgroup" class="btn-group" data-type="horizontal" data-mini="true">';	
					
					//CHECK WHETHER OR NOT TO SHOW COLUMN SELECTOR BUTTON
					if(isset($this->datatables_settings['show_column_selector']) && $this->datatables_settings['show_column_selector']){
						//Toggle Column Selector Button
						$returning_html_data .= '<a href="#" class="btn btn-mini btn-sm default pop-up-button" id="hide-show-columns" title="Hide / Show Columns" data-toggle="popover" data-placement="bottom">Show Columns <i class="icon-caret-down"></i>';
						
							
						$returning_html_data .= '</a>';
						
						
							$returning_html_data .= '<div class="pop-up-content" style="display:none;"><ul class="show-hide-column-con" style="padding:0; margin:0; list-style:none; max-height:380px; overflow-y:auto;" data-table="'.$this->data_table_id.'">';
								$returning_html_data .= $hide_show_col;
							$returning_html_data .= '</ul></div>';
					}
				
				
					//CHECK WHETHER OR NOT SHOW RECORDS VIEW OPTIONS SELECTOR
					if(isset($this->datatables_settings['show_records_view_options_selector']) && $this->datatables_settings['show_records_view_options_selector']){
						if( isset( $this->datatables_settings['array_of_view_options'] ) && is_array( $this->datatables_settings['array_of_view_options'] ) && !empty( $this->datatables_settings['array_of_view_options'] ) ){
							
							$returning_html_data .= get_custom_view_options_select_box( 
								array(
									'class_name' => $this->table,
									'option_list' => $this->datatables_settings['array_of_view_options'],
								) 
							);
							
						}
					}
					
					//CHECK WHETHER OR NOT TO SHOW UNITS CONVERTER
					if(isset($this->datatables_settings['show_units_converter']) && $this->datatables_settings['show_units_converter']){
						//Units Select Box
						if(isset($this->datatables_settings['show_units_converter_volume']) && $this->datatables_settings['show_units_converter_volume']){
							$returning_html_data .= units_select_box("volume");
						}
						if(isset($this->datatables_settings['show_units_converter_currency']) && $this->datatables_settings['show_units_converter_currency']){
							$returning_html_data .= units_select_box("currency");
						}
						if(isset($this->datatables_settings['show_units_converter_currency_per_unit_kvalue']) && $this->datatables_settings['show_units_converter_currency_per_unit_kvalue']){
							$returning_html_data .= units_select_box("currency_per_unit_kvalue");
						}
						if(isset($this->datatables_settings['show_units_converter_kvalue']) && $this->datatables_settings['show_units_converter_kvalue']){
							$returning_html_data .= units_select_box("kvalue");
						}
						if(isset($this->datatables_settings['show_units_converter_time']) && $this->datatables_settings['show_units_converter_time']){
							$returning_html_data .= units_select_box("time");
						}
						if(isset($this->datatables_settings['show_units_converter_pressure']) && $this->datatables_settings['show_units_converter_pressure']){
							$returning_html_data .= units_select_box("pressure");
						}
						if(isset($this->datatables_settings['show_units_converter_volume_per_day']) && $this->datatables_settings['show_units_converter_volume_per_day']){
							$returning_html_data .= units_select_box("volume_per_day");
						}
						if(isset($this->datatables_settings['show_units_converter_heating_value']) && $this->datatables_settings['show_units_converter_heating_value']){
							$returning_html_data .= units_select_box("heating_value");
						}
					}
				
				$returning_html_data .= '</div>';
				
				//REFRESH DATATABLE
				if( isset($this->datatables_settings['show_refresh']) && $this->datatables_settings['show_refresh'] ){
					$caption = 'Refresh';
					$title_text = 'Reload the data from the database';
					
					//if($allow_editing_records){
						$returning_html_data .= '<div class="btn-group pull-right">';
							$returning_html_data .= '<button class="btn show-selected-record-details-popup btn-sm blue" title="Show Selected Record Details"><i class="icon-info-sign"></i>&nbsp;</button>';
							$returning_html_data .= '<button id="refresh-datatable" class="btn btn-sm btn-danger" title="'.$title_text.'"><i class="icon-repeat icon-white"></i> '.$caption.'</button>';
						$returning_html_data .= '</div>';
					//}
				}
			}//END - CHECK WHETHER OR NOT TO SHOW TOOLBAR
			
			//CHECK WHETHER OR NOT TIMELINE WILL BE SHOWN
			if(isset($this->datatables_settings['show_timeline']) && $this->datatables_settings['show_timeline']){
				//Set Timeline Properties
				$_SESSION['timestamp_action'] = $this->datatables_settings['timestamp_action'];
				
				//$returning_html_data .= $this->_timeline();
			}
			
			$this->table = $temp_table_name;
			
			return $returning_html_data;
		} 
			
		private function get_upload_session_id(){
			$up = md5( 'uploadedfile' . $_POST['id'] );
			if( !( isset( $_SESSION[$up] ) && is_array( $_SESSION[$up] ) ) )
				$up = md5( 'uploadedfile' . $_POST['processing'] );	//if record id is not found i.e new records, then use processing id
			
			return $up;
		}
		
		private function user_defined_values($id,$key=''){
			//CLEAN NUMBER
			if($id=='number'){
				return format_and_convert_numbers($_POST[$key],3);
			}
			//MULTI SELECT VALUES
			if($id=='multi-select'){
				//DETERMINE IF NEW RECORD OR RETURNING RECORD
				//if(!(isset($_POST['id']) && $_POST['id']))
				if(is_array($_POST[$key]))
					return implode(':::',$_POST[$key]);
				else
					return $_POST[$key];
			}
			//FILE UPLOAD
			if($id=='file'){
				$file = '0';
				if( isset( $_POST[$key] ) ){
					if( $_POST[$key] )return $_POST[$key];
					return $file;
				}
					
				
				if(isset($_FILES[$key]['name']) && $_FILES[$key]['name']){	
					$image_no = 0;
					$this->upload_dir = $this->calling_page."tmp/".$this->table."/".$this->record_id."/";
					
					//CREATE ITEM FOLDER
					$oldumask = umask(0);
					if(!(is_dir($this->upload_dir)))
					mkdir($this->upload_dir,0755);
					umask($oldumask);
					
					//GET FILE EXTENSION
					$ext = null;		
					
					//UPLOAD FILE
					$upload = new cUpload();
					
					$upload->fixed_name = $this->record_id.'_'.$key.$upload->extension($_FILES[$key]['name']);
					
					$ufile=$_FILES[$key]['tmp_name'];
					$uname=$_FILES[$key]['name'];
					$usize=$_FILES[$key]['size'];
					$utype=$_FILES[$key]['type'];
					$upload->spec_dir = $this->upload_dir;
					
					$upload_status = $upload->upload_($ufile,$uname,$usize,$utype,"/srv/disk2/698680/www/yeah.biz.nf/");
					
					if($upload_status==1)$file = $upload->fixed_name;
					else $file = '';
				}
				
				//FOR AJAX UPLOAD CHECK FOR FILE SESSION HANDLER THAT IS TIED WITH USER ID
				//Store Temp Details of Uploaded File
                $img_table = '';
				//check based on record id first
				$up = $this->get_upload_session_id();
				
				if( isset( $_SESSION[$up][$key] ) && is_array( $_SESSION[$up][$key] ) ){
					$files = array();
					foreach( $_SESSION[$up][$key] as $k_files => $v_files ){
						if(file_exists($this->calling_page.$v_files['dir'].$v_files['filename'].'.'.$v_files['ext'])){
							//Record Name of File
                            $cfile = $this->calling_page.$v_files['dir'].$v_files['filename'].'.'.$v_files['ext'];
							$files[] = $v_files['dir'].$v_files['filename'].'.'.$v_files['ext'];
                            
                            //create thumbnails
                            if( isset( $v_files['table'] ) ){
                                $img_table = $v_files['table'];
                                
                                switch( $v_files['table'] ){
                                case 'product':
                                    $this->create_thumbnails( $cfile , 1 );
                                    $this->create_thumbnails( $cfile , 2 );
                                    $this->create_thumbnails( $cfile , 3 );
                                    $this->create_thumbnails( $cfile , 4 );
                                    $this->create_thumbnails( $cfile , 5 );
                                    $this->create_thumbnails( $cfile , 6 );
                                break;
                                }
                            }
						}
						unset( $_SESSION[$up][$key][$k_files] );
					}
					if( isset($files[0]) && $files[0] ){
                        switch( $img_table ){
                        case 'product':
                        case 'store_banners':
                        case 'store_banners':
                        case 'adverts':
                        case 'homepage_slider':
                            $n = count( $files );
                            if( isset( $files[ $n - 1 ] ) && $files[ $n - 1 ] )
                                $file = $files[ $n - 1 ];
                            else
                                $file = $files[0];
                        break;
                        default:
                            $file = implode(':::',$files);
                        break;
                        }
					}
				}
				return $file;
			}
			
			//IDENTIFY DATE
			if( $id == 'date-5' ){
				if( $_POST[$key] ){
					return convert_date_to_timestamp( $_POST[$key] );
				}
			}
			
			if( $id == 'date' ){
				if(isset($_POST[$key.'cus88day']) && isset($_POST[$key.'cus88month']) && isset($_POST[$key.'cus88year']) && $_POST[$key.'cus88day'] && $_POST[$key.'cus88month'] && $_POST[$key.'cus88year'] ){
					$day = $_POST[$key.'cus88day'];
					$month = $_POST[$key.'cus88month'];
					$year = $_POST[$key.'cus88year'];
					
					$mon = array(
						'jan' => 1,
						'feb' => 2,
						'mar' => 3,
						'apr' => 4,
						'may' => 5,
						'jun' => 6,
						'jul' => 7,
						'aug' => 8,
						'sep' => 9,
						'oct' => 10,
						'nov' => 11,
						'dec' => 12,
					);
					
					//COMPILE DATE
					return mktime(0,0,0,$mon[$month],$day,$year);
				}else{
					return 0;
				}
			}
			
			if( $id=='date_time' ){
				if( isset( $_POST[$key.'cus88timestamp'] ) ){
					return doubleval( $_POST[$key.'cus88timestamp'] );
				}
				return 0;
			}
			if( $id=='time' ){
				if( isset( $_POST[$key] ) ){
					return str_replace( ":", ".", $_POST[$key] );
				}
				return 0;
			}
		}
		
		private function system_values( $settings = array() ){
			//GENERATE ALL SYSTEM VALUES
			
			$record_id = 'undefined';
			
			$user_privilege = 'undefined';
			$created_by = 'undefined';
			$creation_date = 'undefined';
			$update = 0;
			
			if( ( isset( $_POST['id'] ) && ! ($_POST['id']) ) ){
				if( isset( $_POST['uid'] ) )
					$created_by = $_POST['uid'];
				
				if( isset( $_POST['user_priv'] ) )
					$user_privilege = $_POST['user_priv'];
				
				$creation_date = date("U");
				
				$record_id = get_new_id();
			}
			
			if( isset( $_POST['id'] ) && $_POST['id'] ){
				$record_id = $_POST['id'];
				$update = 1;
			}
			
			if( isset( $_POST['tmp'] ) && $_POST['tmp'] ){
				$record_id = $_POST['tmp'];
			}
			
			$this->update_state = $update;
			$this->record_id = $record_id;
			
			$modified_by = '';
			if( isset( $_POST['uid'] ) )
				$modified_by = $_POST['uid'];
			
			return array(
				'update' => $update,
				'id' => $record_id,
				'creator_role' => $user_privilege,
				'record_status' => 1,
				'created_by' => $created_by,
				'creation_date' => $creation_date,
				'modified_by' => $modified_by,
				'modification_date' => date("U"),
				'ip_address' => get_ip_address(),
			);
		}
		
		private function display_error($data){
			switch($data){
			case 8:	//Invalid Password Error
				$this->error_msg_title = 'Invalid Password';
			break;
			}
			//return 'error';
		}
		
		private function validate( $data , $field_details ){
		
			switch( $field_details[ 'form_field' ] ){
			case 'text-file':	//'text' 
            case 'calculated':
			case 'text':	//'text' 
			case 'select':	//'select',
			
				if(strlen($data) > 200){
					$data = substr($data,0,200);
				}
				
			break;
			case 'tag':
				if(strlen($data) > 3990){
					$data = substr( $data , 0 , 3950 );
				}
				//transform text to ids
				if( isset( $field_details[ 'tag_function' ] ) && function_exists( $field_details[ 'tag_function' ] ) ){
					$f = $field_details[ 'tag_function' ];
					$ds = explode( ":::", $data );
					foreach( $ds as & $d ){
						if( clean_numbers_only( $d ) != $d ){
							$a = $f( array( 'id' => md5( $d ) ) );
							if( isset( $a["id"] ) )$d = $a["id"];
						}
					}
					$data = implode(":::", $ds );
				}
			break;
			case 'currency':
                $to = 'to usd';
                if( isset( $field_details['default_currency_field'] ) && isset( $_POST[ $field_details['default_currency_field'] ] ) && $_POST[ $field_details['default_currency_field'] ] && $_POST[ $field_details['default_currency_field'] ] != 'undefined' ){
                    $to = 'to '.$_POST[ $field_details['default_currency_field'] ];
                }
				$data = convert_currency( $data , $to );
			break;
			case 'decimal':
				$data = doubleval( $data );
			break;
			case 'textarea-unlimited': 	//'textarea',
				$data = strip_tags( $data , '<ul><ol><li><p><a><br><div><span><h1><h2><h3><h4><h5><h6><hr><table><td><tr><th><tbody><thead><img><iframe><pre><address><b><strong><i><u><small><i><u><super><sub>' );
                if( strlen($data) > 4000000 ){
					$data = substr($data,0,4000000);
				}
			break;
			case 'textarea-norestriction': 	//'textarea',
			break;
			case 'textarea': 	//'textarea',
				$data = strip_tags( $data , '<ul><ol><li><p><a><br><div><span><h1><h2><h3><h4><h5><h6><hr><table><td><tr><th><tbody><thead><img><iframe><pre><address><b><strong><i><u>' );
				if( strlen($data) > 3990){
					$data = substr($data,0,3950);
				}
				//$data = addslashes( $data );
			break;
			case 'password': 	//'password',
				//ENFORCE PASSWORD POLICY
				//return md5($_POST[$key].get_websalter());
				
				//1. Check password length
				if( $data && strlen( $data ) > 5){
					
					$password = md5( $data.get_websalter() );
					
					if( $this->password_confirmation ){
						if( $this->password_confirmation == $password ){
						
							return $password;
							
						}else{
							
							$this->error_msg_title = 'Confirm Password does not Match Password';
							$this->error_msg_body = 'Please ensure that the password matches the confirmed password';
							
						}
					}else{
						$this->password_confirmation = $password;
						return $password;
					}
					
				}else{
					$this->error_msg_title = 'Invalid Password';
					$this->error_msg_body = 'Please review our password policy.<br /><br />Password must be at least eight(8) Characters long';
				}
				
				$data = '';
				
			break;
			case 'date-5':
			case 'date':
				if( isset( $field_details[ 'custom_data' ][ 'type' ] ) && $field_details[ 'custom_data' ][ 'type' ] ){
					switch( $field_details[ 'custom_data' ][ 'type' ] ){
					case 'birthday':
						$year = date("U");
						$required = false;
						if( isset( $field_details[ 'required_field' ] ) && $field_details[ 'required_field' ]=='yes' ){
							$required = true;
						}
						
						if( isset( $field_details[ 'custom_data' ][ 'min-age-limit' ] ) && $field_details[ 'custom_data' ][ 'min-age-limit' ] ){
							//test for min age limit
							$age_limit = $field_details[ 'custom_data' ][ 'min-age-limit' ];
							$min_year = $year - ( $age_limit * 365 * 3600 * 24 );
							
							if( $data > $min_year ){
								if( $required ){
									//Age Limit Error
									$this->error_msg_title = 'You must be over '.$age_limit.'year(s) of age';
									$this->error_msg_body = 'Your current age does not meet our minimum age limit of '.$age_limit.'year(s)';
									return false;
								}
							}
						}
						
						if( isset( $field_details[ 'custom_data' ][ 'max-age-limit' ] ) && $field_details[ 'custom_data' ][ 'max-age-limit' ] ){
							//test for max age limit
							$age_limit = $field_details[ 'custom_data' ][ 'max-age-limit' ];
							$min_year = $year - ( $age_limit * 365 * 3600 * 24 );
							
							if( $data < $min_year ){
								if( $required ){
									//Age Limit Error
									$this->error_msg_title = 'You must be under '.$age_limit.'year(s) of age';
									$this->error_msg_body = 'Your current age exceeds our maximum age limit of '.$age_limit.'year(s)';
									return false;
								}
							}
						}
					break;
					}
				}
			break;
			}
			
			$data = trim( $data );
			
			if( isset( $field_details[ 'required_field' ] ) && $field_details[ 'required_field' ]=='yes' && (! $data ) && ( ! ( $field_details['form_field']=='file' && $this->update_state )  ) && ( ! $this->skip_form_field_validation ) ){
				//Return Error Message
				$this->error_msg_title = 'Missing Value in <b>' . $field_details[ 'field_label' ] . '</b> Field';
				$this->error_msg_body = 'Please ensure that are required fields are properly filled';
				return false;
			}
			
			return $data;
		}
        
        public function create_thumbnails( $src , $type ){
            $use_original_file = false;
            switch($type){
            case 1:
                $maxw = 100;
            break;
            case 2:
                $maxh = 100;
            break;
            case 3:
                $maxw = 200;
            break;
            case 4:
                $maxh = 200;
            break;
            case 5:
                $maxw = 400;
            break;
            case 6:
                $maxh = 400;
            break;
            case 7: //store image banner
                $maxw = 754;
                $maxh = 250;
                $use_original_file = true;
            break;
            }
            
            if(file_exists($src)){
                $pathinfo = pathinfo( $src );
                
                $dest = $pathinfo['dirname'].'/thumb'.$type.'-'.$pathinfo['basename'];
                if( $use_original_file )
                    $dest = $src;
                
                if( $use_original_file || copy( $src , $dest ) ){
                    $src = $dest;
                
                   $image = new cSimple_image();
                   $image->load($src);
                   //Get current width and height
                    $iw = $image->getWidth();
                    
                   //1. Test width
                   //Set width of new image
                   $tow = $iw;
                   switch($type){
                   case 1: case 3: case 5:
                        $image->resizeToWidth($maxw);
                        $image->save($src);
                        $image->load($src);
                        
                        $tow  = $maxw;
                        $toh  = $image->getHeight();
                   break;
                   case 7:
                        $image->resizeToWidth($maxw);
                        $image->save($src);
                        $image->load($src);
                        
                        $tow  = $maxw;
                        
                        $image->resizeToHeight($maxh);
                        $image->save($src);
                        $image->load($src);
                        
                        $toh  = $maxh;
                   break;
                   default:
                        $image->resizeToHeight($maxh);
                        $image->save($src);
                        $image->load($src);
                        
                        $toh  = $maxh;
                        $tow  = $image->getWidth();
                   break;
                   }
                   
                    //5. Create Image container of 300 x 314
                    $con = imagecreatetruecolor($tow,$toh);
                    $bgc = imagecolorallocate( $con, 255, 255, 255);
                    imagefill($con,0,0,$bgc);
                    
                    //6. Get current width and height
                    $iw = $image->getWidth();
                    $ih  = $image->getHeight();
                    //7. Determine center position of resized image
                    $diffw = ($tow - $iw);
                    if($diffw)$xaxis = $diffw/2;
                    else $xaxis = 0;
                    
                    $diffy = ($toh - $ih);
                    if($diffy)$yaxis = $diffy/2;
                    else $yaxis = 0;
                    
                    //8. Copy resized image into the container
                    imagecopyresized($con, $image->load($src),$xaxis,$yaxis,0,0,$iw,$ih,$iw,$ih);
                    
                    if( file_exists($src) ){
                        unlink( $src );
                    }
                    //9. Save copied image
                    if( $con ){
                        switch( $pathinfo['extension'] ){
                        case 'png':
                            imagepng($con,$src);
                        break;
                        default:
                            imagejpeg($con,$src);
                        break;
                        }
                        //unlink($src);
                    }
                }
            }
        }

    }

?>