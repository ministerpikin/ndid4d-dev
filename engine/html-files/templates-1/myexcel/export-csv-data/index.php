<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>

<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<div class="row">
	<div class="col-md-12">
		<?php 
			if( isset( $data["table"] ) && $data["table"] ){
				$action = isset( $data[ 'action' ] ) && $data[ 'action' ] ? $data[ 'action' ] : 'myexcel';
				$todo = isset( $data[ 'todo' ] ) && $data[ 'todo' ] ? $data[ 'todo' ] : 'save_generate_csv3';
				$params = isset( $data[ 'params' ] ) && $data[ 'params' ] ? $data[ 'params' ] : '';
				$change_form_action = isset( $data[ 'change_form_action' ] ) ? $data[ 'change_form_action' ] : '';
				$plugin = isset( $data["event"]["data"]["plugin"] ) ? $data["event"]["data"]["plugin"] : '';

				$submit_text = isset( $data[ 'submit_text' ] ) ? $data[ 'submit_text' ] : 'Download CSV';
				$extra_field = isset( $data[ 'extra_field' ] ) ? $data[ 'extra_field' ] : '';

				$configurations = isset( $data[ 'configurations' ] ) ? $data[ 'configurations' ] : array();

				$show_all_fields = isset( $data[ 'show_all_fields' ] ) ? $data[ 'show_all_fields' ] : 0;

				$action_to_perform = isset( $data[ 'action_to_perform' ] ) && $data[ 'action_to_perform' ] ? $data[ 'action_to_perform' ] : '';
				$always_open = ( isset( $data["event"]["data"]["always_open"] ) && $data["event"]["data"]["always_open"] ) ? ' open ' : '';

				switch( $action_to_perform ){
				case 'generate_csv_from_mine':
					$form_act = '?action='.$plugin.'&todo=execute&nwp_action='.$action.'&nwp_todo='.$todo;
				break;
				default:
					$form_act = '?action='.$action.'&todo='.$todo;
				break;
				}

				$include = array( 
					'id' => 'ID',
				);
		?>
		<br />
		<form id="export-csv-form" action="<?php echo $form_act . $params; ?>" class="activate-ajax" method="post">
			<?php if( ! ( isset( $data["event"]["data"]["hide_name"] ) && $data["event"]["data"]["hide_name"] ) ){  ?>
			<div class="row">
				<div class="col-md-12">
					<label>Name </label>
					<div class="input-group">
						<input class="form-control " minlength="0"  name="name" placeholder="" value="" required />
					</div>
				</div>
			</div>
			<br />
			<?php } ?>
			
			<?php 
						// echo '<pre>'; print_r( $data["tables"] ); echo '</pre>';
				//print_r( $data["modules"] );
				//print_r( $data["functions"] );
				//print_r( $data["event"] );
				
				$dsel = array();
				if( isset( $data["event"]["data"] ) && $data["event"]["data"] ){
					if( is_array( $data["event"]["data"] ) ){
						$dsel = $data["event"]["data"];
					}else{
						$dsel = json_decode( $data["event"]["data"], true );
					}
				}
				// print_r( $dsel );exit;
				
				$selected = array();

				if( isset( $dsel[ 'tables' ] ) && is_array( $dsel[ 'tables' ] ) && ! empty( $dsel[ 'tables' ] ) ){
					$selected = $dsel[ 'tables' ];
				}

				$apm = array();
			?>
			<textarea name="data" class="form-control hyella-data"><?php echo json_encode( $dsel ); ?></textarea>
			<?php echo $extra_field; ?>
			<br />
		</form>
		<form id="client-form">
			<?php
				
				$sys_modules = array();
				$modules = array();
				$modules2 = array();
				$modules3 = array();
				$m_title = array();
				
				if( ! ( isset( $data["event"]["data"]["hide_data_points"] ) && $data["event"]["data"]["hide_data_points"] ) ){
					if( isset( $data["tables"] ) && ! empty( $data["tables"] ) ){
						
						foreach( $data["tables"] as $bk => $sval ){
							
							if( isset( $sval["additional_fields"] ) && is_array( $sval["additional_fields"] ) && ! empty( $sval["additional_fields"] ) ){
								foreach( $sval["additional_fields"] as $fk => $fv ){

									$s = array();
									$s["id"] = $fk;
									
									$s["label"] = $fv;
									$s["function_name"] = $fv;
									
									$s["table_name"] = $bk;
									$s["class"] = 'status-item';
									$s["input_attr"] = ' data-table="'. $bk .'" ';
									$s["input_key"] = 'tables';
									$s["input_name"] = 'tables[]';
									
									$modules2[ $sval['title'] ]["data"][ $s["id"] ] = $s;
								}
							}

							if( isset( $sval["fields"] ) && is_array( $sval["fields"] ) && ! empty( $sval["fields"] ) ){
								foreach( $sval["fields"] as $fk => $fv ){

									if( ! ( isset( $show_all_fields ) && $show_all_fields ) ){
										if( isset( $sval["labels"][ $fv ] ) && isset( $sval["labels"][ $fv ][ 'display_position' ] ) && $sval["labels"][ $fv ][ 'display_position' ] == 'do-not-display-in-table' )continue;
									}

									if( isset( $configurations[ 'exclude_fields' ][ $bk ] ) && ! empty( $configurations[ 'exclude_fields' ][ $bk ]  ) && in_array( $fk, $configurations[ 'exclude_fields' ][ $bk ]  ) ){
										continue;
									}
									
									$s = array();
									$s["id"] = $fk;
									
									$t = $fk;
									if( isset( $sval["labels"][ $fv ] ) ){
										if( isset( $sval["labels"][ $fv ]["text"] ) ){
											$t = $sval["labels"][ $fv ]["text"];
										}else if( isset( $sval["labels"][ $fv ]["abbreviation"] ) && $sval["labels"][ $fv ]["abbreviation"] ){
											$t = $sval["labels"][ $fv ]["abbreviation"];
										}else{
											$t = $sval["labels"][ $fv ]["field_label"];
										}
									}
									
									$s["label"] = $t;
									$s["function_name"] = $t;
									
									$s["table_name"] = $bk;
									$s["class"] = 'status-item';
									$s["input_attr"] = ' data-table="'. $bk .'" ';
									$s["input_key"] = 'tables';
									$s["input_name"] = 'tables[]';
									
									$modules2[ $sval['title'] ]["data"][ $s["id"] ] = $s;
								}
							}
							/* $sval["id"] = $bk;
							$sval['label'] = $sval['title'];
							$sval["function_name"] = $sval['title'];
							
							$modules2[ 'Modules' ]["data"][ 'Frontend Modules' ][ $sval["id"] ] = $sval; */
						}
						
						unset( $data["frontend_tabs"] );
					}
					
							// echo '<pre>'; print_r( $configurations ); echo '</pre>';
					$sections = array(
						/* array(
							'title' => 'States & LGA',
							'data' => $modules3,
						), */
						array(
							'data' => $modules2,
						),
					);
					
					foreach( $sections as $m1 ){
						if( isset( $m1["data"] ) && ! empty( $m1["data"] ) ){
							$tbs = array();
							foreach( $m1[ 'data' ] as $mk => $mv ){
								if( isset( $mv[ 'data' ] ) && ! empty( $mv[ 'data' ] ) ){
									foreach( $mv[ 'data' ] as $mx2 ){
										$tbs[ $mk ] = $mx2[ 'table_name' ];
										break;
									}
								}
							}
							// print_r( $tbs );exit;
							if( isset( $m1["title"] ) ){
							?>
							<details style="border:1px dashed; margin-bottom:20px; padding:10px;" >
							<summary style="cursor:pointer;"><h4 style="display:inline;"><strong><?php echo isset( $data["event"]["data"]["fields_title"] )?( $data["event"]["data"]["fields_title"] ):$m1["title"]; ?></strong></h4></summary><br />

							<?php
							}
							
							foreach( $m1["data"] as $module_id => $val ){
								?>
								<details style="border:1px dashed; margin-bottom:20px; padding:10px;" <?php if( isset( $val["id"] ) )echo ' id="mod-' . $val["id"] . '"'; ?> class="<?php if( isset( $val["class"] ) )echo ' cls-' . $val["class"]; ?>" <?php echo $always_open; ?> >
								<summary style="cursor:pointer;"><strong><big><?php echo isset( $data["event"]["data"]["fields_title"] )?( $data["event"]["data"]["fields_title"] ):( isset( $m_title[ $module_id ] )?$m_title[ $module_id ]:$module_id ); ?></big></strong></summary>
								<div class="row">
								<?php
									// echo '<pre>'; print_r( $val[ 'data' ] ); echo '</pre>';
									___format_access_capabilities( $val["data"], $selected, $dsel, $tbs, $module_id );
								?>
								</div>
								</details>
								<?php
							}
							
							if( isset( $m1["title"] ) ){
							?>
							</details>
							<hr />
							<?php
							}
							
						}
					}
				}
			?>
			
			<details style="border:1px dashed; margin-bottom:20px; padding:10px;" >
			<summary style="cursor:pointer;"><strong><big>Pagination Settings</big></strong></summary>
			<div class="row">
				<div class="col-md-4">
					<label>Starting Line No.</label>
					<div>
						<input type="number" name="page_offset" class="form-control add-options" min="0" step="1" value="0" />
					</div>
				</div>
				<?php if( ! ( isset( $data["event"]["data"]["hide_max_records"] ) && $data["event"]["data"]["hide_max_records"] ) ){  ?>
				<div class="col-md-4">
					<label>Max No. of Records</label>
					<div>
						<input type="number" name="page_limit" class="form-control add-options" min="0" step="1" value="10000" />
						<!-- <input type="number" name="page_limit" class="form-control add-options" min="0" step="1" value="10000" max="20000" /> -->
					</div>
				</div>
				<?php }  ?>
				<div class="col-md-4">
					<label>No. of Records per Page</label>
					<div>
						<input type="number" name="page_size" class="form-control add-options" min="1" step="1" value="500" max="1500" />
					</div>
				</div>
			</div>
			</details>

			<?php // echo $extra_field; ?>
			
			<br />
			<input type="submit" value="<?php echo $submit_text; ?>" class="btn blue" />
			
		</form>
		<?php }else{ ?>
			<div class="alert alert-danger">
				<h4><i class="icon-bell"></i> No Selected Access Role</h4>
				<p>
				No data was found<br />
				</p>
			</div>

		<?php } ?>
		<br />
		
	</div>
	
