<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$action = isset( $data["table"] )?$data["table"]:'';
	
	if( isset( $data["report_type"] ) && $data["report_type"] ){
		switch( $data["report_type"] ){
		case "upcoming_birthdays":
		case "wedding_anniversary":
		case "work_anniversary":
			?>
			<form class="activate-ajax" method="post" action="?module=&action=<?php echo $action; ?>&todo=search_birthdays">
				<div class="input-group">
				
				 <span class="input-group-addon" style="color:#555;">From</span>
				 <input type="date" class="form-control" required="required" name="start_date" value="<?php echo date("Y-m-d"); ?>" />
				 <span class="input-group-addon" style="color:#555;">To</span>
				 <input type="date" class="form-control" required="required" name="end_date"  value="<?php echo date("Y-12-31"); ?>" />
				 <input type="hidden" name="report_type" value="<?php echo $data['report_type'] ?>">
				 <span class="input-group-btn">
					<button class="btn blue" type="submit" type="button"><i class="icon-search"></i> Search</button>
				 </span>
				</div>
			</form>
			<?php
		break;
		case "contact_list":
		break;
		}
		
		?>
		<hr />
		<div id="data-table-section">
			
		</div>
		<?php
	}
?>
</div>