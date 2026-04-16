<?php
$new_data = isset( $data["form_data"] )?$data["form_data"]:array();

$form_id = isset( $data["form_id"] )?$data["form_id"]:'';
$table_id = isset( $data["table_id"] )?$data["table_id"]:'';

$preview = isset( $data["preview"] )?$data["preview"]:0;

$table = isset( $data["table"] )?$data["table"]:'';
$pdata = isset( $data["params"] )?$data["params"]:array();
$saved_data = isset( $data["saved_data"] )?$data["saved_data"]:array();
$form_settings = isset( $data["form_settings"] )?$data["form_settings"]:array();

$params = '';
if( isset( $data[ "html_replacement_selector" ] ) && $data[ "html_replacement_selector" ] ){
	$params = '&html_replacement_selector=' . $data[ "html_replacement_selector" ];
}

//echo '<pre>'; print_r( $pdata ); echo '</pre>';
//echo '<pre>'; print_r( $data ); echo '</pre>';
$faction = isset( $pdata["form_action"] )?$pdata["form_action"]:'?action='. $table .'&todo=save_new_popup';

$more_data = isset( $pdata["more_data"] )?$pdata["more_data"]:array();
$exclude_form_tag = isset( $pdata["exclude_form_tag"] )?$pdata["exclude_form_tag"]:0;


?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
$html = '';
$html1 = '';

if( $exclude_form_tag ){
	$data[ 'skip_headers' ] = 1;
}
if( ! $exclude_form_tag ){
	$html1 .= '<form class="activate-ajax list" method="POST" action="' . $faction . $params .'" id="'.$form_id.'">';
}

$button_caption = isset( $form_settings["button_caption"] )?$form_settings["button_caption"]:'Save';
$form_values = isset( $form_settings["form_values"] )?$form_settings["form_values"]:array();

$mobile_class1 = '';
$mobile_class2 = '';
$mobile_class3 = '';
$mobile_class4 = ' btn blue ';
$mobile_class5 = '';

$mobile = 0;

if( isset( $_POST["mobile_framework"] ) && $_POST["mobile_framework"] ){
	$html1 .= '<input type="hidden" name="mobile_framework" value="'.$_POST["mobile_framework"].'" />';
	$mobile_class1 = 'item-input-wrap ';	//input-dropdown-wrap
	$mobile_class2 = 'item-title label';
	$mobile_class3 = 'item-inner';
	$mobile_class5 = 'item-contentx item-input';
	$mobile_class4 = 'button button-fill';// color-orange
	
	$mobile = 1;
}

if( ! ( isset( $data[ 'skip_headers' ] ) && $data[ 'skip_headers' ] ) ){
	$html1 .= get_form_headers( array( 
		'table_id' => $table_id,
		'table' => $table,
		'id' =>  isset( $saved_data["id"] )?$saved_data["id"]:'',
		'uid' =>  $user_info["user_id"],
		'user_priv' =>  $user_info["user_privilege"],
		'nw_more_data' =>  $more_data,
	) );
}
	
