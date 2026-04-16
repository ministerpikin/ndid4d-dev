<?php
/**
 * Main Plugin Class
 * @created  Hyella Nathan | 13:44 | 15-Aug-2023
 */
class cNwp_locations extends cPlugin_class{
	
	public $class_settings = array();
	
	private $current_record_id = '';
	
	public $table_name = 'nwp_locations';
	
	public $label = 'Locations';
	public $dir_path = __FILE__;
	
	function nwp_locations(){
		
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
		}
		
		return $returned_value;
	}
		
	public function _get_default_select2( $opt = array() ){

		$where = isset( $this->class_settings[ 'select2_where' ] ) ? $this->class_settings[ 'select2_where' ] : '';

		$table_name = isset( $opt[ 'table_name' ] ) ? $opt[ 'table_name' ] : '';
		$table_fields = isset( $opt[ 'table_fields' ] ) ? $opt[ 'table_fields' ] : [];

		$limit = '';
		$search_term = '';
		$mongo_where = array();
		$mongo_filter = array();
		$mongo_others = array();
		$plugin = isset( $_GET[ 'plugin' ] ) ? $_GET[ 'plugin' ] : '';

		if( ! ( isset( $table_fields[ 'name' ] ) && $table_fields[ 'name' ] )  ){
			return;
		}
		
		if( isset( $_POST["term"] ) && trim( $_POST["term"] ) ){
			$search_term = trim( $_POST["term"] );
			
			//OR `".$table_name."`.`".$table_fields["code"]."` regexp '".$search_term."'
			
			//$where = " AND ( `".$table_name."`.`".$table_fields["name"]."` regexp '".$search_term."' ) ";
			$where = " AND ( `".$table_name."`.`".$table_fields["name"]."` LIKE '%".$search_term."%' OR `".$table_name."`.`id` LIKE '%".$search_term."%' ) ";
			
			$mongo_where[ '$or' ][0][ $table_fields["name"] ][ '$regex' ] = '^'.$search_term;
			$mongo_where[ '$or' ][0][ $table_fields["name"] ][ '$options' ] = 'i';
			
		}else{
			$limit = "LIMIT 0, " . get_default_select2_limit();
			$mongo_filter[ 'limit' ] = get_default_select2_limit();
		}

		if( ( isset( $_GET[ 'exclude' ] ) && $_GET[ 'exclude' ] ) || ( isset( $_GET[ 'include' ] ) && $_GET[ 'include' ] ) ){
			$filter = array( 'exclude' => ( isset( $_GET[ 'exclude' ] ) ? $_GET[ 'exclude' ] : '' ), 'include' => ( isset( $_GET[ 'include' ] ) ? $_GET[ 'include' ] : '' ) );

			foreach( $filter as $f1 => $f2 ){
				
				if( $f2 ){
					$fil = '';
					switch( $f1 ){
					case 'exclude':
						$fil = '<>';
					break;
					case 'include':
						$fil = '=';
					break;
					}

					$z = preg_split( '(-|:)', $f2 );
					end( $z );
					if( ! $z[ key( $z ) ] )unset( $z[ key( $z ) ] );
					$x =  array_chunk( $z, 2 );

					$xx =  array_combine( array_column( $x, 0 ), array_column( $x, 1 ) );

					if( ! empty( $xx ) ){
						foreach( $xx as $kk => $vv ){
							switch( $kk ){
							case 'id':
							case 'created_by':
							case 'creation_date':
							case 'modified_by':
							case 'modified_date':
								$where .= " AND `".$table_name."`.`". $kk ."` ". $fil ." '".$vv."' ";
							break;
							default:
								if( isset( $table_fields[ $kk ] ) && $table_fields[ $kk ] ){
									$where .= " AND `".$table_name."`.`".$table_fields[ $kk ]."` ". $fil ." '".$vv."' ";
								}
							break;
							}
						}
					}
				}
			}
		}
		
		if( isset( $_GET[ "all" ] ) && $_GET[ "all" ] ){
			$limit = "";
			$mongo_filter[ 'limit' ] = "";
		}
		
		if( isset( $_GET[ "check_access" ] ) && $_GET[ "check_access" ] ){
			$access = get_accessed_functions();
			$super = 0;
			if( ! is_array( $access ) && $access == 1 ){
				$super = 1;
			}
			
			if( ! $super ){
				switch( $table_name ){
				case "states":
					
					if( isset( $access["status"]["access_all_states"] ) && $access["status"]["access_all_states"] ){
						
					}else if( isset( $access["states"] ) && ! empty( $access["states"] ) ){
						$ids = array();
						foreach( $access["states"] as $st => $sv ){
							$ids[] = "'". $st ."'";
						}
						$where = " AND `".$table_name."`.`id` IN ( ". implode(",", $ids ) ." ) ";
					}else{
						$where = " AND `".$table_name."`.`id` IS NULL ";
					}
				break;
				}
			}
		}
		
		$selecta = array();
		$select = "";
		$join = "";
		
		$selecta[] = "`".$table_name."`.`id` ";
		$selecta[] = "`".$table_name."`.`serial_num` ";
		
		$mongo_filter[ 'projection' ][ '_id' ] = 0;
		$mongo_filter[ 'projection' ][ 'id' ] = 1;
		$mongo_filter[ 'projection' ][ 'serial_num' ] = 1;
		
		$xkeys = array( 'name', 'code' );
		if( isset( $opt[ 'keys' ][0] ) && $opt[ 'keys' ][0] ){
			$xkeys = array_merge( $opt[ 'keys' ], $xkeys );
		}

		foreach( $table_fields as $key => $val ){
			if( $key == 'data' )continue;

			if( in_array( $key, $xkeys ) ){
				$selecta[] = " `".$table_name."`.`".$val."` as '".$key."' ";
				$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
			}
			
			if( isset( $this->class_settings["select_fields"][ $key ] ) ){
				$selecta[] = " `".$table_name."`.`".$val."` as '".$key."' ";
				$mongo_filter[ 'projection' ][ $key ] = '$' . $val;
			}
			
			$xwhere = array();
			if( isset( $_GET[ $key ] ) && $_GET[ $key ] ){
				$xwhere = explode( ',', $_GET[ $key ] );
			}else if( isset( $_POST[ $key ] ) && $_POST[ $key ] ){
				$xwhere = explode( ',', $_POST[ $key ] );
			}
			
			if( ! empty( $xwhere ) ){
				$where .= " AND `".$val."` IN ( '". implode( "','", $xwhere ) ."' ) ";
				foreach( $xwhere as $xw ){
					$mongo_where[ '$and' ][][ $val ] = $xw;
				}
			}
		}
		
		$select = implode(", ", $selecta );
		
		if( isset( $_GET["source"] ) ){
			if( isset( $this->class_settings[ 'get_select2_option_type' ] ) && $this->class_settings[ 'get_select2_option_type' ] ){

				if( $plugin ){
					if( $plugin == $this->table_name ){
						$this->load_class( array( 'class' => array( $_GET[ 'source' ] ) ) );
					}else{
						$pcl = 'c'.$plugin;

						if( class_exists( $pcl ) ){
							$this->class_settings[ 'plugin' ] = $plugin;
							$pcl = new $pcl;
							$pcl->load_class( array( 'class' => array( $_GET[ 'source' ] ) ) );
						}
					}
				}

				switch( $this->class_settings[ 'get_select2_option_type' ] ){
				case 'states':
					$checks = array( "country" );
					$found_check = 0;
					
					switch( $_GET["source"] ){
					case "lgas":
						$lgas = new cLga_list();
						
						foreach( $checks as $cv ){
							if( isset( $lgas->table_fields[ $cv ] ) && isset( $_POST[ $lgas->table_fields[ $cv ] ] ) ){
								$where .= " AND `".$table_fields[ $cv ]."` = '".$_POST[ $lgas->table_fields[ $cv ] ]."' ";
								$mongo_where[ '$and' ][][ $table_fields[ $cv ] ] = $_POST[ $lgas->table_fields[ $cv ] ];
								$found_check = 1;
								
							}
						}
					break;
					}
				break;
				case 'lgas':
					$checks = array( "country", "state" );
					$found_check = 0;
					$select .= ", `". $table_fields[ 'state' ] ."` as 'state' ";
					
					switch( $_GET["source"] ){
					case "wag":
					case "policy_collaborators":
					case "files":
					case "users":
					case "workflow":
					case "survey_assignment":
					case "households":
					case "community":
					case "community_list":
					case "ward_list":
						$c1 = 'c' . strtolower( $_GET["source"] );
						
						if( class_exists( $c1 ) ){
							$lgas = new $c1();
							
							foreach( $checks as $cv ){
								$field = '';
								$value = '';
								
								if( isset( $lgas->table_fields[ $cv ] ) && isset( $_POST[ $lgas->table_fields[ $cv ] ] ) ){
									$field = $lgas->table_fields[ $cv ];
									
									if( isset( $table_fields[ $cv ] ) ){
										$field = $table_fields[ $cv ];
									}
									
									$value = $_POST[ $lgas->table_fields[ $cv ] ];
								}
								
								if( ! $field ){
									if( isset( $table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
										$field = $table_fields[ $cv ];
										$value = $_POST[ $cv ];
									}
								}
								
								if( $field ){
									if( strpos( $value, ',' ) > -1 ){
										$where .= " AND `". $field ."` IN ( '". implode( "','", explode( ',', $value ) ) ."' ) ";
									}else{
										$where .= " AND `".$field."` = '".$value."' ";
									}
									$mongo_where[ '$and' ][][ $field ] = $value;
									$found_check = 1;
								}
							}
						}else{
							return array( "items" => array(), "do_not_reload_table" => 1 );
						}
					break;
					}
				break;
				case 'ward_list':
					$checks = array( "lga" );
					$found_check = 0;
					$select .= ", `". $table_fields[ 'state' ] ."` as 'state' ";
					$select .= ", `". $table_fields[ 'lga' ] ."` as 'lga' ";
					
					switch( $_GET["source"] ){
					case "wag":
					case "policy_collaborators":
					case "files":
					case "users":
					case "workflow":
					case "survey_assignment":
					case "households":
					case "community":
					case "community_list":
					case "ward_list":
						$c1 = 'c' . strtolower( $_GET["source"] );
						
						if( class_exists( $c1 ) ){
							$lgas = new $c1();
							
							foreach( $checks as $cv ){
								
								$field = '';
								$value = '';
								
								if( isset( $lgas->table_fields[ $cv ] ) && isset( $_POST[ $lgas->table_fields[ $cv ] ] ) ){
									$field = $lgas->table_fields[ $cv ];
									
									if( isset( $table_fields[ $cv ] ) ){
										$field = $table_fields[ $cv ];
									}
									
									$value = $_POST[ $lgas->table_fields[ $cv ] ];
								}
								
								if( ! $field ){
									if( isset( $table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
										$field = $table_fields[ $cv ];
										$value = $_POST[ $cv ];
									}
								}
								
								if( $field ){
									if( strpos( $value, ',' ) > -1 ){
										$where .= " AND `". $field ."` IN ( '". implode( "','", explode( ',', $value ) ) ."' ) ";
									}else{
										$where .= " AND `".$field."` = '".$value."' ";
									}
									$mongo_where[ '$and' ][][ $field ] = $value;
									$found_check = 1;
								}
							}
						}else{
							return array( "items" => array(), "do_not_reload_table" => 1 );
						}
					break;
					}
				break;
				case 'community_list':
					$checks = array( "ward" );
					$found_check = 0;
					$select .= ", `". $table_fields[ 'ward' ] ."` as 'ward' ";
					$select .= ", `". $table_fields[ 'state' ] ."` as 'state' ";
					$select .= ", `". $table_fields[ 'lga' ] ."` as 'lga' ";
					
					switch( $_GET["source"] ){
					case "wag":
					case "policy_collaborators":
					case "files":
					case "users":
					case "workflow":
					case "survey_assignment":
					case "community":
					case "households":
						$c1 = 'c' . strtolower( $_GET["source"] );
						
						if( class_exists( $c1 ) ){
							$lgas = new $c1();
							
							foreach( $checks as $cv ){
								
								$field = '';
								$value = '';
								
								if( isset( $lgas->table_fields[ $cv ] ) && isset( $_POST[ $lgas->table_fields[ $cv ] ] ) ){
									$field = $lgas->table_fields[ $cv ];
									
									if( isset( $table_fields[ $cv ] ) ){
										$field = $table_fields[ $cv ];
									}
									
									$value = $_POST[ $lgas->table_fields[ $cv ] ];
								}
								
								if( ! $field ){
									if( isset( $table_fields[ $cv ] ) && isset( $_POST[ $cv ] ) ){
										$field = $table_fields[ $cv ];
										$value = $_POST[ $cv ];
									}
								}
								
								if( $field ){
									if( strpos( $value, ',' ) > -1 ){
										$where .= " AND `". $field ."` IN ( '". implode( "','", explode( ',', $value ) ) ."' ) ";
									}else{
										$where .= " AND `".$field."` = '".$value."' ";
									}
									$mongo_where[ '$and' ][][ $field ] = $value;
									$found_check = 1;
								}
								
							}
						}
					break;
					}
					
					if( ! $found_check ){
						return array( "items" => array(), "do_not_reload_table" => 1 );
					}
				break;
				}
			}
			
			/*
			if( ! $found_check ){
				return array( "items" => array(), "do_not_reload_table" => 1 );
			}
			*/
		}

		$select .= ", `".$table_name."`.`".$table_fields["name"]."` as 'text'";
		$mongo_others[ 'concat' ][ 'value' ][] = '$'.$table_fields["name"];
		
		if( isset( $table_fields["code"] ) ){
			$mongo_others[ 'concat' ][ 'value' ][] = ' - ';
			$mongo_others[ 'concat' ][ 'value' ][] = '$'.$table_fields["code"];
		}
		$mongo_others[ 'concat' ][ 'text' ] = 'text';

		$mongo_others[ 'use_aggregate' ] = 1;
		
		$this->class_settings["overide_select"] = $select;
		if( isset( $this->class_settings["custom_select"] ) && $this->class_settings["custom_select"] ){
			$this->class_settings["overide_select"] = $this->class_settings["custom_select"];
		}
		$this->class_settings["order_by"] = " ORDER BY `".$table_name."`.`".$table_fields["name"]."` ";
		$this->class_settings["join"] = $join;
		$this->class_settings["where"] = $where;
		$this->class_settings["limit"] = $limit;
		$this->class_settings["mongo_where"] = $mongo_where;
		$this->class_settings["mongo_filter"] = $mongo_filter;
		$this->class_settings["mongo_others"] = $mongo_others;
		
		$b = new cCustomer_call_log;
		$b->table_name = $table_name;
		$b->table_fields = $table_fields;
		$b->class_settings = $this->class_settings;

		$data1 = $b->_get_records();
		
		if( isset( $this->class_settings[ 'return_indexed' ] ) && $this->class_settings[ 'return_indexed' ] ){
			$d_index = array();
			
			$indexed_fields = $this->class_settings[ 'return_indexed' ];
			
			if( ! empty( $data1 ) ){
				
				if( ! is_array( $indexed_fields ) ){
					unset( $indexed_fields );
					$indexed_fields = array( "id" );
				}
				
				$ix = count( $indexed_fields );
				
				foreach( $data1 as $d2 ){
					
					switch( $ix ){
					case 1:
						$d_index[ $d2[ $indexed_fields[ 0 ] ] ] = $d2;
					break;
					case 2:
						$d_index[ $d2[ $indexed_fields[ 0 ] ] ][ $d2[ $indexed_fields[ 1 ] ] ] = $d2;
					break;
					case 3:
						$d_index[ $d2[ $indexed_fields[ 0 ] ] ][ $d2[ $indexed_fields[ 1 ] ] ][ $d2[ $indexed_fields[ 2 ] ] ] = $d2;
					break;
					}
					
					
				}
			}
			
			return $d_index;
		}
		
		// print_r( $data1 );exit;
		return array( "items" => $data1, "do_not_reload_table" => 1 );
	}
	
}
if( file_exists( dirname( __FILE__ ) . '/functions.php' ) )include dirname( __FILE__ ) . '/functions.php';
?>