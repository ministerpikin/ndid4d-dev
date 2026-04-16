<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
if( isset( $data["items"] ) && is_array( $data["items"] ) && !empty( $data["items"] ) ){
	// print_r( $data[ 'action_buttons' ] );exit;
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
	$data["action_buttons"] .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=view_details" title="View Details">View Details</a>';

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
		$show_action_buttons = isset( $data["action_buttons"] )?$data["action_buttons"]:'';
		
		if( $show_delete_checkbox ){
			++$col_span;
			$row_heading .= '<th class="hidden-print no-print">&nbsp;</th>';
		}
		
		foreach( $fields as $key => $val ){
			if( isset( $labels[ $val ] ) && isset( $labels[ $val ][ 'display_position' ] ) ){
				switch( $labels[ $val ][ 'display_position' ] ){
				case "display-in-table-row":
					
					++$col_span;
					
					$class = 'col-'.$key;
					
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
					
					$row_heading .= '<th class="'.$class.'">'. $lx .'</th>';
				break;
				}
			}
		}
		
		if( $show_staff ){
			++$col_span;
			$row_heading .= '<th>By</th>';
		}
		
		if( $show_action_buttons ){
			++$col_span;
			$row_heading .= '<th class="hidden-print no-print" style="'.get_width_for_button_columns( ( isset( $data["table"] )?$data["table"]:'' ) ).'">&nbsp;</th>';
		}
		
		foreach( $values as $sval ){
			$row_body .= '<tr id="'.$sval["id"].'" class="item-record">';
			
			if( $show_delete_checkbox ){
				$show_action_buttons1 = $show_delete_checkbox;
				
				if( is_array( $show_action_buttons ) ){
					$show_action_buttons1 = implode('', $show_action_buttons );
				}
				
				$row_body .= '<td class="hidden-print no-print" align="right">';
					$row_body .= str_replace( ':::id:::', $sval["id"], $show_action_buttons1 );
				$row_body .= '</td>';
			}
			
			$first_col = 1;
			
			foreach( $fields as $key => $val2 ){
				
				$val = isset( $sval[ $key ] )?$sval[ $key ]:'';
				
				if( isset( $fields[ $key ] ) && isset( $labels[ $fields[ $key ] ] ) && isset( $labels[ $fields[ $key ] ][ 'display_position' ] ) ){
					switch( $labels[ $fields[ $key ] ][ 'display_position' ] ){
					case "display-in-table-row":
						
						$class = 'col-'.$key;
						
						$showed = 1;
						if( ! ( isset( $labels[ $fields[ $key ] ][ 'default_appearance_in_table_fields' ] ) && $labels[ $fields[ $key ] ][ 'default_appearance_in_table_fields' ] == 'show' ) ){
							$class .= ' hide-column hide-'.$key;
							$showed = 0;
						}
						
						$value = '';
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
						
						if( $showed && $show_edit_link ){
							if( $first_col ){
								$value = '<a class="custom-single-selected-record-button" override-selected-record="'. $sval["id"] .'" action="?module=&action='. $data["table"] .'&todo=edit_popup_form">'. $value .'</a>';
								
								$first_col = 0;
							}
						}
						
						$row_body .= '<td class="'.$class.'">' . $value . '</td>';
					break;
					}
				}
				
				
			}
			
			if( $show_staff ){
				$row_body .= '<td>';
				$row_body .= get_name_of_referenced_record( array( "id" => $sval["modified_by"], "table" => "users" ) );
				$row_body .= '</td>';
			}
			
			if( $show_action_buttons ){
				$show_action_buttons1 = $show_action_buttons;
				
				if( is_array( $show_action_buttons ) ){
					$show_action_buttons1 = implode('', $show_action_buttons );
				}
				
				$row_body .= '<td class="hidden-print no-print" align="right">';
					$row_body .= str_replace( ':::id:::', $sval["id"], $show_action_buttons1 );

					switch( $sval[ 'status' ] ){
					case get_open_ticket_status();
					case get_re_opened_ticket_status();
					case get_assigned_ticket_status();
					case get_responded_ticket_status();

						$row_body .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record="'. $sval["id"] .'" action="?module=&action='. $data["table"] .'&todo=close" title="Close Ticket">Closed</a>';
						
						switch( $sval[ 'status' ] ){
						case get_assigned_ticket_status();
							$row_body .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record=":::id:::" action="?module=&action='. $data["table"] .'&todo=response" title="View Details">Response</a>';
						break;
						default:
							$row_body .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record="'. $sval["id"] .'" action="?module=&action='. $data["table"] .'&todo=assign" title="Assign Ticket">Assign</a>';
						break;
						}
					break;
					case get_closed_ticket_status();
						$row_body .= '<a href="#" class="btn btn-default btn-block btn-xs custom-single-selected-record-button" override-selected-record="'. $sval["id"] .'" action="?module=&action='. $data["table"] .'&todo=re_open" title="Re-Open Ticket">Re-open</a>';
					break;
					}

				$row_body .= '</td>';
			}
			
			$row_body .= '</tr>';
		}
		
	}
?>
<style type="text/css">.hide-column{ display:none; }</style>
<div class="<?php echo ( isset( $table_class )?$table_class:'report-table-preview' ); ?>">
<table class="table table-striped table-bordered table-hover" cellspacing="0" style="width:100%;">
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