<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$in_progress = 1;
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	$h = '';
	$e = isset( $data["event"] )?$data["event"]:array();
	$action = isset( $data["action"] )?$data["action"]:'all_reports';
	$todo = isset( $data["todo"] )?$data["todo"]:'display_report';
	
	$params = isset( $data["params"] )?$data["params"]:'&html_replacement_selector=display-report-window';
	
	$container = isset( $data["container"] )?$data["container"]:'';
	$chart_filter = isset( $data["chart_filter"] )?$data["chart_filter"]:array();
	
	if( isset( $e["report_type"] ) && $e["report_type"] ){
		$custom_options = isset( $e["custom_options"] )?$e["custom_options"]:array();
		$col_size = isset( $e["col_size"] )?$e["col_size"]:'';
		if( ! $col_size ){
			$col_size = '6';
		}
		
		$form_action = '?action=all_reports&todo=display_report';
		if( isset( $e["settings"]["form_action"] ) && $e["settings"]["form_action"] ){
			$form_action = $e["settings"]["form_action"];
		}
	// echo '<pre>'; print_r( $e ); echo '</pre>';
?>
	<h4><strong><?php echo $e["report_title"]; ?></strong></h4>
	<form id="display-report-type-form" class="activate-ajax" action="?action=<?php echo $action ?>&todo=<?php echo $todo.$params; ?>">
		<input type="hidden" name="report_title" value="<?php echo $e["report_title"]; ?>" />
		<input type="hidden" name="report_type" value="<?php echo $e["report_type"]; ?>" />
		<input type="hidden" name="report" value="<?php echo $e["report"]; ?>" />
		
		<?php 
			$hidden_fields = array();
			if(  isset( $e["settings"]["hidden_fields"] ) && is_array( $e["settings"]["hidden_fields"] ) && ! empty( $e["settings"]["hidden_fields"] ) ){ 
				$hidden_fields = $e["settings"]["hidden_fields"];
			}
			
			if(  isset( $custom_options["hidden_fields"] ) && is_array( $custom_options["hidden_fields"] ) && ! empty( $custom_options["hidden_fields"] ) ){ 
				$hidden_fields = array_merge( $hidden_fields, $custom_options["hidden_fields"] );
			}
			
			if(  ! empty( $hidden_fields ) ){ 
				$hd = '';
				foreach( $hidden_fields as $ek => $ev ){
					$hd .= '<input type="hidden" name="'.$ek.'" value="'. ( isset( $ev["value"] )?$ev["value"]:'' ) .'" />';
				}
				
				echo $hd;
			} 
		?>
		
		<?php $key = 'hide_dates'; if( ! ( isset( $e["settings"]["fields"][ $key ] ) && $e["settings"]["fields"][ $key ] ) ){ 
			$sde = "Y-m-t"; 
			$sd = 'Y-m-01'; 
			if( isset( $e["settings"]["start_date"] ) && $e["settings"]["start_date"] ){
				$sd = $e["settings"]["start_date"];
			}
			if( isset( $e["settings"]["end_date"] ) && $e["settings"]["end_date"] ){
				$sde = $e["settings"]["end_date"];
			}
			
			$stt = date("U");
			$ett = $stt;
			if( isset( $e["settings"]["start_timestamp"] ) && $e["settings"]["start_timestamp"] ){
				$stt = $e["settings"]["start_timestamp"];
			}
			
			if(  isset( $custom_options["settings"]["start_timestamp"] ) && $custom_options["settings"]["start_timestamp"] ){ 
				$stt = $custom_options["settings"]["start_timestamp"];
			}
			
			if(  isset( $e["settings"]["start_timestamp_interval"] ) && $e["settings"]["start_timestamp_interval"] ){ 
				$stt += $e["settings"]["start_timestamp_interval"];
			}
			
			if( isset( $e["settings"]["end_timestamp"] ) && $e["settings"]["end_timestamp"] ){
				$ett = $e["settings"]["end_timestamp"];
			}
			if(  isset( $custom_options["settings"]["end_timestamp"] ) && $custom_options["settings"]["end_timestamp"] ){ 
				$ett = $custom_options["settings"]["end_timestamp"];
			}
			if(  isset( $e["settings"]["end_timestamp_interval"] ) && $e["settings"]["end_timestamp_interval"] ){ 
				$ett += $e["settings"]["end_timestamp_interval"];
			}
			
			$show_sd = 1;
			if(  isset( $e["settings"]["hide_start_date"] ) && $e["settings"]["hide_start_date"] ){ 
				$show_sd = 0;
			}
			
			$show_ed = 1;
			if(  isset( $e["settings"]["hide_end_date"] ) && $e["settings"]["hide_end_date"] ){ 
				$show_ed = 0;
			}
			
			$date_type = 'date';
			if(  isset( $hidden_fields["date_type"]['value'] ) && $hidden_fields["date_type"]['value'] ){ 
				$date_type = $hidden_fields["date_type"]['value'];
			}
		?>
		<div class="row">
			<?php if( $show_sd ){ ?>
			<div class="col-md-<?php echo $col_size; ?>">
				<label>Start Date <sup>*</sup></label>
				<input class="form-control" type="<?php echo $date_type; ?>" value="<?php echo date( $sd, doubleval( $stt ) ); ?>" required name="start_date" />
			</div>
			<?php } ?>
			<?php if( $show_ed ){ ?>
			<div class="col-md-<?php echo $col_size; ?>">
				<label>End Date <sup>*</sup></label>
				<input class="form-control" type="<?php echo $date_type; ?>" value="<?php echo date( $sde, doubleval( $ett ) ); ?>" required name="end_date" />
			</div>
			<?php } ?>
		</div>
		<br />
		<?php } ?>
		
		<?php 
			//echo $d1["field"];
			
			if( ( isset( $e["settings"]["fields"] ) && ! empty( $e["settings"]["fields"] ) ) ){
				$locations = array();
				
				$locations["store"] = array(
					'title' => 'Store',
					'class' => ' form-control select2 allow-clear ',
					'value' => '',
					'name' => 'store',
					'action' => "?action=stores&todo=get_select2",
					'data-params' => '',
					'minlength' => '0',
				);
				
				$locations["category"] = array(
					'title' => 'Category',
					'class' => ' form-control select2 allow-clearX ',
					'placeholder' => 'Category',
					'value' => '',
					'name' => 'category',
					'action' => "?action=category&todo=get_select2",
					'data-params' => '',
					'minlength' => '0',
				);
				$locations["consumable"] = array(
					'title' => 'Consumable',
					'class' => ' form-control select2 allow-clearX ',
					'placeholder' => '',
					'value' => '',
					'name' => 'selected_item',
					'action' => "?action=items&todo=get_items_select2&not=1&type=service,service_lab,service_procedure,service_open,service_imenu,composite,composite_production",
					'data-params' => '',
					'minlength' => '0',
					'style' => ' font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold; ',
				);

				$count = 0;
				$h .= '<div class="row">';
				
				foreach( $e["settings"]["fields"] as $lk => $lv ){
					if( isset( $locations[ $lk ] ) || isset( $lv["custom"] ) ){
						
						if( $count && ! ( $count % 2 ) ){ 
							$h .= '</div><br /><div class="row">';
						}
						$h .= '<div class="col-md-6" >';
							
						if( isset( $locations[ $lk ] ) ){
							$lkv = $locations[ $lk ];
						
							$req = '<sup>*</sup>';
							$req2 = 'required';
							
							if( isset( $lv["optional"] ) && $lv["optional"] ){
								$req = '';
								$req2 = '';
							}
							
							$k1 = "tags";
							if( isset( $lv[ $k1 ] ) && $lv[ $k1 ] ){
								$lkv[ $k1 ] = $lv[ $k1 ];
							}
							
							$k1 = "action";
							if( isset( $lv[$k1] ) && $lv[$k1] ){
								$lkv[$k1] = $lv[$k1];
							}
							
							$k1 = "params";
							if( isset( $lv[ $k1 ] ) && $lv[ $k1 ] ){
								$lkv["action"] .= $lv[ $k1 ];
							}
							
							$h .= '<label>'. ( isset( $lkv["title"] )?$lkv["title"]:'' ) .' '.$req.'</label><input class="form-control '. ( isset( $lkv["class"] )?$lkv["class"]:'' ) .'" type="text" name="'. ( isset( $lkv["name"] )?$lkv["name"]:'' ).'" id="filter-'. ( isset( $lkv["name"] )?$lkv["name"]:'' ).'" value="'. ( isset( $lkv["value"] )?$lkv["value"]:'' ) .'" label="'. ( isset( $lkv["label"] )?$lkv["label"]:'' ) .'" action="'. ( isset( $lkv["action"] )?$lkv["action"]:'' ) .'" data-params="'. ( isset( $lkv["data-params"] )?$lkv["data-params"]:'' ) .'" minlength="'. $lkv["minlength"] .'" tags="'. ( isset( $lkv["tags"] )?$lkv["tags"]:'' ) .'" '.$req2.' placeholder="'. ( isset( $lkv["placeholder"] )?$lkv["placeholder"]:'' ) .'" style="'. ( isset( $lkv["style"] )?$lkv["style"]:'' ) .'" />';
							
						}else if( isset( $lv["custom"] ) && $lv["custom"] ){
							
							$fv2[ $lk ] = array();
							$lv["skip_container_class"] = 1;
							
							$gbal = array(
								'labels' => array( $lk => $lv ),
								'fields' => array( $lk => $lk ),
							);
							
							$d1 = __get_value( '', '', array( 'form_fields' => $fv2, 'globals' => $gbal ) );
							
							if( isset( $d1[ $lk ][ 'label' ] ) ){
								$h .= ' <label>'. $d1[ $lk ][ 'label' ] .'</label>';
							}
							
							if( isset( $d1[ $lk ][ 'field' ] ) ){
								$h .= str_replace( "form-group control-group input-row", "", $d1[ $lk ][ 'field' ] );
							}
							
						}
							
							
						$h .= '</div>';
						
						++$count;
					}
				}
				
				$h .= '</div><br />';
			}
			
			echo $h;

			if( ! empty( $chart_filter ) ){ ?>
				<div class="row">
					<div class="col-md-6">
						<label>Chart Type </label>
						<select name="chart_type" class="form-control">
						<?php foreach( $chart_filter as $key => $value ){ ?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php } ?>
						</select>
					</div>
				</div>
			<?php
			}
		?>
		
		<hr />
		<div class="row">
			<div class="col-md-12">
				<input class="btn blue" value="Generate Report &rarr;" type="submit" />
			</div>
		</div>
		<br />
	</form>

	<?php } ?>
</div>