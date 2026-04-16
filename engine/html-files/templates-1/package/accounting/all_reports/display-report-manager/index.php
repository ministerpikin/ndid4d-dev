<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div style="margin:30px;">
<?php 
	$st1 = get_current_store(1);
	$st2 = get_name_of_referenced_record( array( "id" => $st1, "table" => "stores" ) );
?>
<form class="activate-ajax" method="post" action="?action=all_reports&todo=generate_report&html_replacement_selector=general-ledger-report-container">
	
	<div class="row">
		<div class="col-md-4">
			<div class="input-group">
			 <span class="input-group-addon" style=" line-height: 1.5;">Report Type</span>
			 <select class="form-control" name="report_type" required="required" >
				<option value="">--Select--</option>
				<?php
					//$pm = get_cooperative_report_types();
					$pm = get_cooperative_report_types_grouped();
					if( isset( $pm ) && is_array( $pm ) ){
						foreach( $pm as $k1 => $v1 ){
							?><optgroup label="<?php echo $k1; ?>"><?php
							foreach( $v1 as $key => $val ){
							?>
							<option value="<?php echo $key; ?>">
								<?php echo $val; ?>
							</option>
							<?php
							}
							?></optgroup><?php
						}
					}
				?>
			 </select>
			</div>
		</div>
		
		<div class="col-md-2">
			<div class="input-group">
			 <span class="input-group-addon" style=" line-height: 1.5;">Start Date</span>
			 <input class="form-control" name="start_date" required="required" type="date" value="<?php echo date("Y-m-01"); ?>" />
			</div>
		</div>
		<div class="col-md-2">
			
			<div class="input-group">
			 <span class="input-group-addon" style=" line-height: 1.5;">End Date</span>
			 <input class="form-control" name="end_date" required="required" type="date" value="<?php echo date("Y-m-d"); ?>" />
			</div>
			
		</div>
		<div class="col-md-2">
			
			<div class="input-group">
			 <span class="input-group-addon" style=" line-height: 1.5;">Store</span>
			 <input class="form-control select2" name="store" required="required" type="text" value="<?php echo $st1; ?>" label="<?php echo $st2; ?>" action="?action=stores&todo=get_select2" />
			</div>
			
		</div>
		<div class="col-md-2">
			<input type="submit" value="Generate Report" class="btn btn-block green" />
		</div>
	</div>
	<br />
	<div class="row">
		
		<div class="col-md-2 sub-filter sub-filter-show-display-option" style="display:none;">
			<label>Display Option</label>
			 <select name="display_option" class="form-control ">
				<option value="">Default</option>
				<option value="show_details">Show Details</option>
				<option value="show_summary">Show Summary</option>
				<option value="show_summary_payment_method">Show Summary (Payment Method)</option>
			 </select>
		</div>
		
		<div class="col-md-2 sub-filter sub-filter-show-account-types" style="display:none;" >
			<label>Scheme</label>
			<input type="text" name="scheme" id="f-scheme" value="" class="form-control select2 allow-clear" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;" action="?action=loan_types&todo=get_select2&type=loan" minlength="0" />
		</div>
		<div class="col-md-2 sub-filter sub-filter-show-estate-types" style="display:none;">
			<label>Estate</label>
			<input type="text" name="estate" id="f-estate" value="" class="form-control select2 allow-clear" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;" action="?action=banks&todo=get_select2&type=estate&hide_type=1" minlength="0" />
		</div>
		
		<div class="col-md-2 sub-filter sub-filter-show-contribution-types" style="display:none;" >
			<label>Source of Funds</label>
			<input type="text" name="source_of_fund"  value="" class="form-control select2 allow-clearX" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;" action="?action=loan_types&todo=get_select2&type=withdrawal" minlength="0" />
		</div>
		<div class="col-md-2 sub-filter sub-filter-show-contribution-types" style="display:none;">
			<label>Display Option</label>
			 <select name="display_option2" class="form-control ">
				<option value="">All</option>
				<option value="zero">Zero Balance</option>
				<option value="negative">Negative Balance</option>
				<option value="positive">Positive Balance</option>
			 </select>
		</div>
		<div class="col-md-2 sub-filter sub-filter-show-status-types" style="display:none;">
			<label>Status</label>
			<select name="status[]" class="form-control select2" multiple>
				<?php
					$v = get_loan_application_status();
					
					foreach( $v as $key => $val ){
						$sel = '';
					?>
					<option value="<?php echo $key; ?>" <?php echo $sel; ?>>
						<?php echo $val; ?>
					</option>
					<?php
					}
				?>
			</select>
		</div>
		
		<div class="col-md-2 sub-filter sub-filter-show-consumable-search" style="display:none;" >
			<label>Consumable</label>
			<input type="text" name="selected_item"  value="" class="form-control select2 allow-clearX" style="font-size: 12px; padding: 2px 5px; height: 28px; font-weight: bold;" tags="true" action="?action=items&todo=get_items_select2&not=1&type=service,service_lab,service_procedure,service_open,service_imenu,composite,composite_production" minlength="0" />
		</div>
		
		<div class="col-md-2 sub-filter sub-filter-show-category-type-search" style="display:none;">
			<label>Category</label>
			 <input type="text" name="category" placeholder="Category" action="?action=category&todo=get_select2" class="form-control select2 allow-clearX" minlength="0" />
		</div>
		
		<div class="col-md-2 sub-filter sub-filter-show-payment-method-search" style="display:none;">
			<label>Payment Method</label>
			 <select name="payment_method" class="form-control ">
				<option></option>
				<?php
					$pm = get_payment_method_grouped();
					
					$ptype = '';
					$key2 = 'payment_method';
					
					if( isset( $pm ) && is_array( $pm ) ){
						foreach( $pm as $k => $v ){
							?>
							<optgroup label="<?php echo $k; ?>">
							<?php
							foreach( $v as $key => $val ){
								$sel = '';
								if( $ptype == $key ){
									$sel = ' selected="selected" ';
								}
							?>
							<option value="<?php echo $key; ?>" <?php echo $sel; ?>>
								<?php echo $val; ?>
							</option>
							<?php
							}
							?>
							</optgroup>
							<?php
						}
					}
				?>
			</select>
		</div>
		
	</div>
	
