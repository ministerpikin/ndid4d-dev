<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/item-list-css.css' ) )include "item-list-css.css"; ?>
</style>
<?php
if( isset( $data["items"] ) && is_array( $data["items"] ) && !empty( $data["items"] ) ){
	$data[ "report_data" ] = $data["items"];
	unset( $data["items"] );
	
	if( isset( $data[ 'table_labels' ] ) && is_array( $data[ 'table_labels' ] ) ){
		$GLOBALS["labels"] = $data[ 'table_labels' ];
	}
	
	if( isset( $data[ 'table_fields' ] ) && is_array( $data[ 'table_fields' ] ) ){
		unset( $data[ 'table_fields' ][ 'data' ] );
		$GLOBALS["fields"] = $data[ 'table_fields' ];
	}
	$title = "";
	if( isset( $data["report_title"] ) )$title = $data["report_title"];
	
	$subtitle = "";
	if( isset( $data["report_subtitle"] ) )$subtitle = $data["report_subtitle"];
	
	$params = '';
	if( isset( $data[ "html_replacement_selector" ] ) && $data[ "html_replacement_selector" ] ){
		$params = '&html_replacement_selector=' . $data[ "html_replacement_selector" ];
	}
	
	$data["action_buttons"] = isset( $data[ 'action_buttons' ] ) ? $data[ 'action_buttons' ] : '';
	
	$data["edit_link"] = 1;
	
	$show_delete_box = 1;
	$show_details = 1;
	$show_edit = 0;
	
	// unset( $GLOBALS["fields"]["survey_id"] );

	if( $show_delete_box ){
		$data["delete_checkbox"] = '<input type="checkbox" name="id[]" value=":::id:::" class="checkbox delete-checkbox" />';
	}
	// print_r( $data[ 'action_buttons' ] );exit;
	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=view_details&table='. $data["table"] .'" title="View Details">View Details</a>';

	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action=comments&todo=add_comment&table='. $data["table"] .'" title="Add Comment">Comment</a>';

	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action=files&todo=attach_file_to_record&table='. $data["table"] .'" title="Add File">Attach File</a>';
	
	if( $show_edit ){
		$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=edit_popup_form" mod="edit-'.md5( $data["table"] ).'" title="Edit">Edit</a>';
	}
	
	$table_class = 'shopping-cart-table'; 
	//if( isset( $data["report_data"] ) )print_r( $data["report_data"] );
	
	$row_heading = '';
	$row_body = '';
	
	if( isset( $data[ "report_data" ] ) && is_array( $data[ "report_data" ] ) && isset( $data[ 'table_labels' ] ) && is_array( $data[ 'table_labels' ] ) && isset( $data[ 'table_fields' ] ) && is_array( $data[ 'table_fields' ] ) ){
		
		$col_span = 0;
		$values = $data[ 'report_data' ];
		$e_options = isset( $data[ 'extra_options' ] )?$data[ 'extra_options' ]:array();
		
		$fields = $GLOBALS[ 'fields' ];
		$labels = $GLOBALS[ 'labels' ];
		$row_heading = '';
		$show_delete_checkbox = isset( $data["delete_checkbox"] )?$data["delete_checkbox"]:'';
		$show_edit_link = isset( $data["edit_link"] )?$data["edit_link"]:'';
		
		$show_staff = isset( $data["show_staff"] )?$data["show_staff"]:1;
		$show_starred = isset( $data["show_starred"] )?$data["show_starred"]:1;
		$show_tag_button = isset( $data["show_tag_button"] )?$data["show_tag_button"]:1;
		$show_action_buttons = isset( $data["action_buttons"] )?$data["action_buttons"]:'';
		
		if( $show_delete_checkbox ){
			++$col_span;
			// $row_heading .= '<th class="hidden-print no-print">&nbsp;</th>';
			$row_heading .= '<th>';
				$row_heading .= '<input type="checkbox" class="mail-checkbox mail-group-checkbox">';
			$row_heading .= '</th>';
		}
		
		if( $show_staff ){
			++$col_span;
			$row_heading .= '<th>By</th>';
		}

		// echo '<pre>';print_r( $values );
		$new_index = array( 'labels', 'title', 'message', 'assigned_to', 'type', 'action', 'mode', 'status', 'reference', 'reference_table', 'datetime' );
		$fields = __resort_array( $fields, $new_index );
		// print_r( $fields );
		
		foreach( $fields as $key => $val ){
			if( isset( $labels[ $val ] ) && isset( $labels[ $val ][ 'display_position' ] ) ){
				switch( $labels[ $val ][ 'display_position' ] ){
				case "display-in-table-row":
					
					++$col_span;
					
					$class = 'col-'.$key;
					$style = '';
					
					if( ! ( isset( $labels[ $val ][ 'default_appearance_in_table_fields' ] ) && $labels[ $val ][ 'default_appearance_in_table_fields' ] == 'show' ) ){
						$class .= ' hide-column hide-'.$key;
					}
					
					$lx = $labels[ $val ][ 'field_label' ];
					if( isset( $labels[ $val ][ 'display_field_label' ] ) && $labels[ $val ][ 'display_field_label' ] && $labels[ $val ][ 'display_field_label' ] != 'undefined' ){
						$lx = $labels[ $val ][ 'display_field_label' ];
					}
					
					if( isset( $labels[ $val ][ 'abbreviation' ] ) && $labels[ $val ][ 'abbreviation' ] && $labels[ $val ][ 'abbreviation' ] != 'undefined' ){
						$lx = $labels[ $val ][ 'abbreviation' ];
					}
						switch( $key ){
						case 'title':
						// case 'assigned_to':
						// case 'type':
						case 'message':
						case 'datetime':
						// case 'labels':
							switch( $key ){
							case 'title':
								$style = 'width: 60em;';
							break;
							}
								$row_heading .= '<th class="'.$class.'" style="'. $style .'">'. $lx .'</th>';
						break;
						}
				break;
				}
			}
		}
		
		foreach( $values as $sval ){
			$row_body .= '<tr id="'.$sval["id"].'" class="item-record">';
			
			if( $show_delete_checkbox ){
				$show_action_buttons1 = $show_delete_checkbox;
				
				if( is_array( $show_action_buttons ) ){
					$show_action_buttons1 = implode('', $show_action_buttons );
				}
				
				$row_body .= '<td class="inbox-small-cells">';
					$row_body .= str_replace( ':::id:::', $sval["id"], $show_action_buttons1 );
				$row_body .= '</td>';
			}
			
			if( $show_staff ){
				$row_body .= '<td><a class="custom-single-selected-record-button" override-selected-record="'. $sval["id"] .'" action="?module=&action='. $data["table"] .'&todo=edit_popup_form">';
				$row_body .= get_name_of_referenced_record( array( "id" => $sval["modified_by"], "table" => "users" ) );
				$row_body .= '</a></td>';
			}
			
			foreach( $fields as $key => $val2 ){
				$class = ' custom-single-selected-record-button col-'.$key;
				$attribute = '  override-selected-record="'.$sval["id"].'" action="?module=&action='. $data["table"] .'&todo=view_details&table='. $data["table"] .'" title="View Details" ';
				
				$val = isset( $sval[ $key ] )?$sval[ $key ]:'';
				
				if( isset( $fields[ $key ] ) && isset( $labels[ $fields[ $key ] ] ) && isset( $labels[ $fields[ $key ] ][ 'display_position' ] ) ){
					switch( $labels[ $fields[ $key ] ][ 'display_position' ] ){
					case "display-in-table-row":

						switch( $key ){
						case 'title':
						// case 'assigned_to':
						// case 'type':
						case 'message':
						case 'datetime':
						// case 'labels':
						
							$value = '';
							
							$showed = 1;
							if( ! ( isset( $labels[ $fields[ $key ] ][ 'default_appearance_in_table_fields' ] ) && $labels[ $fields[ $key ] ][ 'default_appearance_in_table_fields' ] == 'show' ) ){
								$class .= ' hide-column hide-'.$key;
								$showed = 0;
							}else{
								$class .= ' view-message';
							}
							
							if( isset( $e_options[ $key ]["options"]["table"] ) ){
								$ex = $e_options[ $key ]["options"];
								$ex["id"] = $val;
								$value = get_name_of_referenced_record( $ex );
							}
							
							if( ! $value ){
								$value = __get_value( $val, $key, array( "pagepointer" => $pagepointer ) );
							}
							
							if( isset( $e_options[ $key ]["strong"] ) ){
								$value = '<strong>' . $value . '</strong>';
							}

								// print_r( $key );exit;
							switch( $key ){
							case 'message':
								$class .= ' hidden-xs';
							break;
							case 'datetime':
								$class .= ' text-right';
							break;
							case 'labels':
								$class .= ' inbox-small-cells custom-single-selected-record-button ';
								$attribute .= ' override-selected-record="'. $sval["id"] .'" action="?module=&action=comments&todo=edit_label_from_comment"';

								if( $val ){
									$lbls = explode(',', $val);
									if( is_array( $lbls ) ){
										$value = '';
										foreach( $lbls as $label ){
											$a = get_record_details( array( "id" => $label, "table" => "labels" ) );
											$style = (isset( $a[ 'colour' ] ) ? 'color:'.$a[ 'colour' ] . ';' : '');
											// if( $style ){
											// 	$style .= 'color:' . ( color_inverse( $a[ 'colour' ] ) ? color_inverse( $a[ 'colour' ] ) : '') . ';';
											// }
											$name = ( isset( $a[ 'name' ] ) ? $a[ 'name' ] : '');
				
											$value .= '<i class="icon-star" style="'. $style .'" title="'. $name .'"></i>';

											// echo '<pre>';print_r( $a );
											// exit;
											// $value .= '<a href="#" class="custom-single-selected-record-button" override-selected-record="'. $label .'" action="?module=&action=labels&todo=edit" title="'. $name .'"><div class="labelz" style="'. $style .'"><span>'. $name .'</span></div></a>';
										}
									}
								}
							break;
							}
						
							$row_body .= '<td class="'.$class.'" '. $attribute .'>' . $value . '</td>';
						break;
						}
					break;
					}
				}
				
				
			}
			
			$row_body .= '</tr>';
		}
		
	}

		
		// if( $show_delete_checkbox ){
		// 	++$col_span;
		// 	$row_heading .= '<th colspan="3">';
		// 		$row_heading .= '<input type="checkbox" class="mail-checkbox mail-group-checkbox">';
		// 		$row_heading .= '<div class="btn-group">';
		// 			$row_heading .= '<a class="btn btn-sm blue" href="#" data-toggle="dropdown"> More';
		// 			$row_heading .= '<i class="icon-angle-down"></i>';
		// 			$row_heading .= '</a>';
		// 			$row_heading .= '<ul class="dropdown-menu">';
		// 			$row_heading .= '<li><a href="#"><i class="icon-pencil"></i> Mark as Read</a></li>';
		// 			$row_heading .= '<li><a href="#"><i class="icon-ban-circle"></i> Spam</a></li>';
		// 			$row_heading .= '<li class="divider"></li>';
		// 			$row_heading .= '<li><a href="#"><i class="icon-trash"></i> Delete</a></li>';
		// 			$row_heading .= '</ul>';
		// 		$row_heading .= '</div>';
		// 	$row_heading .= '</th>';
			
		// 	$row_heading .= '<th class="pagination-control" colspan="3">';
		// 		$row_heading .= '<span class="pagination-info">1-30 of 789</span>';
		// 		$row_heading .= '<a class="btn btn-sm blue"><i class="icon-angle-left"></i></a>';
		// 		$row_heading .= '<a class="btn btn-sm blue"><i class="icon-angle-right"></i></a>';
		// 	$row_heading .= '</th>';
		// }
?>
<style type="text/css">.hide-column{ display:none; }</style>
<div class="<?php echo ( isset( $table_class )?$table_class:'report-table-preview' ); ?>">
<table class="table table-striped table-advance table-hover">
	<thead>
		<?php if( $title ){ ?>
		<tr ><td colspan="<?php echo $col_span; ?>" id="e-report-title" ><h4><strong><?php echo $title; ?></strong></h4></td></tr>
		<?php } ?>
		<?php if( $subtitle ){ ?>
		<tr ><td colspan="<?php echo $col_span; ?>" ><p><?php echo $subtitle; ?></p></td></tr>
		<?php } ?>
	   <tr>
		  <?php echo $row_heading; ?>
	   </tr>
	</thead>
	<tbody>
	  <?php echo $row_body; ?>
	</tbody>
</table>
	
</div>
<?php
	if( isset( $GLOBALS["labels"] ) )unset( $GLOBALS["labels"] );
	if( isset( $GLOBALS["fields"] ) )unset( $GLOBALS["fields"] );
}
?>
</div>