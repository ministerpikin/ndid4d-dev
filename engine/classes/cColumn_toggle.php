<?php
	/**
	 * Column Toggle Class
	 *
	 * @used in  				Show / Hide Column Function
	 * @created  				09:11 | 24-08-2013
	 * @database table name   	none
	 */

	/*
	|--------------------------------------------------------------------------
	| Column Toggle in Toolbars
	|--------------------------------------------------------------------------
	|
	| Used to hide / show columns in the dataTables
	|
	*/
	
	class cColumn_toggle{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'column_toggle';
		
		function column_toggle(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'column_toggle':
				//Keeps Track of Hidden and Showing Columns
				$returned_value = $this->_column_toggle();
			break;
			case 'split_screen':
				//Split dataTables for easy visualization
				$returned_value = $this->_split_screen();
			break;
			}
			
			return $returned_value;
		}
		
		private function _split_screen(){
			$table = isset( $_POST["id"] )?$_POST["id"]:'';
			$method = isset( $_GET["method"] )?$_GET["method"]:'';
			$checked = isset( $_GET["checked"] )?$_GET["checked"]:'';
			
			$default_content_action = isset( $_GET["default_content_action"] )?$_GET["default_content_action"]:'';
			$default_content_todo = isset( $_GET["default_content_todo"] )?$_GET["default_content_todo"]:'';
			
			$selected_record_action = rawurldecode( isset( $_GET["selected_record_action"] )?$_GET["selected_record_action"]:'' );
			$split_num = intval( isset( $_GET["split_num"] )?$_GET["split_num"]:0 );
			
			if( ! $split_num ){
				$split_num = 4;
			}
			
			$handle2 = "dash-board-main-content-area";
			if( ( isset( $this->class_settings[ 'html_replacement_selector' ] ) && $this->class_settings[ 'html_replacement_selector' ] ) ){
				$handle2 = $this->class_settings[ 'html_replacement_selector' ];
				$this->class_settings[ 'data' ][ 'html_replacement_selector' ] = $this->class_settings[ 'html_replacement_selector' ];
			}
			$handle = "#" . $handle2;
			
			if( $table ){
			//if( isset( $_POST["class"] ) && $_POST["class"] && isset( $_POST["method"] ) && $_POST["method"] ){
				//$cl = strtolower( $_POST["class"] );
				//$cl = 'base_class';
				$cl = 'customer_call_log';
				$cls = "c" . ucwords( $cl );
				
				if( class_exists( $cls ) ){
					
					$c = new $cls();
					$c->class_settings = $this->class_settings;
					$c->table_name = $table;
					
					
					$sq = md5( 'split_datatable' . $_SESSION['key'] );
					if( isset( $_SESSION[ $sq ][ $table ][ $method ] ) ){
						$checked = 1;
						unset( $_SESSION[ $sq ][ $table ][ $method ] );
					}
					
					if( $checked ){
						$c->class_settings["datatable_split_screen"]['col'] = 0;
					}else{
						$_SESSION[ $sq ][ $table ][ $method ]["col"] = $split_num;
						$_SESSION[ $sq ][ $table ][ $method ]["action"] = $selected_record_action;
						
						$content = '';
						if( $default_content_action && $default_content_todo ){
							$cl1 = strtolower( $default_content_action );
							$cls1 = "c" . ucwords( $cl1 );
							
							if( class_exists( $cls1 ) ){
								
								$c1 = new $cls1();
								$c1->class_settings = $this->class_settings;
								$c1->class_settings["action_to_perform"] = $default_content_todo;
								$content = $c1->$cl1();
								
							}
							
						}
						
						if( ! $content && $selected_record_action ){
							if( function_exists("get_quick_view_default_message_settings") ){
								$content = get_quick_view_default_message_settings();
							}else{
								$content = '<div style="text-align:center;"><br /><br /><br /><h2>Quick View Window</h2><hr />Select a record by clicking on it</div>';
							}
							
						}
						
						$_SESSION[ $sq ][ $table ][ $method ]["content"] = $content;
						$c->class_settings["datatable_split_screen"] = $_SESSION[ $sq ][ $table ][ $method ];
					}
					
					$c->class_settings["datatable_method"] = $method;
					$c->class_settings["action_to_perform"] = 'split_datatable';
					$r = $c->$cl();
					
					if( isset( $r["html"] ) ){
						
						return array(
							'html_replacement_selector' => $handle,
							'html_replacement' => $r["html"],
							'method_executed' => $this->class_settings['action_to_perform'],
							'status' => 'new-status',
							'javascript_functions' => array( 'nwResizeWindow.resizeWindow','$nwProcessor.recreateDataTables', '$nwProcessor.set_function_click_event', '$nwProcessor.update_column_view_state' ) 
						);
					}
					//return $r;
				}
			}
		}
		
		private function _column_toggle(){
			$returning_html_data = '';
			
			//Check if Table to column_toggle isset
			if(isset($_GET['column_toggle_table']) && $_GET['column_toggle_table'] && isset($_GET['column_toggle_name']) && $_GET['column_toggle_name'] && isset($_GET['column_toggle_num']) ){
				//Validate Table
				$classname = $_GET['column_toggle_table'];
				$column = $_GET['column_toggle_name'];
				$column_num = $_GET['column_toggle_num'];
				$column_state = 'unchecked';
				
				//Toggle Column
				$sq = md5('column_toggle'.$_SESSION['key']);
				if(isset($_SESSION[$sq][$classname][$column])){
					unset($_SESSION[$sq][$classname][$column]);
					$column_state = 'checked';
				}else{
					$_SESSION[$sq][$classname][$column] = 1;
					$column_state = 'unchecked';
				}
				
				//RETURN COLUMN TOGGLE SUCCESSFUL
				$err = new cError('050101');
				$err->action_to_perform = 'notify';
				
				$returned_value = $err->error();
				
				//Append Column Name & Number
				$returned_value['column_name'] = $column;
				$returned_value['column_num'] = $column_num;
				$returned_value['column_state'] = $column_state;
				$returned_value['status'] = 'column-toggle';
				
				return $returned_value;
			}
			
			//RETURN NO column_toggle QUERY FOUND NOTIFICATION
			$err = new cError('050102');
			$err->action_to_perform = 'notify';
			
			return $err->error();
		}
		
	}
?>