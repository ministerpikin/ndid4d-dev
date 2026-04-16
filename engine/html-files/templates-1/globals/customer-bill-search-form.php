<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>

<?php 
	$action = 'hotel_checkin';
	$customer_name = 'guest';
	
	switch( get_package_option() ){
	case "hotel":
	break;
	default:
		$action = 'customers';
		$customer_name = 'customer';
	break;
	}
	
	if( ! isset( $btn_class ) ){
		$btn_class = 'blue';
	}
?>
<form class="activate-ajax" method="post" action="?module=&action=<?php echo $action; ?>&todo=search_guest_bill_frontend">
	<div class="input-group">
	 <!--<span class="input-group-addon" style="color:#555;">From</span>-->
	 <input type="date" class="form-control" required="required" name="start_date" value="<?php echo date("Y-m-01"); ?>" />
	 <span class="input-group-addon" style="color:#555;">To</span>
	 <input type="date" class="form-control" required="required" name="end_date"  value="<?php echo date("Y-m-d"); ?>" />
	 <?php 
		if( isset( $global_customer ) && $global_customer ){
			?>
			<input type="hidden" name="<?php echo $customer_name; ?>"  value="<?php echo $global_customer; ?>" />
			<?php
		}else{
		?>
		 <span class="input-group-addon" style="color:#555;">Guest</span>
		 <input type="text" class="form-control select2" name="<?php echo $customer_name; ?>" required="required" placeholder="Guest Name" action="?action=customers&todo=get_customers_select2" />
		<?php		
		}
	 ?>
	 <span class="input-group-btn">
		<button class="btn <?php echo $btn_class; ?>" type="submit" type="button"><i class="icon-search"></i></button>
	 </span>
	</div>
</form>
<!--<hr />-->

<div id="bill-display-container">
	
</div>

</div>