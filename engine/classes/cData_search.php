<?php
	/**
	 * Data_search Class
	 *
	 * @used in  				data_search Function
	 * @created  				13:27 | 05-01-2013
	 * @database table name   	data_search
	 */

	/*
	|--------------------------------------------------------------------------
	| data_search Function in Settings Module
	|--------------------------------------------------------------------------
	|
	| Interfaces with database table to generate data capture form, dataTable,
	| execute search, insert new records into table, delete and modify existing
	| in the dataTable.
	|
	*/
	
	class cData_search{
		public $class_settings = array();
		
		private $current_record_id = '';
		
		private $table_name = 'businesses';
		
		function data_search(){
			//INITIALIZE RETURN VALUE
			$returned_value = '';
			
			$this->class_settings['current_module'] = '';
			
			if(isset($_GET['module']))
				$this->class_settings['current_module'] = $_GET['module'];
			
			switch ( $this->class_settings['action_to_perform'] ){
			case 'search_business_listings':
				$returned_value = $this->_search_business_listings();
			break;
			}
			
			return $returned_value;
		}
		
		
		private function _search_business_listings(){
			$slimit = '';
			if( isset( $_GET[ 'limit_start' ] ) && isset( $_GET[ 'limit_interval' ] ) && $_GET[ 'limit_interval' ] ){
				//ORDER BY RAND()
				$slimit = " LIMIT " . intval( $_GET[ 'limit_start' ] ) . ", " . intval( $_GET[ 'limit_interval' ] );
			}
			
			$search_condition = "";
			
			if( isset( $_GET[ 'search_condition' ] ) && $_GET[ 'search_condition' ] ){
				$search_condition = " ( `".$this->table_name."`.`businesses001` REGEXP '" . $_GET[ 'search_condition' ] . "'";
					$search_condition .= " OR `".$this->table_name."`.`businesses002` REGEXP '" . $_GET[ 'search_condition' ] . "'";
					$search_condition .= " OR `".$this->table_name."`.`businesses003` REGEXP '" . $_GET[ 'search_condition' ] . "'";
					$search_condition .= " OR `".$this->table_name."`.`businesses009` REGEXP '" . $_GET[ 'search_condition' ] . "'";
					$search_condition .= " OR `".$this->table_name."`.`businesses011` REGEXP '" . $_GET[ 'search_condition' ] . "'";
					$search_condition .= " OR `".$this->table_name."`.`businesses010` REGEXP '" . $_GET[ 'search_condition' ] . "'";
					$search_condition .= " OR `".$this->table_name."`.`businesses012` REGEXP '" . $_GET[ 'search_condition' ] . "'";
					$search_condition .= " OR `".$this->table_name."`.`businesses013` REGEXP '" . $_GET[ 'search_condition' ] . "'";
					$search_condition .= " OR `".$this->table_name."`.`businesses017` REGEXP '" . $_GET[ 'search_condition' ] . "'";
					$search_condition .= " OR `".$this->table_name."`.`businesses018` REGEXP '" . $_GET[ 'search_condition' ] . "'";
				$search_condition .= " ) ";
			}
			
			$project = get_project_data();
			
			$returning_array = array(
				'html' => '',
				'method_executed' => $this->class_settings['action_to_perform'],
				'status' => 'search-business-listings',
			);
			
			//PREPARE FROM DATABASE
			$query = "SELECT * FROM `".$this->class_settings['database_name']."`.`" . $this->table_name . "` WHERE `".$this->table_name."`.`record_status`='1' AND ".$search_condition." ".$slimit;
			$query_settings = array(
				'database' => $this->class_settings['database_name'] ,
				'connect' => $this->class_settings['database_connection'] ,
				'query' => $query,
				'query_type' => 'SELECT',
				'set_memcache' => 1,
				'tables' => array( $this->table_name ),
			);
			
			//$returning_array['query'] = $query;
			
			$sql_result = execute_sql_query($query_settings);
			
			$businesses = array();
			
			if( is_array( $sql_result ) && ! empty( $sql_result ) ){
				
				if( $slimit ){
					$businesses[ 'limit_start' ] = $_GET[ 'limit_start' ] + $_GET[ 'limit_interval'];
				}
				
				$previous_id = '';
				
				foreach( $sql_result as $s_val ){
					
					if( doubleval( $s_val[ 'businesses005' ] ) && doubleval( $s_val[ 'businesses021' ] ) ){
						$rate_converted = $s_val[ 'businesses005' ] / $s_val[ 'businesses021' ];
					}else{
						$rate_converted = 0;
					}
					
					$businesses[ 'business' ][ $s_val[ 'id' ] ] = array(
						'id' => $s_val[ 'id' ],
						
						'name' => $s_val[ 'businesses001' ],
						'tagline' => $s_val[ 'businesses002' ],
						
						'primary_activity' => $this->_get_select_option_value( array( 'id' => $s_val[ 'businesses003' ], 'function_name' => 'get_primary_categories',  ) ),
						
						'rating' => round( ( ( $rate_converted ) * 100 ) / 5 , 1),
						'rating_converted' => round( $rate_converted, 1),
						
						'primary_display_image' => $project[ 'domain_name' ] . $this->_get_image_url( array( 'url' => $s_val[ 'businesses006' ] ) ),
						'display_image_1' => $project[ 'domain_name' ] . $this->_get_image_url( array( 'url' => $s_val[ 'businesses007' ] ) ),
						'display_image_2' => $project[ 'domain_name' ] . $this->_get_image_url( array( 'url' => $s_val[ 'businesses008' ] ) ),
						
						'email' => $s_val[ 'businesses011' ],
						'phone' => $s_val[ 'businesses010' ],
						
						'street_address' => $s_val[ 'businesses012' ],
						'city' => $s_val[ 'businesses013' ],
						'state' => $this->_get_select_option_value( array( 'id' => $s_val[ 'businesses014' ], 'function_name' => 'get_states' ) ),
						'country' => $this->_get_select_option_value( array( 'id' => $s_val[ 'businesses015' ], 'function_name' => 'get_countries' ) ),
						
						'additional_info' =>  strip_tags( $s_val[ 'businesses018' ] , '<p><a><br /><span><strong><h1><h2><h3><h4><h5><h6><b><i>' ),
						
						'description_of_business_activity' => strip_tags( $s_val[ 'businesses017' ] , '<p><a><br /><span><strong><h1><h2><h3><h4><h5><h6><b><i>' ),
						
						'short_address' => $s_val[ 'businesses009' ],
						
						'next_business_id' => '',
						'prev_business_id' => $previous_id,
					);
					
					if( isset( $businesses[ 'business' ][ $previous_id ] ) ){
						$businesses[ 'business' ][ $previous_id ][ 'next_business_id' ] = $s_val[ 'id' ];
					}
					
					$previous_id = $s_val[ 'id' ];
				}
				
				$returning_array[ 'html' ] = $businesses;
				
			}else{
				//return no record
				
			}
			
			return $returning_array;
		}
		
		private function _get_image_url( $settings = array() ){
			if( isset( $settings[ 'url' ] ) && $settings[ 'url' ] ){
				$image = explode( ':::' , $settings[ 'url' ] );
				
				if( isset( $image[0] ) ){
					return $image[0];
				}
			}
		}
		
		private function _get_select_option_value( $settings = array() ){
			if( isset( $settings[ 'id' ] ) && $settings[ 'id' ] && isset( $settings[ 'function_name' ] ) && $settings[ 'function_name' ] ){
				
				if( function_exists( $settings[ 'function_name' ] ) ){
					$primary_categories = $settings[ 'function_name' ]();
				
					if( isset( $primary_categories[ $settings[ 'id' ] ] ) ){
						return $primary_categories[ $settings[ 'id' ] ];
					}
				
				}
			}
			
			return 'not available';
		}
	
	}
?>