if( is_array( $new_data ) && ! empty( $new_data ) ){
	
	$accordion2 = 0;
	$accordion = 0;
	$serialA = 0;
	$firstclass = 'accordion-item-opened';

	$sort_key = 'position';

	uasort($new_data, function($a, $b){
	    if( $a[ 'position' ] == $b[ 'position' ]) {
	        return 0;
	    }
	   		return ($a[ 'position' ] < $b[ 'position' ]) ? -1 : 1;
	});

	$fields_only = '';
	$field_keys = array();

	foreach( $new_data as $key => $value ){
		
		$accordion2 = 0;
		
		$h2 = '<div class="row">';
			
			$count = 12;
			
			if( isset( $value[ 'columns' ] ) && is_array( $value[ 'columns' ] ) && ! empty( $value[ 'columns' ] ) ){
				$accordion2 = 1;
				
				$count = 12 / count( $value[ 'columns' ] );
			}else{
				$value[ 'columns' ][ 1 ] = array(
					'fields' => array( $key => $value ),
				);
			}
			
			foreach( $value[ 'columns' ] as $k => $v ){
				$h2 .= '<div class="col-md-'.$count.'">';
					
					if( isset( $v[ 'fields' ] ) && $v[ 'fields' ] ){
						foreach( $v[ 'fields' ] as $f_key => $f_val ){
							
							if( $mobile ){
								if( isset( $f_val["required_field"] ) ){
									//this allows for form on mobile device to be saved in draft mode, without filling all required fields
									unset( $f_val["required_field"] );
									
								}
							}
							
							// $f_val["display_position"] = 'display-in-table-row';
							// $f_val["form_field"] = $f_val["type"];
							
							switch( $f_val["form_field"] ){
							case "date":
								$f_val["form_field"] = 'date-5';
							break;
							case "calculated":
								//remove this later
								
								if( isset( $f_val["calculations"]["variables"] ) ){
									$f_val["calculations"]["variables"] = array( array( $f_key ) );
								}
								//print_r( $f_val["calculations"] ); exit;
								
								//remove this later
							break;
							}
							
							$f_val["field_label"] = ( isset( $f_val["display_field_label"] ) && $f_val["display_field_label"] )?$f_val["display_field_label"]:( isset( $f_val["field_label"] )?$f_val["field_label"]:'' );
							
							$ips = array();
							$form_value = array();
							// echo '<pre>'; print_r( $f_val[ 'field_key' ] ); echo '</pre>';

							if( isset( $f_val[ 'field_key' ] ) && isset( $form_values[ $f_val[ 'field_key' ] ] ) ){
								$form_value[ $f_key ] = $form_values[ $f_val[ 'field_key' ] ];
							}
							
							if( isset( $f_val[ 'field_key' ] ) && isset( $form_settings["field_settings"][ $f_val[ 'field_key' ] ]["hidden_records"] ) ){
								$form_settings["field_settings"][ $f_key ]["hidden_records"] = 1;
							}
							
							$ips[ $f_key ] = array(
								"value" => ( isset( $saved_data[ $f_key ] )?$saved_data[ $f_key ]:'' ),
							);
							$f_val["skip_container_class"] = 1;
							
							
							if( isset( $f_val['field_identifier'] ) ){
								if( ! isset( $f_val[ 'class' ] ) ){
									$f_val[ 'class' ] = '';
								}
								if( ! isset( $f_val[ 'attributes' ] ) ){
									$f_val[ 'attributes' ] = '';
								}
								
								$f_val[ 'class' ] .= ' fi-' . $f_val['field_identifier'] . ' ';
								$f_val[ 'attributes' ] .= ' field-identifier="' . $f_val['field_identifier'] . '" ';
							}
							
							$gbal = array(
								'labels' => array( $f_key => $f_val ),
								'fields' => array( $f_key => $f_key ),
							);
							
							// echo '<pre>';print_r( $gbal );echo '</pre>';
							$d1 = __get_value( '', '', array( 'form_fields' => $ips, 'globals' => $gbal, 'mobile_class3' => $mobile_class3, 'mobile_class2' => $mobile_class2, 'mobile_class1' => $mobile_class1, 'mobile_class5' => $mobile_class5, 'form_values' => $form_value ) );
							
							//$h2 .= '<div class="item-content item-input">';
							

							switch( $f_val[ 'id' ] ){
							case 'ds21121430723':
								// print_r( $d1 );exit;
							break;
							}
							$style = '';
							if( isset( $form_settings["field_settings"][ $f_key ]["hidden_records"] ) ){
								$style = ' display:none; ';
							}
							
							$h2 .= '<div style="'.$style.'" class="form-con fc-'. $f_key .'" id="con-'. $f_key .'">';
							if( isset( $d1[ $f_key ][ 'label' ]) && $d1[ $f_key ][ 'label' ] ){
								$h2 .= ' <label class="'.$mobile_class2.'">'. $d1[ $f_key ][ 'label' ] .'</label>';
							}
							
							if( isset( $d1[ $f_key ][ 'field' ]) && $d1[ $f_key ][ 'field' ] ){
								$h2 .= $d1[ $f_key ][ 'field' ];
							}
							//$h2 .= '<br /></div>';
							
							$h2 .= '</div>';
						}
					}
					
				$h2 .= '</div>';
			}
			
		$h2 .= '</div>';
		
		if( $mobile && $accordion2 ){
			$accordion = 1;
			
			$h2 = '<li class="accordion-item '.$firstclass.'"><a href="#" class="item-content item-link">
				<div class="item-inner">
				  <div class="item-title"><small><strong>PART '. ++$serialA .'</strong></small></div>
				</div></a>
			  <div class="accordion-item-content">
				<div class="block">'. $h2 . '</div></div></li>';
			
			$firstclass = '';
		}
		
		$fields_only .= $h2;
		$html .= $h2;
	}
	
	if( isset( $data[ 'fields_only' ] ) && $data[ 'fields_only' ] ){
		return $fields_only;
	}
	
	if( $mobile && $accordion ){
		$html = '<div class="list accordion-list"><ul>' . $html . '</ul></div>';
	}
	
}

$form_title = '';
if( isset( $data["form_options"]["form_title"] ) && $data["form_options"]["form_title"] ){
	$form_title = '<h4>'.$data["form_options"]["form_title"] . '</h4>';
}

$html = $form_title . $html1 . $html;

if( ! $exclude_form_tag ){
$html .= '<input type="submit" value="'.$button_caption.'" class="'.$mobile_class4.'" />';

$html .= '</form>';
}

if( isset( $data["form_script"] ) && $data["form_script"] ){
	$fh = '';
	if( isset( $data["form_script_header"] ) && $data["form_script_header"] ){
		$fh = $data["form_script_header"];
	}
	// echo '<pre>'; print_r( rawurldecode( $fh ) ); echo '</pre>';
	//$preview = 1;
	if( $preview ){
		$html .= '<pre>var $formid = "'.$form_id.'"; ' . rawurldecode( $fh . $data["form_script"] ) . '</pre>';
	}else{
		$html .= '<script style1="display:none;" class="form-script">var $formid = "'.$form_id.'"; ' . rawurldecode( $fh . $data["form_script"] ) . ' </script>';
	}
}

echo $html;

?>
</div>