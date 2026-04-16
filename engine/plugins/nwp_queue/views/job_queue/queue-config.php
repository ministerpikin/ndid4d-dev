<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php 
		$settings = isset( $data[ 'settings' ] ) ? $data[ 'settings' ] : [];
		// echo '<pre>';print_r( $settings );echo '</pre>'; 
	?>
	<div id="queue-config-container">
		<div class="row">
			<div class="col-md-6">
				<form method="POST" class="activate-ajax" action="?action=nwp_queue&todo=execute&nwp_action=job_queue&nwp_todo=save_queue_config&html_replacement_selector=queue-config-container">

					<?php $key = 'concurrency'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					<label for="<?php echo $key; ?>-id" class="form-label">Concurrent Background Processes</label>
					<input type="number" min="1" max="10" id="<?php echo $key; ?>-id" name="<?php echo $key; ?>" class="form-control" value="<?php echo $val; ?>" >
					<br>

					<?php $key = 'data_source'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					<label for="<?php echo $key; ?>-id" class="form-label">Data Source<sup>*</sup></label>
					<?php 
						$data["databases"] = get_database_tables( array( "group_plugin" => 1, 'selected_class_only' => ['cNwp_endpoint' => 1, 'cNwp_reports' => 1] ) );
						// echo "<pre>";
						// print_r($data['databases']);
						// echo "</pre>";
						if( isset( $data["databases"] ) && ! empty( $data["databases"] ) ){
							echo '<select name="data_source" class="form-control select2" required>';
							foreach( $data["databases"] as $dbk1 => $dbv1 ){
								if( !in_array( $dbk1, ['*base', 'custom', 'dev'] ) ){
									if( isset( $dbv1["classes"] ) && ! empty( $dbv1["classes"] ) ){
										echo '<optgroup label="'. $dbv1["label"] .'">';
										foreach( $dbv1["classes"] as $dbk => $dbv ){
											
											echo '<option value="type='. $dbv1["type"] .':::key='. $dbk1 .':::value='. $dbk .'">'. $dbv .'</option>';
										}
										echo '</optgroup>';
									}									
								}
							}
							echo '</select>';
						} 
					?>
					<br>
					<br>

					<?php $key = 'method'; $val = isset( $settings[ $key ] ) ? $settings[ $key ] : ''; ?>
					<label for="<?php echo $key; ?>-id" class="form-label">Action to perform</label>
					<div class="row">
						<div class="col-md-11">
							<input type="text" id="<?php echo $key; ?>-id" name="<?php echo $key; ?>" class="form-control select2" tags="true" action="?action=nwp_queue&todo=execute&nwp_action=job_queue&nwp_todo=get_action_select2" minlength="0" data-params='select[name="data_source"]'>
						</div>
						<div class="col-md-1">
							<a href="javascript:void(0);" class="add-btn btn btn-sm btn-secondary"><i class="icon-plus"></i></a>
						</div>						
					</div>
					<br>
					<br>
					<textarea name="actions_to_perform" class="hyella-data"><?php if( isset($settings['action_to_watch']) && $settings['action_to_watch'] ){ echo json_encode( $settings['action_to_watch'] ); } ?></textarea>
					<div class="text-center" bis_skin_checked="1">
	                    <button type="submit" class="btn btn-primary">Save Configurations</button>
	                </div>
				</form>
			</div>
			<div class="col-md-6">
				<div class="actions-table shopping-cart-table">
					<div class="table-responsive">
						<table class="table table-hover table-striped">
							<thead>
								<tr>
									<th><b>Plugin</b></th>
									<th><b>Table</b></th>
									<th><b>Action to perform</b></th>
									<th>&nbsp;</th>									
								</tr>
							</thead>
							<tbody></tbody>
						</table>						
					</div>
				</div>
			</div>	
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			const cCap = a => a.charAt(0).toUpperCase() + a.slice(1);
			const Qt = {
				init(){
					$('.add-btn').click(function(){
						let dt = $('select[name="data_source"]').val();
						let ac = $('input[name="method"]').val();
						if( !ac ){
							return $.fn.cProcessForm.display_notification({ typ: 'jsuerror', err: 'Invalid Selection', msg: 'Kindly select a valid item' });
						}
						if( dt ){
							let jt = $('textarea[name="actions_to_perform"]').val();
							jt = jt ? JSON.parse(jt) : {}
							jt[ dt ] = dt + ':::action=' + ac;
							$('textarea[name="actions_to_perform"]').val(JSON.stringify(jt));
							Qt.refreshSettings();
						}
					});

					$('select[name="data_source"]').change(function(){
						$('input[name="method"]').select2('data', null);
						$('input[name="method"]').trigger('change');
					});

					$('body').on('click', '.del-qt', function(){
						let jt = $('textarea[name="actions_to_perform"]').val();
						jt = jt ? JSON.parse(jt) : {}
						let id = $(this).attr('data-ref');
						if( jt && typeof jt[ id ] !== 'undefined' ){
							delete jt[ id ];
							$('textarea[name="actions_to_perform"]').val(JSON.stringify(jt));
						}
						Qt.refreshSettings();
					})

					Qt.refreshSettings();
				},
				refreshSettings(){
					let jt = $('textarea[name="actions_to_perform"]').val();
					jt = jt ? JSON.parse(jt) : {}
					let tbl = $('.actions-table tbody');
					tbl.html('');
					if( jt ){
						let _html = '';
						$.each(jt, (k, v) => {
							let tba = {};
							dt = v.split(':::');
							dt.forEach(a => {
								tba2 = a.split('=');
								if( typeof tba2[1] !== 'undefined' ){
									tba[ tba2[0] ] = tba2[1];
								}
							});
							if( tba ){
								let okys = Object.keys(tba);
								if( okys.includes('key') && okys.includes('value') && okys.includes('action') ){
									_html += '<tr id="row-'+ k +'">';
										_html += '<td>' + cCap( tba['key'] ) +  '</td>';
										_html += '<td>' + cCap( tba['value'] ) +  '</td>';
										_html += '<td>' + tba['action'] +  '</td>';
										_html += '<td><a class="del-qt btn btn-warning" data-ref="'+ k +'" href="javascript:void(0);"><i class="icon-trash"></i></a></td>';
									_html += '</tr>';									
								}else{
									return $.fn.cProcessForm.display_notification({ typ: 'jsuerror', err: 'Error Parsing Input', msg: 'Kindly contact Technical Team' });
								}
							}
						});
						if( _html ){
							tbl.html(_html);
						}
					}
				}
			}

			Qt.init();
		});
	</script>
</div>