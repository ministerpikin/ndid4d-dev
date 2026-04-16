<?php
	$table_class = 'table-responsive';
	$table_class2 = 'col-md-12 shopping-cart-table';
	$table_cell_style = 'border-right:1px solid #ddd;';
	
	switch( $mobile_framework ){
	case "framework7":
		$table_class = 'table-responsive data-table card';
		$data["view"] = 1;
		$table_cell_style .= ' width:50%;';
		$table_class2 = '';
	break;
	}
	
	$decode = 0;
	$dconstant = strtoupper( 'HYELLA_V3_RAW_URL_DECODE_'.$table );
	if( defined( $dconstant ) && $dconstant ){
		$decode = 1;
	}
	
	if( $print_preview ){
		$table_class = '';
	}
?>
<div class="row">
	<div class="<?php echo $table_class2; ?>">
		<div class="<?php echo $table_class; ?>">
			<table class="table table-striped table-hover table-bordered" >
			<tbody>
				<?php 
					$hc = '';
					
					if( is_array( $dass ) && ! empty( $dass ) ){
					
						if( isset( $dass["id"] ) ){
							echo '<tr><td style="'. $table_cell_style .'">Ref</td><td><i>#' . $dass["id"] .'</i></td></tr>';
						}
						
						if( isset( $dass["data"] ) && $dass["data"] ){
							$edx = json_decode( $dass["data"], true );
							
							if( isset( $edx["custom_fields"] ) && ! empty( $edx["custom_fields"] ) ){
								foreach( $edx["custom_fields"] as $edk => $edv ){
									$l = $edk;
									$vi = '';
									
									if( isset( $data["custom_fields"][ $edk ]['field_label'] ) ){
										$l = $data["custom_fields"][ $edk ]['field_label'];
										
										$GLOBALS["fields"][ $edk ] = $edk;
										$GLOBALS["labels"][ $edk ] = $data["custom_fields"][ $edk ];
										$vi =  __get_value( $edv , $edk, array( "pagepointer" => $pagepointer ) );
									}
									
									$hc .= '<tr><td style="'. $table_cell_style .'">'. ucwords( $l ) .'</td>';
									$hc .= '<td><strong>'. $vi .'</strong></td></tr>';
								}
							}
							unset( $dass["data"] );
						}
						
						foreach( $dass as $k => $v ){
							$value = $v;
							$break = 0;
							
							switch( $k ){
							case "image":	
							case "id":	
							case "created_by":
							case "creation_date":
							case "modification_date":
							case "modified_by":
							case "serial_num":
								$break = 1;
							break;
							}
							
							if( isset( $data[ "hidden_fields" ] ) && isset( $data[ "hidden_fields" ][ $k ] ) ){
								$break = 1;
							}
							
							if( ! $value ){
								$break = 1;
							}
							
							if( $break )continue;
							
							$lbll = [];
							$l = $k;
							$t = "";
							$t1 = "";
							$dp = "";
							if( isset( $data[ "fields" ][ $k ] ) && isset( $data[ "labels" ][ $data[ "fields" ][ $k ] ] ) ){
								$lbll = $data[ "labels" ][ $data[ "fields" ][ $k ] ];
								$l = $lbll['field_label'];
								
								if( isset( $lbll['display_field_label'] ) && $lbll['display_field_label'] && $lbll['display_field_label'] != 'undefined' ){
									$l = $lbll['display_field_label'];
								}
								
								$t = $lbll['form_field'];
								$dp = $lbll['display_position'];
							}
							
							if( ! ( isset( $lbll[ 'obfuscate' ] ) && $lbll[ 'obfuscate' ] ) && $dp != 'display-in-table-row' )continue;
							$addParams = '';
							$addClass = '';
							if( isset( $lbll[ 'obfuscate' ] ) && $lbll[ 'obfuscate' ] ){
								$addParams = ' load="setTimeout(function(){ alert(9200); } , 1000)" ';
								$addClass = ' class="obfuscateMe" ';
							}
							?>
						   <tr>
							<td style="<?php echo $table_cell_style; ?>" ><?php
								$value = __get_value( $value , $k, array( "values" => $dass, "pagepointer" => $pagepointer ) );
								if( $decode ){
									$value = str_replace( '%2520', ' ', $value );
								}
								echo $l; 
							?></td>
							<td id="line-item-<?php echo $k; ?>" <?php echo $addClass; ?> ><strong><?php echo $value; ?></strong></td>
						   </tr>
							<?php
						}
						
					}
					
					if( $hc ){
						echo '<tr><td colspan="2">&nbsp;</td></tr>';
						echo $hc;
					}
				?>
			</tbody>
			</table>
		</div>
	</div>
</div>
<?php 
/*
if( ! ( isset( $data["view"] ) && $data["view"] ) ){ ?>
	<button class="btn dark custom-single-selected-record-button" action="?module=&action=<?php echo isset( $data["table"] )?$data["table"]:"customer_call_log"; ?>&todo=new_popup_form_in_popup<?php echo $params; ?>" override-selected-record="<?php echo isset( $dass[ $reference ] )?$dass[ $reference ]:'-'; ?>">Add Another Item ?</button>
<?php } */ 
?>

</div>