<?php
	/**
	 * modules Class
	 *
	 * @used in  				modules Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	modules
	 */

	/*
	|--------------------------------------------------------------------------
	| modules Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cFaq{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'support_enquiry';
		
		private $table_fields = array(
			'email_address' => 'support_enquiry001',
			'ask_us_anything' => 'support_enquiry002',
			'faq_title' => 'support_enquiry003',
			'same_enquiry' => 'support_enquiry004',
			'processing_status' => 'support_enquiry005',
            
			'faq_category' => 'support_enquiry006',
			'reply' => 'support_enquiry007',
		);
		
		function faq(){
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
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'create_new_record':
			case 'edit':
				$returned_value = $this->_generate_new_data_capture_form();
			break;
			case 'display_all_records':
				$returned_value = $this->_display_data_table();
			break;
			case 'delete':
				$returned_value = $this->_delete_records();
			break;
			case 'save':
				$returned_value = $this->_save_changes();
				
				//Add id of Newly Created Record
				if( $this->current_record_id ){
					$returned_value['saved_record_id'] = $this->current_record_id;
				}
			break;
			case 'save_site_changes':
				$returned_value = $this->_save_site_changes();
			break;
			case 'update_cache':
				$returned_value = $this->_update_cache();
			break;
			case 'get_faq':
				$returned_value = $this->_get_faq();
			break;
			case 'get_recent_faq':
				$returned_value = $this->_get_recent_faq();
			break;
			case 'get_faq_list':
				$returned_value = $this->_get_faq_list();
			break;
			case 'get_faq':
				$returned_value = $this->_get_faq();
			break;
			case 'get_support_ticket_form':
				$returned_value = $this->_get_support_ticket_form();
			break;
			case 'search_faq':
				$returned_value = $this->_search_faq();
			break;
			}
			
			return $returned_value;
		}
		
		private function _generate_new_data_capture_form(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'form_action_todo' ] = 'save';
			
			$process_handler->class_settings[ 'action_to_perform' ] = 'generate_data_capture_form';
			
			$returning_html_data = $process_handler->process_handler();
			
			return array(
				'html' => $returning_html_data[ 'html' ],
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-data-capture-form',
				'message' => 'Returned form data capture form',
			);
		}
		
		private function _delete_records(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'delete_records';
			
			$returning_html_data = $process_handler->process_handler();
			
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'deleted-records';
			
            $settings = array(
                'cache_key' => $this->table_name . '-' . 'recent-support-enquiry',
                'directory_name' => $this->table_name,
                'permanent' => true,
            );
            clear_cache_for_special_values( $settings );
            
			return $returning_html_data;
		}
		
		private function _display_data_table(){
			//GET ALL FIELDS IN TABLE
            $_SESSION[ $this->table_name ][ 'filter' ][ 'where' ] = " AND `".$this->table_fields['faq_category']."`!='1001' ";
            
            $sq = md5('column_toggle'.$_SESSION['key']);
            if(isset($_SESSION[$sq][$this->table_name])){
                unset($_SESSION[$sq][$this->table_name]);
            }
            
			$fields = array();
			$query = "DESCRIBE `".$this->class_settings['database_name']."`.`".$this->table_name."`";
			$query_settings = array(
				'database'=>$this->class_settings['database_name'],
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'DESCRIBE',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			if($sql_result && is_array($sql_result)){
				foreach($sql_result as $sval)
					$fields[] = $sval;
			}else{
				//REPORT INVALid TABLE ERROR
				$err = new cError('000001');
				$err->action_to_perform = 'notify';
				
				$err->class_that_triggered_error = 'cfaq.php';
				$err->method_in_class_that_triggered_error = '_display_data_table';
				$err->additional_details_of_error = 'executed query '.str_replace("'","",$query).' on line 208';
				return $err->error();
			}
			
			
			//INHERIT FORM CLASS TO GENERATE TABLE
			$form = new cForms();
			$form->setDatabase( $this->class_settings['database_connection'] , $this->table_name , $this->class_settings['database_name'] );
			$form->uid = $this->class_settings['user_id']; //Currently logged in user id
			$form->pid = $this->class_settings['priv_id']; //Currently logged in user privilege
			
			$form->datatables_settings = array(
				'show_toolbar' => 1,				//Determines whether or not to show toolbar [Add New | Advance Search | Show Columns will be displayed]
					'show_add_new' => 1,			//Determines whether or not to show add new record button
					'show_advance_search' => 1,		//Determines whether or not to show advance search button
					'show_column_selector' => 1,	//Determines whether or not to show column selector button
					'show_units_converter' => 0,	//Determines whether or not to show units converter
						'show_units_converter_volume' => 0,
						'show_units_converter_currency' => 0,
						'show_units_converter_currency_per_unit_kvalue' => 0,
						'show_units_converter_kvalue' => 0,
						'show_units_converter_time' => 0,
						'show_units_converter_pressure' => 0,
					'show_edit_button' => 1,		//Determines whether or not to show edit button
					'show_delete_button' => 1,		//Determines whether or not to show delete button
					
				'show_timeline' => 0,				//Determines whether or not to show timeline will be shown
					//'timestamp_action' => $this->action_to_perform,	//Set Action of Timestamp
				
				'show_details' => 1,				//Determines whether or not to show details
				'show_serial_number' => 1,			//Determines whether or not to show serial number
				
				'show_verification_status' => 0,	//Determines whether or not to show verification status
				'show_creator' => 0,				//Determines whether or not to show record creator
				'show_modifier' => 0,				//Determines whether or not to show record modifier
				'show_action_buttons' => 0,			//Determines whether or not to show record action buttons
				
				'current_module_id' => $this->class_settings['current_module'],	//Set id of the currently viewed module
				'real_table' => 'faq',	//Set id of the currently viewed module
			);
			
			$returning_html_data = $form->myphp_dttables($fields);
			
			return array(
				'html' => $returning_html_data,
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'display-datatable',
			);
		}
		
		private function _save_changes(){
			$returning_html_data = array();
			
			$process_handler = new cProcess_handler();
			$process_handler->class_settings = $this->class_settings;
			$process_handler->class_settings[ 'database_table' ] = $this->table_name;
			$process_handler->class_settings[ 'action_to_perform' ] = 'save_changes_to_database';
			
			$returning_html_data = $process_handler->process_handler();
			
			$this->_update_cache( $returning_html_data );
			
            $settings = array(
                'cache_key' => $this->table_name . '-' . 'recent-support-enquiry',
                'directory_name' => $this->table_name,
                'permanent' => true,
            );
            clear_cache_for_special_values( $settings );
            
			$returning_html_data['method_executed'] = $this->class_settings['action_to_perform'];
			$returning_html_data['status'] = 'saved-form-data';
			
			return $returning_html_data;
		}
		
        private function _save_site_changes(){
            $returned_value = $this->_save_changes();
            
            if( isset( $returned_value['typ'] ) && $returned_value['typ'] == 'saved' ){
                $err = new cError('010011');
                $err->action_to_perform = 'notify';
                $err->html_format = 3;
                
                $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
                $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
                $err->additional_details_of_error = '<p>Your support ticket has been successfully created</p><p>A member of our support team will contact you shortly</p><br /><p><a href="?page=support" title="Open A New Ticket" class="btn btn-warning">Report another issue</a></p>';
                
                $returned_value = array_merge( $returned_value , $err->error() );
                $returned_value['status'] = 'support-ticket-created';
            }
            return $returned_value;
        }
        
        private function _get_support_ticket_form(){
            $this->class_settings['form_class'] = 'activate-ajax';
            $this->class_settings['do_not_show_headings'] = 1;
            $this->class_settings[ 'form_submit_button' ] = faq_SUBMIT_REQUEST_FORM_BUTTON_CAPTION;
            $this->class_settings[ 'form_clear_button' ] = faq_SUBMIT_REQUEST_FORM_CLEAR_BUTTON_CAPTION;
            
            $this->class_settings['form_action'] = '?action='.$this->table_name.'&todo=save_site_changes';
            
            $this->class_settings[ 'show_recaptcha' ] = 1;
            
            foreach( $this->table_fields as $k => $v ){
                switch( $k ){
                case 'email_address':
                case 'ask_us_anything':
                break;
                default:
                    $this->class_settings[ 'hidden_records' ][$v] = 1;
                break;
                }
            }
            
            return $this->_generate_new_data_capture_form();
        }
        
		private function _update_cache( $settings = array() ){
            return 0;
            
			$query = "";
			
            $select = '';
            foreach( $this->table_fields as $key => $val ){
                if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
                else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
            }
            
            $all = 0;
			if( isset( $settings['saved_record_id'] ) ){
                //Pull up user role record to get functions data  
                $query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `id`='" . $settings['saved_record_id'] . "'";
            }else{
                $query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1'";
                $all = 1;
            }
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query( $query_settings );
			
            $cached_values = array();
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $a ){
                    if( $a['country'] && $a['country'] != 'default' && $a['country'] != 'undefined' ){
						$key = $a['country'];
					}else{
						$key = 'default';
					}
                    
                    if( ! $all ){
                        $cache_settings = array(
                            'cache_key' => $this->table_name.'-'.$key,
                            'directory_name' => $this->table_name,
                            'permanent' => true,
                        );
                        $cached_values[ $key ] = get_cache_for_special_values( $cache_settings );
                    }
                    
                    $cached_values[ $key ][ $a['id'] ] = $a;
				}
			}
			
            foreach( $cached_values as $k => $v ){
                $cache_settings = array(
                    'cache_key' => $this->table_name.'-'.$k,
                    'cache_values' => $v,
                    'directory_name' => $this->table_name,
                    'permanent' => true,
                );
                set_cache_for_special_values( $cache_settings );
            }
		}
		
		private function _get_recent_faq(){
			$topic = 'recent-support-enquiry';
            
            $limit = " ORDER BY `creation_date` DESC LIMIT 0, 20 ";
            
            $cache_settings = array(
				'cache_key' => $this->table_name.'-'.$topic,
				'permanent' => true,
			);
			clear_cache_for_special_values( $cache_settings );
			$cached_values = get_cache_for_special_values( $cache_settings );
            
			if( $cached_values ){
				return $cached_values;
			}
			
			$select = "";
			
			foreach( $this->table_fields as $key => $val ){
                if( $select )$select .= ", `".$val."` as '".$key."'";
                else $select = "`id`, `serial_num`, `creation_date`, `".$val."` as '".$key."'";
			}
			
			//Pull up user role record to get functions data
			$query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `record_status`='1' AND `".$this->table_fields['email_address']."` != '' AND `".$this->table_fields['email_address']."` != '0' ".$limit;
			
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query($query_settings);
			
			$cached_values = array();
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				foreach( $sql_result as $a ){
					$cached_values[ $a['id'] ] = $a;
				}
			}
			
			$cache_settings = array(
				'cache_key' => $this->table_name.'-'.$topic,
				'cache_values' => $cached_values,
				'permanent' => true,
			);
			set_cache_for_special_values( $cache_settings );
			
			return $cached_values;
		}
		
        private function _search_faq(){
            $return = array();
            
            $script_compiler = new cScript_compiler();
			$script_compiler->class_settings = $this->class_settings;
            
            $categories = enquiry_category_frontend();
            
            $s = '';
            if( isset( $_GET['s'] ) ){
                $s = '<strong>'.$_GET['s'].'</strong>';
                
                //get category title
                if( isset( $_GET['categories'] ) && isset( $categories[ $_GET['categories'] ] ) ){
                    if( $_GET['s'] )
                        $s .= " in";
                        
                    $s .= " category <strong>" . $categories[ $_GET['categories'] ].'</strong>';
                }
                
                if( ! $_GET['s'] )
                    $_GET['s'] = ' ';
            }
            
            $script_compiler->class_settings[ 'data' ][ 'faq_list' ] = $this->_get_faq_list();
            $script_compiler->class_settings[ 'data' ][ 'faq_list_title' ] = 'Search Results for: <i>'.$s.'</i>';
            $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/faq-list.php' );
			
			$script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
			$search_result = $script_compiler->script_compiler();
            
            return array(
                'status' => 'got-faq-search-results',
                'html' => $search_result,
            );
        }
        
        private function _build_search_query( $settings = array() ){
            
            $query = "";
            $query1 = "";
            
            $group = " GROUP BY `".$this->table_name."`.`id` ";
            if( isset( $settings['group'] ) && $settings['group'] ){
                $group = $settings['group'];
            }
            
            $query .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP ' ".$settings['s']." ' ".$group." ) UNION ";
            
            $query .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP ' ".$settings['s']."' ".$group." ) UNION ";
            
            $query .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['faq_title']."` LIKE '%".$settings['s']."' ".$group." ) UNION ";
            
            $query .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP '".$settings['s']."' ".$group." ) UNION ";
           
            $query1 .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP ' ".$settings['s']." ' ".$group." ) UNION ";
            
            $query1 .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP ' ".$settings['s']."' ".$group." ) UNION ";
            
            $query1 .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` LIKE '%".$settings['s']."' ".$group." ) UNION ";
            
            $query1 .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP '".$settings['s']."' ".$group." ) UNION ";
           
            $query_options = array();
            $query_options1 = array();
            $search_words = explode( ' ' , trim( $settings['s'] ) );
            if( isset( $search_words[0] ) && $search_words[0] ){
            
                $search_words_condition = '';
                $search_words_condition_1 = '';
                
                $search_words_condition1 = '';
                $search_words_condition1_1 = '';
                foreach( $search_words as $search_word ){
                    
                    $query_options[1][] = "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP ' ".$search_word." ' ".$group.") UNION ";
                    
                    $query_options[2][] = "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP ' ".$search_word."' ".$group.") UNION ";
                    
                    $query_options[3][] = "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND `".$this->table_name."`.`".$this->table_fields['faq_title']."` LIKE '%".$search_word."' ".$group.") UNION ";
                    
                    $query_options[4][] = "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP '".$search_word."' ".$group.") UNION ";
                    
                    $query_options1[1][] = "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP ' ".$search_word." ' ".$group.") UNION ";
                    
                    $query_options1[2][] = "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP ' ".$search_word."' ".$group.") UNION ";
                    
                    $query_options1[3][] = "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` LIKE '%".$search_word."' ".$group.") UNION ";
                    
                    $query_options1[4][] = "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP '".$search_word."' ".$group.") UNION ";
                        
                    if( $search_words_condition ){
                        $search_words_condition .= " AND `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP '".$search_word."' ";
                        if( substr( $search_word , 0 , -1 ) ){
                            $search_words_condition_1 .= " OR `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP '".substr( $search_word , 0 , -1 )."' ";
                            if( substr( $search_word , 0 , -2 ) ){
                                $search_words_condition_1 .= " OR `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP '".substr( $search_word , 0 , -2 )."' ";
                            }
                        }
                    }else{
                        $search_words_condition = " AND ( `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP '".$search_word."' ";
                        if( substr( $search_word , 0 , -1 ) ){
                            $search_words_condition_1 .= " AND ( `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP '".substr( $search_word , 0 , -1 )."' ";
                            if( substr( $search_word , 0 , -2 ) ){
                                $search_words_condition_1 .= " OR `".$this->table_name."`.`".$this->table_fields['faq_title']."` REGEXP '".substr( $search_word , 0 , -2 )."' ";
                            }
                        }
                    }
                    
                    if( $search_words_condition1 ){
                        $search_words_condition1 .= " AND `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP '".$search_word."' ";
                        if( substr( $search_word , 0 , -1 ) ){
                            $search_words_condition1_1 .= " OR `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP '".substr( $search_word , 0 , -1 )."' ";
                            if( substr( $search_word , 0 , -2 ) ){
                                $search_words_condition1_1 .= " OR `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP '".substr( $search_word , 0 , -2 )."' ";
                            }
                        }
                    }else{
                        $search_words_condition1 = " AND ( `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP '".$search_word."' ";
                        if( substr( $search_word , 0 , -1 ) ){
                            $search_words_condition1_1 .= " AND ( `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP '".substr( $search_word , 0 , -1 )."' ";
                            if( substr( $search_word , 0 , -2 ) ){
                                $search_words_condition1_1 .= " OR `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP '".substr( $search_word , 0 , -2 )."' ";
                            }
                        }
                    }
                }
                
                if( $search_words_condition ){
                    $search_words_condition .= " ) ";
                    
                    $query .= "( SELECT ".$settings['select'].$settings['from'].$settings['where'].$search_words_condition." ".$group.") UNION ";
                }
                
                if( $search_words_condition1 ){
                    $search_words_condition1 .= " ) ";
                    
                    $query1 .= "( SELECT ".$settings['select'].$settings['from'].$settings['where'].$search_words_condition1." ".$group.") UNION ";
                }
                
                foreach( $query_options as $qq ){
                    foreach( $qq as $q ){
                        $query .= $q;
                    }
                }
                
                foreach( $query_options1 as $qq ){
                    foreach( $qq as $q ){
                        $query1 .= $q;
                    }
                }
                
                if( $search_words_condition_1 ){
                    $search_words_condition_1 .= " ) ";
                    
                    $query .= "( SELECT ".$settings['select'].$settings['from'].$settings['where'].$search_words_condition_1." ".$group." ) UNION ";
                }
                
                if( $search_words_condition1_1 ){
                    $search_words_condition1_1 .= " ) ";
                    
                    $query1 .= "( SELECT ".$settings['select'].$settings['from'].$settings['where'].$search_words_condition1_1." ".$group." ) UNION ";
                }
                
            }else{
                if( strlen( $settings['s'] ) > 1 ){
                    $last_striped = substr( $settings['s'], 0, -1 );
                    $query .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['faq_title']."` LIKE '".$last_striped."' ) UNION ";
                    
                    $query1 .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` LIKE '".$last_striped."' ) UNION ";
                    
                    if( strlen( $settings['s'] ) > 2 ){
                        $last_striped = substr( $settings['s'], 0, -2 );
                        $query .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['faq_title']."` LIKE '".$last_striped."' ".$group." ) UNION ";
                        
                        $query1 .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` LIKE '".$last_striped."' ".$group." ) UNION ";
                    }
                }
            }
            
            
            $query1 .= "( SELECT ".$settings['select'].$settings['from'].$settings['where']." AND  `".$this->table_name."`.`".$this->table_fields['ask_us_anything']."` REGEXP '".$settings['s']."' ".$group." ) ";
            
            //$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`".md5( $settings['s'] )."` ".$settings['limit'];
            //$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`_".md5( $settings['s'] )."` ".$settings['limit'];
            
            return $query.$query1;
            
            //$_SESSION['log'] = $query;
            //return $query;
            
        }
        
        private function _get_faq_list(){
            $query = "";
			
            $select = '';
            foreach( $this->table_fields as $key => $val ){
                switch( $key ){
                case 'ask_us_anything':
                case 'faq_title':
                    if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
                    else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
                break;
                }
            }
            
            if( ! ( isset($this->class_settings['where']) && $this->class_settings['where'] ) ){
                $this->class_settings['where'] = " WHERE `record_status`='1' AND `".$this->table_fields['processing_status']."`='3'  AND `".$this->table_fields['faq_category']."` != '1001' AND `".$this->table_fields['faq_title']."` != '0' ";
            }
            
            $from = " FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` ";
            if( isset( $_GET['s'] ) ){
                if( isset( $_GET['categories'] ) && $_GET['categories'] && $_GET['categories'] != 'all' ){
                    $this->class_settings['where'] .= " AND `".$this->table_fields['faq_category']."` = '".$_GET['categories']."' ";
                }
                $search_query = $this->_build_search_query( array(
                    'from' => $from,
                    'where' => $this->class_settings['where'],
                    'select' => $select,
                    's' => $_GET['s'],
                ) );
            }
            
            $query = "SELECT ".$select.$from.$this->class_settings['where'];
            if( isset( $search_query ) ){
                $query = $search_query;
            }
            
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			$sql_result = execute_sql_query( $query_settings );
			
            $cached_values = array();
			if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
				return $sql_result;
			}
			
        }
	
        private function _get_faq(){
            $query = "";
			
            if( isset( $_GET['record_id'] ) && $_GET['record_id'] ){
                $serial_num = intval( $_GET['record_id'] );
                
                $select = '';
                foreach( $this->table_fields as $key => $val ){
                    switch( $key ){
                    case 'ask_us_anything':
                    case 'faq_title':
                        if( $select )$select .= ", `".$this->table_name."`.`".$val."` as '".$key."'";
                        else $select = "`".$this->table_name."`.`id`, `".$this->table_name."`.`serial_num`, `".$val."` as '".$key."'";
                    break;
                    }
                }
                
                $query = "SELECT ".$select." FROM `" . $this->class_settings['database_name'] . "`.`".$this->table_name."` WHERE `serial_num`='".intval($serial_num)."'";
                
                $query_settings = array(
                    'database' => $this->class_settings['database_name'] ,
                    'connect' => $this->class_settings['database_connection'] ,
                    'query' => $query,
                    'query_type' => 'SELECT',
                    'set_memcache' => 1,
                    'tables' => array( $this->table_name ),
                );
                $sql_result = execute_sql_query( $query_settings );
                
                if(isset($sql_result) && is_array($sql_result) && isset($sql_result[0]) ){
                    
                    $script_compiler = new cScript_compiler();
                    $script_compiler->class_settings = $this->class_settings;
                    
                    $script_compiler->class_settings[ 'data' ] = $sql_result[0];
                    
                    $script_compiler->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name.'/faq_answer.php' );
                    
                    $script_compiler->class_settings[ 'action_to_perform' ] = 'get_html_data';
                    
                    return array(
                        'status' => 'got-faq',
                        'html' => $script_compiler->script_compiler(),
                        'method_executed' => $this->class_settings['action_to_perform'],
                    );
                }
                
			}
            //invalid record id error
            $err = new cError(000021);
            $err->action_to_perform = 'notify';
            
            $err->class_that_triggered_error = 'c'.ucfirst($this->table_name).'.php';
            $err->method_in_class_that_triggered_error = $this->class_settings['action_to_perform'];
            //$err->additional_details_of_error = ORDER_UNSET_ORDER_ID_MSG;
            return $err->error();
        }
	}
?>