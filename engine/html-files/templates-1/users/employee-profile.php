<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$pr = get_project_data();
	if( isset( $data["event"]["id"] ) && $data["event"]["id"] && isset( $data[ 'labels' ] ) && isset( $data[ 'fields' ] ) ){
		$GLOBALS["labels"] = $data[ 'labels' ];
		$GLOBALS["fields"] = $data[ 'fields' ];
		$e = $data["event"];
?>
<div >
<div class="row">
	<div class="col-md-12">
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-4">
					<div style="border:1px solid #ddd; padding:5px; margin:5px; margin-right:0; margin-left:0;">
					<?php 
						$img = '';
						$img_url = '';
						if( $e["photograph"] ){
							$img = $e["photograph"];
							$img_url = $pr["domain_name"] . $img;
						}
						
						if( $img_url ){
						?>
						<a href="<?php echo $img_url; ?>" target="_blank" title="Click to Enlarge"><img src="<?php echo $img_url; ?>" style="width:100%;" alt="<?php $key = "name"; if( isset( $e[ $key ] ) )echo $e[ $key ]; ?> Picture" /></a>
						<?php } ?>
						
						<div class="row">
							<div class="col-md-12">
								<a href="#" class="custom-single-selected-record-button btn btn-block1 btn-sm red" override-selected-record="<?php echo $e["id"]; ?>" action="?module=&action=<?php echo $data["table"]; ?>&todo=load_image_capture" mod="edit-<?php echo md5( $data["table"] ); ?>" title="Capture Employee Image with Webcam">Capture Image</a>
								<a href="#" class="custom-single-selected-record-button btn btn-block1 btn-sm red" override-selected-record="<?php echo $e["id"]; ?>" action="?module=&action=<?php echo $data["table"]; ?>&todo=edit_popup_form" mod="edit-<?php echo md5( $data["table"] ); ?>" title="Edit Employee Details">Edit</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div id="capture-container"></div>
					<h4 style="margin-top1:15px;"><strong><?php $key = "firstname"; if( isset( $e[ $key ] ) )echo $e[ $key ] .' '. $e[ 'lastname' ]; ?><?php $key1 = "other_names"; $key = "lastname"; if( isset( $e[ $key1 ] ) && $e[ $key1 ] )echo ', ' . $e[ $key1 ]; ?></strong> <small>(<strong><?php $key = "ref_no"; if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong> - <?php $key = "category"; if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?>)</small></h4>
					<div class="shopping-cart-table">
					<table class="table table-striped table-bordered">
						<tr>
							<td><?php $key = "status";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong> <a href="#" class="pull-right custom-single-selected-record-button btn btn-xs red hidden-print" action="?module=&action=<?php echo $data["table"]; ?>&todo=edit_popup_form_status" override-selected-record="<?php echo $e["id"]; ?>" mod="edit-<?php echo md5( $data["table"] ); ?>"><i class="icon-edit"> </i></a></td>
						</tr>
						<tr>
							<td><?php $key = "phone_number";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "email";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "address";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "date_of_birth";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); echo " | " . get_age( $e[ $key ] ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "sex";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
					</table>
					</div>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-6">
					<div class="shopping-cart-table">
					<table class="table table-striped table-bordered">
						<tr>
							<td colspan="2"><strong><?php echo strtoupper( "PERSONAL DETAILS" ); ?></strong></td>
						</tr>
						<tr>
							<td>Number of Dependents</td>
							<td><strong><?php if( isset( $e["number_of_dependents"] ) && $e["number_of_dependents"] )echo $e["number_of_dependents"]; ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "qualification";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><?php if( isset( $e[ $key ] ) ){ ?>
							<strong><a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $e[ $key ]; ?>" action="?module=&action=users_educational_history&todo=view_details" ><?php echo __get_value( $e[ $key ], $key ); ?></a></strong>
							<?php } ?>
							</td>
						</tr>
						<tr>
							<td><?php $key = "nationality";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "state";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "city";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "ref_no";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "serial_number";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "file_number";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "means_of_identification";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "identification_number";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "status";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "reason";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
					</table>
					</div>
				</div>
				<div class="col-md-6">
					<div class="shopping-cart-table">
					<table class="table table-striped table-bordered">
						<tr>
							<td colspan="2"><strong><?php echo strtoupper( "WORK DETAILS" ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "type";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "rank";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><?php if( isset( $e[ $key ] ) ){ ?>
							<strong><a href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $e[ $key ]; ?>" action="?module=&action=users_current_work_history&todo=view_details" ><?php echo __get_value( $e[ $key ], $key ); ?></a></strong>
							<?php } ?>
							</td>
						</tr>
						<tr>
							<td><?php $key = "department";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "division";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) )echo __get_value( $e[ $key ], $key ); ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "grade_level";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "date_employed";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); echo " | " . get_age( $e[ $key ] ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "date_of_confirmation";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); echo " | " . get_age( $e[ $key ] ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "bank_name";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "pfa";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "tax_office_location";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "housing_scheme";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
						<tr>
							<td><?php $key = "health_insurance";  echo __get_value( '', $key, array( "get_label" => 1 ) ); ?></td>
							<td><strong><?php if( isset( $e[ $key ] ) ){ echo __get_value( $e[ $key ], $key ); } ?></strong></td>
						</tr>
					</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<?php 
				$key = "date_of_birth";
				if( isset( $e[ $key ] ) && $e[ $key ] ){
					$x = doubleval( $e[ $key ] );
					$oneday = 3600 * 24;
					
					$current_date = mktime( 0, 0, 0, date("m"), date("j"), date("Y") ) + ( $oneday );
					$birth_time = mktime( 0, 0, 0, date("m", $x ), date("j", $x ), date("Y") );
					if( $birth_time > $current_date ){
						?>
						<div class="note note-info">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<h5><strong><?php echo time_passed_since_action_occurred( $birth_time - $current_date, 0 ); ?></strong> to Birth Day</h5>
							<p>Birth Day is on the <strong><?php echo date( "jS F, Y", $birth_time ); ?></strong></p>
						</div>
						<?php
					}elseif( ( $birth_time + $oneday ) == $current_date ){
						?>
						<div class="note note-success">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<h5>Today is my <strong>Birth Day</strong></h5>
							<p>I was born on <strong><?php echo date( "jS F" ); ?></strong></p>
						</div>
						<?php
					}
				} 
				?>
		</div>
	</div>
</div>
</div>
<?php } ?>
<?php //print_r( $data["event"] ); ?>
<?php //print_r( $user_info ); ?>
</div>