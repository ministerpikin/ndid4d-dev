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
		<h4><strong>Upgrade Database</strong></h4><hr />
		<?php if( isset( $data["current_database"] ) ){ ?>
		<form id="access_role-form" action="?action=audit&todo=upgrade_database_from_database<?php echo $params; ?>" class="activate-ajax" method="post">
			<?php if( get_hyella_development_mode() ){ ?>
			<br />
			<div class="row">
				<div class="col-md-6">
					<label>Current Database</label>
					<input type="text" disabled value="<?php echo $data["current_database"]; ?>" class="form-control" />
					<i>this database will be upgraded</i>
				</div>
			</div>
			<?php } ?>
			<br />
			<div class="row">
				<div class="col-md-6">
					<label>New Database</label>
					<?php if( isset( $data["databases"] ) && ! empty( $data["databases"] ) ){
						echo '<select name="new_database" class="form-control select2" required>';
						foreach( $data["databases"] as $dbv ){
							if( $data["current_database"] == $dbv['schema_name'] ){
								continue;
							}
							$sel = '';
							if( 'vine_cloud' == $dbv['schema_name'] ){
								$sel = ' selected ';
							}
							echo '<option '.$sel.' value="'. $dbv['schema_name'] .'">'. $dbv['schema_name'] .'</option>';
						}
						echo '</select>';
					}else{ ?>
					<input type="text" name="new_database" value="" class="form-control" required />
					<?php } ?>
					<i>this database will be used to upgrade the current database</i>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-6">
					<label>Tables to Upgrade</label>
					<?php 
					$data["databases"] = get_database_tables( array( "group_plugin" => 1 ) );
					if( isset( $data["databases"] ) && ! empty( $data["databases"] ) ){
						echo '<select name="utables[]" class="form-control select2" multiple>';
						foreach( $data["databases"] as $dbk1 => $dbv1 ){
							if( isset( $dbv1["classes"] ) && ! empty( $dbv1["classes"] ) ){
								echo '<optgroup label="'. $dbv1["label"] .'">';
								foreach( $dbv1["classes"] as $dbk => $dbv ){
									
									echo '<option value="type='. $dbv1["type"] .':::key='. $dbk1 .':::value='. $dbk .'">'. $dbv .'</option>';
								}
								echo '</optgroup>';
							}
						}
						echo '</select>';
					} 
					?>
					<i>leave blank to upgrade all tables</i>
				</div>
			</div>
			<br />
			<input type="submit" class="btn blue" value="Begin Upgrade" />
			
		</form>
		<?php }else{ ?>
		<div class="note note-danger">No Database Found</div>
		<?php } ?>
	</div>
</div>
</div>