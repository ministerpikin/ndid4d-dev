<div <?php set_hyella_source_path( __FILE__, 1 ); ?> id="rev-history-con">
<?php 

	$action_to_perform = isset( $data[ 'action_to_perform' ] ) ? $data[ 'action_to_perform' ] : '';
	$type = isset( $data[ 'type' ] ) ? $data[ 'type' ] : '';
	$title = isset( $data[ 'title' ] ) ? $data[ 'title' ] : '';

	$start_date = isset( $data[ 'start_date' ] ) ? $data[ 'start_date' ] : '';
	$end_date = isset( $data[ 'end_date' ] ) ? $data[ 'end_date' ] : '';

	$d = isset( $data[ 'data' ] ) ? $data[ 'data' ] : array();
	$total = array();

	$table = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';

	$today = date("U");
	$colors = array( 'bg-success text-white', 'bg-primary text-white', 'bg-dark', 'bg-warning', 'bg-danger text-white' );

	$show_tabs = 0;
	$sn = 0;
	$class = 4;

	$parent = '';
	switch( $type ){
		case 'department':
			if( isset( $data[ 'chart_data' ] ) && ! empty( $data[ 'chart_data' ] ) ){
				$show_tabs = 1;

				$total_wards = 0;
				$total_patients = 0;
			}
		break;
	}
	// echo '<pre>';print_r( $data );echo '</pre>'; 
	// echo '<pre>';print_r( $d );echo '</pre>'; 
	// echo '<pre>';print_r( $total );echo '</pre>'; 
?>
    
	<form class="activate-ajax" method="post" action="?action=nwp_logging&todo=execute&nwp_action=audit_trail&nwp_todo=filter_manage_data_access_view&html_replacement_selector=aud-table" style="position:relative;">
		<div class="row">
			<div class="col-md-2">
				<label>Table</label>
				<?php
					$opts = get_audit_trail_tables();
					if( $opts ){ ?>
						<select name="table" class="form-control select2">
							<?php
								foreach ($opts as $key => $value) {?>
									<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
								<?php }
							?>
						</select>
					<?php }
				?>

			</div>
			<div class="col-md-2">
				<label>User</label>
				<div>
				<div class="calculated-item-con">
					<div>
						<input class="form-control select2 allow-clear" minlength="0" name="user" action="?action=users&todo=get_select2"  id="filter-users"/>
					</div>
				</div>
				</div>
			</div>
			<div class="col-md-2">
				<label>Start Date<!--  <sup>*</sup> --></label>
				<input type="datetime-local" name="start_date" class="form-control" value="<?php echo $start_date; ?>" />
			</div>
			<div class="col-md-2">
				<label>End Date<!--  <sup>*</sup> --></label>
				<input type="datetime-local" name="end_date" class="form-control" value="<?php echo $end_date; ?>" />
			</div>
			<div class="col-md-1">
				<label>&nbsp;</label>
				<input type="submit" value="Submit &rarr;" class="btn blue form-control" />
			</div>
		</div>
	</form>
	<br />
	<br />
	
	<div class="row" >
		<div class="col-md-12" id="aud-table">
			<br />
			<div class="note note-info"><h4 class="text-center">Click On the Submit Button to Filter Log</h4></div>
			<br />
		</div>
	</div>

</div>