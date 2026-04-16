<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php 
		// echo '<pre>';print_r( $data );echo '</pre>';
		$settings = isset( $data[ 'settings' ] ) ? $data[ 'settings' ] : [];
	?>
	<div id="queue-config-container">

		<form action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=save_bg_settings&html_replacement_selector=queue-config-container" class="activate-ajax">
			
			<div class="row">
				
				<div class="col-md-6 col-offset-md-3">
					
					<?php $key = 'users'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					
					<label for="<?php echo $key; ?>-id" class="form-label">Users</label>

					<?php
						$txt = '';
						if( $val ){
							$fv = explode( ',', $val );
							$txt = implode(',', array_map(function($a){
								return get_name_of_referenced_record( array( 'id' => $a, 'table' => 'users' ) );
							}, $fv));
						}
					?>
					
					<input type="text" name="users" action="?action=users&todo=get_select2" minlength="0" class="form-control select2" tags="true" label="<?php echo $txt; ?>" value="<?php echo $val; ?>">
					
					<br>
					<br>

					<?php $key = 'reports'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					
					<label for="<?php echo $key; ?>-id" class="form-label">Reports <sup>*</sup></label>

					<?php
						if( isset( $data["databases"] ) && ! empty( $data["databases"] ) ){
							echo '<select name="dtable[]" class="form-control select2" multiple required>';
							foreach( $data["databases"] as $dbk1 => $dbv1 ){
								if( isset( $dbv1["classes"] ) && ! empty( $dbv1["classes"] ) ){
									echo '<optgroup label="'. $dbv1["label"] .'">';
									foreach( $dbv1["classes"] as $dbk => $dbv ){
										if( in_array( $dbk, [ 'reports_bay', 'reports_ui' ] ) ){
											continue;
										}
										
										echo '<option value="type='. $dbv1["type"] .':::key='. $dbk1 .':::value='. $dbk .'">'. $dbv .'</option>';
									}
									echo '</optgroup>';
								}
							}
							echo '</select>';
						} 
					?>
										
					<br>
					<br>

					<label for="">Interval: </label>
					<input type="number" name="interval" min="0" max="10">

					<br>
					<br>

					<label for="">Interval: </label>
					<input type="number" name="interval" min="0" max="10">

					<?php $key = 'require_login'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					<label for="<?php echo $key; ?>-id" class="form-label">Require Login</label>
					<div class="form-check form-switch form-switch-leftX form-switch-md" bis_skin_checked="1">
	                    <label class="form-label text-muted"></label>
	                    <input class="form-check-input" type="checkbox" name="<?php echo $key; ?>" id="<?php echo $key; ?>-id">
	                </div>
					<br>

					<div class="">
	                    <button type="submit" class="btn btn-primary">Save Configurations</button>
	                </div>
				</div>

			</div>

		</form>

	</div>

</div>