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
		
		
		private $database_connection; // Deprecated: Creation of dynamic property php8.2 @steve
		private $data_table_id;// Deprecated: Creation of dynamic property php8.2 @steve
		private $data_table_container;// Deprecated: Creation of dynamic property php8.2 @steve

		private $search_combo_option; // Deprecated: Creation of dynamic property php8.2 @steve
		private $action_to_perform; // Deprecated: Creation of dynamic property php8.2 @steve
		public $class_settings; // Deprecated: Creation of dynamic property php8.2 @steve


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
		public $utid = '';
		public $upid = '';
		
		//Current user privilege id
		public $pid = '';
		
		//Current Step in a Stepwise process
		public $step = 1;
		public $mobile_framework = '';
		
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
		public $field_label = array();
		
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
		public $table_temp = '';
		
		//Determine wether form field validation should be skipped
		public $skip_form_field_validation = false;
		
		public $add_empty_select_option = 0;
		public $always_allow_clear = 0;
		public $table_fields_filter = '';
		
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
				'show_edit_passphrase_button' => 0,		//Determines whether or not to show edit password button
				
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
			'skip_fields' => array(),
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
		public $form_extra_options = array();
		
		public $form_extra_field_data = array();
		public $form_extra_field_elements = "";
		public $enabled = 1;	//show form fields or read-only version of form
		public $more_data = array();
		public $form_settings = array();
		public $theme = '';
		
		function __construct(){
			if( defined("HYELLA_THEME") ){
				$this->theme = HYELLA_THEME;
			}
		}
		
		function setDatabase( $database_connection, $table, $database="" ){
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
		
		private function textarea( $field_name, $ctrl='' , $serial_number  = '' , $field_details = array() ){
			//CREATES A TEXTAREA ELEMENT
			//$t = DATATYPE - integer; FUNCTION - defines the tab index as well as the element name
			//$ctrl = DATATYPE - string; FUNCTION - sets the default value of the element
			
			//Set unique id for elements
			if($this->id_increment == 0)$input_id = 'kd';
			else $input_id = 'kd'.$this->id_increment;
			
			++$this->id_increment;
			
			$required = ( ( isset( $field_details[ 'required_field' ]) && $field_details[ 'required_field' ] == 'yes' ) ? 'form-element-required-field ' : '' );

			$adcls = '';
			if( isset( $field_details[ 'wysiwyg' ] ) && $field_details[ 'wysiwyg' ] ){
				switch( $field_details[ 'wysiwyg' ] ){
				case 'summernote':
					$adcls = ' summernote ';
					$this->class_settings[ 'wysiwyg' ][ 'summernote' ][ $field_name ] = $field_details[ 'wysiwyg' ];
				break;
				}
			}
			
			if( $required ){
				if( isset( $field_details['attributes'] ) )$field_details['attributes'] .= ' required="required" ';
				else $field_details['attributes'] = ' required="required" ';
			}
			$returning_html_data =  '<textarea tabindex="' . ( $serial_number + 1 ) . '" class="form-control form-gen-element '. $adcls .  (isset($this->special_element_class[$field_name])?$this->special_element_class[ $field_name ]:'').' '.$this->lbl.' ' . $required . ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' ) .'" type="text" id="' . $field_name . '" name="' . $field_name . '" rows="2" cols="26" tip="' . ( isset ( $field_details['tooltip'] ) ? $field_details['tooltip'] : '' ) . '" placeholder="' . ( isset ( $field_details['placeholder'] ) ? $field_details['placeholder'] : '' ) . '" '.(isset($this->disable_form_element[ $field_name ])?$this->disable_form_element[ $field_name ]:'').' ' . ( isset ( $field_details['attributes'] ) ? $field_details['attributes'] : '' ) . ' alt1="' . $field_details['form_field'] . '" alt="textarea" data-type="textarea" >'.( $ctrl && !( isset( $field_details['skip_stripslash'] ) && $field_details['skip_stripslash'] )?stripslashes($ctrl):$ctrl ).'</textarea>';
			
			return $returning_html_data;
		}
		
		private function upload( $field_details , $field_id, $value, $serial_number ){
			
			//"attributes" => ' skip-uploaded-file-display="1" ' 
			$as = array( "field_id" => $field_id, "t" => $serial_number + 1, "value" => $value );
			
			if( isset ( $field_details['field_label'] ) && $field_details['field_label'] ){
				$as["label"] = rawurlencode( $field_details['field_label'] );
			}
			if( isset ( $field_details['attributes'] ) ){
				$as["attributes"] = $field_details['attributes'];
			}
			if( isset ( $field_details['hide_on_select'] ) ){
				$as["hide_on_select"] = $field_details['hide_on_select'];
			}
			if( isset( $field_details['acceptable_files_format'] ) ){
				$as["acceptable_files_format"] = $field_details['acceptable_files_format'];
			}
			return get_file_upload_form_field( $as );
			
			//CREATES FILE UPLOAD BOX
			//$t = DATATYPE - integer; FUNCTION - defines the tab index as well as the element name
			//$ctrl = DATATYPE - string; FUNCTION - sets the default value of the element
			//$returning_html_data =  '<input type="file" tabindex="'.( $serial_number + 1 ).'" class="form-gen-element '. ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' ) .' '.$this->lbl.'" name="'.$field_id.'" '.(isset($this->disable_form_element[$field_id])?$this->disable_form_element[$field_id]:'').' acceptable-files-format="'.(isset( $field_details['acceptable_files_format'] )?$field_details['acceptable_files_format']:'').'" ' . ( isset ( $field_details['attributes'] ) ? $field_details['attributes'] : '' ) . ' data-value="'.$value.'" />';
			
			//return $returning_html_data;
		}
		
		private function group_boxes( $option, $field_id, $field_details, $t, $value='', $form=true ){
			if( isset( $this->class_settings["default_list_block"] ) && $this->class_settings["default_list_block"] ){
				$mf = $this->mobile_framework;
				$this->mobile_framework = '';
			}
			
			//FUNCTION USED TO DISPLAY GROUP OF CHECK BOXES / OPTION BUTTON
			//$select_rows = DATATYPE - array; FUNCTION - defines number of groups of boxes
			$html = '';
			$f = array();
			$not_editable = '';
			$t = isset( $this->tabindex )?$this->tabindex:$t;
			$_source = isset( $field_details[ 'source' ] )?$field_details[ 'source' ]:', ';
			
			if( isset( $field_details[ 'data' ]["form_field_options_source"] ) && $field_details[ 'data' ]["form_field_options_source"] == 2 ){
				$f = get_list_box_options( $field_details[ 'form_field_options' ], array( "return_type" => 2 ) );
			}else if( is_array( $option ) ){
				$f = $option;
			}else if( function_exists( $option ) ){
				$f = $option();
			}
			
			if( isset( $field_details[ 'options' ] ) && $field_details[ 'options' ] ){
				$ex = explode(";", $field_details[ 'options' ] );

				if( is_array( $ex ) && ! empty( $ex ) ){
					foreach( $ex as $ek => $ev ){
						if( $ev ){
							$es2 = explode(":", $ev );
							if( isset( $es2[0] ) && $es2[0] ){
								$f[ $es2[0] ] = ( isset( $es2[1] ) && $es2[1] )?$es2[1]:$es2[0];
							}
						}
					}
				}
			}
			
			if( ! empty( $f ) ){
				$sel = '';
				if( $value && isset( $f[ $value ] ) ){
				}else{
					//$sel = ' checked="checked" ';
				}
				
				switch( $this->mobile_framework ){
				case "framework7":
					$html .= '<div class="list-block"><ul>';
					$not_editable .= $html;
				break;
				default:
					$html .= '<div>';
					
					switch( $_source ){
					case "ol":
					case "ul":
						$not_editable .= '<'.$_source.' class="nwp-fd-li">';
					break;
					default:
						$not_editable .= '<pre>';
					break;
					}
				break;
				}
				
				$name = $field_id;
				$test_values = array();
				
				switch( $field_details["form_field"] ){
				case "checkbox":
					$name = $field_id . '[]';
					if( $value )$test_values = explode( ":::", $value );
					$sel = '';
				break;
				default:
					if( $value )$test_values = array( $value );
				break;
				}
				
				if( empty( $test_values ) ){
					$not_editable .= '&nbsp;';
				}
				
				$attr = '';
				if( isset( $field_details['attributes'] ) ){
					$attr = $field_details['attributes'];
				}
				if( isset( $this->disable_form_element[ $field_id ] ) && $this->disable_form_element[ $field_id ] ){
					$attr .= $this->disable_form_element[ $field_id ];
				}
				
				$cls = '';
				if( isset( $field_details['field_identifier'] ) ){
					$cls = 'fi-' . $field_details['field_identifier'];
				}
				
				$strict_options = array();
				if( isset( $this->form_extra_options[ $field_id ]['strict_options'] ) && is_array( $this->form_extra_options[ $field_id ]['strict_options'] ) ){
					$strict_options = $this->form_extra_options[ $field_id ]['strict_options'];
				}
				
				$mcls1 = 'form-check-inline';
				switch( $field_details["form_field"] ){
				case "radio":
					$mcls1 = 'form-check-block';
					$attr .= ( ( isset( $field_details[ 'required_field' ]) && $field_details[ 'required_field' ] == 'yes' ) ? ' required ' : '' );
				break;
				}
				
				foreach( $f as $k => $v ){
					if( ! empty( $strict_options ) && ! isset( $strict_options[ $k ] ) ){
						continue;
					}
					if( in_array( $k, $test_values ) ){
						$sel = ' checked="checked" ';
					}
					$attr2 = '';
					if( $t ){
						$attr2 = ' tabindex="'.$t.'" ';
						++$t;
					}
				// echo $f ;
				
					if( isset( $field_details[ 'dbo_attributes' ] ) && $field_details[ 'dbo_attributes' ] ){
						$attr2 .= $field_details[ 'dbo_attributes' ] .' data-key="'. $k .'" ';
					}
					
					switch( $this->mobile_framework ){
					case "framework7":
						$html .= '<li><label class="label-'.$field_details["form_field"].' item-content">';
						$html .= '<input '.$attr2.' type="'.$field_details["form_field"].'" name="'.$field_id.'" id="'.$field_id.'-'.$k.'" value="'.$k.'" data-text="'. $v .'" '.$sel.' class="'. $cls .'" '.$attr.' >';
							$html .= '<div class="item-media"><i class="icon icon-form-'.$field_details["form_field"].'"></i></div>';
							$html .= '<div class="item-inner"><div class="item-title">'.$v.'</div></div>';
						$html .= '</label></li>';
						
						if( $sel ){
							$not_editable .= '<li><div class="item-inner"><div class="item-title">'.$v.'</div></div></li>';
						}
					break;
					default:
						$html .= '<div class="form-check '.$mcls1.'">';
						$html .= '<label class="form-check-label"><input '.$attr2.' '.$attr.' class="form-check-input '. $cls .'" '.$sel.' type="'.$field_details["form_field"].'" name="'. $name .'" id="'.$field_id.'-'.$k.'" value="'.$k.'" data-text="'. $v .'"> '.$v.'</label>';
						$html .= '</div>';
						
						if( $sel ){
							switch( $_source ){
							case "ol":
								$not_editable .= '<li>' . $v . '</li>';
							break;
							case "ul":
								$not_editable .= '<li><i class="icon-check"></i> ' . $v . '</li>';
							break;
							default:
								$not_editable .= $v . $_source;
							break;
							}
							
						}
					break;
					}
					
					$sel = '';
				}
				
				
				switch( $this->mobile_framework ){
				case "framework7":
					$html .= '</ul></div>';
					$not_editable .= '</ul></div>';
				break;
				default:
					switch( $_source ){
					case "ol":
					case "ul":
						$not_editable .= '</'.$_source.'>';
					break;
					default:
						$not_editable .= '</pre>';
					break;
					}
					
					
					$html .= '</div>';
				break;
				}
				
			}
			
			if( isset( $this->form_display_not_editable_value[ $field_id ] ) ){
				$html = $not_editable;
			}
			// echo $html;exit;
			if( $t ){
				$this->tabindex = $t;
			}
			
			if( isset( $this->class_settings["default_list_block"] ) && $this->class_settings["default_list_block"] ){
				$this->mobile_framework = $mf;
			}
			
			return $html;
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
			if( $populate_form_with_values && isset($val) && $val ){
				
				$df = 'Y-m-d';
				if( isset( $this->form_display_not_editable_value[ $field_id ] ) && $this->form_display_not_editable_value[ $field_id ] ){
					$df = 'd-M-Y ';
				}

				switch( $form_details['form_field'] ){
				case 'text':
					if( $val ){
						return strip_tags( stripslashes( $val ) );
					}
				break;
				case 'date':
				case 'datetime':
					if( $val ){ 
						return date( $df , doubleval( $val ) ); 
					}
				break;
				case 'datetime-local':
					// print_r( date( $df."\TH:i" , doubleval( $val ) ) );exit;
					if( $val ){ 
						return date( $df."\TH:i" , doubleval( $val ) );
					}
				break;
				case 'time':
					if( isset( $form_details[ 'time_type' ] ) && $form_details[ 'time_type' ] ){
						return format_time( $val, $form_details[ 'time_type' ] );
					}else{
						return format_time( $val );
					}
				break;
				case 'old-password':
				case 'password':
					return '';
				break;
				case 'number':
					if( $val ){ 
						$val = doubleval( $val );
					}
					
					if( isset( $form_details["display_text"] ) && $form_details["display_text"] ){
						return number_format( $val, 0 );
					}
					
					return $val;
				break;
				case 'decimal_long':
				case 'decimal':
				case 'currency':
					if( $val ){ 
						$val = doubleval( $val );
					}
					if( isset( $form_details["display_text"] ) && $form_details["display_text"] ){
						return number_format( $val, 2 );
					}
					
					return $val;
				break;
				case 'textarea-unlimited':
				case 'textarea-unlimited-med':
					if( isset( $form_details['skip_stripslash'] ) && $form_details['skip_stripslash'] ){
						return $val;
					}
				break;
				}
				
				if( isset( $form_details['callback']['load'] ) && function_exists( $form_details['callback']['load'] ) ){
					$ff = $form_details['callback']['load'];
					return $ff( ( $val?stripslashes( $val ):'') );
				}
				
				return ( $val?stripslashes($val):'');
			}
			
			if( ( ! $populate_form_with_values ) && isset( $_POST[ $field_id ] ) ){
				return $_POST[ $field_id ];
			}
		}
		
		private function date( $t , $value = '' , $field_details = array()  , $field_id = ''  ){
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
				$key = array();
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
		
		private function select( $field_details , $key , $value , $field_id , $cus_name = '' , $val = '' , $display = '' , $elementid = '' , $end = '' , $serial_number = '' ){
			//DISPLAY OPTIONS SELECT ELEMENT
			$returning_html_data = null;
			
			$ctrl_form = $field_details[ 'form_field' ];
			// print_r( $field_details );exit;
			
			if( isset( $field_details[ 'options' ] ) && $field_details[ 'options' ] ){
				$ex = explode(";", $field_details[ 'options' ] );

				if( is_array( $ex ) && ! empty( $ex ) ){
					foreach( $ex as $ek => $ev ){
						if( $ev ){
							$es2 = explode(":", $ev );
							if( isset( $es2[0] ) && $es2[0] ){
								$key[ $ek ] = $es2[0];
								$value[ $ek ] = ( isset( $es2[1] ) && $es2[1] )?$es2[1]:$es2[0];
							}
						}
					}
				}
			}
			
			// print_r( $field_details );exit;
			if( isset( $field_details[ 'data' ]["form_field_options_source"] ) && ( $field_details[ 'data' ]["form_field_options_source"] == 2 || $field_details[ 'data' ]["form_field_options_source"] == 'list_box_class' ) ){
				$ex = get_list_box_options( $field_details[ 'form_field_options' ], array( "return_type" => 1 ) );
				
				$key = array();
				$value = array();
				
				if( isset( $ex["keys"] ) && isset( $ex["values"] ) ){
					$key = $ex["keys"];
					$value = $ex["values"];
				}
			}
			
			$array = '';
			$multi = null;
			
			//Multi-select Menu
			$data_role = '';
			
			$autocomplete_select = '';
			
			$select_option_tooltip = '';
			
			$array_of_accessible_functions_tooltips = array();
			
			$sel1 = array();
			if( $ctrl_form == 'multi-select' ){
				/* if( isset( $this->form_display_not_editable_value[ $field_id ] ) ){
					return '<pre>' . __get_value( $val, $field_id, array( 'globals' => array( $field_id => $field_details ), "text-date" => 1 ) ) . '</pre>';
					
				} */
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
			
			$strict_options = array();
			if( isset( $this->form_extra_options[ $field_id ]['strict_options'] ) && is_array( $this->form_extra_options[ $field_id ]['strict_options'] ) ){
				$strict_options = $this->form_extra_options[ $field_id ]['strict_options'];
			}
			
			/*------------------------------------------*/
			//Remove after resolving pop-up issue in forms
			$data_native_menu = 'true';
			/*------------------------------------------*/
			
			$h_not_editable = '';
			
			
			$returning_html_data .= '<select '.$multi.' data-mini="true" data-native-menu="'.$data_native_menu.'" tabindex="' . ( $serial_number + 1 ) . '" class="form-gen-element form-control '.$autocomplete_select.' '.$display.' '.$this->lbl.' '.(isset($this->special_element_class[$field_id])?$this->special_element_class[ $field_id ]:'') . ' ' . ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' ) . ( ( isset( $field_details[ 'required_field' ]) && $field_details[ 'required_field' ] == 'yes' ) ? 'form-element-required-field" required="required' : '' ) . '" name="' . $field_id . $cus_name . $array . '" id="' . $field_id . $cus_name . '" '.$data_role.' '.(isset($this->disable_form_element[ $field_id ])?$this->disable_form_element[ $field_id ]:'').' '.(isset($this->form_display_not_editable_value[ $field_id ])?'style="display:none;"':'').' ' . ( isset ( $field_details['attributes'] ) ? $field_details['attributes'] : '' ) . ' alt="' . $field_details['form_field'] . '" data-type="' . $field_details['form_field'] . '" >';
				////if($multi)$returning_html_data .= '<option>'.ucwords(str_replace('-',' ',$this->lbl)).'</option>';
				
				//If Search Mode is active ensure first select element is null
				if($this->searching || $this->add_empty_select_option || ( isset( $field_details["add_empty"] ) && $field_details["add_empty"] ) )$returning_html_data .= '<option value=""></option>';
				
				if( isset( $field_details["form_field_options_group"] ) && function_exists( $field_details["form_field_options_group"] )  ){
					
					$options = $field_details["form_field_options_group"]();
					foreach( $options as $opt => $opts ){
						$returning_html_data .= '<optgroup label="'.$opt.'">';
						foreach( $opts as $k => $v ){
							if( ! empty( $strict_options ) && ! isset( $strict_options[ $k ] ) ){
								continue;
							}
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
				
				$n=0;
				if( is_array( $key ) && ! empty( $key ) ){
					foreach($key as $k){
						if( ! empty( $strict_options ) && ! isset( $strict_options[ $k ] ) ){
							continue;
						}
						
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
								$h_not_editable = '<pre class="not-editable-form-element-value">'.ucwords(str_replace("_"," ",$value[$n])).'</pre>';
							}
							
						}else $sel = null;
						
						$returning_html_data .= '<option '.$sel.' alt="'.(isset($end[$n])?$end[$n]:'').'" title="'. ucwords(str_replace("_"," ",$value[$n])) .'" '.$select_option_tooltip.' value="'.$k.'">'.ucwords(str_replace("_"," ",$value[$n])).'</option>';
						
						++$n;
					}
				}
			$returning_html_data .= '</select>';
			
			return $h_not_editable.$returning_html_data;
		}
		
		function myphp_form( $fields , $values='' , $columns = 2 , $options = array() ){
			//Search Combo Container
			$enabled = $this->enabled;
			$search_combo_option = '';
			$search_combo_option_text = '';
			
			//Set HTML ID of Form
			$html_id = $this->table;
			if( $this->html_id )
				$html_id = $this->html_id;
				
			$h_content = '';
			
			$returning_html_data = '<div id="form-panel-wrapper">';
			$mobile = 0;
			if( isset( $_POST["mobile_framework"] ) && $_POST["mobile_framework"] ){
				$mobile = $_POST["mobile_framework"];
			}
			if( isset( $this->class_settings["mobile"] ) && $this->class_settings["mobile"] ){
				$mobile = $this->class_settings["mobile"];
			}
			
			if( $this->inline_edit_form ){
				$returning_html_data = '<div id="inline-edit-form-wrapper">';
			}
			
			if( $mobile ){
				$returning_html_data = '<div id="form-panel-wrapper" class="list no-hairlines-md">';
			}
			if( $enabled ){
				
				$returning_html_data .= '<form name="'.$this->table.'" id="'.$html_id.'-form" method="'.$this->method.'" action="'.$this->action_to_perform.'" enctype="multipart/form-data" data-ajax="false" class=" inputs-list login-form '.$this->form_class.'">';
			}
			//NB: $ctrl_form - ARRAY USED TO DETERMINE TYPE OF FORM COMPONENT TO BE PLACED
			//		$uid holds the key field of a record that should be displayed
			
			
			//GET ARRAY OF VALUES FOR FORM LABELS
			$database_table_field_intepretation_function_name = $this->table;
			
			if( $this->table_field_temp && function_exists( $database_table_field_intepretation_function_name . $this->table_field_temp ) )
				$database_table_field_intepretation_function_name .= $this->table_field_temp;
			
			if( $this->table_temp && function_exists( $this->table_temp ) ){
				$database_table_field_intepretation_function_name = $this->table_temp;
			}
				
			if(isset($this->datatables_settings['multiple_table_header']) && $this->datatables_settings['multiple_table_header'] ){
				//CHECK FOR MULTI-ROW TABLE HEADER
				$func = $this->table.'_multi_table_header';
			}
			
			if( function_exists( $database_table_field_intepretation_function_name ) ){
				
				if( isset( $this->form_extra_options['table_fields_filter'] ) && $this->form_extra_options['table_fields_filter'] ){
					$form_label = $database_table_field_intepretation_function_name( $this->form_extra_options['table_fields_filter'] );
				}else{
					$form_label = $database_table_field_intepretation_function_name();
				}
				
				//ALTERNATE FIELD CONTROLLER FUNCTION
				if( isset( $this->hidden_records_function ) && $this->hidden_records_function && function_exists( $this->hidden_records_function ) ){
					$function_name = $this->hidden_records_function;
					
					$form_label = $function_name();
				}
				// print_r( $form_label );exit;
				
				if( ! ( is_array( $form_label ) ) ){
					$form_label = array();
				}

				
				$mobile_class1 = '';
				$mobile_class2 = '';
				$mobile_class3 = '';
				$mobile_class4 = 'btn btn-primary blue ';
				$mobile_class5 = '';
				
				if( $mobile ){
					$this->mobile_framework = $mobile;
					$mobile_class1 = 'item-input item-input-wrap item-input-field';
					$mobile_class2 = 'item-title item-label';
					$mobile_class3 = 'item-inner';
					$mobile_class4 = ' button button-fill color-orange '; //color-orange
					$mobile_class5 = 'item-content item-input';
					$returning_html_data .= '<input type="hidden" name="mobile_framework" value="'.$this->mobile_framework.'" />';
				}
				
				//$n=1;
				$t = 0;
				$stop = 1;
				
				$css_hide = '"';
				
				//USED TO DETERMINE IF FORM FILLED VALUE SHOULD BE FILLED
				
				if( is_array( $values ) && ! empty( $values ) ){
					$populate_form_with_values = true;
					$aa = $values;
				}else{
					$populate_form_with_values = false;
				}
				
				if($this->searching ){
					$aa = array();
				}
				
				//print_r( $fields );
				// print_r( $this->attributes["custom_form_fields"] );exit;
				if( isset( $this->attributes["custom_form_fields"]["field_ids"] ) && isset( $this->attributes["custom_form_fields"]["form_label"] ) && $this->attributes["custom_form_fields"]["field_ids"] ){

					$fields = array_merge( $fields, $this->attributes["custom_form_fields"]["field_ids"] );
					$form_label = array_merge( $form_label, $this->attributes["custom_form_fields"]["form_label"] );
					
					$jd = array();
					if( isset( $this->form_extra_field_data ) && $this->form_extra_field_data ){
						$jd = json_decode( $this->form_extra_field_data, true );
					}
					$jd["custom_fields"] = $this->attributes["custom_form_fields"]["field_ids"];
					$this->form_extra_field_data = json_encode( $jd );
					unset( $jd );
				}
				
				
				$fields = reorder_fields_based_on_serial_number( $fields , $form_label, array( "use_table_fields" => 1 ) );
				$this->search_combo_option = '';
				
				// print_r( $values ); exit; 
				/* print_r( $this->hide_record_css );
				print_r( $this->hide_record );
				print_r( $form_label );
				print_r( $fields ); exit; */
				 
				$check_fields = array( 'fields' => array(), 'value' => '' );
				
				foreach( $fields as $field_id ){
					
					if( ! $enabled ){
						$this->form_display_not_editable_value[ $field_id ] = 1;
					}
					
					if( isset( $this->disable_form_element[ $field_id ] ) ){
						$check_fields['fields'][] = $field_id;
						if( isset( $aa[ $field_id ] ) ){
							$check_fields['value'] .= $aa[ $field_id ];
						}
					}
					
					$ips = array(
						"field_id" => $field_id,
						"form_label" => $form_label,
						
						"populate_form_with_values" => $populate_form_with_values,
						"mobile_class5" => $mobile_class5,
						"mobile_class4" => $mobile_class4,
						"mobile_class3" => $mobile_class3,
						"mobile_class2" => $mobile_class2,
						"mobile_class1" => $mobile_class1,
						
						"aa" => isset( $aa )?$aa:array(),
						"t" => $t,
					);
					$h_content .= $this->nw_generate_form_field( $ips );
					
					++$t;
					
				}
				
				if( $enabled ){
					$nw_more_data = array();	
					if( empty( $check_fields['fields'] ) ){
						$check_fields['fields'] = array( 'nw_empty' );
						$check_fields['value'] = rand();
					}
					
					//13-mar-23: old values to streamline update process
					if( isset( $option['def_values'] ) && $option['def_values'] ){
						$check_fields['old_values'] = $option['def_values'];
						$check_fields['old_values']["_t"] = $this->table;
						$check_fields['old_values']["_s"] = 'form';
					}
					
					$check_fields['value'] = md5( $check_fields['value'] . get_websalter() );
					$check_fields['time'] = date("U");
					
					$_SESSION['checksum'][ $check_fields['value'] ] = $check_fields;
					
					$checksum = $check_fields['value'];
					unset( $check_fields );
					
					$hopt = array( 
						'table' => $this->table, 
						'id' =>  ( isset($values['id'])?$values['id']:'' ),
						'uid' =>  $this->uid,
						'user_priv' =>  $this->pid,
						'action' =>  $this->action_to_perform,
					);

					if( isset( $this->attributes[ 'nw_more_data' ] ) && $this->attributes[ 'nw_more_data' ] ){
						$hopt[ 'nw_more_data' ] = $this->attributes[ 'nw_more_data' ];
					}

					//SET CONSTANTS
					$returning_html_data .= get_form_headers( $hopt );
					
					$returning_html_data .= '<input type="hidden" name="nw_checksum" value="'. $checksum .'" />';
					$returning_html_data .= '<input type="hidden" name="module" value="c'.ucfirst($this->table).'" />';
						
					$returning_html_data .= '<input type="hidden" name="stepmaxstep" value="'.$this->step.'::'.$this->maxstep.'" />';
					$returning_html_data .= '<input type="hidden" name="skip_validation" value="'.$this->skip_form_field_validation.'" />';
					
					if( defined("HYELLA_MINIMUM_PASSWORD_LENGTH") && HYELLA_MINIMUM_PASSWORD_LENGTH ){
						$returning_html_data .= '<input type="hidden" id="min-password-length" value="'. HYELLA_MINIMUM_PASSWORD_LENGTH .'" />';
					}
					
					if( isset( $_POST["tmp_id"] ) && $_POST["tmp_id"] ){
						$returning_html_data .= '<input type="hidden" name="tmp" value="'.$_POST["tmp_id"].'" />';
						unset( $_POST["tmp_id"] );
					}
						
					
					if($this->searching || $this->add_empty_select_option ){
						$returning_html_data .= '<input type="hidden" name="nw_searching" value="1" />';
					}
					
					if( isset( $this->form_extra_field_data ) && $this->form_extra_field_data ){
						$returning_html_data .= '<textarea class="hyella-data" style="height:1px;" id="extra_fields" name="extra_fields">'.$this->form_extra_field_data.'</textarea>';
					}
					
					
				}
				$returning_html_data .= '<div class="form-body" >';
				
				
				$search_combo_option = $this->search_combo_option;
				
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
									$returning_html_data .= '<option>---Select Field to Search---</option>';
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
						/*
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
						*/
						
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
					
					if( $enabled ){
					$returning_html_data .= '<div id="bottom-row-container">';
					$returning_html_data .= '<div class="control-group input-row bottom-row" style="margin-bottom:20px; clear:both;">';
						$returning_html_data .= '<div class="controls cell">';
						if( $this->butsubmit )$returning_html_data .= '<input tabindex="'.( $t + 2 ).'" id="form-gen-submit" data-loading-text="processing..." class="'.$mobile_class4.' form-gen-button " data-theme="'.$this->but_theme.'" value="'.$this->submit.'" type="submit"/> ';
						
						if( $this->searching )$this->clear .= ' Search Form';
						
						//if( $this->butclear || $this->searching )$returning_html_data .= '<input id="form-gen-clear" class="form-gen-button btn btn-primary" data-theme="'.$this->but_theme.'" value="'.$this->clear.'" type="reset" tabindex="'.($t+3).'"/>';
						if( $this->butclear )$returning_html_data .= '<input id="form-gen-clear" class="form-gen-button btn btn-primary" data-theme="'.$this->but_theme.'" value="'.$this->clear.'" type="reset" tabindex="'.($t+3).'"/>';
						
						$returning_html_data .= '</div>';
					$returning_html_data .= '</div>';
					$returning_html_data .= '</div>';
					}
					
					//CLOSE THE BOX
					$returning_html_data .= '</div>';
				if( $enabled ){
					$returning_html_data .= '</form>';
				}

				if( isset( $this->class_settings[ 'wysiwyg' ] ) && ! empty( $this->class_settings[ 'wysiwyg' ] ) ){
					$addscr = '';
					foreach( $this->class_settings[ 'wysiwyg' ] as $w1 => $w2 ){
						switch( $w1 ){
						case 'summernote':
							$sela = '';
							if( ! empty( $w2 ) ){
								foreach( $w2 as $wk => $wv ){
									if( $sela ){
										$sela .= ',';
									}
									$sela .= 'textarea#'.$wk;
								}

								$addscr .= "$('". $sela ."').summernote({
									tabsize: 2,
									height: 320,
									toolbar: [
									// [groupName, [list of button]]
									//['style', ['bold', 'italic', 'underline', 'clear']],
									['style', ['bold', 'underline', 'clear']],
									//['font', ['strikethrough', 'superscript', 'subscript']],
									['fontsize', ['fontsize']],
									['color', ['color']],
									['table', ['table',]],
									['para', ['ul', 'ol', 'paragraph']],
									//['height', ['height', 'codeview']]
								  ],
									callbacks: {
									  }
								});
								
								$('.note-editable').css('font-size','18px');";

							}
						break;
						}
					}
					$this->class_settings[ 'wysiwyg' ] = array();
					if( $addscr ){
						$addscr = '<script>'. $addscr .'</script>';
					}
					$returning_html_data .= $addscr;
				}

				$returning_html_data .= '</div>';
			}
			
            
			return $returning_html_data;
		}
		
		function get_field_group( $field_id = '', $field_details = array(), $values = '' ){
			$h = '';
			
			if( $field_id && isset( $field_details["database_objects"] ) && $field_details["database_objects"] ){
				$get_form = 1;
				if( isset( $this->form_display_not_editable_value[ $field_id ] ) ){
					$get_form = 0;
				}
				
				$h .= get_nw_database_object( array( "field_id" => $field_id, "object_ids" => $field_details["database_objects"], "values" => $values, "get_form" => $get_form, 'table' => ( isset( $field_details[ 'table' ] ) ? $field_details[ 'table' ] : '' ) ) );
			}else if( $field_id && isset( $field_details["raw_html"] ) && $field_details["raw_html"] ){
				$h .= $field_details["raw_html"];
			}
			
			return $h;
		}
		
		function nw_generate_form_field( $iparams = array() ){
			$h_content_loop = '';
			$field_details = array();
			$more_attr = '';
			
			if( empty( $iparams ) )return '<p style="color:#d42111;">Invalid Form Settings</p>';
			extract( $iparams );
			/*
			foreach( $iparams as $ik => $iv ){
				switch( $ik ){
				case "field_details":
					$field_details = $iv;
				break;
				case "form_label":
					$form_label = $iv;
				break;
				case "form_label":
					$form_label = $iv;
				break;
				}
			}
			*/
			$field_details = array();
			if( isset( $form_label[$field_id] ) && is_array( $form_label[$field_id] ) )
				$field_details = $form_label[ $field_id ];

			$manage_action = 'new_popup_form';
			
			if( isset( $this->form_extra_options['modal'] ) && $this->form_extra_options['modal'] ){
				$manage_action = 'new_popup_form_in_popup';
			}
			
			if( !empty( $field_details ) ){
				
				$this->lbl = $field_id;
				
				if( isset( $field_details["data"]["access_control"] ) ){
					if( isset( $this->class_settings["search_form"] ) && $this->class_settings["search_form"] ){
						if( preg_match("/no-search/", $field_details["data"]["access_control"] ) ){
							$this->hide_record[ $field_id ] = 1;
						}
					}else{
						if( isset( $this->class_settings["edit_record"] ) && $this->class_settings["edit_record"] ){
							if( preg_match("/no-edit/", $field_details["data"]["access_control"] ) ){
								$this->form_display_not_editable_value[ $field_id ] = 1;
							}
						}else{
							if( preg_match("/no-create/", $field_details["data"]["access_control"] ) ){
								$this->hide_record[ $field_id ] = 1;
							}
						}
					}
				}
				
				//Check if field is not hidden
				if(!(isset($this->hide_record[ $field_id ]) && $this->hide_record[ $field_id ])){
					
					//Check if field is hidden with css
					$css_hide = '"';
					if((isset($this->hide_record_css[ $field_id ]) && $this->hide_record_css[ $field_id ]))
						$css_hide = ' default-hidden-row" style="display:none;" ';
					
					//Check in search mode -- hide all fields with css
					if($this->searching ){
						$css_hide = ' default-hidden-row" style="display:none;" ';
						
					}
					if($this->searching || isset( $this->attributes["disable_class"][ $field_id ] ) || ( isset( $this->attributes["disable_all_class"] ) && $this->attributes["disable_all_class"] ) ){
						if( isset( $field_details[ 'class' ] ) )
							unset( $field_details[ 'class' ] );
					}
					
					if( $this->searching || isset( $this->attributes["disable_required_field"][ $field_id ] ) ){
						if( isset( $field_details[ 'required_field' ] ) )
							unset( $field_details[ 'required_field' ] );
					}
					
					if( isset( $this->form_extra_options[ $field_id ]['required_field'] ) && $this->form_extra_options[ $field_id ]['required_field'] ){
						$field_details[ 'required_field' ] = $this->form_extra_options[ $field_id ]['required_field'];
					}
					
					if( isset( $this->form_extra_options[ $field_id ]['note'] ) && $this->form_extra_options[ $field_id ]['note'] ){
						$field_details[ 'note' ] = $this->form_extra_options[ $field_id ]['note'];
					}
					
					if( isset( $this->form_extra_options[ $field_id ]['tooltip'] ) && $this->form_extra_options[ $field_id ]['tooltip'] ){
						$field_details[ 'tooltip' ] = $this->form_extra_options[ $field_id ]['tooltip'];
					}
					
					if( isset( $this->form_extra_options[ $field_id ]['options'] ) && $this->form_extra_options[ $field_id ]['options'] ){
						$field_details[ 'options' ] = $this->form_extra_options[ $field_id ]['options'];
					}

					if( isset( $this->form_extra_options[ $field_id ]['form_field_options'] ) ){
						$field_details[ 'form_field_options' ] = $this->form_extra_options[ $field_id ]['form_field_options'];
					}

					if( isset( $this->form_extra_options[ $field_id ]['field_data'] ) && $this->form_extra_options[ $field_id ]['field_data'] ){
						$field_details[ 'data' ] = $this->form_extra_options[ $field_id ]['field_data'];
					}


					if( isset( $field_details['display_position'] ) || $this->searching){
						
						$cl2 = ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' );
						
						if( isset( $this->form_extra_options[ $field_id ]['class'] ) ){
							$cl2 = $this->form_extra_options[ $field_id ]['class'];
						}
						
						$con_csl = '';
						if( isset( $field_details["skip_container_class"] ) ){
							$cl2 = '';
						}else{
							$con_csl = $cl2 . '-row ';
						}
						
						$css_hide = ' ' . $field_details['form_field'] .'-item-con '. $css_hide;
						if( ! $cl2 ){
							$css_hide = ' clear ' . $css_hide;
						}

						$parent_div = 1;
						$child_div = 1;
						if( isset( $field_details['embed'] ) && $field_details['embed'] ){
							$child_div = 0;
							$parent_div = 0;
						}
						
						if( isset( $mobile_class6 ) && $mobile_class6 ){
							$h_content_loop .= '<div class="'.$mobile_class6 .' input-row '.$cl2.'">';
						}
						if( $mobile_class5 ){
							$h_content_loop .= '<div class="'.$mobile_class5 . $css_hide.'>';
						}
						if( $parent_div ){
							if( isset( $mobile_class6 ) && $mobile_class6 ){
								$h_content_loop .= '<div class="'.$mobile_class3 . ' fc-'. $field_id .'" id="con-'.$field_id.'">';
							}else{
								$h_content_loop .= '<div class="'.$mobile_class3.' form-group control-group input-row '.$cl2.'-row '.($this->lbl).'-row'.$css_hide.'>';
							}
						}
						
						
						//Search Combo Options -- if field is not hidden
						if( $field_details['form_field']!='group-file' && $this->searching ){
							//DISPLAY LABEL
							$h_content_loop .= '<label class="control-label cell searching-label">';
								$h_content_loop .= 'Condition';
							$h_content_loop .= '</label>';
							
							//Display Search Condition Control
							
							$h_content_loop .= '<div class=" controls cell-element ">';
								$h_content_loop .= '<select name="sq' . $field_id .'" class="form-control">';
									switch( $field_details['form_field'] ){
									case 'currency':
									case 'decimal':
									case 'decimal_long':
									case 'time':
									case 'date-5time':
									case 'date-5':
									case 'datetime':
									case 'date':
									case 'date_time':
									case 'number':
										$h_content_loop .= $this->search_conditions;
									break;
									case 'single_json_data':
									case 'multiple_json_data':
									case 'passphrase':
									case 'password':
									case 'checkbox':
									case 'radio':
									case 'hidden':
									break;
									default:
									case 'text':
									case 'tag':
									case 'calculated':
									case 'select':
									case 'multi-select':
									case 'textarea':
									case 'textarea-unlimited':
									case 'textarea-unlimited-med':
									case 'textarea-norestriction':
										$h_content_loop .= $this->text_fields_search_conditions;
										
									break;
									}
								$h_content_loop .= '</select>';
							$h_content_loop .= '</div><br />';
							
							switch( $field_details['form_field'] ){
							case 'single_json_data':
							case 'multiple_json_data':
							case 'passphrase':
							case 'password':
							case 'checkbox':
							case 'radio':
							case 'hidden':
							case 'html':
							case 'field_group':
								return;
							break;
							}
						}
						
						$show_label = 1;
						switch( $field_details['form_field'] ){
						case 'hidden':
						case 'html':
							$show_label = 0;
						break;
						}
						
						//11-may-23
						$use_validate_attr = 1;
						switch( $this->mobile_framework ){
						case "framework7":
							$use_validate_attr = 0;
						break;
						}
						//echo $use_validate_attr; exit;
						
						if( $show_label && !( $this->inline_edit_form || $this->hide_form_labels ) ){
							
							switch( $this->mobile_framework ){
							case "framework7":
								if( $field_details['form_field'] == "multi-select" ){
									$field_details['form_field'] = "checkbox";
								}
							break;
							}
							
							//DISPLAY LABEL
							$h_content_loop .= '<label class="'.$mobile_class2.' control-label cell '.($this->lbl).'-label form-element-required-label-' . ( isset( $field_details[ 'required_field' ] )?$field_details[ 'required_field' ] : '' ) .'">';
								
								$this_field_label = nl2br( $field_details[ 'field_label' ] );
								
								if( isset( $this->field_label[ $field_id ] ) && $this->field_label[ $field_id ] ){
									$this_field_label = $this->field_label[ $field_id ];
								}
								
								if( isset( $this->form_extra_options[ $field_id ]['field_label'] ) && $this->form_extra_options[ $field_id ]['field_label'] ){
									$this_field_label = $this->form_extra_options[ $field_id ]['field_label'];
								}
								
								$h_content_loop .=  $this_field_label;
								
								//Set Text to be displayed in search combo
								$search_combo_option_text = $this_field_label;
								
							$h_content_loop .= '</label>';
						}
						
						$calc_field = 0;
						if( $field_details['form_field'] == 'text-file' || $field_details['form_field'] == 'calculated' ){
							$calc_field = 1;
							$field_details['form_field'] = 'text';
							if( isset( $field_details['calculations']['form_field'] ) && $field_details['calculations']['form_field'] ){
								$field_details['form_field'] = $field_details['calculations']['form_field'];
								
								if( isset( $this->form_extra_options[ $field_id ]['attributes'] ) && $this->form_extra_options[ $field_id ]['attributes'] ){
									$field_details[ 'attributes' ] = $this->form_extra_options[ $field_id ]['attributes'];
								}
								
								if( ! isset( $field_details['attributes'] ) ){
									$field_details['attributes'] = '';
								}
								
								$field_details['attributes'] .= ' autocomplete="new-password" ';
								
								if( isset( $field_details['calculations']['action'] ) && $field_details['calculations']['todo'] ){
									
									$mt = '';
									$mkt = $field_details['calculations']["action"] . $field_details['calculations']["todo"];
									if( isset( $field_details['calculations']['todo2'] ) ){
										$mt = $field_details['calculations']['todo2'];
										$mkt .= $field_details['calculations']['todo2'];
									}
									
									$field_details['attributes'] .= ' action="?action='. $field_details['calculations']['action'] .'&todo='. $field_details['calculations']['todo'] . $mt .'" ';
									
									$field_details['attributes'] .= ' data-action="'. $field_details['calculations']['action'] .'" ';
									
									if( isset( $field_details['calculations']['minlength'] ) ){
										$field_details['attributes'] .= ' minlength="'. $field_details['calculations']['minlength'] .'" ';
									}
									
									if( isset( $field_details['calculations']['tags'] ) ){
										$field_details['attributes'] .= ' tags="'. $field_details['calculations']['tags'] .'" ';
									}
									
									if( isset( $field_details['calculations']['data-key-field'] ) ){
										$field_details['attributes'] .= ' data-key-field="'. $field_details['calculations']['data-key-field'] .'" ';
									}
									
									if( isset( $field_details['calculations']['data-params'] ) ){
										$field_details['attributes'] .= ' data-params="'. $field_details['calculations']['data-params'] .'" ';
									}
									
									if( isset( $field_details['calculations']['key'] ) && $field_details['calculations']['key'] ){
										$field_details['attributes'] .= ' data-key="'. $field_details['calculations']['key'] .'" ';
									}else{
										$field_details['attributes'] .= ' data-key="'. md5( $mkt ) .'" ';
									}
								}
							}	
						}
						
						if( isset( $this->form_extra_options[ $field_id ]['form_field'] ) && $this->form_extra_options[ $field_id ]['form_field'] ){
							$field_details['form_field'] = $this->form_extra_options[ $field_id ]['form_field'];
						}
						if( isset( $this->form_extra_options[ $field_id ]['class'] ) && $this->form_extra_options[ $field_id ]['class'] ){
							$field_details['class'] = $this->form_extra_options[ $field_id ]['class'];
						}
						
						if( isset( $this->attributes[ $field_id ] ) && $this->attributes[ $field_id ] ){
							$field_details['attributes'] = $this->attributes[ $field_id ];
						}
						
						if( isset( $this->form_extra_options[ $field_id ]['attributes'] ) && $this->form_extra_options[ $field_id ]['attributes'] ){
							$field_details['attributes'] = $this->form_extra_options[ $field_id ]['attributes'];
						}
						
						$element_class = ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : ' ' );
						$element_class = str_replace( "col-md", "", $element_class );
						$element_class = str_replace( "col-lg", "", $element_class );

						if( isset( $field_details['embed'] ) && $field_details['embed'] ){
							$element_class .= "embedded-field";
						}

						if( isset( $this->always_allow_clear ) && $this->always_allow_clear && isset( $calc_field ) && $calc_field ){
							$element_class .= " allow-clear ";
						}

						$field_details[ 'class' ] = $element_class;
						
						$more_attr2 = '';
						if( isset( $field_details['field_key'] ) && $field_details['field_key'] ){
							$more_attr2 .= ' field_key="'. $field_details['field_key'] .'" ';
						}
						
						if( isset( $field_details['field_identifier'] ) && $field_details['field_identifier'] ){
							$more_attr2 .= ' nwp_fi="'. $field_details['field_identifier'] .'" ';
						}
						if( $more_attr2 ){
							if( isset( $field_details['attributes'] ) ){
								$field_details['attributes'] .= $more_attr2;
							}else{
								$field_details['attributes'] = $more_attr2;
							}
						}
						
						if( isset( $mobile_class8 ) && $mobile_class8 ){
							$h_content_loop .= '<div class="'.$mobile_class8 .'">';
						}
						
						if( isset( $mobile_class7 ) && $mobile_class7 ){
							$h_content_loop .= '<div class="'.$mobile_class7 .'">';
						}
						
						//text,password,hidden,single radio, single check
						switch( $field_details['form_field'] ){
						case 'signature':
						case 'text':
						case 'time':
						case 'tag':
						case 'calculated':
						case 'passphrase':
						case 'password':
						case 'old-password':
						case 'hidden':
						case 'email':
						case 'tel':
						case 'number':
						case 'currency':
						case 'decimal':
						case 'decimal_long':
						case 'date-5time':
						case 'date-5':
						case 'datetime':
						case 'date_time':
						case 'color':
						case 'text-file':
							
							$input_value_step = 'step="any"';
							$max_length = 200;
							
							if( $field_details['form_field'] == 'number' ){
								$input_value_step = 'step="1"';
							}
							
							$fdx = $field_details['form_field'];
							switch( $fdx ){
							case 'currency':
							case 'decimal':
							case 'decimal_long':
								$more_attr .= ' data-o-type="'. $fdx .'" ';
								
								$field_details['form_field'] = 'number';
								$input_value_step = 'step="any"';
							case 'decimal_long':
							break;
								if( isset( $aa[ $field_id ] ) && $aa[ $field_id ] ){
									$aa[ $field_id ] = convert_currency( $aa[ $field_id ] ,'' , 1 );
								}
								if( isset( $_POST[ $field_id ] ) && $_POST[ $field_id ] ){
									$_POST[ $field_id ] = convert_currency( $_POST[ $field_id ] ,'' , 1 );
								}
							break;
							case "tel":
								if( defined("HYELLA_INTL_PHONE_FORMAT") && HYELLA_INTL_PHONE_FORMAT ){
									$input_value_step = ' pattern="\+[0-9]{4,14}" ';
									$field_details['title'] = 'Phone Numbers must be of this format. eg. +2348010000009'; //Dont publicize your phone number
									$field_details['placeholder'] = '+2348052529580';
								}else{
									$input_value_step = ' pattern="[\+0-9]{4,14}" ';
									$field_details['title'] = 'Phone Numbers must be at least 4 digits and not more than 14 digits E.g: +2348010000040 or 08010000040'; //Dont publicize your phone number
									$field_details['placeholder'] = 'E.g: 08010000004';
								}
							break;
							case 'email':
								$input_value_step = ' pattern="[^@\s]+@[^@\s]+\.[^@\s]+" ';
								$field_details['title'] = 'Email must be of this format. eg. pat2echo@gmail.com';
							break;
							}
							/* 
							if( $field_details['form_field'] == 'currency' || $field_details['form_field'] == 'decimal' ){
								$field_details['form_field'] = 'number';
								$input_value_step = 'step="any"';
								
								if( isset( $aa[ $field_id ] ) && $aa[ $field_id ] ){
									$aa[ $field_id ] = convert_currency( $aa[ $field_id ] ,'' , 1 );
								}
								if( isset( $_POST[ $field_id ] ) && $_POST[ $field_id ] ){
									$_POST[ $field_id ] = convert_currency( $_POST[ $field_id ] ,'' , 1 );
								}
							} */
							
							if( $field_details['form_field'] == 'old-password' ){
								$field_details['form_field'] = 'password';
							}
							
							if( $field_details['form_field'] == 'passphrase' ){
								$field_details['form_field'] = 'password';
							}
							
							if( $field_details['form_field'] == 'tag' ){
								$field_details['form_field'] = 'text';
							}
							
							if( $field_details['form_field'] == 'date-5' || $field_details['form_field'] == 'date-5time' ){
								
								if( $field_details['form_field'] == 'date-5time' ){

									$field_details['form_field'] = 'datetime-local';
									$field_details['placeholder'] = 'YYYY-MM-DD:H:';
									$input_value_step = ' current-date="' . date("Y-M-d") . '"';

								}else{

									$field_details['form_field'] = 'date';
									$field_details['placeholder'] = 'YYYY-MM-DD';
									$input_value_step = ' current-date="' . date("Y-M-d") . '"';

								}
								
								if( isset( $field_details[ 'custom_data' ][ 'min-age-limit' ] ) && $field_details[ 'custom_data' ][ 'min-age-limit' ] ){
									$input_value_step .= ' min-year="'.$field_details[ 'custom_data' ][ 'min-age-limit' ].'" ';
								}
								
								if( isset( $field_details[ 'custom_data' ][ 'max-age-limit' ] ) && $field_details[ 'custom_data' ][ 'max-age-limit' ] ){
									$input_value_step .= ' max-year="'.$field_details[ 'custom_data' ][ 'min-age-limit' ].'" ';
								}
							}
							
							$min_value = ' min="0" ';
							if( isset( $field_details['minimum'] ) && $field_details['minimum'] == 'no' ){
								$min_value = '';
							}
							
							$option_array = array();
							$option_use_text = 0;
							$option_text = '';
							if( isset( $aa[ $field_id ] ) && $aa[ $field_id ] && isset( $field_details['calculations'] ) && $field_details['calculations'] ){
								$fsource = 'form';
								if( isset( $this->form_display_not_editable_value[ $field_id ] ) ){
									$fsource = '<br />';
								}
								
								$option_array2 = array( $field_id => $aa[ $field_id ] );
								$fdx = isset( $field_details['calculations'][ 'reference' ][ 'key' ] ) ? $field_details['calculations'][ 'reference' ][ 'key' ] : '';
								if( isset( $aa[ $fdx ] ) && $aa[ $fdx ] ){
									$option_array2[ $fdx ] = $aa[ $fdx ];
								}
								
								$_data = evaluate_calculated_value(
									array(
										'source' => $fsource,
										'row_data' => $option_array2,
										'form_field_data' => $field_details,
									) 
								);
								// print_r( array(
								// 		'source' => $fsource,
								// 		'row_data' => $option_array2,
								// 		'form_field_data' => $field_details,
								// 	)  );
								
								if( isset( $_data["value"] ) && $_data["value"] ){
									if( is_array( $_data["value"] ) ){
										$option_array = $_data["value"];
									}else{
										$option_text = $_data["value"];
									}
								}
								
								if( isset( $field_details['calculations']['multiple'] ) && $field_details['calculations']['multiple'] ){
									$option_use_text = 1;
								}
								
							}
							
						if( $child_div )$h_content_loop .= '<div class="'.$mobile_class1.' controls cell-element '.( ( isset( $field_details['icon'] ) && $field_details['icon'] )?'input-icon':'').'">'; // . $option_text;
							
							//Not Editable Value
							$vx = $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'' , $populate_form_with_values , $field_id , $field_details );
							
							if( isset( $this->form_display_not_editable_value[ $field_id ] ) ){
								/* if( ! $option_use_text ){
									$vx = '';
								} */
								if( $option_text ){
									$aa[ $field_id ] = $option_text;
									$vx = $option_text;
								}
								$field_details["display_text"] = 1;
								
								if( ! $vx ){
									$vx = '&nbsp;';
								}
								
								$h_content_loop .= '<pre class="not-editable-form-element-value">'. $vx .'</pre>';
							}else{
								if( isset( $field_details['icon'] ) )$h_content_loop .= $field_details['icon'];
								
								//11-may-23
								$h_content_loop .=  '<input value="'. $vx .'" tabindex="'.(doubleval( $t )+1).'" class=" form-control form-gen-element ' . $field_id . ' demo-input-local '.(isset($this->special_element_class[$field_id])?$this->special_element_class[ $field_id ]:' ').' ' . $element_class . ( ( isset( $field_details[ 'required_field' ]) && $field_details[ 'required_field' ] == 'yes' ) ? ' form-element-required-field" required="required' : ' ' ) . '" type="' . $field_details['form_field'] . '" id="' . $field_id . '" name="' . $field_id . '" tip="' . ( isset ( $field_details['tooltip'] ) ? $field_details['tooltip'] : '' ) . '" placeholder="' . ( isset ( $field_details['placeholder'] ) ? $field_details['placeholder'] : '' ) . '" '.(isset($this->disable_form_element[ $field_id ])?$this->disable_form_element[ $field_id ]:'').' title="' . ( isset ( $field_details['title'] ) ? $field_details['title'] : '' ) . '" alt="' . $field_details['form_field'] . '" data-type="' . $field_details['form_field'] . '" ';
								
								if( $use_validate_attr ){
									$h_content_loop .=  ' data-validate="' . $field_details['form_field'] . '" ';
								}
								
								$h_content_loop .=  ' maxlength="'.$max_length.'" ' . $input_value_step . ' ' . ( isset ( $field_details['attributes'] ) ? $field_details['attributes'] : '' ) . ' '.$min_value.' label="'.$option_text.'" '.$more_attr.' /><span class="input-status"></span>';
								//11-may-23
								
								if( ! empty( $option_array ) ){
									$h_content_loop .=  '<textarea style="display:none;" id="' . $field_id . '-option-array">' . json_encode( $option_array ) . '</textarea>';
								}
							
							}
						if( $child_div )$h_content_loop .= '</div>';
						break;								
						case 'single_json_data':
						case 'multiple_json_data':
							if( isset( $aa['id'] ) && $aa['id'] && isset( $field_details['form_field_options'] ) && $field_details['form_field_options'] ){
								$h_content_loop .= '<div class="controls cell-element">';
									$h_content_loop .= '<a href="#" class="btn btn-sm btn-default btn-block custom-single-selected-record-button" action="?module=&action=json_options&todo='. $manage_action .'&reference_id='.$aa["id"].'&reference_table='.$this->table.'&option_table='.$field_details['form_field_options'].'" override-selected-record="-" title="'.$this_field_label.'">Manage '.$this_field_label.'</a><br />';
								$h_content_loop .= '</div>';
							}else{
								$h_content_loop .= '<div class="controls cell-element">Record must be saved first</div>';
							}
						break;
						case 'checkbox':
						case 'radio':
							
							$option = '';
							if( isset( $field_details['form_field_options'] ) && $field_details['form_field_options'] ){
								$option = $field_details['form_field_options'];
							}
							
							$this->tabindex = $t + 1;
							// print_r( $field_details );exit;
							switch( $this->mobile_framework ){
							case "framework7":
								$h_content_loop .= $this->group_boxes( $option, $field_id, $field_details, $t, $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'' , $populate_form_with_values , $field_id , $field_details ) );
							break;
							default:
							$h_content_loop .= '<div class="controls cell-element">';
								$h_content_loop .= $this->group_boxes( $option, $field_id, $field_details, $t, $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'' , $populate_form_with_values , $field_id , $field_details ) );
							$h_content_loop .= '<span class="input-status "></span></div>';
							break;
							}
							
							$t = $this->tabindex;
						break;
						case 'date':
						case 'date_time':
						case 'date-time':
						if( $child_div )$h_content_loop .= '<div class="'.$mobile_class1.' controls cell-element">';
						
							$h_content_loop .= $this->date( $t, $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'' , $populate_form_with_values , $field_id , $field_details ) , $field_details , $field_id );
							
						if( $child_div )$h_content_loop .= '</div><span class="input-status"></span>';
						break;
						case 'select':
						case 'multi-select':
						if( $child_div )$h_content_loop .= '<div class="'.$mobile_class1.' controls cell-element">';
							//Initialize key value pair
							$key = array();
							$value = array();
							
							if( isset( $this->form_extra_options[ $field_id ]['form_field_options'] ) && $this->form_extra_options[ $field_id ]['form_field_options'] ){
								$field_details['form_field_options'] = $this->form_extra_options[ $field_id ]['form_field_options'];
							}
							
							if( isset( $field_details['form_field_options'] ) && $field_details['form_field_options'] ){
								$options = convert_array_to_key_value_pair_for_selectbox( $field_details['form_field_options'] );
								
								$key = $options[0];
								$value = $options[1];
							}
							
							$h_content_loop .= $this->select( $field_details , $key , $value , $field_id , '' , $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'' , $populate_form_with_values , $field_id , $field_details ) , '' , '' , '' , $t );
								
						if( $child_div )$h_content_loop .= '<span class="input-status"></span></div>';
						break;
						case 'picture':
						case 'file':
						if( ! isset( $this->form_display_not_editable_value[ $field_id ] ) ){
							//$h_content_loop .= '<input alt="'.$field_details['form_field'].'" type="hidden" class="'.$field_id.'-replace" /><img id="'.$field_id.'-img" class="form-gen-element-image-upload-preview" style="display:none;" /><div class="controls cell-element upload-box " id="upload-box-'.$t.'">';
							//'. ( ( isset( $field_details[ 'class' ]) ) ? $field_details[ 'class' ] : '' ) .'
								$h_content_loop .= $this->upload( $field_details , $field_id , $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'' , $populate_form_with_values , $field_id , $field_details ), $t );
							//$h_content_loop .= '<span class="input-status"></span></div>';
							
							if( isset( $field_details[ 'tooltip' ] ) ){
								$h_content_loop .= '<i>'. $field_details[ 'tooltip' ] .'</i>';
							}

						}
						
						$h_content_loop .= '<div class="file-content">'.get_uploaded_files( $this->class_settings["calling_page"] , $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'' , $populate_form_with_values , $field_id , $field_details ), $field_details[ 'field_label' ] , $field_id ).'</div>';
						break;
						case 'textarea':
						case 'textarea-unlimited':
						case 'textarea-unlimited-med':
						case 'textarea-norestriction':
							$vx = $this->form_value( isset($aa[ $field_id ])?$aa[ $field_id ]:'' , $populate_form_with_values , $field_id , $field_details );
							
							if( $child_div )$h_content_loop .= '<div class="'.$mobile_class1.' controls cell-element">';
							if( isset($this->form_display_not_editable_value[$field_id]) ){
								if( ! $vx ){
									$vx = '&nbsp;';
								}
								
								$h_content_loop .= '<pre class="not-editable-form-element-value">'. $vx .'</pre>';
							}else{
								$h_content_loop .= $this->textarea( $field_id , $vx , $t , $field_details );
							}
							
							if( $child_div )$h_content_loop .= '<span class="input-status"></span></div>';
						break;
						case 'category':
						if( $child_div )$h_content_loop .= '<div class="controls cell-element">';
							$h_content_loop .= $this->category($field_details['form_field'],$t,$this->form_value( isset($aa[$t])?$aa[$t]:'' ,$populate_form_with_values , $field_id , $field_details ));
						if( $child_div )$h_content_loop .= '</div>';
						break;
						case 'group-file':
							if( $child_div )$h_content_loop .= '<div class="controls cell-element">';
								$h_content_loop .= $this->group_upload('','','',$t);
							if( $child_div )$h_content_loop .= '<span class="input-status"></span></div>';
						break;
						case 'html':
						case 'field_group':
							$h_content_loop .= $this->get_field_group( $field_id, $field_details, isset($aa[ $field_id ])?$aa[ $field_id ]:'' );
						break;
						}
						
						//Search Combo Options -- if field is not hidden
						if($field_details['form_field']!='file' && $field_details['form_field']!='group-file' && $this->searching){
							$this->search_combo_option .= '<option value="'.($this->lbl).'-row">'.$search_combo_option_text.'</option>';
						}
						
						if( ! isset( $this->form_display_not_editable_value[ $field_id ] ) ){
							$note = '';
							if( isset( $this->form_extra_options[ $field_id ]['note'] ) && $this->form_extra_options[ $field_id ]['note'] ){
								$note = $this->form_extra_options[ $field_id ]['note'];
							}else{
								if( isset( $field_details['note'] ) ){
									$note = $field_details['note'];
								}
								if( isset( $field_details["data"]['note'] ) ){
									$note = $field_details["data"]['note'];
								}
							}
							
							if( $note ){
								$h_content_loop .= '<i>' . $note . '</i>';
							}
							
						}
						
						if( isset( $mobile_class7 ) && $mobile_class7 ){
							$h_content_loop .= '</div>';
						}
						
						if( isset( $mobile_class8 ) && $mobile_class8 ){
							$h_content_loop .= '</div>';
						}
						
						//CLOSE THE ROW
						if( $parent_div )$h_content_loop .=  '</div>';
						if( $mobile_class5 ){
							$h_content_loop .=  '</div>';
						}
						if( isset( $mobile_class6 ) && $mobile_class6 ){
							$h_content_loop .= '</div>';
						}
					}
					
				}
					
				
			}
			
			if( isset( $this_field_label ) && isset( $this->attributes["show_date_range"][ $field_id ] ) && $this->attributes["show_date_range"][ $field_id ] ){
				//duplicate field
				$duplicate = str_replace( $field_id, $field_id . "_range", $h_content_loop );
				$h_content_loop = str_replace( $this_field_label, "Start " . $this_field_label, $h_content_loop );
				$duplicate = str_replace( $this_field_label, "End " . $this_field_label, $duplicate );
				$h_content_loop .= $duplicate;
			}
			
			return $h_content_loop;
		}
		
		function myphp_post( $fields, $options = array() ){
			if( isset( $_POST["origin"] ) && $_POST["origin"] ){
				$this->more_data["origin_info"] = _convert_id_into_actions( $_POST["origin"], array( "delimiter1" => '&', "delimiter2" => '=' ) );
			}
			
			if( isset( $_POST[ 'test-recaptcha' ] ) && $_POST[ 'test-recaptcha' ]=='yes' ){
				if( isset( $_POST["recaptcha_challenge_field"] ) && isset( $_POST["recaptcha_response_field"] ) ){
				  $privatekey = "6LdxqewSAAAAAOsV3q7nYIbLTX9J3C4F1vH_6Ll_";
				  $resp = recaptcha_check_answer ($privatekey,
												$_SERVER["REMOTE_ADDR"],
												$_POST["recaptcha_challenge_field"],
												$_POST["recaptcha_response_field"]);

				  if ( ! $resp->is_valid ) {
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
                
				if( $skip_token || ( isset( $_POST['processing'] ) && generate_token( array( 'validate' => $_POST['processing'] ) ) ) ){
					
					//RECONSTRUCT FORM DATA FROM TABLE NAME
					
					if( isset( $_POST[ 'test-agreement' ] ) && $_POST[ 'test-agreement' ]=='yes' ){
						if( ! ( isset( $_POST[ 'agreement' ] ) && $_POST[ 'agreement' ] == 'agreement' ) ){
							
							$this->error_msg_title = 'You must agree to the <b>Terms of Service</b>';
							$this->error_msg_body = 'Please ensure that you checked the agreement checkbox';
							
							return false;
						}
					}
					
					if( 0 ){
					//if( ! $skip_token ){ //disabled bcos catholic lagos online, edit general settings not working
					
						if( isset( $_POST[ 'nw_checksum' ] ) && $_POST[ 'nw_checksum' ] ){
							
							//clear expired checksums
							if( isset( $_SESSION['checksum'] ) && is_array( $_SESSION['checksum'] ) && ! empty( $_SESSION['checksum'] ) ){
								$now = date("U") - ( 3600 * 4 );
								foreach( $_SESSION['checksum'] as $ck => $chk ){
									if( isset( $chk["time"] ) && $now > ( $chk["time"] ) ){
										unset( $_SESSION['checksum'][ $ck ] );
									}
								}
							}
							
							if( isset( $_SESSION['checksum'][ $_POST[ 'nw_checksum' ] ][ "fields" ] ) && is_array( $_SESSION['checksum'][ $_POST[ 'nw_checksum' ] ][ "fields" ] ) && ! empty( $_SESSION['checksum'][ $_POST[ 'nw_checksum' ] ][ "fields" ] ) ){
								$chks = $_SESSION['checksum'][ $_POST[ 'nw_checksum' ] ][ "fields" ];
								
								if( ! in_array( 'nw_empty', $chks ) ){
									
									//this is not right bcos if error in validation below, checksum fails during 2nd resubmission
									//unset( $_SESSION['checksum'][ $_POST[ 'nw_checksum' ] ] );
									
									$v = '';
									
									foreach( $chks as $ck => $chk ){
										if( isset( $_POST[ $chk ] ) ){
											$v .= $_POST[ $chk ];
										}
									}
									
									if( md5( $v . get_websalter() ) != $_POST[ 'nw_checksum' ] ){
										$this->error_msg_title = 'Invalid Security Key';
										$this->error_msg_body = 'Please repeat the process to generate a new security key';
										return false;
									}
								}
								
							
							}else{
								$this->error_msg_title = 'Expired Security Key';
								$this->error_msg_body = 'Please repeat the process to generate a new security key';
								return false;
							}
						}else{
							$this->error_msg_title = 'No Security Key';
							$this->error_msg_body = 'Please repeat the process to generate a new security key';
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
					
					if( isset( $options["form_label"] ) && is_array( $options["form_label"] ) && ! empty( $options["form_label"] ) ){
						$form_label = $options["form_label"];
					}else{
						//GET ARRAY OF VALUES FOR FORM LABELS
						$database_table_field_intepretation_function_name = $this->table;
						
						if( $this->table_field_temp && function_exists( $database_table_field_intepretation_function_name . $this->table_field_temp ) )
							$database_table_field_intepretation_function_name .= $this->table_field_temp;
						
						if( $this->table_temp && function_exists( $this->table_temp ) ){
							$database_table_field_intepretation_function_name = $this->table_temp;
						}
						
						if( $database_table_field_intepretation_function_name && function_exists( $database_table_field_intepretation_function_name ) ){
							$form_label = $database_table_field_intepretation_function_name();
						}
					}
					
					if( isset( $form_label ) && ! empty( $form_label ) ){
						
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
											'form_field' => isset( $field_details[ 'form_field' ] )?$field_details[ 'form_field' ]:'',
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
										case 'date-5time':
										case 'date-5':
										//case 'datetime':
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
										
										if( isset( $field_details[ "field_identifier" ] ) ){
											$transformed_form_data[ $field_id ]['field_identifier'] = $field_details[ "field_identifier" ];
										}
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
											$vvv = '0';
											if( isset( $system_values[ 'update' ] ) && $system_values[ 'update' ] ){
												$vvv = 'undefined';
											}
										}
										
										if($vvv || $system_values[ 'update' ] ){
											$transformed_form_data[ $field_id ] = array(
												'search_condition' => 'LIKE',
												'value' => $vvv,
												// 'form_field' => isset( $field_details[ 'form_field' ] )?$field_details[ 'form_field' ]:'',
											);
										}
									}
								}
								
								
								++$t;
								
							}
						}
						//print_r( $transformed_form_data );
						
						//13-mar-23
						$nw_checksum = '';
						$old_values = [];
						if( defined( "NWP_FORMS_STREAMLINE_UPDATE_QUERY" ) && NWP_FORMS_STREAMLINE_UPDATE_QUERY ){
							if( isset( $_POST[ 'nw_checksum' ] ) && $_POST[ 'nw_checksum' ] ){
								$nw_checksum = $_POST[ 'nw_checksum' ];
								
								//streamline update query, by passing only values that have changed
								if( isset( $_SESSION['checksum'][ $nw_checksum ][ "old_values" ]["_t"] ) && $_SESSION['checksum'][ $nw_checksum ][ "old_values" ]["_t"] == $this->table ){
									$old_values = $_SESSION['checksum'][ $nw_checksum ][ "old_values" ];
								}
							}else if( isset( $this->attributes[ "old_values" ]["_t"] ) && $this->attributes[ "old_values" ]["_t"] == $this->table ){
								$old_values = $this->attributes[ "old_values" ];
							}
							
							if( ! empty( $old_values ) ){
								//print_r( $old_values ); exit;
								foreach( $old_values as $ok => $ov ){
									if( in_array( $ok, array( "_t", "_s", "modification_date", "modified_by", "modified_source" ) ) ){
										continue;
									}
									
									if( isset( $transformed_form_data[ $ok ]["value"] ) && $transformed_form_data[ $ok ]["value"] == $ov ){
										unset( $transformed_form_data[ $ok ] );
										unset( $old_values[ $ok ] );
									}
								}
							}
						}
						
						return array(
							'form_data' => $transformed_form_data,
							'update' => $system_values[ 'update' ],
							'id' => $system_values[ 'id' ],
							'nw_checksum' => $nw_checksum,
							'old_values' => $old_values,
						);
						
					}else{
						//SOMETHING WENT WRONG
						return false;
					}
				}else{
					//INVALID TOKEN
			
					$this->error_msg_title = 'Invalid Token';
					$this->error_msg_body = 'Please ensure that the form was generated from the app';
					
					return -1;
				}
			}
			
			
		}
		
		function myphp_dttables( $fields = array() ){
			
			//DISPLAY SELECTED RECORDS FROM A DATABASE QUERY IN TABULAR FORM
			$returning_html_data = '';
			// print_r( $this->datatables_settings );exit;
			$t = 'View ';
			
			$returning_html_data = '';
			$hide_show_col = '';
			$hsbc = '';	
			$header_left = '';
			$header_right = '';
			$header_bottom = '';
			
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
			
			$attr = array('a' => '');
			$plugin = '';
			if( isset( $this->datatables_settings[ 'table_data' ] ) && $this->datatables_settings[ 'table_data' ] ){
				$attr = $this->datatables_settings[ 'table_data' ];
			}
			
			if( isset( $this->datatables_settings[ 'plugin' ] ) && $this->datatables_settings[ 'plugin' ] ){
				$plugin = $this->datatables_settings[ 'plugin' ];
				$attr['plugin'] = $plugin;
			}
			
			if( isset( $this->datatables_settings['show_selection'] ) && ! empty( $this->datatables_settings['show_selection'] ) ){
				$attr['show_selection'] = $this->datatables_settings['show_selection'];
			}
			
			
			//GET ARRAY OF VALUES FOR FORM LABELS
			$database_table_field_intepretation_function_name = $this->table;
			if( isset( $this->datatables_settings['real_table'] ) && $this->datatables_settings['real_table'] ){
				$database_table_field_intepretation_function_name = $this->datatables_settings['real_table'];
				$attr['real_table'] = $database_table_field_intepretation_function_name;
			}
			
			if( isset( $this->datatables_settings['db_table'] ) && $this->datatables_settings['db_table'] ){
				$attr['db_table'] = $this->datatables_settings['db_table'];
			}
			
			if(isset($this->datatables_settings['multiple_table_header']) && $this->datatables_settings['multiple_table_header'] ){
				//CHECK FOR MULTI-ROW TABLE HEADER
				$database_table_field_intepretation_function_name = $this->table.'_multi_table_header';
			}
			
			if( function_exists( $database_table_field_intepretation_function_name ) ){
				if( isset( $this->datatables_settings['show_selection']["table_fields_filter"] ) && $this->datatables_settings['show_selection']["table_fields_filter"] ){
					$fl = $database_table_field_intepretation_function_name( $this->datatables_settings['show_selection']["table_fields_filter"] );
					if( isset( $fl["custom_parameters"] ) ){
						$custom_parameters = $fl["custom_parameters"];
						unset( $fl["custom_parameters"] );
					}
					
					$form_label = $fl;
					unset( $fl );
				}else{
					$form_label = $database_table_field_intepretation_function_name();
				}
				
				if( isset( $this->datatables_settings["skip_fields"] ) && is_array( $this->datatables_settings["skip_fields"] ) && ! empty( $this->datatables_settings["skip_fields"] ) ){
					$attr['show_selection']["skip_fields"] = $this->datatables_settings["skip_fields"];
					
					foreach( $this->datatables_settings["skip_fields"] as $skv ){
						if( isset( $form_label[ $skv ] ) ){
							unset( $form_label[ $skv ] );
						}
					}
				}
				
				$fields = reorder_fields_based_on_serial_number( $fields , $form_label );
				
				//ALTERNATE FIELD CONTROLLER FUNCTION
				if( isset( $this->datatables_settings['field_controller_function'] ) && $this->datatables_settings['field_controller_function'] && function_exists( $this->datatables_settings['field_controller_function'] ) ){
					$form_label = $this->datatables_settings['field_controller_function']();
				}
				
				if( isset( $custom_parameters["custom_fields"] ) && ! empty( $custom_parameters["custom_fields"] ) ){
					$fields = array_merge( $fields, $custom_parameters["custom_fields"] );
				}
			}
			
			$this->data_table_container = $table_name_container;
			
				$returning_html_data .= '<textarea class="hyella-data" style="height:1px;" id="'.$table_name.'-attributes">'.json_encode( $attr ).'</textarea><table cellpadding="0" cellspacing="0" border="0" width="100%" class="table table-striped table-bordered table-hover dataTable display '.( isset( $this->datatables_settings['table_class'] )?$this->datatables_settings['table_class']:'display-no-scroll' ).' '.$classname.'" id="'.$table_name.'" class-name="'.$classname.'" container="'.$table_name_container.'">';
				$returning_html_data .= '<thead>';
				
				//CHECK WHETHER OR NOT TO SHOW DETAILS
				$sel = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				$sel_attr = ' style=" max-width:65px;" ';
				if( isset( $this->datatables_settings['show_selection']['action'] ) && $this->datatables_settings['show_selection']['action'] ){
					$sel = '<input type="checkbox" id="datatable-select-all-checkbox" />';
				}
				
				if( isset( $this->datatables_settings['show_selection']['title'] ) && $this->datatables_settings['show_selection']['title'] ){
					$sel_attr = '';
					$sel = $this->datatables_settings['show_selection']['title'];
				}
				
				if(isset($this->datatables_settings['show_details']) && $this->datatables_settings['show_details']){
					$header_left .= '<th data-priority="persit" class="table-header remove-before-export" '. $sel_attr .' '.$row_span.' >';
					$header_left .= $sel;
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
				$custom_parameters = array();
				
				if( ! empty( $fields ) ){
					
					//print_r( $fields ); exit;
					
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
										
										
										$temp_header .=  isset( $field_details['text'] )?$field_details['text']:$field_details['field_label'];
										
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
											
											$header_bottom .=  isset( $field_details['text'] )?$field_details['text']:$field_details['field_label'];
											
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
				if( isset($this->datatables_settings['multiple_table_header']) && $this->datatables_settings['multiple_table_header'] && isset($this->datatables_settings['multiple_table_header_cells'])){
					
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
				
				if( isset( $this->datatables_settings['datatable_method'] ) ){
					$sq = md5( 'split_datatable' . $_SESSION['key'] );
					if( isset( $_SESSION[ $sq ][ $classname ][ $this->datatables_settings['datatable_method'] ] ) ){
						$this->datatables_settings['datatable_split_screen'] = $_SESSION[ $sq ][ $classname ][ $this->datatables_settings['datatable_method'] ];
						
						$this->datatables_settings['split_screen']['checked'] = ' checked="checked" ';
					}
				}
				
				$selection = $this->_get_selection_form();
				
				if( isset( $this->datatables_settings['datatable_split_screen']["col"] ) ){
					$split_screen = intval( $this->datatables_settings['datatable_split_screen']["col"] );
					
					$split_screen_action = isset( $this->datatables_settings['datatable_split_screen']["action"] )?$this->datatables_settings['datatable_split_screen']["action"]:'';
					
					$split_screen_content = isset( $this->datatables_settings['datatable_split_screen']["content"] )?$this->datatables_settings['datatable_split_screen']["content"]:'';
					
					if( $split_screen_action ){
						$split_screen_action .= '&html_replacement_selector=datatable-split-screen-'.$classname;
					}
					
					if( $split_screen && $split_screen < 12 ){
						$returning_html_data = '<div class="row"><div class="col-md-'.( 12 - $split_screen ).'">' . $returning_html_data . '</div><div class="col-md-'.( $split_screen ).'">'. $selection .'<div class="datatable-split-screen resizable-height" style="overflow-y:auto; overflow-x:hidden;" id="datatable-split-screen-'.$classname.'" action="'. $split_screen_action .'" data-subtract="156">'. $split_screen_content .'</div></div></div>';
					}
				}
				
				$returning_html_data = '<div id="'.$table_name_container.'" class="dynamic" data-table="'.$classname.'">' . $this->toolbar( $hide_show_col ) . $returning_html_data;
				
				$returning_html_data .= '</div>';
				
			return $returning_html_data;
		}
		
		private function _get_selection_form( $e = array() ){
			if( isset( $this->datatables_settings['show_selection']['action'] ) && $this->datatables_settings['show_selection']['action'] ){
				
				$l = 'Process Selection';
				if( isset( $this->datatables_settings['show_selection']["button_label"] ) && $this->datatables_settings['show_selection']["button_label"] ){
					$l = $this->datatables_settings['show_selection']["button_label"];
				}
				$selection = '';
				if( isset( $this->datatables_settings['show_selection']["before_html"] ) && $this->datatables_settings['show_selection']["before_html"] ){
					$selection .= $this->datatables_settings['show_selection']["before_html"];
				}
				
				$selection .= '<form class="activate-ajax confirm-prompt" action="'.$this->datatables_settings['show_selection']['action'].'" id="datatable-select-all" >';
					$selection .= get_form_headers( array(
						'action' => $this->datatables_settings['show_selection']['action'],
						'table' => $this->table,
						'nw_more_data' => isset( $this->datatables_settings['show_selection']["form_more_data"] )?$this->datatables_settings['show_selection']["form_more_data"]:'',
					) );
					$selection .= '<div class="note note-info" id="selected-count"></div>';
				
					if( isset( $this->datatables_settings['show_selection']["form_html"] ) && $this->datatables_settings['show_selection']["form_html"] ){
						$selection .= $this->datatables_settings['show_selection']["form_html"];
					}
					
					if( isset( $this->datatables_settings['show_selection']["fields"] ) && ! empty( $this->datatables_settings['show_selection']["fields"] ) ){
						
						foreach( $this->datatables_settings['show_selection']["fields"] as $lk => $lv ){
							if( isset( $lv["form_field"] ) && $lv["form_field"] == 'hidden' ){
								$selection .= '<input type="hidden" name="'.$lk.'" value="'. ( isset( $lv["value"] )?$lv["value"]:'' ) .'" />';
							}else{
								$fv2[ $lk ] = array();
								if( isset( $lv["value"] ) ){
									$fv2[ $lk ]["value"] = $lv["value"];
								}
								$lv["skip_container_class"] = 1;
								
								$gbal = array(
									'labels' => array( $lk => $lv ),
									'fields' => array( $lk => $lk ),
								);
								
								$d1 = __get_value( '', '', array( 'form_fields' => $fv2, 'globals' => $gbal ) );
								
								$selection .= '<div class="nwp-fsel-con '. ( isset( $lv["class"] )?$lv["class"]:'' ) .'" id="nwp-fsel-'.$lk.'">';
								if( isset( $d1[ $lk ][ 'label' ] ) ){
									$selection .= '<label>'. $d1[ $lk ][ 'label' ] .'</label>';
								}
								
								if( isset( $d1[ $lk ][ 'field' ] ) ){
									$selection .= str_replace( "form-group control-group input-row", "", $d1[ $lk ][ 'field' ] ) . '<br />';
								}
								$selection .= '</div>';
							}
						}
						
					}
					
					$selection .= '<textarea style="display:none; height:1px;" class="hyella-data" name="data"></textarea>';
					$selection .= '<input type="submit" value="'.$l.' &rarr;" class="btn blue" />';
				$selection .= '</form>';
				
				if( isset( $this->datatables_settings['show_selection']["after_html"] ) && $this->datatables_settings['show_selection']["after_html"] ){
					$selection .= $this->datatables_settings['show_selection']["after_html"];
				}
				
				$selection .= '<hr />';
				
				if( ! isset( $this->datatables_settings['datatable_split_screen']["col"] ) ){
					$this->datatables_settings['datatable_split_screen']["col"] = 4;
				}
				
				return $selection;
			}
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
			if( isset( $this->datatables_settings['skip_access_control'] ) && $this->datatables_settings['skip_access_control'] ){
				$super = 1;
			}else{
				$access = get_accessed_functions();
				$super = 0;
				if( ! is_array( $access ) && $access == 1 ){
					$super = 1;
				}
			}
            
			//CHECK FOR BUTTONS ACTION PROCESSING CLASS
			$temp_table_name = $this->table;
			if( isset( $this->datatables_settings['buttons_action_processing_class'] ) && $this->datatables_settings['buttons_action_processing_class'] ){
				$this->table = $this->datatables_settings['buttons_action_processing_class'];
			}
			
			
			//Check for New record privilege
			$gsettings = array( "force" => 1 );
			$classname = $this->table;
			$access_class = $classname;
			
			if( isset( $this->datatables_settings['access_control_class'] ) && $this->datatables_settings['access_control_class'] ){
				$access_class = $this->datatables_settings['access_control_class'];
			}
			$access_class2 = $access_class;
			if( isset( $this->datatables_settings[ 'plugin' ] ) && $this->datatables_settings[ 'plugin' ] ){
				$access_class = $this->datatables_settings[ 'plugin' ] . '.' . $access_class;
			}
			
			//echo $access_class; exit;
			$allow_new = 1;
			if( ! $super && ! ( isset( $access["accessible_functions"][ $access_class . ".create_new_record" ] ) || isset( $access["accessible_functions"][ $access_class2 . ".create_new_record" ] ) ) ){
				$allow_new = 0;
			}
			if( isset( $this->datatables_settings[ "access_add_new" ] ) && $this->datatables_settings[ "access_add_new" ] ){
				$allow_new = 1;
			}
			
			$allow_import_excel_table = 1; //permission($current_user_session_details,'import_excel_table',$classname,$this->database_connection,$this->database, $gsettings );
			
			$allow_editing_records = 0;
			if(isset($this->datatables_settings['show_edit_button']) && $this->datatables_settings['show_edit_button']){
				$allow_editing_records = 1;
				if( ! $super && ! ( isset( $access["accessible_functions"][ $access_class . ".edit" ] ) || isset( $access["accessible_functions"][ $access_class2 . ".edit" ] ) ) ){
					$allow_editing_records = 0;
				}
				
				$this->datatables_settings['user_can_edit'] = $allow_editing_records;
			}
			
			if( isset( $this->datatables_settings[ "access_edit" ] ) && $this->datatables_settings[ "access_edit" ] ){
				$allow_editing_records = 1;
			}
			
			$allow_deleting_records = 1;
			if( ! $super && ! ( isset( $access["accessible_functions"][ $access_class . ".delete" ] ) || isset( $access["accessible_functions"][ $access_class2 . ".delete" ] ) ) ){
				$allow_deleting_records = 0;
			}
			if( isset( $this->datatables_settings[ "access_delete" ] ) && $this->datatables_settings[ "access_delete" ] ){
				$allow_deleting_records = 1;
			}
			
			$allow_restore_button = 1;
			if( ! $super && ! ( isset( $access["accessible_functions"][ $access_class . ".restore" ] ) || isset( $access["accessible_functions"][ $access_class2 . ".restore" ] ) ) ){
				$allow_restore_button = 0;
			}
			if( isset( $this->datatables_settings[ "access_restore" ] ) && $this->datatables_settings[ "access_restore" ] ){
				$allow_restore_button = 1;
			}
			
			$allow_export = 1;
			if( ! $super && ! ( isset( $access["accessible_functions"][ $access_class . ".export" ] ) || isset( $access["accessible_functions"][ $access_class2 . ".export" ] ) ) ){
				$allow_export = 0;
			}
			if( isset( $this->datatables_settings[ "access_export" ] ) && $this->datatables_settings[ "access_export" ] ){
				$allow_export = 1;
			}
			
			$pplugin = '';
			if(isset( $this->datatables_settings['plugin'] ) && $this->datatables_settings['plugin']){
				$pplugin = '&plugin=' . $this->datatables_settings['plugin'];
			}
			
			
			//CHECK WHETHER OR NOT TO SHOW TOOLBAR
			if(isset($this->datatables_settings['show_toolbar']) && $this->datatables_settings['show_toolbar']){
					
				$hf = array();
				$hf[] = array( 'name' => 'table', 'value' => $this->table );
					
				if( $allow_export ){
					$hide_csv = ( isset( $this->datatables_settings['hide_csv'] ) && $this->datatables_settings['hide_csv'] )?0:1;
					
					$hide_excel = ( isset( $this->datatables_settings['hide_excel'] ) )?$this->datatables_settings['hide_excel']:0;
					
					$xa = array( 'hidden_fields' => $hf, "csv" => $hide_csv, "hide_excel" => $hide_excel );
					if(isset( $this->datatables_settings['plugin'] ) && $this->datatables_settings['plugin']){
						$xa[ 'plugin' ] = $this->datatables_settings['plugin'];
					}
					if(isset( $this->datatables_settings['current_store'] ) && $this->datatables_settings['current_store']){
						$xa[ 'current_store' ] = $this->datatables_settings['current_store'];
					}
					if(isset( $this->datatables_settings['hide_signatories'] ) && $this->datatables_settings['hide_signatories']){ // @steve 29-04-2024 Feature switch to enable the Signatory portions of the export component
						$xa[ 'hide_signatories' ] = $this->datatables_settings['hide_signatories'];
					}

					if( defined("HIDE_EXPORT_SIGNATORY_FIELDS") && HIDE_EXPORT_SIGNATORY_FIELDS ){
						$xa[ 'hide_signatories' ] = 1;
					}

					if(isset( $_GET['current_tab'] ) && $_GET['current_tab']){
						$xa[ 'current_tab' ] = $_GET['current_tab'];
					}
					if(isset( $_POST['id'] ) && $_POST['id']){
						$xa[ 'selected_id' ] = $_POST['id'];
					}
					// print_r( $xa );exit;

					$returning_html_data .= get_export_and_print_popup( "#".$this->data_table_id , "#".$this->data_table_container, "true", 0, $xa );
				  
					//$returning_html_data .= '<a href="#" class="btn btn-mini default btn-sm btn-sm quick-print" target="#'.$this->data_table_container.'" target-table="#'.$this->data_table_id.'" merge-and-clean-data="true" title="Print"><i class="icon-print fa fa-print fa-fw"></i> Print</a>';
					$returning_html_data .= '</div>&nbsp;';
				}
				
				//Toolbar
				$returning_html_data .= '<div data-role="controlgroup" class="btn-group" data-type="horizontal" data-mini="true">';	
					
					//CHECK WHETHER OR NOT TO SHOW ADD NEW RECORD BUTTON
					$button_params = '';
					if( isset( $this->datatables_settings["button_params"] ) && $this->datatables_settings["button_params"] ){
						$button_params = $this->datatables_settings["button_params"];
					}
					
					if(isset($this->datatables_settings['show_add_new']) && $this->datatables_settings['show_add_new']){
						if($allow_new){

							$function_name = 'create_new_record';
							if( isset( $this->datatables_settings['show_add_new']['function-name'] ) )
								$function_name = $this->datatables_settings['show_add_new']['function-name'];
								
							$function_text = 'New Record';
							if( isset( $this->datatables_settings['show_add_new']['function-text'] ) )
								$function_text = $this->datatables_settings['show_add_new']['function-text'];
								
							$function_title = 'Add new record to the dataTable';
							if( isset( $this->datatables_settings['show_add_new']['function-title'] ) )
								$function_title = $this->datatables_settings['show_add_new']['function-title'];
								
							$function_class = $classname;
							if( isset( $this->datatables_settings['show_add_new']['function-class'] ) && $this->datatables_settings['show_add_new']['function-class'] ){
								$function_class = $this->datatables_settings['show_add_new']['function-class'];
							}
							
							$b_prms = ' function-id="-" search-table="" function-class="'.$function_class.'" function-name="'.$function_name.'" module-id="'.$this->datatables_settings['current_module_id'].'" ';
							
							$href = '#';
							$b_class = '';
							if( $button_params ){
								$href = '?action=' . $function_class . '&todo=' . $function_name . $button_params;
								$b_class = 'custom-action-button-url';
							}
							
							if( isset( $this->datatables_settings['plugin'] ) && $this->datatables_settings['plugin'] ){
								$href = '?action='. $this->datatables_settings['plugin'] .'&todo=execute&nwp_action='. $function_class .'&nwp_todo='. $function_name . $button_params;
								$b_class = 'custom-action-button-url';
							}

							if( isset( $this->datatables_settings['new_window_popup']['show_add_new'] ) && $this->datatables_settings['new_window_popup']['show_add_new'] ){
								$href .= '&expandable_details=1&tx_details=1';
								$b_prms = ' target="_blank" action="'. $href .'" override-selected-record="-" ';
								$b_class = 'custom-single-selected-record-button';
								$href = '#';
							}
							
							$returning_html_data .= '<a href="'.$href.'" id="add-new-record" class="'.$b_class.' btn btn-mini btn-sm dark" data-role="button" data-mini="true" data-inline="true" data-theme="e" data-corners="false" data-icon="plus" data-iconpos="notext"  module-name="" title="'.$function_title.'" '. $b_prms .'>'.$function_text.'</a>';
							
							//$returning_html_data .= '<a href="#" id="add-new-record" class="custom-action-button-url btn btn-mini btn-sm dark" data-role="button" data-mini="true" data-inline="true" data-theme="e" data-corners="false" data-icon="plus" data-iconpos="notext" function-id="-" search-table="" function-class="'.$classname.'" function-name="'.$function_name.'" module-id="'.$this->datatables_settings['current_module_id'].'" more_params="'.$button_params.'"  module-name="" title="'.$function_title.'">'.$function_text.'</a>';
						}
					}
					
					//CHECK WHETHER OR NOT TO SHOW IMPORT EXCEL TABLE BUTTON
					if(isset($this->datatables_settings['show_import_excel_table']) && $this->datatables_settings['show_import_excel_table']){
						if($allow_import_excel_table){
							$returning_html_data .= '<a href="#" id="import-excel-table" data-role="button" data-mini="true" data-inline="true" class="btn btn-mini btn-sm dark" data-theme="e" data-corners="false" data-icon="back" function-id="-" search-table="'.$classname.'" function-class="myexcel" function-name="import_excel_table" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Import data from excel file">Import File</a>';
						}
					}
					
					//CHECK WHETHER OR NOT TO SHOW EDIT BUTTON
					if(isset($this->datatables_settings['show_edit_button']) && $this->datatables_settings['show_edit_button']){
						$caption = 'Edit Record';
						$title_text = 'Edit Record the Selected Record';
						
						$function_name = 'edit';
						$function_class = $classname;
						
						if( is_array( $this->datatables_settings['show_edit_button'] ) ){
							if( isset( $this->datatables_settings['show_edit_button']['function-name'] ) ){
								$function_name = $this->datatables_settings['show_edit_button']['function-name'];
							}
							
							if( isset( $this->datatables_settings['show_edit_button']['function-text'] ) ){
								$caption = $this->datatables_settings['show_edit_button']['function-text'];
							}
						
							if( isset( $this->datatables_settings['show_edit_button']['function-title'] ) ){
								$title_text = $this->datatables_settings['show_edit_button']['function-title'];
							}
							
							if( isset( $this->datatables_settings['show_edit_button']['function-class'] ) && $this->datatables_settings['show_edit_button']['function-class'] ){
								$function_class = $this->datatables_settings['show_edit_button']['function-class'];
							}
						}else if( strlen( $this->datatables_settings['show_edit_button'] ) > 1 ){
							$caption = $this->datatables_settings['show_edit_button'];
							$atitle_text = strip_tags( $this->datatables_settings['show_edit_button'] );
						}
                        if( isset( $atitle_text ) && $atitle_text)$title_text = $atitle_text;
                        
						
						if($allow_editing_records){
							
							$ea = '&action='.$function_class.'&todo='. $function_name;
							if(isset( $this->datatables_settings['plugin'] ) && $this->datatables_settings['plugin']){
								$ea = '&action='. $this->datatables_settings['plugin'] .'&todo=execute&nwp_action='.$function_class.'&nwp_todo='. $function_name;
							}
							
							$returning_html_data .= '<a href="#" id="edit-selected-record" class="btn btn-mini btn-sm dark" data-role="button" function-id="'.$allow_editing_records.'" search-table="" function-class="'.$function_class.'" function-name="'. $function_name .'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'] . $ea . $button_params .'" mod="edit-'.md5($function_class).'" todo="edit" title="'.$title_text.'">'.$caption.'</a>';
						}
					}
					
					if(isset($this->datatables_settings['custom_edit_button']) && $this->datatables_settings['custom_edit_button']){
						$returning_html_data .= $this->datatables_settings['custom_edit_button'];
					}
					
					//CHECK WHETHER OR NOT TO SHOW DELETE BUTTON
					if( isset($this->datatables_settings['show_restore_button']) && $this->datatables_settings['show_restore_button']){
						
						if( $allow_restore_button ){
							$function_name = 'restore';
							if( isset( $this->datatables_settings['show_restore_button']['function-name'] ) ){
								$function_name = $this->datatables_settings['show_restore_button']['function-name'];
							}
								
							$function_text = 'Restore';
							if( isset( $this->datatables_settings['show_restore_button']['function-text'] ) )
								$function_text = $this->datatables_settings['show_restore_button']['function-text'];
								
							$function_title = 'Restore selected record(s)';
							if( isset( $this->datatables_settings['show_restore_button']['function-title'] ) )
								$function_title = $this->datatables_settings['show_restore_button']['function-title'];
							
							$function_class = $classname;
							if( isset( $this->datatables_settings['show_restore_button']['function-class'] ) && $this->datatables_settings['show_restore_button']['function-class'] ){
								$function_class = $this->datatables_settings['show_restore_button']['function-class'];
							}
							
							$ea = '&action='.$function_class.'&todo='. $function_name;
							if(isset( $this->datatables_settings['plugin'] ) && $this->datatables_settings['plugin']){
								if( isset( $this->datatables_settings['show_restore_button']['no_plugin'] ) ){
									$ea .= '&source_plugin='. $this->datatables_settings['plugin'];
								}else{
									$ea = '&action='. $this->datatables_settings['plugin'] .'&todo=execute&nwp_action='.$function_class.	'&nwp_todo='. $function_name;
								}
							}
							
							if( $this->theme == 'v3' ){
								$returning_html_data .= '<a href="javascript:;" class="btn btn-mini btn-sm dark custom-multi-selected-record-button" function-id="'.$allow_deleting_records.'" search-table="" function-class="'.$function_class.'" function-name="'.$function_name.'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'] . $ea . '" mod="delete-'.md5($this->table).'" todo="'.$function_name.'" title="'.$function_title.'" confirm-prompt="restore the selected record(s)">'.$function_text.'</a>';
									
							}else{
								$returning_html_data .= '<a href="#" id="restore-selected-record" class="btn btn-mini btn-sm dark pop-up-button" data-role="button"  data-iconpos="notext" data-mini="true" data-inline="true" data-theme="e" data-corners="false" data-icon="restore" function-id="'.$allow_deleting_records.'" search-table="" function-class="'.$function_class.'" function-name="'.$function_name.'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'] . $ea . '" mod="delete-'.md5($this->table).'" todo="'.$function_name.'" data-toggle="popover" data-trigger="manual" data-placement="bottom" title="'.$function_title.'">'.$function_text;
								$returning_html_data .= '</a>';
								
								$returning_html_data .= '<div class="pop-up-content" style="display:none;">';
								$returning_html_data .= 'Are you sure you want to restore the selected record(s)<br /><br /><input type="button" class="btn btn-mini btn-sm btn-primary" value="Yes" id="restore-button-yes" />&nbsp;<input type="button" value="No" class="btn btn-mini" id="restore-button-no" />';
								$returning_html_data .= '</div>';
							}
						}
					}else if(isset($this->datatables_settings['show_delete_button']) && $this->datatables_settings['show_delete_button']){
						if($allow_deleting_records){
							$function_name = 'delete';
							if( isset( $this->datatables_settings['show_delete_button']['function-name'] ) )
								$function_name = $this->datatables_settings['show_delete_button']['function-name'];
								
							$function_text = 'Delete';
							if( isset( $this->datatables_settings['show_delete_button']['function-text'] ) )
								$function_text = $this->datatables_settings['show_delete_button']['function-text'];
								
							$function_title = 'Delete selected record(s)';
							if( isset( $this->datatables_settings['show_delete_button']['function-title'] ) )
								$function_title = $this->datatables_settings['show_delete_button']['function-title'];
							
							$function_class = $classname;
							if( isset( $this->datatables_settings['show_delete_button']['function-class'] ) && $this->datatables_settings['show_delete_button']['function-class'] ){
								$function_class = $this->datatables_settings['show_delete_button']['function-class'];
							}
							
							$ea = '&action='.$function_class.'&todo='. $function_name;
							if(isset( $this->datatables_settings['plugin'] ) && $this->datatables_settings['plugin']){
								$ea = '&action='. $this->datatables_settings['plugin'] .'&todo=execute&nwp_action='.$function_class.'&nwp_todo='. $function_name;
							}

							if( $this->theme == 'v3' ){
								$returning_html_data .= '<a href="javascript:;" class="btn btn-mini btn-sm dark custom-multi-selected-record-button" function-id="'.$allow_deleting_records.'" search-table="" function-class="'.$function_class.'" function-name="'.$function_name.'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'] . $ea . '" mod="delete-'.md5($this->table).'" todo="'.$function_name.'" title="'.$function_title.'" confirm-prompt="delete the selected record(s)">'.$function_text.'</a>';
									
							}else{
								$returning_html_data .= '<a href="#" id="delete-selected-record" class="btn btn-mini btn-sm dark pop-up-button" data-role="button"  data-iconpos="notext" data-mini="true" data-inline="true" data-theme="e" data-corners="false" data-icon="delete" function-id="'.$allow_deleting_records.'" search-table="" function-class="'.$function_class.'" function-name="'.$function_name.'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'] . $ea . '" mod="delete-'.md5($this->table).'" todo="'.$function_name.'" data-toggle="popover" data-bs-toggle="popover" data-trigger="manual" data-bs-trigger="manual" data-placement="bottom" data-bs-placement="bottom" title="'.$function_title.'">'.$function_text;
								$returning_html_data .= '</a>';
								
								$returning_html_data .= '<div class="pop-up-content" style="display:none;">';
								$returning_html_data .= 'Are you sure you want to delete the selected record(s)<br /><br /><input type="button" class="btn btn-mini btn-sm btn-primary" value="Yes" id="delete-button-yes" />&nbsp;<input type="button" value="No" class="btn btn-mini" id="delete-button-no" />';
								$returning_html_data .= '</div>';
							}
						}
					}
					
					$show_rcache = 0;
					if( isset( $this->datatables_settings['show_refresh_cache'] ) && $this->datatables_settings['show_refresh_cache'] ){
						$show_rcache = 1;
					}

					if( $show_rcache ){
						$function_name = 'refresh_cache';
						$ea = '&action='.$this->table.'&todo='. $function_name;
						if(isset( $this->datatables_settings['plugin'] ) && $this->datatables_settings['plugin']){
							$ea = '&action='. $this->datatables_settings['plugin'] .'&todo=execute&nwp_action='.$this->table.'&nwp_todo='. $function_name;
						}
						$rcl = 'custom-single-selected-record-button';
						if( isset( $this->datatables_settings[ 'refresh_cache_multiple' ] ) && $this->datatables_settings[ 'refresh_cache_multiple' ] ){
							$rcl = 'custom-multi-selected-record-button';
						}
						
						$returning_html_data .= '<a href="#" class="'. $rcl .' btn btn-mini btn-sm dark" override-selected-record="1" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" action="?module='.$this->datatables_settings['current_module_id'] . $ea . '" mod="1" title="Refresh Cache">Refresh Cache</a>';
					}
					
				$returning_html_data .= '</div>&nbsp;';
				
                if(isset($this->datatables_settings['custom_single_select_button']) && $this->datatables_settings['custom_single_select_button']){
                    $returning_html_data .= $this->datatables_settings['custom_single_select_button'];
                }
                    
                if(isset($this->datatables_settings['custom_multi_select_button']) && $this->datatables_settings['custom_multi_select_button']){
                    $returning_html_data .= $this->datatables_settings['custom_multi_select_button'];
                }
                    
				$returning_html_data .= '<div data-role="controlgroup" class="btn-group" data-type="horizontal" data-mini="true">';	
					
					$show_cancel = 0;
					//CHECK WHETHER OR NOT TO SHOW BULK EDIT BUTTON
					if(isset($this->datatables_settings['show_bulk_edit']) && $this->datatables_settings['show_bulk_edit']){
						//Advance Search Button

						$returning_html_data .= '<a href="#" class="btn btn-sm dark custom-single-selected-record-button" override-selected-record="1" action="?action=search&todo=bulk_edit&table='.$this->table . $pplugin .'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Perform edit on multiple records">Bulk Edit</a>';

						$show_cancel = 1;
					}

					//CHECK WHETHER OR NOT TO SHOW ADVANCE SEARCH BUTTON
					if(isset($this->datatables_settings['popup_search']) && $this->datatables_settings['popup_search'] && class_exists( 'cNwp_reports' )){
						
						if(isset($this->datatables_settings['form_data']) && $this->datatables_settings['form_data']){
							//$returning_html_data .= '<a href="#" class="btn btn-sm dark custom-single-selected-record-buttonX" override-selected-record="1" actionX="?action=search&todo=search_window&table='.$this->table . $pplugin .'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Quick Search"><i class="icon-search"></i></a>';
							
							$returning_html_data .= '<div class="btn-group"><a type="button" class="btn btn-sm dark dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"><i class="icon-search"></i></a><div class="dropdown-backdrop"></div><ul class="dropdown-menu hold-on-click" role="menu" style="padding: 10px 20px; box-shadow: 1px 3px 5px #ddd; margin:0; list-style:none; max-height:360px; min-width:460px;  overflow-y:auto; text-align:left;">' . $this->datatables_settings['form_data'] . '</ul></div>';
						}
						
						//@nw5
						$adx = '';
						if( isset( $this->datatables_settings['real_table'] ) && $this->datatables_settings['real_table'] ){
							$adx .= '&real_table=' . $this->datatables_settings['real_table'];
						}
						
						if( isset( $this->datatables_settings['db_table'] ) && $this->datatables_settings['db_table'] ){
							$adx .= '&db_table=' . $this->datatables_settings['db_table'];
						}
						if( isset( $this->datatables_settings['show_selection']["table_fields_filter"] ) && $this->datatables_settings['show_selection']["table_fields_filter"] ){
							$adx .= '&db_table_filter=' . $this->datatables_settings['show_selection']["table_fields_filter"];
						}
						
						//Advance Search Button
						$returning_html_data .= '<a href="#" class="btn btn-sm dark custom-single-selected-record-button" override-selected-record="1" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=search_window&table='.$this->table . $pplugin . $adx .'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Perform advance search query">Advance Search</a>';
						// $returning_html_data .= '<a href="#" class="btn btn-sm dark custom-single-selected-record-button" override-selected-record="1" action="?action=search&todo=search_window&table='.$this->table . $pplugin . $adx .'" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Perform advance search query">Advance Search</a>';
						
						$show_cancel = 1;
					}

					if( $show_cancel ){
						//check for search query
						$xstyle = 'display:none;';

						$sq = md5('search_query'.$_SESSION['key']);
						if( isset( $_SESSION[$sq][ $this->table ]['query'] ) && $_SESSION[$sq][ $this->table ]['query'] ){
							$xstyle = '';
						}
						
						$returning_html_data .= '<a href="#" id="clear-advance-search-button" style="'.$xstyle.'" class="btn btn-sm red 	custom-single-selected-record-button" override-selected-record="1" action="?action=search&todo=clear_search_window&table='.$this->table . $pplugin . '" title="Clear advance search query"><i class="icon-remove"></i></a>';
					}
					
					//CHECK WHETHER OR NOT TO SHOW ADVANCE SEARCH BUTTON
					if(isset($this->datatables_settings['show_advance_search']) && $this->datatables_settings['show_advance_search']){
						//Advance Search Button
						$returning_html_data .= '<a href="#" id="advance-search" class="btn btn-mini btn-sm dark " data-role="button" data-mini="true" data-inline="true" data-theme="f" data-corners="false" data-icon="search" function-id="search" function-class="search" search-table="'.$this->table.'" function-name="search" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Perform advance search query">Advance Search</a>';
						
						//Advance Clear Search Button
						$returning_html_data .= '<a href="#" id="clear-search"class="btn btn-mini btn-sm dark "  data-role="button" data-mini="true" data-inline="true" data-theme="f" data-corners="false" data-icon="delete" function-id="clear_search" function-class="search" search-table="'.$this->table.'" function-name="clear_search" module-id="'.$this->datatables_settings['current_module_id'].'" module-name="" title="Clear the advance search query">Clear Search</a>';
						
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
						$returning_html_data .= '<a href="#" class="btn btn-mini btn-sm default dropdown-toggle" id="hide-show-columns" title="Hide / Show Columns" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" data-toggle="dropdown" data-placement="bottom">Show Columns <!--<i class="icon-caret-down"></i>-->';
						$returning_html_data .= '</a>';
						
						$split_screen = '';
						
						  if( isset( $this->datatables_settings['split_screen'] ) && ! empty( $this->datatables_settings['split_screen'] ) ){
							
							$split_screen_action = isset( $this->datatables_settings['split_screen']['action'] )?rawurlencode( $this->datatables_settings['split_screen']['action'] ):'';
							
							
							$eparam = isset( $this->datatables_settings['split_screen']['more_params'] )?( $this->datatables_settings['split_screen']['more_params'] ):'';
							
							$split_screen_checked = isset( $this->datatables_settings['split_screen']['checked'] )?$this->datatables_settings['split_screen']['checked']:'';
							
							if( $split_screen_checked ){
								$eparam .= '&checked=1';
							}
							
							$sm = isset( $this->datatables_settings['datatable_method'] )?$this->datatables_settings['datatable_method']:'';
							 
							$split_screen = '<li><label class="checkbox"><input '.$split_screen_checked.' type="checkbox" class="custom-single-selected-record-button nw-skip" allow-default="1" action="?action=column_toggle&todo=split_screen&method='. $sm .'&html_replacement_selector=data-table-section&selected_record_action='. $split_screen_action . $eparam .'" override-selected-record="'.$this->table.'" >Split Screen</label></li>';
						  }
						  
						  if( $split_screen ){
							  $split_screen = '<ul style="padding:0; margin:0; list-style:none;">'. $split_screen .'</ul><hr />';
						  }
						  
						$returning_html_data .= '<div class="pop-up-contentX dropdown-menu hold-on-click" style="displayX:none; margin:0; box-shadow: 1px 3px 5px #ddd; padding-left:10px; list-style:none;  background-color: #F5F5F5;  border: 1px solid #CCCCCC" aria-labelledby="hide-show-columns">'. $split_screen .'<ul class="show-hide-column-con" style="padding:0; margin:0; list-style:none; max-height:380px; overflow-y:auto;" data-table="'.$this->data_table_id.'">';
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
			if( $id == 'date-5' || $id == 'date-5time' ){
				
				if( $_POST[$key] ){
					if( $id == 'date-5time' ){
						return convert_date_to_timestamp( rawurldecode( $_POST[$key] ), 4 );
					}else{
						if( isset( $this->attributes[ $key ][ 'save_date_from_start' ] ) && $this->attributes[ $key ][ 'save_date_from_start' ] ){
							return convert_date_to_timestamp( rawurldecode( $_POST[$key] ), 1 );
						}else{
							return convert_date_to_timestamp( rawurldecode( $_POST[$key] ) );
						}
					}
				}else{
					return 0;
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
			
			if( $id == 'date_time' ){
				if( isset( $_POST[$key.'cus88timestamp'] ) ){
					return doubleval( $_POST[$key.'cus88timestamp'] );
				}
				return 0;
			}
			if( $id == 'time' ){
				if( isset( $_POST[$key] ) ){
					return str_replace( ":", ".", $_POST[$key] );
				}
				return 0;
			}
		}
		
		private function system_values( $settings = array() ){
			//GENERATE ALL SYSTEM VALUES
			
			$record_id = 'undefined';
			
			$device_id = '';
			$created_source = 'undefined';
			$user_privilege = 'undefined';
			$created_by = 'undefined';
			$creation_date = 0;
			$update = 0;
			
			if( isset( $_POST['id'] ) && ! $_POST['id'] ){
				if( isset( $_POST['uid'] ) ){
					$created_by = $_POST['uid'];
					if( $this->upid ){
						$created_source = $this->upid . '.';
					}else{
						$created_source = '';
					}
					$created_source .= $this->utid;
				}
				
				if( isset( $_POST['user_priv'] ) )
					$user_privilege = $_POST['user_priv'];
				
				$creation_date = date("U");
				if( isset( $_POST["tmp_creation_date"] ) && doubleval( $_POST["tmp_creation_date"] ) ){
					$creation_date = doubleval( $_POST["tmp_creation_date"] );
				}
				
				$prefix = '';
				if( $this->table ){
					$prefix = substr( $this->table, 0, 1 );
					$prefix .= substr( $this->table, -2 );
				}
				
				$record_id = $prefix . get_new_id();
			}else if( isset( $_POST['id'] ) && $_POST['id'] ){
				$record_id = $_POST['id'];
				$update = 1;
			}
			
			if( isset( $_POST['device_id'] ) && $_POST['device_id'] ){
				$device_id = $_POST['device_id'];
			}
			
			if( isset( $_POST['tmp'] ) && $_POST['tmp'] ){
				$record_id = $_POST['tmp'];
				unset( $_POST['tmp'] );
			}
			
			$this->update_state = $update;
			$this->record_id = $record_id;
			
			$modified_by = '';
			$modified_source = '';
			if( isset( $_POST['uid'] ) ){
				$modified_by = $_POST['uid'];
				if( $this->upid ){
					$modified_source = $this->upid . '.';
				}else{
					$modified_source = '';
				}
				$modified_source .= $this->utid;
			}
			return array(
				'update' => $update,
				'id' => $record_id,
				'creator_role' => $user_privilege,
				'record_status' => 1,
				'created_by' => $created_by,
				'creation_date' => $creation_date,
				'modified_by' => $modified_by,
				'created_source' => $created_source,
				'modified_source' => $modified_source,
				'modification_date' => date("U"),
				'ip_address' => get_ip_address(),
				'device_id' => $device_id,
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
			$allow_zero = 0;
			if( isset( $field_details['callback']['save'] ) && function_exists( $field_details['callback']['save'] ) ){
				$ff = $field_details['callback']['save'];
				$data = $ff( $data );
			}
			
			switch( $field_details[ 'form_field' ] ){
			case 'text-file':	//'text' 
            case 'calculated':
			case 'text':	//'text' 
			case 'select':	//'select',
			
				if(strlen($data) > 200){
					$data = substr($data,0,200);
				}
				
			break;
			case 'passphrase':	//'text' 
				$data = md5( strtolower( trim( $data ) ) . get_websalter() );
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
				/*
				if( get_currency_version2() ){
					$data = doubleval( $data );
				}else{
					$to = 'to usd';
					if( isset( $field_details['default_currency_field'] ) && isset( $_POST[ $field_details['default_currency_field'] ] ) && $_POST[ $field_details['default_currency_field'] ] && ( $_POST[ $field_details['default_currency_field'] ] != 'undefined' ) ){
						$to = 'to '.$_POST[ $field_details['default_currency_field'] ];
					}
					$data = convert_currency( $data , $to );
                }
			break;*/
			case 'number':
			case 'decimal_long':
			case 'decimal':
				$data = doubleval( clean_numbers( $data ) );
				$allow_zero = 1;
			break;
			case 'textarea-unlimited': 	//'textarea',
				$data = $data;
				//$data = addslashes( $data );
                /*
				if( strlen($data) > 6000000 ){
					$data = substr($data,0,6000000);
				}
				*/
			break;
			case 'textarea-unlimited-med':
			case "field_group":
			case "html":
			case 'textarea-norestriction': 	//'textarea',
			break;
			case 'textarea': 	//'textarea',
				$data = strip_tags( $data , '<ul><ol><li><p><a><br><div><span><h1><h2><h3><h4><h5><h6><hr><table><td><tr><th><tbody><thead><img><iframe><pre><address><b><strong><i><u>' );
				if( strlen($data) > 3990){
					$data = substr($data,0,3950);
				}
				//$data = addslashes( $data );
			break;
			case 'old-password': 	//'password',
				$data = md5( $data.get_websalter() );
			break;
			case 'password': 	//'password',
				//ENFORCE PASSWORD POLICY
				//return md5($_POST[$key].get_websalter());
				
				//1. Check password length
				$mlen = 6;
				if( defined("HYELLA_MINIMUM_PASSWORD_LENGTH") && HYELLA_MINIMUM_PASSWORD_LENGTH ){
					$mlen = HYELLA_MINIMUM_PASSWORD_LENGTH;
				}
				
				if( $data && strlen( $data ) > $mlen ){
					
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
					$this->error_msg_body = 'Please review our password policy.<br /><br />Password must be at least four(4) Characters long';
				}
				
				$data = '';
				
			break;
			case 'date-5time':
			case 'date-5':
			case 'date':
			case 'datetime':
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
			case "checkbox":
				if( is_array( $data ) && ! empty( $data ) ){
					$data = implode( ":::", $data );
				}
			break;
			}
			
			if( ! empty( $data ) ){
				$data = trim( $data );
			}
			$test_data = $data;
			
			switch( $field_details[ 'form_field' ] ){
			case "textarea":
			case "text":
				if( $data == '0' ){
					$test_data = 'NIL';
				}
			break;
			}
			
			if( $allow_zero ){
				if( isset( $this->form_settings["accept_zero"] ) && $this->form_settings["accept_zero"] && intval( $test_data ) == 0 ){
					$test_data = 1;
				}else if( defined("NWP_ACCEPT_ZERO_AS_NUMBER") && NWP_ACCEPT_ZERO_AS_NUMBER && intval( $test_data ) == 0 ){
					$test_data = 1;
				}else if( defined("NWP_ACCEPT_ZERO_AS_NUMBER_TABLE") && NWP_ACCEPT_ZERO_AS_NUMBER_TABLE && in_array( $this->table, explode( ",", NWP_ACCEPT_ZERO_AS_NUMBER_TABLE ) ) && intval( $test_data ) == 0 ){
					$test_data = 1;
				}
			}
			
			if( isset( $field_details[ 'required_field' ] ) && $field_details[ 'required_field' ]=='yes' && ( ! $test_data ) && ( ! ( $field_details['form_field']=='file' && $this->update_state )  ) && ( ! $this->skip_form_field_validation ) ){
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

		//13-mar-23
		public function clear_checksum( $o = array() ){
			//disabled to allow multiple clicks on save button if form is still open
			/* if( isset( $o["nw_checksum"] ) && $o["nw_checksum"] && isset( $_SESSION['checksum'][ $o["nw_checksum"] ] ) ){
				unset( $_SESSION['checksum'][ $o["nw_checksum"] ] );
			} */
			
			//clear expired checksums
			if( isset( $_SESSION['checksum'] ) && is_array( $_SESSION['checksum'] ) && ! empty( $_SESSION['checksum'] ) ){
				$now = date("U") - ( 3600 * 4 );
				foreach( $_SESSION['checksum'] as $ck => $chk ){
					if( isset( $chk["time"] ) && $now > ( $chk["time"] ) ){
						unset( $_SESSION['checksum'][ $ck ] );
					}
				}
			}
		}
    }
?>