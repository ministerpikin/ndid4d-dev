<?php
/**
 * Main Plugin Class
 * @created  Bay4 Mike | 02:15 | 21-Jul-2023
 */
class cNwp_orm extends cPlugin_class{
	
	public $class_settings = array();
	
	private $current_record_id = '';
	
	public $ignore_basic_crud = 1;
	public $ignore_table_list = 1;
	public $table_name = 'nwp_orm';
	
	public $label = 'ORM';
	public $dir_path = __FILE__;
	
	function nwp_orm(){
		
		$returned_value = array();
		
		$this->class_settings['current_module'] = '';
		
		switch ( $this->class_settings['action_to_perform'] ){
		case "display_plugin_details":
		case "get_plugin_details":
			$returned_value = $this->_get_plugin_details();
		break;
		case "execute":
			$returned_value = $this->_execute();
		break;
		case "execute_query":
			$returned_value = $this->_execute_query();
		break;
		}
		
		return $returned_value;
	}
	
	public static function error( $settings = array() ){
		$error = [];
		
		$error['typ'] = 'serror';
		$error['err'] = isset( $settings["title"] ) ? '<strong>'. $settings["title"] .'</strong>' :'';
		$error['msg'] = isset( $settings["message"] )?$settings["message"]:'';
		$error['html'] = '<div>';
			$error['html'] .= '<h3>'.$error['err'].'</h3>';
			$error['html'] .= '<p>'.$error['msg'].'</p>';
		$error['html'] .= '</div>';
		
		//$error['trace'] = array_column( debug_backtrace(), 'line', 'file' );
		$error['trace'] = debug_backtrace();
		
		if( isset( $settings["output"] ) && $settings["output"] ){
			echo json_encode($error); exit;
		}
		
		return json_encode($error);
	}
	
