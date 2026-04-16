<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<style type="text/css">
		.title{
			text-align:center;
			font-size:1.1em;
		}
	</style>
	<?php
		if( isset( $data["items"] ) && is_array( $data["items"] ) && !empty( $data["items"] ) ){
			
			$more_actions = isset( $data[ 'other_params' ]["more_actions"] )?$data[ 'other_params' ]["more_actions"]:'';
				
			$minimal = ( $more_actions )?1:0;
			
			$fields = isset( $data['fields'] )?$data['fields']:array();
			$labels = isset( $data['labels'] )?$data['labels']:array();
			$table = isset( $data["table"] )?$data["table"]:"";
			$user_type = isset( $data[ 'user_type' ] )?$data[ 'user_type' ]:"";

			$user_id = isset( $user_info["user_id"] )?$user_info["user_id"]:"";
			
			$GLOBALS["fields"] = $fields;
			$GLOBALS["labels"] = $labels;
			
			$preview = 0;
			
			$params = '';
			if( isset( $data[ "html_replacement_selector" ] ) && $data[ "html_replacement_selector" ] ){
				$params = '&html_replacement_selector=' . $data[ "html_replacement_selector" ];
			}
			
			$action_to_perform = '';
			if( isset( $data["action_to_perform"] ) && $data["action_to_perform"] ){
				$action_to_perform = $data["action_to_perform"];
			}
			
			$start_survey = isset( $data["start_survey"] )?$data["start_survey"]:0;
			
			$h = '';

			
			if( $more_actions ){
				$h .=  $more_actions;
				$h .= '<br /><br />';
			}
			
			echo $h;
				
			foreach( $data["items"] as $item ){
				?>
				<div id="<?php $container = $table . '-window-'; echo $container; ?>">
					<div class="row">
						<div class="col-md-12">
							<div class="report-table-preview-20 overflow-auto">
								<table class="table table-bordered table-hover" cellspacing="0">
									<tbody>
										<?php 
											$value = '';
											unset( $fields['reference'] );
											unset( $fields['reference_table'] );
											unset( $fields['plugin'] );
											unset( $fields['form'] );
											unset( $fields['data'] );
											if( !get_hyella_development_mode() ){
												unset( $fields['last_run'] );
											}

											foreach( array_keys($fields) as $key ){
												if( isset( $item[ $key ] ) && $item[ $key ] ){?>
													<tr>
														<td class="col-md-4"><strong><?php echo __get_value('', $key, array( 'get_label' => 1 ) ); ?></strong></td>
														<td>
															<!-- <textarea class="form-control" style="height:auto;" readonly> -->
															<?php
																switch( $key ){
																	// case 'last_run':
																	// case 'body':
																	// case 'options':
																	// 	$dd = json_encode(json_decode( $item[ $key ], 1 ), JSON_PRETTY_PRINT );
																	// 	echo '<textarea class="form-control" style="height:10em;" readonly>'; echo $dd ; echo '</textarea>';
																	// break;
																	default:?>
																			<?php echo __get_value( $item[ $key ] , $key ); ?>
																		<?php
																	break;
																} 
															?>
															<!-- </textarea> -->
														</td>
													</tr>
													<?php
												}
											}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
	?>
</div>
