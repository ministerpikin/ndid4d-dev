<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php
		$version = defined( 'HYELLA_THEME' ) && HYELLA_THEME ? HYELLA_THEME : 'v1';

		$show_update = 0;
		$action_to_perform = isset( $data[ "action_to_perform" ] ) ? $data[ "action_to_perform" ] : '';

		switch( $action_to_perform ){
		case 'bulk_edit':
			$show_update = 1;
		break;
		}

		$hide_json = 0;
		if( defined( 'HYELLA_V3_HIDE_ADVANCED_QUERY_JSON' ) && HYELLA_V3_HIDE_ADVANCED_QUERY_JSON ){
			$hide_json = 1;
		}

		$show_save_options = [];
		if( isset( $data[ 'show_save_options' ] ) && $data[ 'show_save_options' ] ){
			$show_save_options = $data[ 'show_save_options' ];
		}

		$single_table = 0;
		$tables = isset( $data[ "tables" ] ) ? $data[ "tables" ] : array();
		if( count( $tables ) == 1 ){
			$single_table = 1;
		}
		$dd = isset( $data[ 'cart_data' ] ) ? $data[ 'cart_data' ] : array();
		$osearch = array();
		if( isset( $data[ 'old_search' ] ) && $data[ 'old_search' ] ){
			$osearch = $data[ 'old_search' ];
		}

		// echo '<pre>'; print_r( $data ); echo '</pre>';

		if( ! isset( $dd ) && isset( $osearch[ 'cart_items' ] ) && $osearch[ 'cart_items' ] ){
			$dd[ 'cart_items' ] = $osearch[ 'cart_items' ];
		}
		if( ! isset( $dd ) && isset( $osearch[ 'conditions' ] ) && $osearch[ 'conditions' ] ){
			$dd[ 'conditions' ] = $osearch[ 'conditions' ];
		}
		if( ! isset( $dd[ 'query' ] ) && isset( $osearch[ 'query' ] ) && $osearch[ 'query' ] ){
			$dd[ 'query' ] = $osearch[ 'query' ];
		}
		
		if( isset( $osearch['report_limit'] ) && $osearch['report_limit'] ){
			$dd['report_limit'] = $osearch['report_limit'];
		}

		$preview_results = 0;
		if( isset( $data[ "preview_results" ] ) && $data[ "preview_results" ] ){
			$preview_results = 1;
		}

		$plugin = isset( $data[ 'plugin' ] ) ? $data[ 'plugin' ] : '';
		$additional_info = isset( $data[ 'additional_info' ] ) ? $data[ 'additional_info' ] : array();
		$parent_table = isset( $data[ "parent_table" ] ) ? $data[ "parent_table" ] : '';

		$tdod = 'run_search_query';
		if( isset( $data[ "preview_results" ] ) && $data[ "preview_results" ] ){
			$preview_results = 1;
			$tdod = 'run_search_query2';
			if( ! isset( $data[ 'params' ] ) ){
				$data[ 'params' ] = '';
			}
			$data[ 'params' ] .= '&html_replacement_selector=query-result';
		}

		$action = '?action=' . ( isset( $data[ 'action' ] ) ? $data[ 'action' ] : 'search' );
		$action .= '&todo=' . ( isset( $data[ 'todo' ] ) ? $data[ 'todo' ] : $tdod );
		$action .= isset( $data[ 'params' ] ) ? $data[ 'params' ] : '';
		$action .= '&set_cache_query=1';

		$tabs = isset( $data[ 'tabs' ] ) ? $data[ 'tabs' ] : array();
		$add_system_fields = isset( $data[ 'add_system_fields' ] ) ? $data[ 'add_system_fields' ] : 0;
		$add_select_fields = isset( $data[ 'add_select_fields' ] ) ? $data[ 'add_select_fields' ] : 0;
		$update_form_id = 'update-value-form';
		
		$query_builder_only = isset( $data[ 'query_builder' ] ) ? $data[ 'query_builder' ] : '';

	?>
	<div style="display:none;">
		<select id="select-condition">
			<?php
				$a = array( "AND" => "AND", "OR" => "OR" );
				foreach( $a as $key => $value ){
					echo '<option value="'. $key .'" >'. $value .'</option>';
				}
			?>
		</select>

		<div id="value-container-yes-no">
			<label>Value <sup>*</sup></label>
			<select class="form-control" required="required" type="text" name="text" type2="search_value">
				<?php
					$a = array( "yes" => "Yes", "no" => "No" );
					foreach( $a as $key => $value ){
						echo '<option value="'. $key .'" >'. $value .'</option>';
					}
				?>
			</select>
		</div>
		
		<div id="value-container-default">
			<label>Value <sup>*</sup></label>
			<input class="form-control" required="required" type="text" name="text" type2="search_value" />
		</div>
		<div id="value-container-text">
			<label>Value <sup>*</sup></label>
			<textarea class="form-control" required="required" type="text" name="text" type2="search_value" ></textarea>
		</div>
		<div id="value-container-date-5">
			
			<label>Start Date <sup>*</sup></label>
			<input class="form-control" requiredX="required" type="date" value="<?php echo date("Y-m-d"); ?>" name="start_date" type2="search_value" />
		
			<br />
		
			<label>End Date <sup>*</sup></label>
			<input class="form-control" requiredX="required" type="date" value="<?php echo date("Y-m-t"); ?>" name="end_date" type2="search_value" />
				
		</div>
		<div id="value-container-date-relative">
			
			<div class="row">
				<div class="col-md-6">
					<label>From Type</label>
					<select class="form-control" type="text" name="from_type" type2="search_value">
						<option value=""></option>
						<option value="y"> Years </option>
						<option value="M"> Months </option>
						<option value="w"> Weeks </option>
						<option value="d"> Days </option>
						<option value="h"> Hours </option>
						<option value="H"> Hours </option>
						<option value="m"> Minutes </option>
						<option value="s"> Seconds </option>
						<option></option>
					</select>
				</div>
				<div class="col-md-6">
					<label>From Value</label>
						<input class="form-control" type="number" value="" name="from_value" type2="search_value" />
				</div>
			</div>
		
			<br />
			
			<div class="row">
				<div class="col-md-6">
					<label>To Type</label>
					<select class="form-control" type="text" name="to_type" type2="search_value">
						<option value=""></option>
						<option value="y"> Years </option>
						<option value="M"> Months </option>
						<option value="w"> Weeks </option>
						<option value="d"> Days </option>
						<option value="h"> Hours </option>
						<option value="H"> Hours </option>
						<option value="m"> Minutes </option>
						<option value="s"> Seconds </option>
						<option></option>
					</select>
				</div>
				<div class="col-md-6">
					<label>To Value</label>
						<input class="form-control" type="number" value="" name="to_value" type2="search_value" />
				</div>
			</div>
			
			<br>
			<div class="row">
				<div class="col-md-6">

					<label class="checkbox-inlinex" style="margin-right:15px;">
						<input type="checkbox" id="checkbox-invert" value="invert" class="form-controlX" name="invert" value="" onchange=""> <small>Invert</small>
					</label>

				</div>
			</div>
			
		</div>
		<div id="value-container-date-5-update-value-form">

			<label>Date <sup>*</sup></label>
			<input class="form-control" required="required" type="date" value="<?php echo date("Y-m-d"); ?>" name="start_date" type2="search_value" />
		
		</div>
		<div id="value-container-number">
			<div class="row">
				<div class="col-md-6">
					<label>Min<sup>*</sup></label>
					<input class="form-control" required="required" type="number" step="1" min="0" name="min" type2="search_value" />
				</div>
				<div class="col-md-6">
					<label>Max <sup>*</sup></label>
					<input class="form-control" required="required" type="number" step="1" min="0" name="max" type2="search_value" />
				</div>
			</div>
		</div>
		<div id="value-container-number-update-value-form">
			<div class="row">
				<div class="col-md-6">
					<label>Min<sup>*</sup></label>
					<input class="form-control" required="required" type="number" step="1" min="0" name="min" type2="search_value" />
				</div>
			</div>
		</div>
		<div id="value-container-decimal">
			<div class="row">
				<div class="col-md-6">
					<label>Min<sup>*</sup></label>
					<input class="form-control" required="required" type="number" step="any" name="min" type2="search_value" />
				</div>
				<div class="col-md-6">
					<label>Max<sup>*</sup></label>
					<input class="form-control" required="required" type="number" step="any" name="max" type2="search_value" />
				</div>
			</div>
		</div>
		<div id="value-container-select2">
			<label>Values </label>
			<input class="form-control select2-2 add-attr" setattribute type="text" name="options" type2="search_value" />
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?php 
				if( $preview_results ){
			?>
			<div class="tabbable tabbable-custom" id="transaction-tabs">
				<ul class="nav nav-tabs nav-border-top nav-border-top-primary mb-3" id="idkk" role="tablist">
					<?php
					if( ! empty( $tabs ) ){
						$tabs = $tabs;
					}else{
						$tabs = array(
							'create-query' => 'Build Search Query',
							'query-result' => 'Preview Results',
							'create-mine' => 'Download CSV',
						);
					}

					$sn = 0;
					foreach( $tabs as $tbx => $tbs ){ 
						$active = '';
						// echo '<pre>'; print_r( key($tabs) ); echo '</pre>';
						if( ! $sn ){
							$active = 'active';
						}
					?>
					   <li role="presentation" class=" nav-item">
				   			<a aria-selected="<?php echo $active ? 'true' : 'false'; ?>" role="tab" data-bs-toggle="tab" class="nav-link <?php echo $active; ?> " data-toggle="tab" id="tab-<?php echo $tbx; ?>" <?php echo $active ? '' : 'tabindex="-1"'; ?> href="#<?php echo $tbx; ?>"><?php echo ++$sn . '. ' .$tbs; ?></a>
					   </li>
					<?php } ?>
				</ul>
				<div class="tab-content resizable-height" style="overflow-y:auto; overflow-x:hidden;">
					
					<?php 
						$sn = 0;
						foreach( $tabs as $tbx => $tbs ){ 
							$active = '';
							// echo '<pre>'; print_r( key($tabs) ); echo '</pre>';
							if( ! $sn++ ){
								$active = 'active';
							}
						?>
						<div class="tab-pane <?php echo $active; ?>" id="<?php echo $tbx; ?>">
							<?php 
							switch( $tbx ){
							case 'create-query':
								include "build_query.php"; 
							break;
							default:
								echo '<div class="note note-warning">Perform the previous action first</div>';
							break;
							}
							?>
						</div>
					<?php } ?>

				</div>
			</div>
			<?php }else{
					include "build_query.php"; 
				}
			?>
		</div>
	</div>

	<br />
	<br />
</div>
<script type="text/javascript" >
	var theme_version = "<?php echo $version; ?>";
	var add_system_fields = "<?php echo $add_system_fields; ?>";
	var g_site_url = "<?php echo $site_url; ?>";
	var dd = <?php echo ! empty( $dd ) ? json_encode( $dd ) : "''"; ?>;
	var tables = <?php echo json_encode( $tables ); ?>;
	var parent_table = '<?php echo $parent_table; ?>';
	var plugin = "<?php echo $plugin; ?>";
	var additional_info = <?php echo json_encode( $additional_info ); ?>;
	var show_update = '<?php echo $show_update; ?>';
	var update_form_id = '<?php echo $update_form_id; ?>';
	// console.log( dd )
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
	<?php 
		if( $preview_results ){
			if( file_exists( dirname( __FILE__ ).'/benefit-program-script.js' ) ){
				include "benefit-program-script.js"; 
			}
		}
	?>
</script>