<span <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
	if( isset( $data["skip_access_control"] ) && $data["skip_access_control"] ){
		$super = 1;
	}else{
		$access = get_accessed_functions();
		$super = 0;
		if( ! is_array( $access ) && $access == 1 ){
			$super = 1;
		}
	}
	$gattr = '';
	
	if( isset( $data["new_title"] ) && $data["new_title"] ){
		$gattr .= ' new_title="'. strip_tags( $data["new_title"] ) . '" ';
	}
	
	$plugin = isset( $data["plugin"] )?$data["plugin"]:'';
	$development = isset( $data["development"] )?$data["development"]:0;
	
	$g_search_params3 = '';
	$g_search_params2 = '';
	$html_replacement_selector = '';
	if( isset( $data["html_replacement_selector"] ) && $data["html_replacement_selector"] ){
		$html_replacement_selector = $data["html_replacement_selector"];
		$g_search_params3 = '&html_replacement_selector=' . $data["html_replacement_selector"];
	}
	
	$cls = ' custom-single-selected-record-button-old ';
	$cls2 = ' dark ';
	$cls3 = 'More';
	
	$attr = '';
	$shared_type = isset( $more_data[ 'nw_shared' ] )?$more_data[ 'nw_shared' ]:'';
	$gbranch = isset( $more_data[ 'branch' ] )?$more_data[ 'branch' ]:'';
	$m_more_data = isset( $more_data[ 'more_data' ] )?$more_data[ 'more_data' ]:array();
	$additional_params = isset( $data[ 'more_data' ][ 'additional_params' ] )?$data[ 'more_data' ][ 'additional_params' ]:( isset( $m_more_data[ 'additional_params' ] )?$m_more_data[ 'additional_params' ]:'' );
	
	if( $gbranch ){
		$store = $gbranch;
		$g_search_params2 .= '&branch='.$gbranch;
	}
	
	$dt = 1;
	if( isset( $data["selected_record"] ) && $data["selected_record"] ){
		$dt = 0;
		$cls2 = ' btn-default ';
		$cls3 = 'Actions';
		
		$cls = ' custom-single-selected-record-button ';
		$attr .= ' override-selected-record="'. $data["selected_record"] .'" ';
	}

	if( isset( $data["mod"] ) && $data["mod"] ){
		$attr .= ' mod="'. $data["mod"] .'" ';
	}
	
	if( function_exists("get_nwp_share_buttons") ){
		$shb = get_nwp_share_buttons( array( "table" => isset($data["table"])?$data["table"]:'', "plugin" => $plugin, "more_data" => $m_more_data ) );
		if( isset( $shb['more_actions'] ) && is_array( $shb['more_actions'] ) && ! empty( $shb['more_actions'] ) ){
			if( isset( $shb["replace"] ) && $shb["replace"] ){
				$data["more_actions"] = $shb['more_actions'];
			}else if( isset( $data["more_actions"] ) && ! empty( $data["more_actions"] ) ){
				$data["more_actions"] = array_merge( $shb['more_actions'], $data["more_actions"] );
			}else{
				$data["more_actions"] = $shb['more_actions'];
			}
		}
	}
	
	//echo $plugin;
	//echo '<pre>';print_r( $html_replacement_selector ); echo '</pre>';
	// echo '<pre>';print_r( $data ); echo '</pre>';
	// echo '<pre>';print_r( $data["more_actions"] ); echo '</pre>';
	
	$standalone = '';
	$ma_html = '';
	if( isset( $data["more_actions"] ) && ! empty( $data["more_actions"] ) ){
		foreach( $data["more_actions"] as $act2k => $act2 ){
			if( ! isset( $act2["title"] ) ){
				continue;
			}
			$h = '';
			
			if( isset( $act2["data"] ) && ! empty( $act2["data"] ) ){
				foreach( $act2["data"] as $act3 ){
					if( ! empty( $act3 ) ){
						
						if( $h ){
							$h .= '<li class="divider"></li>';
						}
						
						foreach( $act3 as $bk1 => $act ){
							$table = isset( $act["action"] )?$act["action"]:$data["table"];
							$clt = $table;
							if( ! isset( $act["no_plugin"] ) && $plugin ){
								$clt = $plugin;
							}
							if( ! $development && isset( $act["development"] ) && $act["development"] ){
								continue;
							}
							if( $shared_type && isset( $act["hide"][ $shared_type ] ) ){
								continue;
							}
							
							// echo '<pre>'; print_r( $clt ); echo '</pre>';
							if( ! class_exists("c" . ucwords( $clt ) ) || ! ( isset( $act["todo"] ) || isset( $act["url"] ) ) ){
								continue;
							}
							
							$pk1 = '';
							if( ! isset( $act["no_plugin"] ) && $plugin ){
								$pk1 = $plugin . '.';
							}
							
							$ar_tb = isset( $data[ 'access_role_tb' ] ) ? $data[ 'access_role_tb' ] : $data["table"];
							if( isset( $act[ 'access_table' ] ) && $act[ 'access_table' ] ){
								$ar_tb = $act[ 'access_table' ];
							}
							
							//echo '<li>'.$ar_tb . "." . $act2k . "." .$bk1.'</li>';
							if( ! isset( $act["all_access"] ) && ! $super && ! ( isset( $access["accessible_functions"][ $pk1 . $ar_tb . "." . $act2k . "." .$bk1 ] ) || isset( $access["accessible_functions"][ $ar_tb . "." . $act2k . "." .$bk1 ] ) ) ){
								continue;
							}
		
							$url = '#';
							$d_action = '';
							$d_cls = '';

							$attr2 = $attr;
	
							if( isset( $act["url"] ) && $act["url"] ){
								$url = $act["url"];
							}else{
								
								$d_action = 'action='. $table .'&todo='. $act["todo"];
								if( ! isset( $act["no_plugin"] ) && $plugin ){
									$d_action = 'action='. $plugin .'&todo=execute&nwp_action='. $table .'&nwp_todo='. $act["todo"];
								}
								
								if( isset( $act["empty_container"] ) && $act["empty_container"] ){
									$d_action .= '&empty_container=' . ( isset( $act["empty_container_id"] )?$act["empty_container_id"]:$html_replacement_selector );
								}
								
								$d_cls = $cls;
								if( isset( $act["multiple"] ) && $act["multiple"] ){
									$d_cls = ' custom-multi-selected-record-button ';
								}
								
								if( isset( $act["selected_record"] ) && $act["selected_record"] ){
									$d_cls = ' custom-single-selected-record-button ';
									$attr2 = ' override-selected-record="'. $act["selected_record"] .'" ';

								}

								if( isset( $act[ 'record_id' ] ) && $act[ 'record_id' ] && isset( $data["selected_record"] ) && $data["selected_record"] )$d_action .= '&record_id=' . $data["selected_record"];
								
								if( isset( $act["id_field"] ) && $act["id_field"] && isset( $data["id_field"][ $act["id_field"] ] ) && $data["id_field"][ $act["id_field"] ] ){
									$d_cls = ' custom-single-selected-record-button ';
									$attr2 = ' override-selected-record="'. $data["id_field"][ $act["id_field"] ] .'" ';
								}
		
								if( isset( $act["mod"] ) && $act["mod"] ){
									$attr2 .= ' mod="'. $act["mod"] .'" ';
								}
								
								$g_trans = $g_search_params3;
								if( isset( $act["no_selector"] ) && $act["no_selector"] ){
									$g_trans = '';
								}
							
								if( isset( $act["html_replacement_key"] ) && isset( $data[ $act["html_replacement_key"] ] ) && $data[ $act["html_replacement_key"] ] ){
									$d_action .= '&html_replacement_selector=' . $data[ $act["html_replacement_key"] ];
								}else{
									$d_action .= $g_search_params2 . $g_trans;
								}
								// print_r( $cls ); exit;
							}
							
							if( isset( $act["attributes"] ) && $act["attributes"] ){
								$attr2 .= $act["attributes"];
							}
							
							if( isset( $act["class"] ) && $act["class"] ){
								$d_cls .= $act["class"];
							}
							
							$d_action .= $additional_params;
							
							if( isset( $data["standalone_buttons"][ $bk1 ] ) && $data["standalone_buttons"][ $bk1 ] ){
								$d_cls .= ( isset( $act[ 'standalone_class' ] )?$act[ 'standalone_class' ]:' btn-default ' );
								
								$standalone .= '<a href="'. $url .'" class="btn btn-sm '.$d_cls.'" '.$attr2.$gattr.' action="?' . $d_action .'&menu_title='. rawurlencode( $act["title"] ) .'" title="'. $act["title"] .'">'. $act["text"] .'</a>';
							}else{
								$h .= '<li><a href="'. $url .'" class="dropdown-item '.$d_cls.'" '.$attr2.$gattr.' action="?' . $d_action .'&menu_title='. rawurlencode( $act["title"] ) .'" title="'. $act["title"] .'">'. $act["text"] .'</a></li>';
							}
						}
					}
				}
				
				if( $h ){
					$h = '<div class="btn-group"><a type="button"  class="btn btn-sm '. $cls2 .' dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">'. $act2["title"] .' <!--<i class="icon-angle-down"></i>--></a><div class="dropdown-backdrop"></div><ul class="dropdown-menu" role="menu">' . $h . '</ul></div>';
				}
				
			}else{
				if( isset( $act2["todo"] ) || isset( $act2[ 'url' ] ) ){
					$pk1 = '';
					$table = isset( $act2["action"] )?$act2["action"]:$data["table"];
					$clt = $table;
					if( ! isset( $act2["no_plugin"] ) && $plugin ){
						$pk1 = $plugin . '.';
						$clt = $plugin;
					}
					
					if( ! $development && isset( $act2["development"] ) && $act2["development"] ){
						continue;
					}
					if( $shared_type && isset( $act2["hide"][ $shared_type ] ) ){
						continue;
					}
					if( ! class_exists("c" . ucwords( $clt ) ) ){
						continue;
					}

					if( ! isset( $act2["text"] ) ){
						$act2["text"] = '';
					}
					
					$ar_tb = isset( $data[ 'access_role_tb' ] ) ? $data[ 'access_role_tb' ] : $data["table"];
					if( isset( $act2[ 'access_table' ] ) && $act2[ 'access_table' ] ){
						$ar_tb = $act2[ 'access_table' ];
					}
					
					//echo '<li>'.$ar_tb . "." . $act2k .'</li>';
					//echo '<li>'.$pk1 . $ar_tb . "." . $act2k .'</li>';
					//echo '<li>'.$act2k . $ar_tb . "." . $act2k .'</li>';
					if( ! isset( $act2["all_access"] ) && ! $super && ! ( isset( $access["accessible_functions"][ $pk1 . $ar_tb . "." . $act2k ] ) || isset( $access["accessible_functions"][ $ar_tb . "." . $act2k ] ) || isset( $access["accessible_functions"][ $ar_tb . ".actions." . $act2k ] ) ) ){
						continue;
					}
					
					$attr2 = $attr;
					$d_action = '';
					$btn_color = isset( $act2["button_class"] )?$act2["button_class"]:$cls2;
					$d_cls = $cls;
					$url = '#';

					if( isset( $act2["url"] ) && $act2["url"] && ! ( isset( $act2[ 'todo' ] ) && $act2[ 'todo' ] ) ){
						$d_cls = '';
						$url = $act2["url"];
						// $cls = '';
					}else{

						if( isset( $act2["multiple"] ) && $act2["multiple"] ){
							$d_cls = ' custom-multi-selected-record-button ';
						}
						
						if( isset( $act2["selected_record"] ) && $act2["selected_record"] ){
							$d_cls = ' custom-single-selected-record-button ';
							$attr2 = ' override-selected-record="'. $act2["selected_record"] .'" ';
						}
						
						if( isset( $act2[ 'record_id' ] ) && $act2[ 'record_id' ] && isset( $data["selected_record"] ) && $data["selected_record"] )$act2["todo"] .= '&record_id=' . $data["selected_record"];
		
						if( isset( $act2["id_field"] ) && $act2["id_field"] && isset( $data["id_field"][ $act2["id_field"] ] ) && $data["id_field"][ $act2["id_field"] ] ){
							$d_cls = ' custom-single-selected-record-button ';
							$attr2 = ' override-selected-record="'. $data["id_field"][ $act2["id_field"] ] .'" ';
						}

						if( isset( $act2["url"] ) && $act2["url"] ){
							$url = $act2["url"];
							// $d_cls = '';
						}
		
						if( isset( $act2["mod"] ) && $act2["mod"] ){
							$attr .= ' mod="'. $act2["mod"] .'" ';
						}
						
						$d_action = 'action='. $table .'&todo='. $act2["todo"];
						if( ! isset( $act2["no_plugin"] ) && $plugin ){
							$d_action = 'action='. $plugin .'&todo=execute&nwp_action='. $table .'&nwp_todo='. $act2["todo"];
						}
						
						if( isset( $act2["empty_container"] ) && $act2["empty_container"] ){
							$d_action .= '&empty_container=' . ( isset( $act2["empty_container_id"] )?$act2["empty_container_id"]:$html_replacement_selector );
						}
						
						$g_trans = $g_search_params3;
						if( isset( $act2["no_selector"] ) && $act2["no_selector"] ){
							$g_trans = '';
						}
					
						if( isset( $act2["html_replacement_key"] ) && isset( $data[ $act2["html_replacement_key"] ] ) && $data[ $act2["html_replacement_key"] ] ){
							$d_action .= '&html_replacement_selector=' . $data[ $act2["html_replacement_key"] ];
						}else{
							$d_action .= $g_search_params2 . $g_trans;
						}
					}
					
					if( isset( $act2["attributes"] ) && $act2["attributes"] ){
						$attr2 .= $act2["attributes"];
					}
					
					if( isset( $act2["class"] ) && $act2["class"] ){
						$d_cls .= $act2["class"];
					}
			
					$d_action .= $additional_params;
					
					$h = '<a href="'. $url .'" class="'.$d_cls.' btn btn-sm '.$btn_color.'" action="?' . $d_action .'&menu_title='. rawurlencode( $act2["title"] ) .'" '.$attr2.$gattr.' title="'. $act2["title"] .'">'. $act2["text"] .'</a>';

					if( isset( $act2["append_html"] ) && $act2["append_html"] ){
						$h .= $act2["append_html"];
					}
				}
			}
			
			$ma_html .= $h;
		}
	}
	
	echo $standalone . $ma_html;
	include "utility-buttons.php";
?>
</span>