<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
	//print_r($data["user_info"]);
	$col_offset = 'col-md-offset-1';
	
	$default_style = 'background:#fff; border:1px solid #ddd;';
	$show_more = 1;
	$show_button = 0;
	$pr = get_project_data();
	
	if( isset( $data["action_to_performed"] ) ){
		switch( $data["action_to_performed"] ){
		case "view_my_profile_dashboard":
			$show_more = 0;
			$col_offset = '';
			$default_style = '';
			$show_button = 1;
		break;
		}
	}
	
	if( ( isset( $data["user_info"] ) && is_array( $data["user_info"] ) && ! empty( $data["user_info"] ) ) ){
?>
<div class="row">
<div class="col-md-3">
<div style="<?php echo $default_style; ?> padding-bottom:20px; border-radius: 50%;">
		<?php 
			$pp = isset( $pagepointer2 )?$pagepointer2:$pagepointer;
			
			$key = "photograph";
			if( isset( $data["user_info"][$key] ) && $data["user_info"][$key] && $data["user_info"][$key] !== 'none' && file_exists( $pp . $data["user_info"][$key] ) ){
				$pic1 = $data["user_info"][$key];
			}else if( defined("IPIN_APP_DEFAULT_AVATAR") && IPIN_APP_DEFAULT_AVATAR ){
				$pic1 = IPIN_APP_DEFAULT_AVATAR;
			}else{
				$pic1 = 'files/resource_library/images/avatars/1.jpg';
			}
			$pic = get_uploaded_files( $pp , $pic1, '', '', array( "return_link" => 1 ) );
			
		?>
		<img src="<?php echo $pic; ?>" style="max-width:100%; margin-top:20px; b" /> 
		<?php
	
		if( $show_button ){
			?><br /><a href="#" class="custom-action-button-old btn btn-sm btn-default btn-block" id="8270082573" function-id="8270082579" function-class="users" function-name="display_my_profile_manager" module-id="1412705497" module-name="Users Manager" title="My Profile Manager" budget-id="-" month-id="-">
				<i class="icon-user"></i> My Profile
			</a>
			<a href="sign_out?action=signout" class="btn btn-sm btn-default btn-block"><i class="icon-power-off"></i> Sign Out</a>
			<?php
		}
		?> 
</div>
</div>
<?php if( $show_more ){ ?>
<div class="col-md-9 col-md-offset-3X">
	<div class="table-responsive">
		<table class="table table-striped table-hover bordered" width="100%">
		<tbody>
			<?php 
				foreach( $data["user_info"] as $k => $v ){
					if( ! $v )continue;
					$value = $v;
					$break = 0;
					
					switch( $k ){
					// case "lastname":	
					// case "firstname":	
					// case "division":	
					// case "department":	
					// case "role":	
					// case "phone_number":	
					// case "email":	
					case "oldpassword":	
					case "confirmpassword":	
					case "password":	
					case "photograph":	
					case "image":
					case "data":
					case "id":	
					case "created_by":
					case "creation_date":
					case "modification_date":
					case "modified_by":
					case "serial_num":
						$break = 1;
					break;
					}
					
					$l = $k;
					$t = "";
					$t1 = "";
					
					if( isset( $data[ "fields" ][ $k ] ) && isset( $data[ "labels" ][ $data[ "fields" ][ $k ] ] ) ){
						$l = $data[ "labels" ][ $data[ "fields" ][ $k ] ]['field_label'];
						$t = $data[ "labels" ][ $data[ "fields" ][ $k ] ]['form_field'];
					}else{
						$break = 1;
					}
					
					if( $break )continue;
					?>
				   <tr>
					<td style="border-right:1px solid #ddd; font-size:11px;"><?php
						
						switch( $t ){
						case "currency":
						case "decimal":
							$value = format_and_convert_numbers( $value, 4 );
						break;
						case "date-5":
						case "date":
							if( doubleval( $value ) ){
								$value = date( "d-M-Y", doubleval( $value ) );
							}else{
								$value = "-";
							}
						break;
						case "select":
							$t1 = $data[ "labels" ][ $data[ "fields" ][ $k ] ]['form_field_options'];
							$value = get_select_option_value( array( "id" => $value, "function_name" => $t1 ) );
						break;
						}
						
						echo $l; 
					?></td>
					<td style="font-size:11px;"><strong><?php echo $value; ?></strong></td>
				   </tr>
					<?php
				}
			?>
		</tbody>
		</table>
	</div>
</div>
<?php } ?>
</div>
<?php }else{ ?>
<div class="alert alert-danger">
	<h4><i class="icon-bell"></i> No Data Found</h4>
	<p>
	No data was found<br />
	<strong>Please Sign-out & Sign-n</strong>
	</p>
</div>
<?php } ?>
</div>