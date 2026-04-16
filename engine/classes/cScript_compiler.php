<?php
	/**
	 * Script Compiler Class
	 *
	 * @used in  				Classes that provides website view for use default operational 
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
	
	class cScript_compiler{
		public $class_settings = array();
		
		private $temp_directory_js = "tmp/js-bundle/";
		private $temp_directory_css = "tmp/css-bundle/";
		
		function script_compiler(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'compile_scripts':
				$returned_value = $this->_compile_scripts();
			break;
			case 'get_html_data':
				$returned_value = $this->_html_markup();
			break;
			}
			
			return $returned_value;
		}
		
		private function _compile_scripts(){
			
			$pr = get_project_data();
			$this->class_settings[ 'calling_page_display' ] = $pr['domain_name'];
			$this->class_settings[ 'project_title' ] = $pr['project_title'];
				
            //CHECK FOR EXISTING FILE
			$js_file = $this->_compile_js();
			
			$css_file = $this->_compile_css();
			
			$html_markup = $this->_html_markup();
			
			return array(
				'js_file' => $js_file,
				'css_file' => $css_file,
				'html_markup' => $html_markup,
			);
		}
		
		private function _compile_css(){
			//CHECK FOR EXISTING FILE
			$css_file_content = "";
			
			$filename = "";
			$rurl = $this->class_settings[ 'calling_page_display' ];
			if(  defined("NWP_RESOURCE_URL") && NWP_RESOURCE_URL ){
				$rurl = NWP_RESOURCE_URL;
			}
			
			if( isset( $this->class_settings[ 'stylesheet' ] ) && $this->class_settings[ 'stylesheet' ] && isset( $this->class_settings[ 'css' ] ) && is_array( $this->class_settings[ 'css' ] ) ){
				
				$filename = $this->class_settings[ 'calling_page' ] . $this->temp_directory_css . $this->class_settings[ 'stylesheet' ] . '.css';
				$add_file = false;
				
				//if( ! file_exists( $filename ) || $this->class_settings[ 'project_data' ][ 'development_mode' ] ){
					//GET LIST OF FILE NAMES & PAGE NAME
					foreach( $this->class_settings[ 'css' ] as $file_link ){
						if( file_exists( $this->class_settings[ 'calling_page' ] . $file_link ) ){
							//READ CONTENTS OF EACH FILE
							//$css_file_content .= file_get_contents( $this->class_settings[ 'calling_page' ] . $file_link );
							$media = '';
							if( strpos( $file_link, 'print' ) > -1 ){
								$media = ' media="print" ';
							}
                            $css_file_content .= '<link type="text/css" id="'.clean2( $file_link, '' ).'" href="' . $rurl . $file_link . '" rel="stylesheet" '.$media.' />';
                           // $css_file_content .= '<link type="text/css" href="' . $this->class_settings[ 'calling_page' ] . $file_link . '" rel="stylesheet" />';
						}
					}
					
                    return $css_file_content;
					if( $css_file_content ){
						//CREATE NEW FILE FOR PAGE
						//file_put_contents( $filename , $css_file_content );
						
						$add_file = true;
					}
                    /*
				}else{
					$add_file = true;
				}*/
				
				if( $add_file ){
					if( isset( $this->class_settings[ 'calling_page_display' ] ) && $this->class_settings[ 'calling_page_display' ] ){
                        $css_file_content = '<link type="text/css" href="' . str_replace($this->class_settings[ 'calling_page' ], $this->class_settings[ 'calling_page_display' ], $filename ). '" rel="stylesheet" />';
                    }else{
                        $css_file_content = '<link type="text/css" href="' . $filename . '" rel="stylesheet" />';
                    }
				}
			}
			
			return $css_file_content;
		}
		
		private function _compile_js(){
			//CHECK FOR EXISTING FILE
			$js_file_content = "";
			$js_libraries = "";
			
			$filename = "";
			$rurl = $this->class_settings[ 'calling_page' ] . "engine/";
			if( isset( $this->class_settings[ 'calling_page_display' ] ) && $this->class_settings[ 'calling_page_display' ] ){
				$rurl = $this->class_settings[ 'calling_page_display' ];
			}
			if(  defined("NWP_RESOURCE_URL") && NWP_RESOURCE_URL ){
				$rurl = NWP_RESOURCE_URL;
			}
			
			// echo '<pre>';
			// print_r( $returning );exit;
			
            if( isset( $this->class_settings[ 'js_lib' ] ) && is_array( $this->class_settings[ 'js_lib' ] ) ){
                foreach( $this->class_settings[ 'js_lib' ] as $file_link ){
                    
                    if( file_exists( $this->class_settings[ 'calling_page' ] . $file_link ) ){
                        //READ CONTENTS OF EACH FILE
                        if( isset( $this->class_settings[ 'calling_page_display' ] ) && $this->class_settings[ 'calling_page_display' ] ){
                            $js_libraries .= '<script type="text/javascript" src="' . $rurl . $file_link . '"></script>';
                            //$js_libraries .= '<script type="text/javascript" src="' . $this->class_settings[ 'calling_page_display' ] . "engine/" . $file_link . '"></script>';
                        }else{
                            $js_libraries .= '<script type="text/javascript" src="' . $this->class_settings[ 'calling_page' ] . "engine/" . $file_link . '"></script>';
                        }
                    }
                }
            }
				
			if( isset( $this->class_settings[ 'script_name' ] ) && $this->class_settings[ 'script_name' ] && isset( $this->class_settings[ 'js' ] ) && is_array( $this->class_settings[ 'js' ] ) ){
				
				$filename = $this->class_settings[ 'calling_page' ] . $this->temp_directory_js . $this->class_settings[ 'script_name' ] . '.js';
				$add_file = false;
				
				if( ! file_exists( $filename ) || $this->class_settings[ 'project_data' ][ 'development_mode' ] ){
					//GET LIST OF FILE NAMES & PAGE NAME
					foreach( $this->class_settings[ 'js' ] as $file_link ){
						if( file_exists( $this->class_settings[ 'calling_page' ] . $file_link ) ){
							//READ CONTENTS OF EACH FILE
							$js_file_content .= file_get_contents( $this->class_settings[ 'calling_page' ] .  $file_link );
						}
					}
					
					if( $js_file_content ){
						//ADD TO JQUERY DOCUMENT READY STRUCTURE
						$js_file_content = "(function(jQuery) {	$(document).ready(function(){ " . $js_file_content . "}); })(jQuery);";
						
						//CREATE NEW FILE FOR PAGE
						file_put_contents( $filename , $js_file_content );
						
						$add_file = true;
					}
				}else{
					$add_file = true;
				}
				
				if( $add_file ){
					if( isset( $this->class_settings[ 'calling_page_display' ] ) && $this->class_settings[ 'calling_page_display' ] ){
                        $js_libraries .= '<script type="text/javascript" src="' . str_replace($this->class_settings[ 'calling_page' ], $this->class_settings[ 'calling_page_display' ] . "engine/" , $filename ). '"></script>';
                    }else{
                        $js_libraries .= '<script type="text/javascript" src="' . $filename . '"></script>';
                    }
				}
			}
			
			return $js_libraries;
		}
		
		private function _html_markup(){
			$before = "";
			$after = "";
			$html_file_content = "";
			
			$user_info = array();
			if( isset( $this->class_settings[ 'calling_page_display' ] ) && $this->class_settings[ 'calling_page_display' ] ){
				$display_pagepointer = $this->class_settings[ 'calling_page_display' ];
				$site_url = $this->class_settings[ 'calling_page_display' ];
			}
			
			if( isset( $this->class_settings[ 'calling_page' ] ) && $this->class_settings[ 'calling_page' ] ){
				$pagepointer = $this->class_settings[ 'calling_page' ];
				$pagepointer2 = $pagepointer;
				if( defined("NWP_APP_DIR") && NWP_APP_DIR ){
					$pagepointer2 = '../' . NWP_APP_DIR . '/';
				}
			}
			
			if( isset( $this->class_settings[ 'project_title' ] ) ){
				$project_title = $this->class_settings[ 'project_title' ];
			}
			
			if( isset( $this->class_settings[ 'user_id' ] ) ){	//currently logged in user id
				$user_info[ 'user_id' ] = $this->class_settings[ 'user_id' ];
			}
			
			if( isset( $this->class_settings[ 'user_role' ] ) ){	//currently logged in user id
				$user_info[ 'user_role' ] = $this->class_settings[ 'user_role' ];
			}
			
			if( isset( $this->class_settings[ 'photograph' ] ) ){	//currently logged in user id
				$user_info[ 'photograph' ] = $this->class_settings[ 'photograph' ];
			}
			
			if( isset( $this->class_settings[ 'priv_id' ] ) ){	//currently logged in user access role
				$user_info[ 'priv_id' ] = $this->class_settings[ 'priv_id' ];
				$user_info[ 'user_privilege' ] = $this->class_settings[ 'priv_id' ];
			}
			if( isset( $this->class_settings[ 'user_store' ] ) ){
				$user_info[ 'user_store' ] = $this->class_settings[ 'user_store' ];
			}
			if( isset( $this->class_settings[ 'user_dept' ] ) ){
				$user_info[ 'user_dept' ] = $this->class_settings[ 'user_dept' ];
			}
			if( isset( $this->class_settings[ 'user_email' ] ) ){
				$user_info[ 'user_email' ] = $this->class_settings[ 'user_email' ];
			}
			if( isset( $this->class_settings[ 'user_full_name' ] ) ){
				$user_info[ 'user_full_name' ] = $this->class_settings[ 'user_full_name' ];
				
				$user_info[ 'user_initials' ] = substr( $this->class_settings[ 'user_full_name' ] , 0 , 1 ) . '. ' . $this->class_settings[ 'user_lname' ];
			}
			
			if( defined("HYELLA_PACKAGE") ){
				switch( HYELLA_PACKAGE ){
				case "fiaps":
				case "performance-appraisal":
					$access = get_fiaps_roles_and_capabilities( get_fiaps_current_user_role() );
				break;
				}
			}
			
			$create_demo = isset( $this->class_settings[ 'skip_demo' ] ) ? $this->class_settings[ 'skip_demo' ] : 1;
			$deve = get_hyella_development_mode();
			$aa = array( 'nw_shared', 'nw_breadcrum', 'branch', 'frontend', 'mobile', 'html_replacement_selector', 'more_data', 'ref_params', 'overide_form_action' );
			$data = array();
			$more_data = array();
			foreach( $aa as $av ){
				if( isset( $this->class_settings[ $av ] ) && $this->class_settings[ $av ] ){
					$more_data[ $av ] = $this->class_settings[ $av ];
				}
			}
			
			if( isset( $this->class_settings[ 'data' ] ) && is_array( $this->class_settings[ 'data' ] ) ){
				$data = $this->class_settings[ 'data' ];
			}
			
			if( isset( $data["before_html"] ) ){
				$before = $data["before_html"];
				unset( $data["before_html"] );
			}
			
			if( isset( $data["after_html"] ) ){
				$after = $data["after_html"];
				unset( $data["after_html"] );
			}
			
			if( isset( $data['breadcrum'] ) && is_array( $data['breadcrum'] ) ){
				$more_data[ "breadcrum" ] = nw_get_breadcrum( $data['breadcrum'] );
			}else if( isset( $GLOBALS['nw_use_breadcrum'] ) && $GLOBALS['nw_use_breadcrum'] ){
				$more_data[ "breadcrum" ] = nw_get_breadcrum( array( "type" => "global" ) );
			}
			
			if( isset( $this->class_settings[ 'phtml_replacement_selector' ] ) && $this->class_settings[ 'phtml_replacement_selector' ] ){
				$data['phtml_replacement_selector'] = $this->class_settings[ 'phtml_replacement_selector' ];
			}
			$data["development"] = $deve;
			
			if( isset( $this->class_settings[ 'plugin_hooks' ] ) && is_array( $this->class_settings[ 'plugin_hooks' ] ) && ! empty( $this->class_settings[ 'plugin_hooks' ] ) ){
				$ph = $this->class_settings[ 'plugin_hooks' ];
				unset( $this->class_settings[ 'plugin_hooks' ] );
				
				$xdata = $data;
				if( isset( $xdata[ 'labels' ] ) )unset( $xdata[ 'labels' ] );
				if( isset( $xdata[ 'fields' ] ) )unset( $xdata[ 'fields' ] );
				
				$plugin_hooks = array();
				foreach( $ph as $pk => $pv ){
					$plugin_hooks = add_nwp_plugin_options( array( "type" => $pk, "data" => $plugin_hooks, "class_settings" => $this->class_settings, "params" => array( "class_data" => $xdata, "more_data" => $more_data ) ) );
				}
				$data[ "plugin_hooks" ] = $plugin_hooks;
				unset( $xdata );
				unset( $plugin_hooks );
			}
			
			if( isset( $this->class_settings[ 'html' ] ) && is_array( $this->class_settings[ 'html' ] ) ){
				//GET LIST OF FILE NAMES & PAGE NAME
				foreach( $this->class_settings[ 'html' ] as $file_link ){
					$link = $this->class_settings[ 'calling_page' ] . $file_link;
					if( file_exists( $link ) && is_file( $link ) ){
						//READ CONTENTS OF EACH FILE
						ob_start();
						include( $link );
						$html_file_content .= ob_get_contents();
						ob_end_clean();
						
						//$html_file_content .= file_get_contents( $link );
					}else if( file_exists( $link . '/index.php' ) ){
						//READ CONTENTS OF EACH FILE
						ob_start();
						include( $link . '/index.php' );
						$html_file_content .= ob_get_contents();
						ob_end_clean();
					}else{
						if( $deve && $create_demo ){
							// print_r( $file_link );exit;
							$development_path = $this->class_settings["calling_page"] . "classes/dev/";
							$skip_ads = 0;
							$f_name = 'index.php';

							$x = explode( '/', $file_link );

							// Create Folders if Missing
							$check_folders = $this->class_settings[ 'calling_page' ];
							foreach( $x as $xx ){
								$check_folders .= $xx;
								if( $xx && ! strpos( $xx, '.php' ) && ! file_exists( $check_folders ) ){
									create_folder( $check_folders, "", "" );
								}
								$check_folders .= '/';
							}

							if( substr( $file_link, -1 ) !== '/' ){
								$x = explode( '/', $file_link );
								$name = end( $x );

								if( strpos( $name, '.php' ) ){
									$f_name = $name;
									$skip_ads = 1;
								}else{
									$link .= '/';
								}
							}

							if( ! $skip_ads ){
								$x = explode( '/', $link );
								unset( $x[ count( $x ) - 1 ] );
								$link = implode( '/', $x ) . '/';

								$new_name = '';
								if( $f_name !== 'index.php' ){
									$new_name = substr( $f_name, 0, -4 );
								}

								$style_name = 'style.css';
								if( $new_name )
									$style_name = $new_name . '-style.css';

								if( ! file_exists( $link . $style_name ) ){
									if( file_exists( $development_path . "demo_view_css.css" ) && is_file( $development_path . "demo_view_css.css" ) ){
										copy( $development_path . "demo_view_css.css", $link . $style_name );
										//file_put_contents( , $view );
									}
								}

								$script_name = 'script.js';
								if( $new_name )
									$script_name = $new_name . '-script.js';

								if( ! file_exists( $link . $script_name ) ){
									if( file_exists( $development_path . "demo_view_js.js" ) && is_file( $development_path . "demo_view_js.js" ) ){
										$view = file_get_contents( $development_path . "demo_view_js.js" );
										$view = str_replace('demo_js', 'nw'.( $new_name ? ucwords( $new_name ) : 'Demo' ), $view ); 

										file_put_contents( $link . $script_name, $view );
									}
								}
							}

							if( file_exists( $development_path . "demo_view_php.php" ) && is_file( $development_path . "demo_view_php.php" ) ){
								$view = file_get_contents( $development_path . "demo_view_php.php" );

								if( $skip_ads ){
									$style_name = 'style';
									$script_name = 'script';
								}
								$view = str_replace('demo_style', $style_name, $view );
								$view = str_replace('demo_script', $script_name, $view ); 

								if( $skip_ads ){
									$name = $link;
								}else{
									$name = $link . $f_name;
								}

								file_put_contents( $name, $view );
							}
							
							ob_start();
							include( $name );
							$html_file_content .= ob_get_contents();
							ob_end_clean();
							
						}
					}
				}
			}
			
			if( isset( $returned_data ) ){
				$this->class_settings["returned_data"] = $returned_data;
			}
			
			//return $html_file_content;
			return iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", ( $before . $html_file_content . $after ) );
			//return iconv( "UTF-8", "ASCII//IGNORE", ( $before . $html_file_content . $after ) );
		}
		
	}
?>