</div>

<script type="text/javascript" class="auto-remove">
	<?php 
		function ___format_access_capabilities( $val = array(), $selected = array(), $dsel = array(), &$tbs = array(), $module_id = '' ){
			$serial = 0;
			foreach( $val as $mname => $sval ){
				
				if( isset( $sval["function_name"] ) ){
					if( $serial && ! ( $serial % 3 ) ){
						?>
						</div><br /><div class="row">
						<?php
					}
					++$serial;
					
					$sel = '';
					if( ! empty( $selected ) && in_array( $sval["id"], $selected ) ){
						$sel = ' checked="checked" ';
					} 
					
					$saved_key2 = '';
					$saved_key = 'accessible_functions';
					$input_name = 'accessible_functions[]';
					if( isset( $sval["input_name"] ) && $sval["input_name"] ){
						$input_name = $sval["input_name"];
					}
					
					$input_attr = '';
					if( isset( $sval["input_attr"] ) && $sval["input_attr"] ){
						$input_attr = $sval["input_attr"];
					}
					
						// echo '<pre>'; print_r( $mname ); echo '</pre>';
					if( isset( $selected[ $sval[ 'table_name' ] ][ $sval[ 'id' ] ] ) && $selected[ $sval[ 'table_name' ] ][ $sval[ 'id' ] ] ){
						$sel = ' checked="checked" ';
					}

					if( isset( $tbs[ $module_id ] ) && $tbs[ $module_id ] ){
					?>
					<div class="col-md-4">
					<label><input type="checkbox" class="sel-all" table="<?php echo $tbs[ $module_id ]; ?>" value="<?php echo $tbs[ $module_id ]; ?>" id="sel-all-<?php echo $tbs[ $module_id ]; ?>" /> TOGGLE ALL</label>
					</div>
					<?php 
					unset( $tbs[ $module_id ] );
					} ?>

					<div class="col-md-4">
					<label><input type="checkbox" name="<?php echo $input_name; ?>" value="<?php echo $sval["id"]; ?>" <?php echo $sel; ?> class="<?php if( isset( $sval["class"] ) )echo $sval["class"]; ?>" class="<?php if( isset( $sval["class"] ) )echo $sval["class"]; ?>" <?php echo $input_attr; ?> /> <?php echo strtoupper( $sval["function_name"] ); ?></label>
					</div>
					<?php
					unset( $val[ $mname ] );
				}
				
			}
			
			if( ! empty( $val ) ){
				foreach( $val as $mname => $sval ){
					?>
					</div><br /><div class="row">
					<div class="col-md-10 col-md-offset-1">
					<i><strong><?php echo $mname; ?></strong></i><br />
					
					<div class="row">
					<?php
						___format_access_capabilities( $sval, $selected, $dsel, $tbs, $module_id );
					?>
					</div>
					</div><br />
					<?php
					
				}
			}
		}
	?>
	var action = "<?php echo isset( $action_to_perform ) ? $action_to_perform : ''; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>

</div>
