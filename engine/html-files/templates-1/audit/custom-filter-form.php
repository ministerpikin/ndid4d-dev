<div class="portlet  box">
<div class="portlet-body" style=" background:transparent;">
<form class="activate-ajax" method="post" id="audit" action="?action=audit&todo=view">
	<div class="row">
		<div class="col-md-12">
		<label>User</label>
		<select class="form-control select2" placeholder="Select User" name="user">
			<option value="">-Select User-</option>
			<?php
				$data['customers'] = get_employees();
				if( isset( $data['customers'] ) && is_array( $data['customers'] ) ){
					foreach( $data['customers'] as $key => $val ){
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
			 <input type="date" name="start_date" class="form-control" value="<?php echo date("Y-n-01"); ?>" />
		</div>
		<br />
		<div class="col-md-12">
			<label>End Date</label>
			 <input type="date" name="end_date" class="form-control" value="<?php echo date("Y-n-t"); ?>" />
		</div>
		<br />
		<div class="col-md-12">
			<button class="btn blue btn-block" type="submit" style="">SEARCH</button>
		 </div>
	</div>
</form>
</div>
</div>