	public function _execute_query( $settings = array() ){
		
		$return = array();
		$error = '';

		if( isset( $settings[ 'db_mode' ] ) && $settings[ 'db_mode' ] ){
			$tb = 'orm_' . $settings[ 'db_mode' ];
			$al = $this->load_class( array( 'class' => array( $tb ), 'initialize' => 1 ) );
			$qtype = isset( $settings[ 'query_type' ] ) ? $settings[ 'query_type' ] : '';
			// print_r( $al );exit;

			if( ! $qtype ){
				$error = 'Undefined Query Type';
			}

			if( ! $error ){
				if( isset( $al[ $tb ] ) && $al[ $tb ] ){
				
					$callStartTime = microtime(true);

					$return = $al[ $tb ]->_load_query( $settings );

					if( isset( $settings['set_memcache'] ) && $settings['set_memcache'] ){
						if( ( isset($GLOBALS['app_memcache'] ) && $GLOBALS['app_memcache'] ) ){
							$settings['set_memcache'] = $GLOBALS['app_memcache'];
						}else{
							unset( $settings['set_memcache'] );
						}
					}

					if( ! isset( $settings['table'] ) ){
						if( isset( $settings['tables'][0] ) && $settings['tables'][0] ){
							$settings['table'] = $settings['tables'][0];
						}
					}

					switch( $qtype ){
					case 'SELECT':
					
						//10 Hours Expiry Time
						$cache_time = 3600*10;
						$cache_key = '';
						$cache_table_key = 'database_tables';
						if( isset( $al[ $tb ]->last_query ) && $al[ $tb ]->last_query ){
							if( is_array( $al[ $tb ]->last_query ) ){
								$audit_params = array( "query" => json_encode( $al[ $tb ]->last_query ) );
							}else{
								$audit_params = array( "query" => $al[ $tb ]->last_query );
							}
						}

						if( isset( $audit_params[ 'query' ] ) && $audit_params[ 'query' ] ){
							$cache_key = md5( $audit_params[ 'query' ] );
						}

						if( isset( $return[ 'error' ] ) && $return[ 'error' ] ){
							
							$callEndTime = microtime(true);
							$audit_params["comment"] = $return[ 'error' ];
							$audit_params["duration"] = $callEndTime - $callStartTime;
							$audit_params["trace"] = array_column( debug_backtrace(), 'line', 'file' );
							auditor( "", "sql_error", $settings['table'], $audit_params );

							switch( $settings[ 'db_mode' ] ){
							case 'mysql':
								$ee = 'Could not execute statement: '. $audit_params["comment"] . ( isset( $audit_params['query'] ) ? ' Q:'.$audit_params['query'] : '' );
								trigger_error( $ee, E_USER_ERROR );
								
								return $audit_params["comment"];
							break;
							}
							
						}elseif( ! empty( $return ) ){
							$audit_params["count"] = count( $return );
							//Check if Memcache is turned on
							if( isset($settings['set_memcache']) && $settings['set_memcache'] && $cache_key ){
								
								if(isset( $settings['set_memcache_time'] ) && $settings['set_memcache_time'] ){
									$cache_time = $settings['set_memcache_time'];
								}
								
								//Cache Query Result
								$settings[ 'set_memcache' ]->set( $cache_key, $return, $cache_time );
								
								//Update Cached Tables Array
								if( isset( $settings[ 'tables' ] ) && is_array( $settings[ 'tables' ] ) ){
									//Get Array of All Cached Tables Keys
									$table_keys = $settings['set_memcache']->get( $cache_table_key );
									
									if(is_array($table_keys)){
										foreach($settings['tables'] as $table){
											$table_keys[ strtolower($table) ][ $cache_key ] = true;
										}
									}else{
										foreach($settings['tables'] as $table){
											$table_keys[ strtolower($table) ][ $cache_key ] = true;
										}
									}
									
									//Update Table Keys
									$settings['set_memcache']->set($cache_table_key,$table_keys,$cache_time);	//Set for two hours
								}
								
							}
						}

						if( isset($settings['skip_log']) && $settings['skip_log'] ){
						}else{
							$callEndTime = microtime(true);
							$audit_params["duration"] = $callEndTime - $callStartTime;
							$ak = isset( $settings[ 'query' ][ 'view' ][ 'view_name' ] ) && $settings[ 'query' ][ 'view' ][ 'view_name' ] ? 'execute' : 'read';

							$acl = '';
							$atd = '';
							if( isset( $settings[ 'parent_tb' ] ) && $settings[ 'parent_tb' ] ){
								$acl = $settings[ 'parent_tb' ];
							}else if( isset( $settings[ 'table_name' ] ) && $settings[ 'table_name' ] ){
								$acl = $settings[ 'table_name' ];
							}else if( isset( $settings[ 'tables' ][0] ) && $settings[ 'tables' ][0] ){
								$acl = $settings[ 'tables' ][0];
							}
							if( isset( $settings[ 'action_to_perform' ] ) && $settings[ 'action_to_perform' ] ){
								$atd = $settings[ 'action_to_perform' ];
							}
							if( isset( $settings[ 'plugin' ] ) && $settings[ 'plugin' ] ){
								$audit_params[ 'class' ] = $settings[ 'plugin' ];
								$audit_params[ 'nwp_action' ] = $acl;
								$audit_params[ 'nwp_todo' ] = $atd;
							}else{
								$audit_params[ 'class' ] = $acl;
								$audit_params[ 'action' ] = $atd;
							}

							if( isset( $settings[ 'record_id' ] ) ){
								$audit_params[ 'record_id' ] = $settings[ 'record_id' ];
							}
							if( isset( $settings[ 'table_name' ] ) ){
								$audit_params[ 'record_table' ] = $settings[ 'table_name' ];
							}elseif( isset( $settings[ 'tables' ][0] ) ){
								$audit_params[ 'record_table' ] = $settings[ 'tables' ][0];
							}
							if( isset( $settings[ 'record_plugin' ] ) ){
								$audit_params[ 'record_plugin' ] = $settings[ 'record_plugin' ];
							}elseif( isset( $settings[ 'plugin' ] ) && $settings[ 'plugin' ] ){
								$audit_params[ 'record_plugin' ] = $settings[ 'plugin' ];
							}

							auditor( "", $ak, $settings['table'] , $audit_params );
						}
						
					break;
					default:
					
						if( isset($settings['skip_log']) && $settings['skip_log'] ){
						}else{
							$callEndTime = microtime(true);
							$audit_params["duration"] = $callEndTime - $callStartTime;
							$ak = $qtype;
							
							$acl = '';
							$atd = '';
							if( isset( $settings[ 'parent_tb' ] ) && $settings[ 'parent_tb' ] ){
								$acl = $settings[ 'parent_tb' ];
							}else if( isset( $settings[ 'table_name' ] ) && $settings[ 'table_name' ] ){
								$acl = $settings[ 'table_name' ];
							}else if( isset( $settings[ 'tables' ][0] ) && $settings[ 'tables' ][0] ){
								$acl = $settings[ 'tables' ][0];
							}
							if( isset( $settings[ 'action_to_perform' ] ) && $settings[ 'action_to_perform' ] ){
								$atd = $settings[ 'action_to_perform' ];
							}
							if( isset( $settings[ 'plugin' ] ) && $settings[ 'plugin' ] ){
								$audit_params[ 'class' ] = $settings[ 'plugin' ];
								$audit_params[ 'nwp_action' ] = $acl;
								$audit_params[ 'nwp_todo' ] = $atd;
							}else{
								$audit_params[ 'class' ] = $acl;
								$audit_params[ 'action' ] = $atd;
							}

							if( isset( $settings[ 'record_id' ] ) ){
								$audit_params[ 'record_id' ] = $settings[ 'record_id' ];
							}
							if( isset( $settings[ 'table_name' ] ) ){
								$audit_params[ 'record_table' ] = $settings[ 'table_name' ];
							}elseif( isset( $settings[ 'tables' ][0] ) ){
								$audit_params[ 'record_table' ] = $settings[ 'tables' ][0];
							}
							if( isset( $settings[ 'record_plugin' ] ) ){
								$audit_params[ 'record_plugin' ] = $settings[ 'record_plugin' ];
							}elseif( isset( $settings[ 'plugin' ] ) && $settings[ 'plugin' ] ){
								$audit_params[ 'record_plugin' ] = $settings[ 'plugin' ];
							}

							auditor( "", $ak, $settings['table'] , $audit_params );
						}
						
					break;
					}
				}
			}
		}

		return $return;
	}

}
if( file_exists( dirname( __FILE__ ) . '/functions.php' ) )include dirname( __FILE__ ) . '/functions.php';
?>