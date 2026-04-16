<?php
	
	$new_record = 0;
	if( isset( $data[ 'new_record' ] ) )
		$new_record = $data[ 'new_record' ];
	
	$reg_date = 0;
	if( isset( $data[ "registration_date" ] ) )
		$reg_date = $data[ "registration_date" ];
	
	$no_delete = 0;
	if( isset( $data[ 'no_delete' ] ) )
		$no_delete = $data[ 'no_delete' ];
		
	$no_edit = 0;
	if( isset( $data[ 'no_edit' ] ) )
		$no_edit = $data[ 'no_edit' ];
		
	if( isset( $data["change_of_name"] ) && is_array( $data["change_of_name"] ) && $data["change_of_name"] ){
		$serial = 0;
		foreach( $data["change_of_name"] as $val ){
			
		?>		
		<tr id="change_of_name-<?php echo $val["id"]; ?>">
			<td><?php if( $new_record )echo '<strong>**new</strong>'; else echo ++$serial; ?></td>
			<td><?php echo $val["previous_surname"] . " " . $val["previous_othernames"]; ?></td>
			<td><?php echo $val["new_surname"] . " " . $val["new_othernames"]; ?></td>
			
			<td><?php 
				$keep = 0;
				if( isset( $_SESSION["admin_page"] ) ){
					$keep = $_SESSION["admin_page"];
					unset($_SESSION["admin_page"]);
				}
				
				echo get_uploaded_files( $data['pagepointer'] , $val["supporting_document"], "Supporting Document" ); 
				
				if( $keep )
					$_SESSION["admin_page"] = $keep;
			?></td>
			<td><?php echo get_select_option_value( array( "id" => $val["status"], "function_name" => "get_approval_status" ) ); ?></td>
			
			<td>
				<?php if( $val["status"] != "approved" ){ ?>
				<?php if( ! $no_edit ){ ?>
				<a href="#" function-id="1" function-class="change_of_name" function-name="edit_change_of_name" module="" title="Click Here to Edit Record" class="btn dark btn-sm custom-action-button" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["member_id"]; ?>" ><i class="icon-edit"></i></a> 
				<?php } ?>
				<?php if( ! $no_delete ){ ?>
				
					<?php //if( $reg_date ){ ?>
					<a href="#" function-id="1" function-class="change_of_name" function-name="delete_change_of_name" module="" title="Click Here to Delete Record" class="btn dark btn-sm custom-action-button" month-id="<?php echo $val["id"]; ?>" budget-id="<?php echo $val["member_id"]; ?>" ><i class="icon-trash"></i></a> 
					<?php //} ?>
					
				<?php } ?>
				<?php } ?>
			</td>
		</tr>
		<?php
		}
	}
?>