<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$params = '';
	$hide_title = isset( $data["hide_title"] )?$data["hide_title"]:'';
	
	if( isset( $data["html_replacement_selector"] ) && $data["html_replacement_selector"] ){
		$params = '&html_replacement_selector=' . $data["html_replacement_selector"];
	}
	
	$theme = '';
	if( defined("HYELLA_THEME") ){
		$theme = HYELLA_THEME;
	}
	
	$top_header = 0;
	switch( $theme ){
	case "v3":	//reports ui
		$top_header = 1;
	break;
	}

	if( ! $hide_title ){
		if( $top_header ){
?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<h4 class="mb-sm-0 text-primary"><a href="#" class="custom-single-selected-record-button" action="?module=&action=access_roles&todo=manage_access_roles<?php echo $params; ?>" override-selected-record="<?php echo isset( $data["event"]["id"] )?$data["event"]["id"]:'1'; ?>" mod="1" title="Manage Access Roles"><i class="icon-refresh mdi mdi-refresh"></i></a> Manage Access Role(s)</h4>
</div>
		<?php } ?>
<div class="row">
<div class="col-md-10 col-md-offset-1">

<div class="portlet grey box">

<?php if( ! $top_header ){ ?>
<div class="portlet-title">
	<div class="caption">
		<a href="#" class="custom-single-selected-record-button" action="?module=&action=access_roles&todo=manage_access_roles<?php echo $params; ?>" override-selected-record="<?php echo isset( $data["event"]["id"] )?$data["event"]["id"]:'1'; ?>" mod="1" title="Manage Access Roles"><i class="icon-refresh mdi mdi-refresh"></i></a>
		<small>Manage Access Role(s)</small>
	</div>
</div>
<?php } ?>
<div class="portlet-body resizable-height auto-scroll" style="padding-bottom:50px; overflow-x:hidden;">

<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
	<?php } ?>

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<?php 
			if( isset( $data["event"]["id"] ) && $data["event"]["id"] ){
		?>
		<br />
		<form id="access_role-form" action="?action=access_roles&todo=save_manage_access_role2<?php echo $params; ?>" class="activate-ajax" method="post">
			
			<?php 
				
				$dsel = array();
				if( isset( $data["event"]["data"] ) && $data["event"]["data"] ){
					$dsel = json_decode( $data["event"]["data"], true );
				}
				
				$selected = array();
				if( empty( $dsel ) ){
					if( isset($data['event']['accessible_functions']) && $data['event']['accessible_functions'] ){
						$selected = explode( ":::", $data["event"]["accessible_functions"] );
					}
				}
				
				$apm = array();
			?>
			<textarea name="data" class="form-control hyella-data" style="display:none;"><?php echo json_encode( $dsel ); ?></textarea>
			<input type="hidden" name="id" value="<?php echo $data["event"]["id"]; ?>" />
			<div class="row">
				<div class="col-md-6">
					<input type="text" name="role_name" style="font-weight:bold;" value="<?php echo $data["event"]["role_name"]; ?>" class="form-control" required="required" />
				</div>
			</div>
			<br />
		</form>
		<form id="client-form" >
			<?php
				$stores_modules = array();
				$api_modules = array();
				$sys_modules = array();
				$modules = array();
				$modules2 = array();
				$modules3 = array();
				$m_title = isset( $data["modules"] )?$data["modules"]:array();
				
				if( isset( $data["functions"] ) && ! empty( $data["functions"] ) ){
					foreach( $data["functions"] as $sval ){
						
						$ax = explode(" - ", $sval["function_name"] );
						if( isset( $ax[3] ) && $ax[3] ){
							$ax1 = $ax[0] .' - '. $ax[1] .' - '. $ax[2];
							unset( $ax[2] );
							unset( $ax[1] );
							unset( $ax[0] );
							
							$sval["function_name"] = implode(" - ", $ax );
							$modules[ $sval["module_name"] ]["data"][ $ax1 ][ $sval["id"] ] = $sval;
						}else{
							$modules[ $sval["module_name"] ]["data"][ $sval["id"] ] = $sval;
						}
						
						
					}
					unset( $data["functions"] );
				}
				
				if( isset( $data["frontend_menus"] ) && ! empty( $data["frontend_menus"] ) ){
					foreach( $data["frontend_menus"] as $bk => $sval ){
						
						$sval["id"] = $bk;
						$sval['label'] = isset( $sval['title'] )?$sval['title']:$bk;
						if( isset( $sval['menu_title'] ) && $sval['menu_title'] ){
							$sval['label'] = $sval['menu_title'];
						}
						$sval["function_name"] = $sval['label'];
						
						if( isset( $sval["sub_menu"] ) && is_array( $sval["sub_menu"] ) && ! empty( $sval["sub_menu"] ) ){
							
							$sval["module_name"] = ( isset( $sval['prefix'] )?$sval['prefix']:'' ) . $sval['label'];

							foreach( $sval["sub_menu"] as $bk2 ){
								if( ! is_array( $bk2 ) && isset( $data["frontend_menus_function"][ $bk2 ]["title"] ) && !(isset( $data["frontend_menus_function"][ $bk2 ]["development"] ) && $data["frontend_menus_function"][ $bk2 ]["development"]) ){
									$sval2 = $data["frontend_menus_function"][ $bk2 ];
									
									if( isset( $sval2["sub_menu"] ) && is_array( $sval2["sub_menu"] ) && ! empty( $sval2["sub_menu"] ) ){
										
										foreach( $sval2["sub_menu"] as $bk3 ){
											if( isset( $data["frontend_menus_function"][ $bk3 ]["title"] ) && !(isset( $data["frontend_menus_function"][ $bk3 ]["development"] ) && $data["frontend_menus_function"][ $bk3 ]["development"]) ){
												$sval3 = $data["frontend_menus_function"][ $bk3 ];
												
												if( isset( $sval3['menu_title'] ) && $sval3['menu_title'] ){
													$sval3['title'] = $sval3['menu_title'];
												}
												
												$sval3["id"] = $bk3;
												$sval3["function_name"] = $sval3['title'];
												
												$sys_modules[ $sval["module_name"] ]["data"][ $sval2['title'] ][ $sval3["id"] ] = $sval3;
											}
										}
										
									}else{
										
										$sval2["id"] = $bk2;
										$sval2["function_name"] = ( isset( $sval2['prefix'] )?$sval2['prefix']:'' ) . $sval2['title'];
										
										$sys_modules[ $sval["module_name"] ]["data"][ $sval2["id"] ] = $sval2;
									}
								}
							}
							
						}else{
							$sys_modules[ '*Other Menus' ]["data"][ $sval["id"] ] = $sval;
						}
						
						
					}
					ksort( $sys_modules );
					unset( $data["frontend_menus"] );
				}
				 
				if( isset( $data["frontend_tabs"] ) && ! empty( $data["frontend_tabs"] ) ){
					
					foreach( $data["frontend_tabs"] as $bk => $sval ){
						if( ! isset( $sval["no_access"] ) ){
							$sval["id"] = "frontend_tabs." . $bk;
							$sval['label'] = $sval['title'];
							$sval["function_name"] = $sval['title'];
							
							$modules2[ 'Modules' ]["data"][ 'Frontend Modules' ][ $sval["id"] ] = $sval;
						}
					}
					
					unset( $data["frontend_tabs"] );
				}
				
				if( isset( $data["basic_crud"] ) && ! empty( $data["basic_crud"] ) ){
					
					$basic_crud_methods = array( 'create_new_record' => 'Create', 'edit' => 'Edit', 'delete' => 'Delete', 'export' => 'Export' );
					
					foreach( $data["basic_crud"] as $sval ){
						
						foreach( $basic_crud_methods as $bk => $bv ){
							if( ! isset( $sval["exclude_from_crud"][ $bk ] ) ){
								$sval["id"] = $sval["table_name"] . '.' . $bk;
								$sval["function_name"] = $bv;
								
								$modules2[ 'Basic CRUD' ]["data"][ $sval["label"] ][ $sval["id"] ] = $sval;
							}
						}
						
						if( isset( $sval["special_actions"] ) && is_array( $sval["special_actions"] ) && ! empty( $sval["special_actions"] ) ){
							foreach( $sval["special_actions"] as $spk => $spv ){
								if( isset( $spv['type'] ) && isset( $spv['todo'] ) ){
									switch( $spv['type'] ){
									case "function":
										if( function_exists( $spv['todo'] ) ){
											$spf = $spv['todo']();
											if( is_array( $spf ) && ! empty( $spf ) ){
												foreach( $spf as $spfk => $spfv ){
													$sp_val = array();
													$sp_val["id"] = $sval["table_name"] . '.' . $spk . '.' . $spfk;
													$sp_val["function_name"] = $spfv;

													$slbl = isset( $spv["label"] ) ? $spv["label"] : ( isset( $spv["title"] ) ? $spv["title"] : '' );

													$modules2[ 'Basic CRUD' ]["data"][ $sval["label"] .' - '. $slbl ][ $sp_val["id"] ] = $sp_val;
												}
											}
										}
									break;
									default:
										$spv["id"] = $sval["table_name"]  . '.' . $spk . '.' . $spv['todo'];
										$spv["function_name"] = $spv['title'];
										
										$modules2[ 'Basic CRUD' ]["data"][ $sval["label"] ][ $spv["id"] ] = $spv;
									break;
									}
								}
							}
						}
						
						if( isset( $sval["more_actions"] ) && is_array( $sval["more_actions"] ) && ! empty( $sval["more_actions"] ) ){
							foreach( $sval["more_actions"] as $act2k => $act2 ){
								if( ! isset( $act2["title"] ) ){
									continue;
								}
								
								if( isset( $act2["data"] ) && ! empty( $act2["data"] ) ){
									foreach( $act2["data"] as $act3 ){
										if( ! empty( $act3 ) ){
											foreach( $act3 as $bk1 => $bv1 ){
												
												if( isset( $sval["exclude_from_crud"][ $act2k . '.' . $bk1 ] ) ){
													continue;
												}

												if( ! isset( $bv1[ 'text' ] ) ){
													continue;
												}
												
												$act_val = array();
												$act_val["id"] = $sval["table_name"] . '.' . $act2k . '.' . $bk1;
												$act_val["function_name"] = ( isset( $bv1["title"] ) && $bv1["title"] )?$bv1["title"]:$bv1["text"];
												
												$modules2[ 'Basic CRUD' ]["data"][ $sval["label"] ][ $act_val["id"] ] = $act_val;
											}
										}
									}
								}else{
									if( isset( $act2["todo"] ) && ! isset( $sval["exclude_from_crud"][ $act2k ] ) ){
										
										$act_val = array();
										$act_val["id"] = $sval["table_name"] . '.' . $act2k;
										$act_val["function_name"] = ( isset( $act2["title"] ) && $act2["title"] )?$act2["title"]:$act2["text"];
										
										$modules2[ 'Basic CRUD' ]["data"][ $sval["label"] ][ $act_val["id"] ] = $act_val;
									}
								}
							}
						}
					}
					unset( $data["basic_crud"] );
				}
				
				if( isset( $data["status_table"] ) && ! empty( $data["status_table"] ) ){
					foreach( $data["status_table"] as $ttext => $dst ){
						
						if( ! empty( $dst ) ){
							foreach( $dst as $k1 => $sv ){
								// $k1 .= $tp;
								
								if( ! empty( $sv ) ){
									foreach( $sv as $bk => $bv ){
										$sval = array();
										
										$sval["class"] = 'status-item';
										$sval["input_key"] = 'status';
										$sval["input_key2"] = $k1;
										$sval["input_name"] = 'status[]';
										
										$sval["label"] = $ttext;
										$sval["table_name"] = $k1;
										$sval["id"] = $bk;
										$sval["function_name"] = $bv;
										$sval["input_attr"] = ' data-table="'. $k1 .'" ';
										
										$modules2[ 'Status' ]["data"][ $sval["label"] ][ $sval["id"] ] = $sval;
										
									}
								}
							}
							// unset( $data["status_table"] );
						}
					}
				}
				
				if( isset( $data["states"] ) && ! empty( $data["states"] ) ){
					
					foreach( $data["states"] as $sval ){
						
						if( isset( $data["lga"][ $sval["id"] ] ) && ! empty( $data["lga"][ $sval["id"] ] ) ){
							
							$lgas = $data["lga"][ $sval["id"] ];
							unset( $data["lga"][ $sval["id"] ] );
							
							$r_sid = $sval["id"];
							$sid = $sval["id"];
							$sname = $sval["name"];
							
							$sval["id"] = "all";
							$sval["class"] = 'lga-item-all lga-item';
							$sval["function_name"] = "**All LGA**";
							$sval["input_name"] = 'lga['. $r_sid .'][all]';
							$sval["input_key"] = 'lga';
							$sval["input_key2"] = $sid;
							$sval["input_attr"] = ' data-state="'. $r_sid .'" ';
							$modules3[ $sval["name"] ]["data"][ $sval["id"] ] = $sval;
							
							unset( $sval["input_key"] );
							unset( $sval["input_key2"] );
							unset( $sval["input_attr"] );
							
							$sval["id"] = $sid;
							$sval["class"] = 'state-item';
							$sval["function_name"] = $sval["name"];
							$sval["input_key"] = 'states';
							$sval["input_name"] = 'state[]';
							$modules3[ "States" ]["data"][  $sval["id"] ] = $sval;
							
							foreach( $lgas as $bk => $bv ){
								$lga_id = $bv["id"];
								$lga_name = $sname .' - '. $bv["name"];
								
								$sval["id"] = $bv["id"];
								$sval["function_name"] = $bv["name"];
								$sval["class"] = 'lga-item lga-' . $sid;
								
								$sval["input_key"] = 'lga';
								$sval["input_key2"] = $sid;
								$sval["input_name"] = 'lga['. $r_sid .'][]';
								//$sval["input_name"] = 'lga['. $r_sid .'][]';
								
								$sval["input_attr"] = ' data-state="'. $r_sid .'" ';
								
								$modules3[ $sval["name"] ]["data"][ $sval["id"] ] = $sval;
								$modules3[ $sval["name"] ]["class"] = 'con-lga';
								$modules3[ $sval["name"] ]["id"] = $sid;
								
								if( isset( $data["ward"][ $sid ][ $lga_id ] ) && ! empty( $data["ward"][ $sid ][ $lga_id ] ) ){
									$wards = $data["ward"][ $sid ][ $lga_id ];
									unset( $data["ward"][ $sid ][ $lga_id ] );
									
									$sval["id"] = "all";
									$sval["class"] = 'ward-item-all ward-item';
									$sval["function_name"] = "**All WARDs**";
									$sval["input_name"] = 'ward['. $lga_id .'][all]';
									$sval["input_key"] = 'ward';
									$sval["input_key2"] = $lga_id;
									$sval["input_attr"] = ' data-state="'. $sid .'" data-lga="'. $lga_id .'" ';
									$modules3[ $lga_name ]["data"][ $lga_id ] = $sval;
									
									unset( $sval["input_key"] );
									unset( $sval["input_key2"] );
									unset( $sval["input_attr"] );
									
									foreach( $wards as $bkw => $bvw ){
										$ward_id = $bvw["id"];
										$sval["id"] = $ward_id;
										$sval["function_name"] = $bvw["name"];
										$sval["class"] = 'ward-item ward-' . $lga_id;
										
										$sval["input_key"] = 'ward';
										$sval["input_key2"] = $lga_id;
										$sval["input_attr"] = ' data-state="'. $sid .'" data-lga="'. $lga_id .'" ';
										$sval["input_name"] = 'ward['. $lga_id .'][]';
										
										$modules3[ $lga_name ]["data"][ $ward_id ] = $sval;
										$modules3[ $lga_name ]["class"] = 'con-ward con-lga-' . $sid;
										$modules3[ $lga_name ]["id"] = $lga_id;
									}
									
								}
							}
							
						}
						
					}
					
					if( isset( $data["states_smart_options"] ) && ! empty( $data["states_smart_options"] ) ){
						foreach( $data["states_smart_options"] as $k1 => $sv ){
							$sval = array();
							
							$sval["class"] = 'status-item';
							$sval["input_key"] = 'status';
							$sval["input_key2"] = $k1;
							$sval["input_name"] = 'status[]';
							
							$sval["label"] = $k1;
							$sval["table_name"] = $k1;
							$sval["id"] = $k1;
							$sval["function_name"] = $sv;
							$sval["input_attr"] = ' data-table="'. $k1 .'" ';
							
							$modules3[ 'Smart Options' ]["data"][ $sval["id"] ] = $sval;
						}
						unset( $data["states_smart_options"] );
					}
					
					unset( $data["lga"] );
					unset( $data["states"] );
				}
				
				if( isset( $data["api_methods"] ) && ! empty( $data["api_methods"] ) ){
					
					foreach( $data["api_methods"] as $sval ){
						
						$r_sid = $sval["id"];
						$sid = $sval["id"];
						
						unset( $sval["input_key"] );
						unset( $sval["input_key2"] );
						unset( $sval["input_attr"] );
						
						$sval["id"] = $sid;
						$sval["class"] = 'status-item';
						$sval["function_name"] = $sval["name"];
						$sval["input_key2"] = 'api';
						$sval["input_key"] = 'status';
						$sval["input_name"] = 'status[]';
						$sval["input_attr"] = ' data-table="api" ';
						
						$api_modules[ $sval["request_type"] ]["data"][  $sval["id"] ] = $sval;
						
					}
					unset( $data["api_methods"] );
				}
				
				if( isset( $data["stores"] ) && ! empty( $data["stores"] ) ){
					
					foreach( $data["stores"] as $sval ){
						
						$r_sid = $sval["id"];
						$sid = $sval["id"];
						
						unset( $sval["input_key"] );
						unset( $sval["input_key2"] );
						unset( $sval["input_attr"] );
						
						$sval["id"] = $sid;
						$sval["class"] = 'status-item';
						$sval["function_name"] = $sval["name"];
						$sval["input_key"] = 'status';
						$sval["input_key2"] = 'stores';
						$sval["input_name"] = 'stores[]';
						$sval["input_attr"] = ' data-table="stores" ';
						
						$stores_modules[ $sval["branch"] ]["data"][  $sval["id"] ] = $sval;
						
					}
					unset( $data["stores"] );
					
					if( isset( $data["stores_smart_options"] ) && ! empty( $data["stores_smart_options"] ) ){
						foreach( $data["stores_smart_options"] as $k1 => $sv ){
							$sval = array();
							
							$sval["class"] = 'status-item';
							$sval["input_key"] = 'status';
							$sval["input_key2"] = 'stores';
							$sval["input_name"] = 'stores[]';
							
							$sval["label"] = $k1;
							$sval["table_name"] = $k1;
							$sval["id"] = $k1;
							$sval["function_name"] = $sv;
							$sval["input_attr"] = ' data-table="stores" ';
							
							$stores_modules[ 'Smart Options' ]["data"][ $sval["id"] ] = $sval;
						}
						unset( $data["stores_smart_options"] );
					}
					
				}
				
				$sections = array(
					array(
						'title' => 'Custom Modules',
						'data' => $modules,
					),
					array(
						'title' => 'System Modules',
						'data' => $sys_modules,
					),
					array(
						'title' => 'API Modules',
						'data' => $api_modules,
					),
					array(
						'title' => 'States & LGA',
						'data' => $modules3,
					),
					array(
						'title' => 'Business Units',
						'data' => $stores_modules,
					),
					array(
						'data' => $modules2,
					),
				);
				
				foreach( $sections as $m1 ){
					if( isset( $m1["data"] ) && ! empty( $m1["data"] ) ){
						
						if( isset( $m1["title"] ) ){
						?>
						<details style="border:1px dashed; margin-bottom:20px; padding:10px;">
						<summary style="cursor:pointer;"><h4 style="display:inline;"><strong><?php echo $m1["title"]; ?></strong></h4></summary><br />
						<?php
						}
						
						foreach( $m1["data"] as $module_id => $val ){
							?>
							<details style="border:1px dashed; margin-bottom:20px; padding:10px;" <?php if( isset( $val["id"] ) )echo ' id="mod-' . $val["id"] . '"'; ?> class="<?php if( isset( $val["class"] ) )echo ' cls-' . $val["class"]; ?>">
							<summary style="cursor:pointer;"><strong><big><?php echo isset( $m_title[ $module_id ] )?$m_title[ $module_id ]:$module_id; ?></big></strong></summary>
							<div class="row">
							<?php
								___format_access_capabilities( $val["data"], $selected, $dsel );
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
			?>
			<br />
			<input type="submit" value="Save Changes" class="btn blue" />
			
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
		function ___format_access_capabilities( $val = array(), $selected = array(), $dsel = array() ){
			$serial = 0;
			foreach( $val as $mname => $sval ){
				
				if( isset( $sval["function_name"] ) ){
					if( $serial && ! ( $serial % 4 ) ){
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
					
					if( isset( $sval["input_key"] ) && $sval["input_key"] ){
						$saved_key = $sval["input_key"];
					}
					
					if( isset( $sval["input_key2"] ) && $sval["input_key2"] ){
						$saved_key2 = $sval["input_key2"];
					}
					
					if( $saved_key2 ){
						if( isset( $dsel[ $saved_key ][ $saved_key2 ][ $sval["id"] ] ) ){
							$sel = ' checked="checked" ';
						}
					}else{
						if( isset( $dsel[ $saved_key ][ $sval["id"] ] ) ){
							$sel = ' checked="checked" ';
						}
					}

					if( !is_array($sval['function_name']) ){ ?>
						<div class="col-md-3">
							<label style="font-weight:normal;"><input type="checkbox" name="<?php echo $input_name; ?>" value="<?php echo $sval["id"]; ?>" <?php echo $sel; ?> class="<?php if( isset( $sval["class"] ) )echo $sval["class"]; ?>" class="<?php if( isset( $sval["class"] ) )echo $sval["class"]; ?>" <?php echo $input_attr; ?> /> <?php echo strtoupper( $sval["function_name"] ); ?></label>
						</div>
					<?php }

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
						___format_access_capabilities( $sval, $selected, $dsel );
					?>
					</div>
					</div><br />
					<?php
					
				}
			}
		}
	?>
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>

<?php if( ! $hide_title ){ ?>
</div>
</div>
</div>
<?php } ?>
</div>
</div>