<?php 
	//if( isset( $data["report_data"] ) )print_r( $data["report_data"] );
	
	$row_heading = '';
	$row_body = '';

	if( isset( $data[ "report_data" ] ) && is_array( $data[ "report_data" ] ) && isset( $data[ 'table_labels' ] ) && is_array( $data[ 'table_labels' ] ) && isset( $data[ 'table_fields' ] ) && is_array( $data[ 'table_fields' ] ) ){
		
		$values = $data[ 'report_data' ];
		$fields = $data[ 'table_fields' ];
		$labels = $data[ 'table_labels' ];
		$row_heading = '';
		
		$user_id = ( isset( $user_info["user_id"] )?$user_info["user_id"]:'' );
		
		$row_heading .= '<th>L/N</th>';
		
		foreach( $fields as $key => $val ){
			if( isset( $labels[ $val ] ) && isset( $labels[ $val ][ 'display_position' ] ) ){
				switch( $labels[ $val ][ 'display_position' ] ){
				case "display-in-table-row":
					
					$class = $key;
					
					if( ! ( isset( $labels[ $val ][ 'default_appearance_in_table_fields' ] ) && $labels[ $val ][ 'default_appearance_in_table_fields' ] == 'show' ) ){
						$class .= ' hide-'.$key;
					}
					
					$row_heading .= '<th class="'.$class.'">'. $labels[ $val ][ 'field_label' ] .'</th>';
				break;
				}
			}
		}
		
		$serial = 0;
		foreach( $values as $sval ){
			$row_body .= '<tr class="item-record" >';
			
			++$serial;
			$fserial = ( isset( $sval[ '_flag_num_' ] )?$sval[ '_flag_num_' ]:0 ) + 1;
			$row_body .= '<td>'.$fserial.'</td>';
			
			foreach( $fields as $key => $val ){
				if( isset( $labels[ $val ] ) && isset( $labels[ $val ][ 'display_position' ] ) ){
					switch( $labels[ $val ][ 'display_position' ] ){
					case "display-in-table-row":
						$dval = isset( $sval[ $key ] )?$sval[ $key ]:'';
						
						$flagx = isset( $sval[ $key.'_flag_' ] )?$sval[ $key.'_flag_' ]:0;
						$class = $key;
						
						if( ! ( isset( $labels[ $val ][ 'default_appearance_in_table_fields' ] ) && $labels[ $val ][ 'default_appearance_in_table_fields' ] == 'show' ) ){
							$class .= ' hide-'.$key;
						}
						
						$value = __get_value( $dval, $key, array( "globals" => array( "fields" => array( $key => $val ), "labels" => array( $val => $labels[ $val ] ) ) ) );
						
						$style = "";
						
						if( $flagx ){
							$style = 'style="color:red;"';
							if( ! $value )$value = 'required field**';
						}
						$row_body .= '<td class="'.$class.'" '.$style.'>' . $value . '</td>';
					break;
					}
				}
			}
			
			$row_body .= '</tr>';
			
		}
		
	}
?>
<div class=" shopping-cart-table" >
	<div class="table-responsive" style="overflow-x:auto;">
		<table class="table table-striped table-hover bordered">
		<thead>
		   <tr>
			  <?php echo $row_heading; ?>
		   </tr>
		</thead>
		<tbody>
		  <?php echo $row_body; ?>
		</tbody>
		</table>
		
	</div>
</div>