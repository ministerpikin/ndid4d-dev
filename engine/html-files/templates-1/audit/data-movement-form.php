<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	//echo '<pre>';print_r( $data );echo '</pre>';
	
	$params = '';
	if( isset( $data["html_replacement_selector"] ) && $data["html_replacement_selector"] ){
		$params = '&html_replacement_selector=' . $data["html_replacement_selector"];
	}
 ?>
 
 <div class="row">
	<div class="col-md-8 col-md-offset-2">
		<h4><strong>Migrate Data from Old Fields to New</strong></h4><hr />
		<?php if( isset( $data["tables"] ) && is_array( $data["tables"] ) && ! empty( $data["tables"] ) ){ ?>
		<form id="access_role-form" action="?action=audit&todo=move_data_from_old_fields_to_new_background<?php echo $params; ?>" class="activate-ajax" method="post">
		
			<br />
			<div class="row">
				<div class="col-md-6">
					<label>Classes to Migrate</label>
					<?php 
						echo '<select name="tables" multiple class="form-control select2" required>';
						foreach( $data["tables"] as $dbv ){
							echo '<option value="'. $dbv['class'] .'">'. $dbv['label'] .'</option>';
						}
						echo '</select>';
					?>
					<i>data from the selected classes will be migrated and unused fields will be deleted</i>
					<br /><br /><i style="color:#d32111;">NOTE: PLEASE TAKE A BACKUP OF YOUR DATABASE BEFORE PROCEEDING</i>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-12">
					<label><input type="checkbox" class="" name="show_queries_only" /> Show Queries Only</label>
				</div>
			</div>
			<br />
			<input type="submit" class="btn blue" value="Begin Upgrade" />
			
		</form>
		<?php }else{ ?>
		<div class="note note-danger">No Classes Found</div>
		<?php } ?>
	</div>
</div>
</div>