</form>
<hr />

<div id="general-ledger-report-container">
	
</div>


<script type="text/javascript" class="auto-remove">
	setTimeout(function(){ $('.sub-filter').hide(); }, 300 );
	$('select[name="report_type"]')
	.on("change", function(){
		
		$('.sub-filter').hide();
		
		$('.sub-filter')
		.find('input.select2')
		.select2("val", "");
		
		$('.sub-filter')
		.find('select')
		.val("");
		
		$('.sub-filter')
		.find('input')
		.val("");
		
		switch( $(this).val() ){
		case 'stock_level_report2':
		case 'low_stock_level_report2':
		case 'stock_level_report_grouped_by_stores':
		
		case 'stock_reconciliation':
		
		case 'stock_value_report':
		case 'stock_value_report2':
		case 'stock_value_report_with_cost2':
		
		case 'stock_supply_history_report':
		case 'stock_supply_history_report_only':
		case 'stock_expiration_report':
	
			$('.sub-filter-show-consumable-search')
			.add('.sub-filter-show-category-type-search')
			.show();
			
		break;
		
		case 'draft_transfer_report':
		case 'import_draft_transfer_report':
		case 'pending_transfer_report':
		case 'active_transfer_report':
		case 'in_active_transfer_report':
	
			$('.sub-filter-show-account-type-search')
			.add('.sub-filter-show-member-search')
			.show();
			
		break;
		
		case 'draft_deposit_report':
		case 'import_pending_deposit_report':
		case 'import_draft_deposit_report':
		case 'pending_deposit_report':
		case 'active_deposit_report':
		case 'in_active_deposit_report':
	
			$('.sub-filter-show-member-search')
			.add('.sub-filter-show-payment-method-search')
			.show();
			
		break;
		}
		
		switch( $(this).val() ){
		//case 'draft_deposit_report':
		case 'import_pending_deposit_report':
		case 'import_draft_deposit_report':
		case 'pending_deposit_report':
		case 'active_deposit_report':
		case 'in_active_deposit_report':
	
			$('.sub-filter-show-display-option')
			.show();
			
		break;
		case 'refund_report':
			$('.sub-filter-show-contribution-types')
			.show();
		break;
		case 'estate_report':
			$('.sub-filter-show-estate-types')
			.add('.sub-filter-show-status-types')
			.add('.sub-filter-show-account-types')
			.show();
		break;
		}
		
	});
	
</script>


</div>
</div>