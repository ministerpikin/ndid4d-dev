<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<br />
<?php 
	$handle = isset( $data["html_replacement_selector"] )?$data["html_replacement_selector"]:'';
	$action_to_perform = isset( $data["action_to_perform"] )?$data["action_to_perform"]:'';
	$id = isset( $data["id"] )?$data["id"]:'-';
	
	$hide_title = isset( $data["hide_title"] )?$data["hide_title"]:0;
	$error = isset( $data["error"] )?$data["error"]:'';
	
	$eparams = 'display_erd';
	if( $action_to_perform ){
		$eparams = $action_to_perform;
	}

	if( $handle ){
		$eparams .= '&html_replacement_selector=' . $handle;
	}
?>
<div class="row">
	<div class="col-md-12">
		<?php 
		if( $error ){
			echo '<div class="note note-danger">' . $error . '</div>';
		}else{
			if( ! $hide_title ){ ?>
			<h4 class="card-title-1"><a href="#" class="btn btn-default btn-xs custom-single-selected-record-button" action="?module=&action=audit&todo=<?php echo $eparams; ?>" override-selected-record="<?php echo $id; ?>" mod="1" title="ERD"><i class="icon-refresh"></i></a> <strong><?php if( isset( $data["title"] ) )echo $data["title"]; ?></strong></h4>
			<hr />
			<?php
			}
		
			$h1 = '<ol>';
			$h2 = '';
			$f = get_form_fields();
			
			if( isset( $data["tables"] ) && ! empty( $data["tables"] ) ){
				foreach( $data["tables"] as $table_name => $v ){
					
					$h1 .= '<li style="margin-bottom:8px;"><a style="color:#eee; font-size:14px;" href="#link-'.$table_name.'">' . $table_name . '</a></li>';
					
					if( isset( $v["table_clone"] ) ){
						unset( $v["table_clone"][ $table_name ] );
						if( ! empty( $v["table_clone"] ) ){
							foreach( $v["table_clone"] as $stb ){
								$h1 .= '<li style="margin-bottom:8px;"><a style="color:#faa; font-size:14px;" href="#link-'.$table_name.'">' . $stb . '</a></li>';
							}
						}
					}
					
					$buttons = '';
					if( $buttons ){
						
					}
					
					$h2 .= '<h4 id="link-'.$table_name.'" class="card-title-1"><strong>' . strtoupper( $table_name ) . ' ['. $v["label"] .']</strong></h4>' . $buttons;
						$h2 .= '<table class="table table-bordered table-striped">';
							$h2 .= '<thead>';
							$h2 .= '<tr>';
								$h2 .= '<th>Key</th>';
								$h2 .= '<th>Field Name</th>';
								$h2 .= '<th>User Friendly Name</th>';
								$h2 .= '<th>Data Type</th>';
								$h2 .= '<th>Option</th>';
								$h2 .= '<th></th>';
							$h2 .= '</tr>';
							$h2 .= '</thead>';
							$h2 .= '<tbody>';
							
								foreach( $v[ 'table_fields' ] as $key => $val ){
									
									$lv2 = isset( $v[ "table_labels" ][ $val ] )?$v[ "table_labels" ][ $val ]:array();
									
									$required_field = isset( $lv2[ "required_field" ] )?$lv2[ "required_field" ]:'';
									$uname = isset( $lv2[ "field_label" ] )?$lv2[ "field_label" ]:'';
									$utype = isset( $lv2[ "form_field" ] )?$lv2[ "form_field" ]:'';
									$uoption = isset( $lv2[ "form_field_options" ] )?$lv2[ "form_field_options" ]:'';
									$sn = isset( $lv2[ "serial_number" ] )?$lv2[ "serial_number" ]:'';
									
									$utype2 = isset( $f[ $utype ] )?$f[ $utype ]:$utype;
									
									switch( $utype ){
									case "calculated":
										$uoption = isset( $lv2[ "attributes" ] )?$lv2[ "attributes" ]:'';
									break;
									case "html":
									case "field_group":
										$uoption = isset( $lv2[ "database_objects" ] )?$lv2[ "database_objects" ]:'';
									break;
									case "checkbox":
									case "radio":
									case "multi-select":
									case "select":
										$uoption = '<a href="#" class="custom-single-selected-record-button" action="?module=&action=list_box_options&todo=modify_list_box2" override-selected-record="'.$uoption.'">'. $uoption .'</a>';
									break;
									default:
									break;
									}
									
									if( isset( $lv2["data"] ) && is_array( $lv2["data"] ) && ! empty( $lv2["data"] ) ){
										if( $uoption && isset( $lv2["data"]["form_field_options_source"] ) ){
											
											switch( $lv2["data"]["form_field_options_source"] ){
											case 2:
											case "list_box_class":
												$uoption = isset( $lv2[ "form_field_options" ] )?$lv2[ "form_field_options" ]:'';
												$uoption = '<a href="#" class="custom-single-selected-record-button" action="?module=&action=list_box_options&todo=modify_list_box" override-selected-record="'.$uoption.'">'. $uoption .'</a>';
											break;
											}
											
										}
									}
									
									if( $required_field == 'yes' ){
										$uname = '<span style="color:#d32111;">'.$uname.'*</span>';
									}
									
									$h2 .= '<tr>';
										$h2 .= '<td>'. $key .'</td>';
										$h2 .= '<td>'. $val .'</td>';
										$h2 .= '<td>'. $uname .'</td>';
										$h2 .= '<td>'. $utype2 .'</td>';
										$h2 .= '<td style="max-width:200px; word-break: break-all;">'. $uoption .'</td>';
										$h2 .= '<td>'. $sn .'</td>';
									$h2 .= '</tr>';
								}
								
							$h2 .= '</tbody>';
						$h2 .= '</table><br />';
				}
			}
			
			$h1 .= '<ol>';
		?>
		
		<div class="row">
			<div class="col-md-3" >
				<div class="resizable-height" style="overflow-y:auto; background:#000; color:#fff; padding:10px;">
					<h4>Entities</h4>
					<?php echo $h1; ?>
				</div>
			</div>
			<div class="col-md-9" >
				<div class="resizable-height report-table-preview-20" style="overflow-y:auto; overflow-x:hidden;">
					<?php echo $h2; ?>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
</div>