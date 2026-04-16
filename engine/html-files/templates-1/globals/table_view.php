<?php 
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
		
		$show_staff = isset( $data["show_staff"] )?$data["show_staff"]:1;
		$show_delete_checkbox = isset( $data["delete_checkbox"] )?$data["delete_checkbox"]:'';
		$show_edit_link = isset( $data["edit_link"] )?$data["edit_link"]:'';
		$show_serial_num = isset( $data["serial_num"] )?$data["serial_num"]:'';
		
		$show_action_buttons = isset( $data["action_buttons"] )?$data["action_buttons"]:'';
		
		if( $show_delete_checkbox ){
			++$col_span;
			$row_heading .= '<th class="hidden-print no-print">&nbsp;</th>';
		}
		
		if( $show_serial_num ){
			++$col_span;
			$row_heading .= '<th>#</th>';
		}
		
		foreach( $fields as $key => $val ){
			if( isset( $labels[ $val ] ) && isset( $labels[ $val ][ 'display_position' ] ) ){
				switch( $labels[ $val ][ 'display_position' ] ){
				case "display-in-table-row":
					
					$class = 'col-'.$key;
					$show1 = 1;
					
					if( ! ( isset( $labels[ $val ][ 'default_appearance_in_table_fields' ] ) && $labels[ $val ][ 'default_appearance_in_table_fields' ] == 'show' ) ){
						$class .= ' hide-column hide-'.$key;
						$show1 = 0;
					}
					
					if( $show1 ){
						
						++$col_span;
					
						$lx = $labels[ $val ][ 'field_label' ];
						if( isset( $labels[ $val ][ 'display_field_label' ] ) && $labels[ $val ][ 'display_field_label' ] && $labels[ $val ][ 'display_field_label' ] != 'undefined' ){
							$lx = $labels[ $val ][ 'display_field_label' ];
						}
						
						$row_heading .= '<th class="'.$class.'">'. $lx .'</th>';
					}
				break;
				}
			}
		}
		
		if( $show_staff ){
			++$col_span;
			$row_heading .= '<th >By</th>';
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
			
			if( $show_serial_num ){
				$row_body .= '<td >';
					$row_body .= $sval["serial_num"];
				$row_body .= '</td>';
			}
			
			$first_col = 1;
			
			foreach( $fields as $key => $val2 ){
				
				$val = isset( $sval[ $key ] )?$sval[ $key ]:'';
				
				
				if( isset( $fields[ $key ] ) && isset( $labels[ $fields[ $key ] ] ) && isset( $labels[ $fields[ $key ] ][ 'display_position' ] ) ){
					switch( $labels[ $fields[ $key ] ][ 'display_position' ] ){
					case "display-in-table-row":
						
						$show1 = 1;
						$class = 'col-'.$key;
						
						if( ! ( isset( $labels[ $fields[ $key ] ][ 'default_appearance_in_table_fields' ] ) && $labels[ $fields[ $key ] ][ 'default_appearance_in_table_fields' ] == 'show' ) ){
							$class .= ' hide-column hide-'.$key;
							$show1 = 0;
						}
						
						if( $show1 ){
							$value = '';
							if( isset( $e_options[ $key ]["options"]["table"] ) ){
								$ex = $e_options[ $key ]["options"];
								$ex["id"] = $val;
								$value = get_name_of_referenced_record( $ex );
							}
							
							if( ! $value ){
								$value = __get_value( $val, $key, array( "pagepointer" => $pagepointer ) );
							}
							
							if( isset( $e_options[ $key ]["append_options"] ) ){
								if( isset( $e_options[ $key ]["append_options"]["field"] ) && isset( $e_options[ $key ]["append_options"]["table"] ) && isset( $sval[ $e_options[ $key ]["append_options"]["field"] ] ) ){
									$ex = $e_options[ $key ]["append_options"];
									$ex["id"] = $sval[ $e_options[ $key ]["append_options"]["field"] ];
									$value .= '<br />' . get_name_of_referenced_record( $ex );
								}else{
									
								}
							}
							
							if( isset( $e_options[ $key ]["strong"] ) ){
								$value = '<strong>' . $value . '</strong>';
							}
							
							if( $show_edit_link ){
								if( $first_col ){	
									$act2 = 'action='. $data[ 'table' ] .'&todo=';

									if( isset( $plugin ) && $plugin ){
										$act2 = 'action='.$plugin .'&todo=execute&nwp_action='. $data[ 'table' ] .'&nwp_todo=';
									}

									$value = '<a class="custom-single-selected-record-button" override-selected-record="'. $sval["id"] .'" action="?module=&'. $act2 .'edit_popup_form&data_source=table2" style="text-decoration:underline; color:#195dbd; cursor:pointer;" title="Click to Edit">'. $value .'</a>';
									
									$first_col = 0;
								}
							}
							
							$row_body .= '<td class="'.$class.'">' . $value . '</td>';
						}
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