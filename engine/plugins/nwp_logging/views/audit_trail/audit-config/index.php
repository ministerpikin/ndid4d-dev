<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php 
		$settings = isset( $data[ 'settings' ] ) ? $data[ 'settings' ] : [];
		// echo '<pre>';print_r( $settings );echo '</pre>'; 
	?>
	<div id="audit-config-container">
		<form method="POST" class="activate-ajax" action="?action=nwp_logging&todo=execute&nwp_action=audit_trail&nwp_todo=save_audit_config&html_replacement_selector=audit-config-container">
			<div class="row">
				<div class="col-md-6 col-offset-md-3">

					<?php $key = 'rention_period'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					<label for="<?php echo $key; ?>-id" class="form-label">Rentention Period (in hrs)</label>
					<input type="decimal" id="<?php echo $key; ?>-id" name="<?php echo $key; ?>" class="form-control" value="<?php echo $val; ?>" >
					<br>

					<?php $key = 'push_to_es'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					<label for="<?php echo $key; ?>-id" class="form-label">Push to Elastic Search?</label>
					<div class="form-check form-switch form-switch-leftX form-switch-md" bis_skin_checked="1">
	                    <label class="form-label text-muted"></label>
	                    <input class="form-check-input" type="checkbox" name="<?php echo $key; ?>" id="<?php echo $key; ?>-id">
	                </div>
					<br>

					<?php $key = 'zip_path'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					<label for="<?php echo $key; ?>-id" class="form-label">Zip Archive Path</label>
					<input type="text" id="<?php echo $key; ?>-id" name="<?php echo $key; ?>" class="form-control" value="<?php echo $val; ?>" >
					<br>

					<?php $key = 'audit_backup'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					<label for="<?php echo $key; ?>-id" class="form-label">Audit Trail Sr Backup</label>
					<div class="form-check form-switch form-switch-leftX form-switch-md" bis_skin_checked="1">
	                    <label class="form-label text-muted"></label>
	                    <input class="form-check-input" type="checkbox" name="<?php echo $key; ?>" id="<?php echo $key; ?>-id">
	                </div>
					<br>

					<?php $key = 'audit_backup_retention'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					<label for="<?php echo $key; ?>-id" class="form-label">Audit Trail Sr Backup Retention (in hrs)</label>
					<input type="number" id="<?php echo $key; ?>-id" name="<?php echo $key; ?>" class="form-control" value="<?php echo $val; ?>" >
					<br>

					<div class="text-end" bis_skin_checked="1">
	                    <button type="submit" class="btn btn-primary">Save Configurations</button>
	                </div>

				</div>	
			</div>
		</form>
	</div>
	<script type="text/javascript" >
		var dd = <?php echo json_encode( $settings ); ?>;
		var key;

		key = 'push_to_es';
		if( dd && dd[ key ] && dd[ key ] == 'on' ){
			$('#'+ key +'-id').prop("checked", true);
		}

		key = 'audit_backup';
		if( dd && dd[ key ] && dd[ key ] == 'on' ){
			$('#'+ key +'-id').prop("checked", true);
		}
	</script>
</div>