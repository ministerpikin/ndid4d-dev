<span <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
$plugin = isset( $data["plugin"] )?$data["plugin"]:'';
$table = isset( $data["table"] )?$data["table"]:'';
$rtable = ( isset( $data["records_table"] ) && $data["records_table"] )?$data["records_table"]:$table;
$params = isset( $data["params"] )?$data["params"]:'';
$ub = isset( $data["utility_buttons"] )?$data["utility_buttons"]:array();
$cb = isset( $data["custom_button"] )?$data["custom_button"]:'';
$set_html_container = isset( $data["set_html_container"] )?$data["set_html_container"]:array();
$html_replacement_selector = isset( $data["html_replacement_selector"] )?$data["html_replacement_selector"]:'';
$workflow_id = isset( $data["workflow_id"] )?$data["workflow_id"]:'';
$uflag = '';
// echo '<pre>'; print_r($ub); echo '</pre>';

if( isset( $data['uflag']['g_text'] ) && $data['uflag']['g_text'] ){
	$uflag = '<b>'.$data['uflag']['g_text'] . '</b>';
}
if( is_array( $ub ) && ! empty( $ub ) ){
		$html = "";
		$use_group = 0;
		$group_name = 0;

		$params = array();
		$class = "btn dark btn-sm custom-multi-selected-record-button";
		$class2 = "btn dark btn-sm custom-single-selected-record-button-old ";
		$class4 = "btn btn-sm custom-single-selected-record-button-old ";
		$class3 = "btn dark btn-sm custom-single-selected-record-button ";
		$group_class = ' dark ';
		
		$attr = '';
		$attr_specific = isset( $data[ 'attr_specific' ] ) ? $data[ 'attr_specific' ] : array();
		if( isset( $data["selected_record"] ) && $data["selected_record"] ){
			$group_class = ' btn-default ';
			$class2 = ' btn-sm btn btn-default custom-single-selected-record-button ';
			$attr = ' override-selected-record="'. $data["selected_record"] .'" ';
		}
		
// echo '<pre>'; print_r( $ub ); echo '</pre>';
		foreach( $ub as $ubs => $usv ){
			if( isset( $set_html_container[ $ubs ] ) && $html_replacement_selector ){
				$params[ $ubs ][ 'params' ] = 'html_replacement_selector='. $html_replacement_selector;
			}
			
			if( $shared_type && is_array( $usv ) && isset( $usv["hide"][ $shared_type ] ) ){
				continue;
			}
			
			if( $usv === 0 ){
				continue;
			}
			
			switch( $ubs ){
			case 'open_workflow':
			case 'add_to_workflow':
				$ltxt = isset( $usv[ 'label' ] ) ? $usv[ 'label' ] : 'Start Workflow';
				$params[ $ubs ][ 'no_plugin' ] = 1;
				$params[ $ubs ][ 'action' ] = 'nwp_workflow';
				$params[ $ubs ][ 'class' ] = $class2;
				$params[ $ubs ][ 'todo' ] = 'execute&nwp_action=workflow&nwp_todo='. $ubs .'&table='. $table;
				$params[ $ubs ][ 'text' ] = $ltxt;
				$params[ $ubs ][ 'title' ] = $ltxt;
				
				if( $plugin )$params[ $ubs ][ 'todo' ] .= '&plugin='. $plugin;
				
				if( is_array( $usv ) ){
					if( isset( $usv["workflow_settings_id"] ) )$params[ $ubs ][ 'todo' ] .= '&workflow_settings_id='.$usv["workflow_settings_id"];
				}
			break;
			case 'view_details':
				$params[ $ubs ][ 'action' ] = $table;
				$params[ $ubs ][ 'class' ] = $class2;
				$params[ $ubs ][ 'todo' ] = 'view_details';
				$params[ $ubs ][ 'text' ] = 'View Details';
				$params[ $ubs ][ 'title' ] = 'View Details';
			break;
			case 'create_mine':
				$params[ $ubs ][ 'action' ] = 'mines';
				$params[ $ubs ][ 'class' ] = 'btn dark btn-sm custom-multi-selected-record-button-old';
				$params[ $ubs ][ 'todo' ] = 'create_mine';
				$params[ $ubs ][ 'text' ] = 'Proceed -> Create Mine';
				$params[ $ubs ][ 'title' ] = 'Click to Create Mine';
				$params[ $ubs ][ 'params' ] = 'table='. $table .'&callback=benefitProgram.createMine&html_replacement_selector=create-mine';
				$params[ $ubs ][ 'id' ] = 'comment-records-button';
			break;
			case 'assign':
			case 'share':
			case 'tags':
			case 'comments':
			case 'attach_file':
			case 'revision_history':
			case 'view_attachments':
			case 'get_collaborators':
			case 'view_folders':
			
				$params[ 'utilities' ][ 'use_group' ] = 1;
				$params[ 'utilities' ][ 'group_name' ] = 'Utilities';
				$clas = 'custom-multi-selected-record-button';
				
				if( isset( $data["selected_record"] ) && $data["selected_record"] ){
					$clas = ' custom-single-selected-record-button ';
					$attr = ' override-selected-record="'. $data["selected_record"] .'" ';
				}
				
				switch( $ubs ){
				case 'view_attachments':
					$uflagx = '';
					if( isset( $data['uflag']['files'] ) && $data['uflag']['files'] ){
						$uflagx = '<b>'.$data['uflag']['files'] . '</b>&nbsp;&nbsp;';
					}
					
					$params[ 'utilities' ][ $ubs ][ 'class' ] = $clas;
					$params[ 'utilities' ][ $ubs ][ 'action' ] = 'files';
					$params[ 'utilities' ][ $ubs ][ 'todo' ] = 'view_attachments';
					$params[ 'utilities' ][ $ubs ][ 'text' ] = 'View Attachments' . $uflagx;
					$params[ 'utilities' ][ $ubs ][ 'title' ] = 'Display Files Attached to this File';
					$params[ 'utilities' ][ $ubs ][ 'params' ] = 'reference_table='. $rtable;
					$params[ 'utilities' ][ $ubs ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ $ubs ][ 'icon' ] = 'icon-share';
					
				break;
				case 'view_folders':
					$params[ 'utilities' ][ $ubs ][ 'class' ] = $clas;
					$params[ 'utilities' ][ $ubs ][ 'action' ] = 'tags';
					$params[ 'utilities' ][ $ubs ][ 'todo' ] = 'view_folders';
					$params[ 'utilities' ][ $ubs ][ 'text' ] = 'View Folders';
					$params[ 'utilities' ][ $ubs ][ 'title' ] = 'Display Folders that Contains this File';
					$params[ 'utilities' ][ $ubs ][ 'params' ] = 'reference_table='. $rtable;
					$params[ 'utilities' ][ $ubs ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ $ubs ][ 'icon' ] = 'icon-share';
				break;
				case 'get_collaborators':
					$params[ 'utilities' ][ $ubs ][ 'class' ] = $clas;
					$params[ 'utilities' ][ $ubs ][ 'action' ] = 'share';
					$params[ 'utilities' ][ $ubs ][ 'todo' ] = 'get_collaborators';
					$params[ 'utilities' ][ $ubs ][ 'text' ] = 'View Collaborators';
					$params[ 'utilities' ][ $ubs ][ 'title' ] = 'Display Persons who has Access to this Record';
					$params[ 'utilities' ][ $ubs ][ 'params' ] = 'reference_table='. $rtable;
					$params[ 'utilities' ][ $ubs ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ $ubs ][ 'icon' ] = 'icon-share';
				break;
				case 'share':
					$params[ 'utilities' ][ $ubs ][ 'class' ] = $clas;
					$params[ 'utilities' ][ $ubs ][ 'action' ] = 'share';
					$params[ 'utilities' ][ $ubs ][ 'todo' ] = 'display_form';
					$params[ 'utilities' ][ $ubs ][ 'text' ] = 'Share';
					$params[ 'utilities' ][ $ubs ][ 'title' ] = 'Share';
					$params[ 'utilities' ][ $ubs ][ 'params' ] = 'reference_table='. $rtable;
					$params[ 'utilities' ][ $ubs ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ $ubs ][ 'icon' ] = 'icon-share';
				break;
				case 'assign':
					$params[ 'utilities' ][ $ubs ][ 'class' ] = $clas;
					$params[ 'utilities' ][ $ubs ][ 'action' ] = 'share';
					$params[ 'utilities' ][ $ubs ][ 'todo' ] = 'assign_record';
					$params[ 'utilities' ][ $ubs ][ 'text' ] = 'Assign';
					$params[ 'utilities' ][ $ubs ][ 'title' ] = 'Assign';
					$params[ 'utilities' ][ $ubs ][ 'params' ] = 'reference_table='. $rtable;
					$params[ 'utilities' ][ $ubs ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ $ubs ][ 'icon' ] = 'icon-arrow-down';
					
					
					if( isset( $usv["use_plugin"] ) ){
						$params[ 'utilities' ][ $ubs ][ 'params' ] .= '&plugin='.$plugin;
					}
				break;
				case 'tags':
					$params[ 'utilities' ][ $ubs ][ 'class' ] = $clas;
					$params[ 'utilities' ][ $ubs ][ 'action' ] = 'tags';
					$params[ 'utilities' ][ $ubs ][ 'todo' ] = 'create_tag';
					$params[ 'utilities' ][ $ubs ][ 'text' ] = 'Add to Folder';
					$params[ 'utilities' ][ $ubs ][ 'title' ] = 'Add to Folder';
					$params[ 'utilities' ][ $ubs ][ 'params' ] = 'reference_table='. $rtable;
					$params[ 'utilities' ][ $ubs ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ $ubs ][ 'icon' ] = 'icon-tag';
				break;
				case 'attach_file':
					$params[ 'utilities' ][ $ubs ][ 'class' ] = $clas;
					$params[ 'utilities' ][ $ubs ][ 'action' ] = 'files';
					$params[ 'utilities' ][ $ubs ][ 'todo' ] = 'attach_file_to_record';
					$params[ 'utilities' ][ $ubs ][ 'text' ] = 'Attach File';
					$params[ 'utilities' ][ $ubs ][ 'title' ] = 'Add File';
					$params[ 'utilities' ][ $ubs ][ 'params' ] = 'table='. $rtable;
					$params[ 'utilities' ][ $ubs ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ $ubs ][ 'icon' ] = 'icon-tag';
				break;
				case 'revision_history':

					$show_rhistory = 0;
					if( defined( 'HYELLA_V3_HIDE_REVISION_HISTORY' ) ){
						switch( HYELLA_V3_HIDE_REVISION_HISTORY ){
						case 1:
							if( isset( $this->class_settings[ 'priv_id' ] ) && $this->class_settings[ 'priv_id' ] == '1300130013' )$show_rhistory = 1;
						break;
						case 2:
							$show_rhistory = 1;
						break;
						}
					}
					// print_r( $show_rhistory );exit;

					if( ! $show_rhistory )continue 3;

					$ltxt = isset( $usv[ 'label' ] ) ? $usv[ 'label' ] : 'Revision History';

					$params[ 'utilities' ][ $ubs ][ 'class' ] = $clas;
					$params[ 'utilities' ][ $ubs ][ 'action' ] = 'revision_history';
					$params[ 'utilities' ][ $ubs ][ 'todo' ] = 'show_revision_history';
					$params[ 'utilities' ][ $ubs ][ 'text' ] = $ltxt;
					$params[ 'utilities' ][ $ubs ][ 'title' ] = $ltxt;
					$params[ 'utilities' ][ $ubs ][ 'params' ] = 'table='. $rtable;
					$params[ 'utilities' ][ $ubs ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ $ubs ][ 'icon' ] = 'icon-tag';
				
					if( $plugin )$params[ 'utilities' ][ $ubs ][ 'params' ] .= '&plugin='. $plugin;
				break;
				case 'comments':
					$params[ 'utilities' ][ $ubs ][ 'class' ] = $clas;
					$params[ 'utilities' ][ $ubs ][ 'action' ] = 'comments';
					$params[ 'utilities' ][ $ubs ][ 'todo' ] = 'add_comment';
					$params[ 'utilities' ][ $ubs ][ 'text' ] = 'Comments';
					$params[ 'utilities' ][ $ubs ][ 'title' ] = 'Comments';
					
					$params[ 'utilities' ][ $ubs ][ 'params' ] = 'table='. $rtable;
					if( isset( $data[ 'comment_table' ] ) && $data[ 'comment_table' ] ){
						$params[ 'utilities' ][ $ubs ][ 'params' ] = 'table='. $data[ 'comment_table' ];
					}

				   	if( $plugin ){
						$params[ 'utilities' ][ $ubs ][ 'params' ] .= '&plugin='. $plugin;
					}
					$params[ 'utilities' ][ $ubs ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ $ubs ][ 'icon' ] = 'icon-comment';
					
					$uflagx = '';
					if( isset( $data['uflag']['comments'] ) && $data['uflag']['comments'] ){
						$uflagx = '<b>'.$data['uflag']['comments'] . '</b>&nbsp;&nbsp;';
					}

					$params[ 'utilities' ][ 'view_comments' ][ 'class' ] = $clas;
					$params[ 'utilities' ][ 'view_comments' ][ 'key' ] = $ubs;
					$params[ 'utilities' ][ 'view_comments' ][ 'action' ] = 'comments';
					$params[ 'utilities' ][ 'view_comments' ][ 'todo' ] = 'view_details_by_reference';
					$params[ 'utilities' ][ 'view_comments' ][ 'text' ] = 'View Comments' . $uflagx;
					$params[ 'utilities' ][ 'view_comments' ][ 'title' ] = 'View Comments';

					$params[ 'utilities' ][ 'view_comments' ][ 'params' ] = 'table='. $rtable;
					if( isset( $data[ 'comment_table' ] ) && $data[ 'comment_table' ] ){
						$params[ 'utilities' ][ 'view_comments' ][ 'params' ] = 'table='. $data[ 'comment_table' ];
					}
					
				   	if( $plugin ){
						$params[ 'utilities' ][ 'view_comments' ][ 'params' ] .= '&plugin='. $plugin;
					}
					$params[ 'utilities' ][ 'view_comments' ][ 'id' ] = 'share-records-button';
					$params[ 'utilities' ][ 'view_comments' ][ 'icon' ] = 'icon-comment';
				break;
				}
				
				
				if( isset( $set_html_container[ $ubs ] ) && $html_replacement_selector ){
					$params[ 'utilities' ][ $ubs ][ 'params' ] .= 'html_replacement_selector='. $html_replacement_selector;
				}
				
			break;
			}
			
			
			if( is_array( $usv ) ){
				if( isset( $usv["html_replacement_key"] ) && isset( $data[ $usv["html_replacement_key"] ] ) && $data[ $usv["html_replacement_key"] ] ){
					$params[ $ubs ][ 'params' ] = 'html_replacement_selector=' . $data[ $usv["html_replacement_key"] ];
				}
				if( isset( $usv["params"] ) && $usv["params"] ){
					if( ! isset( $params[ $ubs ][ 'params' ] ) ){
						$params[ $ubs ][ 'params' ] = '';
					}
					if( substr( $usv["params"], 0, 1 ) != '&' ){
						$usv["params"] = '&' . $usv["params"];
					}
					$params[ $ubs ][ 'params' ] .= $usv["params"];
				}
				
				if( isset( $usv["attributes"] ) && $usv["attributes"] ){
					$params[ $ubs ][ 'attributes' ] = $usv["attributes"];
				}
				if( isset( $usv["text"] ) && $usv["text"] ){
					$params[ $ubs ][ 'text' ] = $usv["text"];
				}
				if( isset( $usv["title"] ) && $usv["title"] ){
					$params[ $ubs ][ 'title' ] = $usv["title"];
				}
				if( isset( $usv["standalone_class"] ) && isset( $params[ $ubs ][ 'class' ] ) )$params[ $ubs ][ 'class' ] .= $usv["standalone_class"];
				
				if( isset( $usv["no_plugin"] ) )$params[ $ubs ][ 'no_plugin' ] = $usv["no_plugin"];
				if( isset( $usv["table"] ) )$params[ $ubs ][ 'action' ] = $usv["table"];
			}
			
		}

//echo '<pre>'; print_r( $params ); echo '</pre>';
		foreach( $params as $k1 => $val ){

			if( isset( $val[ 'use_group' ] ) ){
				$html .= '<div class="btn-group">
				<a type="button"  class="btn btn-sm '.$group_class.' dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">'. $val[ 'group_name' ] . $uflag .'</a><div class="dropdown-backdrop"></div>
					 <ul class="dropdown-menu" role="menu">';
					unset( $val[ 'use_group' ] );
					unset( $val[ 'group_name' ] );
					$htm2 = '';
					
					foreach( $val as $k2 => $v ){
						$k = $k2;
						if( isset( $v["key"] ) && $v["key"] ){
							$k = $v["key"];
						}
						foreach( array( 'params', 'action' ) as $pk1 ){
							if( isset( $params[ $k ][ $pk1 ] ) && $params[ $k ][ $pk1 ] ){
								$v[ $pk1 ] = $params[ $k ][ $pk1 ];
							}
						}
						
						if( isset( $params[ $k ][ 'action' ] ) && $params[ $k ][ 'action' ] && $plugin ){
							$v[ 'params' ] .= '&nwp_action=' . $v[ 'action' ];
							$v[ 'params' ] .= '&nwp_todo=' . $v[ 'todo' ];
							
							$v[ 'action' ] = $plugin;
							$v[ 'todo' ] = 'execute';
						}
						
						$attr2 = '';
						if( isset( $v[ 'attributes' ] ) && $v[ 'attributes' ] ){
							$attr2 = $v[ 'attributes' ];
						}
						
						$attr3 = $attr;
						if( isset( $attr_specific[ $k2 ] ) && $attr_specific[ $k2 ] ){
							$attr3 = $attr_specific[ $k2 ];
						}

						$htm2 .= '<li><a href="#" id="'. $v[ 'id' ] .'" class="dropdown-item '. $v[ 'class' ] .'" action="?module=&action='. $v[ 'action' ] .'&todo='. $v[ 'todo' ] . '&app_manager_control=1&'. $v[ 'params' ] .'" title="'. $v[ 'title' ] .'" '.$attr3.$attr2.'>'.( $v[ 'icon' ] ? '<i class="'. $v[ 'icon' ] .'"></i>' : '' ) . $v[ 'text' ] .'</a></li>';
					}

					if( $htm2 ){
						$html .= $htm2;
						$html .= '</ul>
							</div>';
					}else{
						$html = '';
					}
			}else{
				
				if( isset( $val[ 'action' ] ) && isset( $val[ 'todo' ] ) ){
					$attr2 = '';
					$attr3 = $attr;
					if( isset( $attr_specific[ $k1 ] ) && $attr_specific[ $k1 ] ){
						$attr3 = $attr_specific[ $k1 ];
					}

					if( isset( $val[ 'attributes' ] ) && $val[ 'attributes' ] ){
						$attr2 = $val[ 'attributes' ];
					}
					
					if( isset( $val[ 'no_plugin' ] ) && $val[ 'no_plugin' ] ){
						
					}else if( $plugin ){
						if( ! isset( $val[ 'params' ] ) ){
							$val[ 'params' ] = '';
						}
						
						$val[ 'params' ] .= '&nwp_action=' . $val[ 'action' ];
						$val[ 'params' ] .= '&nwp_todo=' . $val[ 'todo' ];
						
						$val[ 'action' ] = $plugin;
						$val[ 'todo' ] = 'execute';
					}
						
					$html .= '<a href="#" id="'. (isset( $val[ 'id' ] ) ? $val[ 'id' ] : '') .'" class="'. (isset( $val[ 'class' ] ) ? $val[ 'class' ] : '') .'" action="?module=&action='. $val[ 'action' ] .'&todo='. $val[ 'todo' ] . '&app_manager_control=1&'. (isset( $val[ 'params' ] ) ? $val[ 'params' ] : '') .'" title="'. (isset( $val[ 'title' ] ) ? $val[ 'title' ] : '') .'" '.$attr3.$attr2.'>'. ( isset( $val[ 'icon' ] ) ? $val[ 'icon' ] : '' ) . $val[ 'text' ] .'</a>';
				}
			}
		}

	echo $html;
}
?>
</span>