<?php
	class cPlugin_class{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		public $table_name = 'plugin_class';
		public $table_name_static = 'plugin_class';
		public $type = 'plugin';
		
		public $label = 'Default Plugin Class';
		public $dir_path = '';
		public $namespace = '';
		
		public $table_fields = array();
		public $table_classes = array();
		public $database_connection = array();
		
		protected $datatable_settings = array();
		
		function __construct(){
			if( file_exists( dirname( $this->dir_path ) . '/' . $this->table_name . '.json' ) ){
				$return = json_decode( file_get_contents( dirname( $this->dir_path ) . '/' . $this->table_name . '.json' ), true );
				if( isset( $return[ "classes" ] ) )$this->table_classes = $return[ "classes" ];
				if( isset( $return[ "database" ] ) )$this->database_connection = $return[ "database" ];
			}
			if( method_exists( $this, '__after_construct' ) ){
				$this->__after_construct();
			}
		}
	
		protected function _get_plugin_details( $o = array() ){
			$return_type = isset( $o["return_type"] )?$o["return_type"]:'';
			$html = '';
			
			switch( $this->class_settings['action_to_perform'] ){
			case "get_plugin_details":
				$return_type = 'display';
			break;
			}
			
			$base_class = new cCustomer_call_log();
			$base_class->class_settings = $this->class_settings;
			
			$base_class->class_settings[ 'data' ][ 'title' ] = $this->label;
			$base_class->class_settings[ 'data' ][ 'name' ] = $this->table_name;
			$base_class->class_settings[ 'data' ]['tech_info'][ 'database connection' ] = $this->database_connection;
			$base_class->class_settings[ 'data' ]['tech_info'][ 'classes' ] = $this->table_classes;
			$base_class->class_settings[ 'html' ] = array( 'html-files/templates-1/'.$this->table_name_static.'/about.php' );
			
			$html = $base_class->_get_html_view();
			
			switch( $return_type ){
			case 'display':
				echo $html; exit;
			break;
			}
			
			$handle = "display_mis_menu-sub";
			if( isset( $this->class_settings["html_replacement_selector"] ) ){
				$handle = $this->class_settings["html_replacement_selector"];
			}
			
			return array(
				'html_replacement' => $html,
				'html_replacement_selector' => "#" . $handle,
				'status' => 'new-status',
			);
		}

		function get_plugin_classes( $o = array() ){
			$error_msg = 'Unknown Error';
			
			if( isset( $o["plugin"] ) && $o["plugin"] ){
				$tb = trim( strtolower( $o["plugin"] ) );
				$cl = "c".ucwords( $tb );
				
				if( class_exists( $cl ) ){
					$cls = new $cl();
					
					if( isset( $cls->table_classes ) && is_array( $cls->table_classes ) && ! empty( $cls->table_classes ) ){
						
						$tables = $cls->_load_class( array( "class" => array_keys( $cls->table_classes ), "return_class_info" => 1 ) );
						
						$lb = array();
						if( function_exists( $cls->table_name ) ){
							$tx = $cls->table_name;
							$lb = $tx();
						}else{
							if( isset($cls->namespace) && $cls->namespace ){
								$tx = $cls->namespace . '\\'. $cls->table_name;
								if( function_exists( $tx ) ){
									$lb = $tx();
								}
							}
						}
						
						$to_add = array(
							"class" => $cl,
							"label" => isset( $cls->label )?$cls->label:$cl,
							"table_name" => $cls->table_name,
							"table_fields" => $cls->table_fields,
							"table_clone" => isset( $cls->table_clone )?$cls->table_clone:array(),
							"table_labels" => $lb,
						);
						$tables[ $cls->table_name ] = $to_add;
						
						return $tables;
					}else{
						$error_msg = '<h4>Plugin has no class</h4>';
					}
					
				}else{
					$error_msg = '<h4>Plugin does not exists</h4>';
				}
			}else{
				$error_msg = '<h4>Undefined Plugin</h4>';
			}
			
			return $error_msg;
		}
		
		function load_class( $o = array() ){
			return $this->_load_class( $o );
		}
		
		protected function _load_class( $o = array() ){
			$error_msg = '';
			$initialize = isset( $o["initialize"] )?$o["initialize"]:0;
			$return_class_info = isset( $o["return_class_info"] )?$o["return_class_info"]:0;
			$class_info = array();
			$cl_path = "Undefined";
			
			if( isset( $o["class"] ) && is_array( $o["class"] ) && ! empty( $o["class"] ) ){
				foreach( $o["class"] as $tk ){
					$cl_path = dirname( $this->dir_path ) . '/classes/' . $tk . '.php';
					
					if( isset( $this->table_classes[ $tk ] ) ){
						$tb = $this->table_classes[ $tk ];
						
						if( isset( $tb["table_name"] ) && $tb["table_name"] ){
							$tb = trim( strtolower( $tb["table_name"] ) );
							$cl = "c".ucwords( $tb );
							$cl_path = dirname( $this->dir_path ) . '/classes/' . $cl . '.php';
							
							if( file_exists( $cl_path ) ){
								require_once $cl_path;
								
								if( $return_class_info ){
									$cls = new $cl();
									
									$lb = array();
									if( function_exists( $cls->table_name ) ){
										$tx = $cls->table_name;
										$lb = $tx();
									}
									
									$to_add = array(
										"class" => $cl,
										"label" => isset( $cls->label )?$cls->label:$cl,
										"table_name" => $cls->table_name,
										"table_fields" => $cls->table_fields,
										"table_clone" => isset( $cls->table_clone )?$cls->table_clone:array(),
										"table_labels" => $lb,
									);
									$class_info[ $cls->table_name ] = $to_add;
								}else if( $initialize ){
									if( $this->namespace ){
										$cl = $this->namespace .'\\'.$cl;
									}
									$cls = new $cl();
									$cls->class_settings = $this->class_settings;
									$cls->plugin_instance = $this;
									$cls->plugin = $this->table_name;
									$cls->view_dir = 'plugins/' . $this->table_name . '/views/';
									$cls->view_path = $cls->view_dir . $cls->table_name . '/';
									
									if( isset( $this->refresh_cache_after_save_line_items ) ){
										$cls->refresh_cache_after_save_line_items = $this->refresh_cache_after_save_line_items;
									}
									
									if( isset( $cls->basic_data["real_table"] ) && $cls->basic_data["real_table"] ){
										$class_info[ $cls->basic_data["real_table"] ] = $cls;
									}else{
										$class_info[ $cls->table_name ] = $cls;
									}
									
								}
							}else{
								$error_msg = '<h4><strong>Missing Plugin Class</strong></h4><p><strong>'. $cl_path .'</strong> was not found</p>';
								if( ! $return_class_info ){
									break;
								}
							}
						}
					}else{
						$error_msg = '<h4><strong>Invalid Plugin Class</strong></h4><p><strong>'. $cl_path .'</strong> was not registered</p>';
					}
				}
			}else{
				$error_msg = '<h4><strong>Unset Plugin Class</strong></h4><p><strong>'. $cl_path .'</strong> class was not defined</p>';
			}
			
			if( $return_class_info || $initialize ){
				return $class_info;
			}
			
			if(	! $error_msg ){
				return $error_msg;
			}

			$error['err'] = $error_msg;
			$error['typ'] = 'serror';
			$error['msg'] = 'Please contact your administrator';
			$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
				$error['html'] .= $error_msg;
				// $error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
			$error['html'] .= '</div>';
			
			return json_encode($error);

			return $error_msg;
		}
		
		function execute( $o = array() ){
			return $this->_execute( $o );
		}

		protected function _execute( $o = array() ){
			//echo '<h1>' . $this->class_settings["action_to_perform"] . '</h1>';
			$error_msg2 = 'Please contact your administrator';
			$error_msg = '';
			$execute = 1;
			$classes = isset( $o["class"] )?$o["class"]:array();
			
			if( isset( $o["do_not_execute"] ) && $o["do_not_execute"] ){
				$execute = 0;
			}
			/* 
			if( isset( $this->class_settings[ 'mike' ] ) ){
				// print_r( 'mike' );exit;
			} */
			
			if( isset( $this->class_settings["nwp_action"] ) && $this->class_settings["nwp_action"] && isset( $this->class_settings["nwp_todo"] ) && $this->class_settings["nwp_todo"] ){
				
				// print_r( $this->table_classes );exit;
				//load classes: later make it selective i.e load only classes that are needed
				if( isset( $this->table_classes[ $this->class_settings["nwp_action"] ] ) ){
					
					$error_msg = $this->_load_class( array( "class" => array( $this->class_settings["nwp_action"] ) ) );
					
					if( ! $error_msg ){
						$tb = trim( strtolower( $this->class_settings["nwp_action"] ) );
						$cl = "c".ucwords( $tb );
						$cl_path = dirname( $this->dir_path ) . '/classes/' . $cl . '.php';
						if( $this->namespace ){
							$cl = $this->namespace .'\\'.$cl;
						}	 
						
						if( class_exists( $cl ) ){
							$cls = new $cl();
							$cls->class_settings = $this->class_settings;
							
							$cls->plugin_instance = $this;
							$cls->plugin = $this->table_name;
							$cls->plugin_dir = 'plugins/' . $this->table_name . '/';
							$cls->view_dir = 'plugins/' . $this->table_name . '/views/';
							$cls->view_path = $cls->view_dir . $tb . '/';
							// print_r( $cls->view_path );exit;
							$cls->class_settings["action_to_perform"] = $this->class_settings["nwp_todo"];
							
							$return = $cls->$tb();

							if( isset( $cls->class_settings[ 'success_callback_id' ] ) && $cls->class_settings[ 'success_callback_id' ] ){
								$this->class_settings[ 'success_callback_id' ] = $cls->class_settings[ 'success_callback_id' ];
							}

							if( isset( $cls->class_settings[ 'success_callback_action' ] ) && $cls->class_settings[ 'success_callback_action' ] ){
								$this->class_settings[ 'success_callback_action' ] = $cls->class_settings[ 'success_callback_action' ];
							}

							if( $return ){
								return $return;
							}else{
								$error_msg = '<h4><strong>Empty/Undefined Method</strong></h4>';
								$error_msg2 = '<p>'. $this->class_settings[ 'nwp_todo' ] .' probably does not exist in '. $cl .'</strong></p>';
							}
						}else{
							$error_msg = '<h4><strong>Unrecognized Class Definition</strong></h4><p>Class <strong>'. $cl .'</strong> is not defined in <strong>'. $cl_path .'</strong></p>';
						}
					}
				}else{
					$error_msg = '<h4><strong>Invalid Plugin Class</strong></h4>';
				}
				
			}else{
				$error_msg = '<h4><strong>Undefined Plugin Class & Method</strong></h4>';
			}
			
			
			$error['typ'] = 'serror';
			$error['err'] = $error_msg;
			$error['msg'] = $error_msg2;
			$error['html'] = '<div data-role="popup" id="errorNotice" data-position-to="#" class="ui-content" data-theme="a">';
				$error['html'] .= $error_msg;
				// $error['html'] .= '<p>Class = '.$classname.'<br />Action = '.$action.'</p>';
			$error['html'] .= '</div>';
			
			return json_encode($error);
			echo $error_msg; exit;
		}

		private function _test_cases( $dir, $isTest = false, $subDirCount = 0, $ts = [] ){
			
			if( is_dir( $dir ) ){
				
				$pisTest = false;
				if( $isTest ){
					$pisTest = $isTest;
				}else if( strtolower( substr( $dir, -5 ) ) == 'tests' ){
					$pisTest = true;
				}

				foreach( scandir( $dir ) as $file ){
					if ($file !== "." && $file !== "..") {
						$path = $dir . '/' . $file;
			
						if ( is_dir($path)) {
							$count = $subDirCount;
							++$count;
							if( $count < 3 || $pisTest ){
								$ts = self::_test_cases( $path, $pisTest, $count, $ts );
							}
						}else if( $pisTest && substr( $file, -8 ) == 'Test.php' ){
							$ts[] = $path;
						}
					}
					
				}
			}

			return $ts;
		}

		public function test( $o = array() ){
			return $this->_test( $o );
		}

		protected function _test( $o = array() ){

			$args = '';
			$testFile = dirname( $this->dir_path );
			if( isset( $o["testFile"] ) && $o["testFile"] ){
				$testFile = $o["testFile"];
			}
			if( isset( $o["testPlugins"] ) && $o["testPlugins"] ){
				//$testFile .= '/../';
				$testFile = dirname( dirname( $this->dir_path ) );
				//echo $testFile; exit;
			}

			$Command = $this->class_settings["calling_page"] . "phpunit/vendor/bin/phpunit";
			$html = '';

			if( file_exists( $Command ) ){

				if( isset( $o["testSuites"] ) && $o["testSuites"] ){
					$testSuites = $o["testSuites"];
				}else{
					$testSuites = $this->_test_cases( $testFile, false, 0 );
				}
				//print_r( $testSuites ); exit;
				$subText = explode("engine/", $testFile );

				$html .= "<h1>Executing Test Cases for ".( isset( $subText[1] )?$subText[1]:$testFile )."</h1>";
				if( ! empty( $testSuites ) ){
					$html .= "<ol>";

					/*
					$testSuites = [
						'path/to/your/TestSuite1.php',
						'path/to/your/TestSuite2.php',
						// Add more test suite paths or names as needed
					];
					
					// Construct the full command
					$command = "$phpunitCommand --warnings " . implode(' ', $testSuites);
					*/

					foreach( $testSuites as $testCase ){
						$li = '';
						$sli = '';
						$output = null;

						if( defined("PLATFORM") && PLATFORM == "linux" ){
							$retval=null;

							exec($Command ." \"". $testCase ."\" " .  addslashes( escapeshellarg( str_replace('"','', $args) ) ), $output, $retval);
						}else{
							ob_start();
							system( $Command ." \"". $testCase . "\"\" " . escapeshellarg($args));
							$output = ob_get_contents();
							ob_end_clean();
						}
						if( is_array( $output ) ){
							$output = implode("<br>", $output);
						}
						if( strpos($output, 'Errors:') > -1 ){
							$sli = ' style="color:orange;" ';
						}else if( strpos($output, 'Failures:') > -1 ){
							$sli = ' style="color:red;" ';
						}else{
							$ta = explode("Time: ", $output);

							$output = isset( $ta[1] )?( "Time: " . $ta[1] ):$output;
						}
						$li .= "<li>";
							$li .= "<h4 ". $sli .">Testcase: " . $this->table_name . "::". str_replace( $testFile, "", $testCase ) ."</h4>";
							$li .= $output;
						$li .= "</li>";
						$html .= $li;
					}
					$html .= "</ol>";
				}else{
					$html = 'No test case found';
				}
				
			}else{
				$html = 'PHPUNIT was not found';
			}

			if( isset( $o["return_string"] ) && $o["return_string"] ){
				return $html;
			}else{
				print_r( $html ); exit;
			}
		}

	}
?>