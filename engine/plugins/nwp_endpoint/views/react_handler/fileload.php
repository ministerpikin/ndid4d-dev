<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<style type="text/css">
		<?php // if( file_exists( dirname( __FILE__ ).'/jsoneditor.min.css' ) )include "jsoneditor.min.css"; ?>
	</style>
	<div class="row">
		<div class="col-md-5">
			<div class="card">
				<!--<div class="card-header">
					<h4>LOAD DATA</h4>
				</div>-->
				<div class="card-body" id="bulk-upload-form-container">
					<form action="?action=nwp_endpoint&todo=execute&nwp_action=endpoint&nwp_todo=save_dataload&html_replacement_selector=bulk-upload-form-container" class="activate-ajax" id="form-wrapper">
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
									echo '<select name="dtable" class="form-control select2" required>';
										echo '<option></option>';
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
						<div class="form-group" id="indicator-form-group">
							<label for="type">Indicator<sup>*</sup></label>
							<select required name="aData[indicator]" class="form-control select2" id="aData_indicator">
								<?php
									$sc = get_report_indicators();
									if( $sc ){
										foreach ($sc as $key => $value) { ?>
											<option value="<?php echo $key; ?>"><?php echo  $value; ?></option>
										<?php }
									}else{ ?>
										<option value="">Access Denied</option>
									<?php }
								?>
							</select>
						</div>

						<div class="form-group">
							<label for="type">Type<sup>*</sup></label>
							<select name="type" class="form-control" id="type">
								<?php
									$sc = get_supported_load_type();
									$first = '';
									if( $sc ){
										foreach ($sc as $key => $value) { 
											if( ! $first ){
												$first = $value;
											}	
										?>
											<option value="<?php echo $key; ?>"><?php echo  $value; ?></option>
										<?php }
									}
								?>
							</select>
						</div>

						<?php if( defined("NWP_SUPPORT_FILE_UPLOAD_TEXTAREA") && NWP_SUPPORT_FILE_UPLOAD_TEXTAREA ){ ?>
						<div class="form-group">
							<label for="db_name"><span class="lb-s"><?php echo $first; ?></span></label>
							<textarea class="form-control" rows="20" cols="30" name="dataupload" id=""></textarea>
						</div>

						<p class="text-center"> --- OR --- </p>
						<?php } ?>

						<div class="form-group">
							<label for="db_name">File Upload<sup>*</sup></label>
							<?php echo get_file_upload_form_field( array( "field_id" => "_file", "t" => 1, "attributes" => ' skip-uploaded-file-display="1" ', 'acceptable_files_format' => implode(":::", array_keys( $sc ) ) ) ); ?>
							<i>Accepted File Format: <span class="lb-s"><?php echo implode(", ", $sc ); ?></span></i>
						</div>

						<div class="input-group">
							<input type="submit" value="Upload" class="btn blue">
						</div>
					</form>						
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<div id="import-tutorial-view"></div>
		</div>
	</div>
	<script>
		var nwReloadTableFunction = function( v ){
			$.fn.cProcessForm.ajax_data = {
				ajax_data: {table: v},
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: '?action=nwp_endpoint&todo=execute&nwp_action=react_handler&nwp_todo=import_csv_tutorial_view&html_replacement_selector=import-tutorial-view',
			};
			$.fn.cProcessForm.ajax_send();
		}

		$(function(){

			$('#indicator-form-group').hide();
			$('#indicator-form-group select').prop('required', false);

			$('select[name="type"]').change(function(){
				$('.lb-s').text( $(this).find("option:selected").text() );
				if( $('a[data-name="_file"]').length ){
					$('a[data-name="_file"]').click();
				}
				$('textarea[name="dataupload"]').val("");
			});

			$('[name="dtable"').change(function(){
				let v = $(this).val();
				if( v ){
					switch( v ){
						case 'type=plugin:::key=nwp_reports:::value=indicators':
							$('#indicator-form-group').show();
							$('#indicator-form-group select').prop('required', true);
						break;
						default:
							$('#indicator-form-group').hide();
							$('#indicator-form-group select').prop('required', false);
						break;
					}

					nwReloadTableFunction( v );
				}
			}).change();
		});
	</script>
</div>