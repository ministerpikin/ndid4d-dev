<div class="portlet  box">
<div class="portlet-body" style="background:transparent;">
<h4>Search Customer</h4>
<form class="activate-ajax" method="post" id="inventory" action="?action=customer_deposits&todo=filter_customer_search_all">
	<div class="row">
		<div class="col-md-12">
		<label>Customer</label>
		<select class="form-control select2" placeholder="Select Vendor" name="customer">
			<option value="">-Select Customer-</option>
			<?php
				$data['vendors'] = get_customers();
				if( isset( $data['vendors'] ) && is_array( $data['vendors'] ) ){
					foreach( $data['vendors'] as $key => $val ){
						?>
						<option value="<?php echo $key; ?>">
							<?php echo $val; ?>
						</option>
						<?php
					}
				}
			?>
		</select>
		</div>
		<br />
		<div class="col-md-12">
			<label>Start Date</label>
			 <input type="date" name="start_date" class="form-control" value="<?php echo date("Y-01-01"); ?>" />
		</div>
		<br />
		<div class="col-md-12">
			<label>End Date</label>
			 <input type="date" name="end_date" class="form-control" value="<?php echo date("Y-12-31"); ?>" />
		</div>
		<br />
		<div class="col-md-12">
			<button class="btn blue btn-block" type="submit" style="">SEARCH</button>
		 </div>
	</div>
</form>
</div>
</div>