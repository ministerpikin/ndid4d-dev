<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<style type="text/css">
		<?php // if( file_exists( dirname( __FILE__ ).'/jsoneditor.min.css' ) )include "jsoneditor.min.css"; ?>
	</style>
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-offset-3 col-md-6">
				<div class="card">
					<div class="card-body">
						<div class="note note-warning"><h4><strong>Note:</strong></h4>Any existing data in the selected table(s) will be permanently lost</div>
						<form action="?action=nwp_endpoint&todo=execute&nwp_action=react_handler&nwp_todo=save_create_index&html_replacement_selector=ms-table_container" class="activate-ajax" id="form-wrapper" confirm-prompt="Any existing data in the selected table(s) will be permanently lost">
							<?php
								echo get_form_headers( [
									'id' => '',
									'table' => 'endpoint'
								]);
							?>
							<div class="form-group">
								<label for="type">Select Data Source<sup>*</sup></label>
								<?php
									//$data["databases"] = get_database_tables( array( "group_plugin" => 1 ) );
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
							</div>
							<div class="input-group">
								<input type="submit" value="Drop & Re-Create Data Source(s)" class="btn blue">
							</div>
						</form>						
					</div>
				</div>
			</div>
			<br /><br />
			<div class="col-md-offset-3 col-md-6">
				<div class="card">
					<div class="card-body">
						<h4><strong>Refresh Data Source Cache</strong></h4>
						<form action="?action=nwp_endpoint&todo=execute&nwp_action=react_handler&nwp_todo=save_refresh_index&html_replacement_selector=ms-table_container" class="activate-ajax" id="form-wrapper" confirm-prompt="Any existing data in the selected table(s) will be permanently lost">
							<?php
								echo get_form_headers( [
									'id' => '',
									'table' => 'endpoint'
								]);
							?>
							<div class="form-group">
								<label for="type">Select Data Source<sup>*</sup></label>
								<?php
									$arr["nwp_device_management"] = $data["databases"]["nwp_device_management"];
									// echo "<pre>";
									// 	print_r($arr);
									// echo "</pre>";
									if( isset( $arr ) && ! empty( $arr ) ){
										echo '<select name="dtable[]" class="form-control select2" multiple required>';
										foreach( $arr as $dbk1 => $dbv1 ){
											if( isset( $dbv1["classes"] ) && ! empty( $dbv1["classes"] ) ){
												echo '<optgroup label="'. $dbv1["label"] .'">';
												foreach( $dbv1["classes"] as $dbk => $dbv ){
													if( !in_array( $dbk, [ 'device_mgt', 'device_mgt_sr' ] ) ){
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
							</div>
							<div class="input-group">
								<input type="submit" value="Refresh Data Source(s)" class="btn blue">
							</div>
						</form>						
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		(function(){
			$('select[name="type"]').change(function(){
				$('.lb-s').text( $(this).find("option:selected").text() );
				if( $('a[data-name="_file"]').length ){
					$('a[data-name="_file"]').click();
				}
				$('textarea[name="dataupload"]').val("");
			});
		})();
	</script>
</div>