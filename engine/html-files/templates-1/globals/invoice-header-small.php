<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="row invoice-logo-small">
   <div class="col-xs-12" style="line-height:0.5;">
	<?php 
	// echo '<pre>';print_r( $currency );echo '</pre>'; 
		$clabel = 'Customer';
		
		switch( HYELLA_PACKAGE ){
		case "hospital":
			$clabel = 'Patient';
		break;
		}
		
		//if( $store_name ){
			?><h4 class="store-name-small"><strong><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php //echo $store_name; ?></strong></h4><?php 
		/*
		}else{
			$store_name = "Support";
	?>
	<img src="<?php echo $pr["domain_name"] . get_logo_file_settings(); ?>" style="max-height:60px;" />
	<?php } */ ?>
   </div>
   <?php $key = "serial_num"; if( isset( $data["event"][$key] ) ){ ?>
   <div class="col-xs-12 smaller-font">
	  <p style="margin-bottom:2px;"><span class="muted"><small><?php if( isset( $support_addr ) )echo $support_addr;  ?></small></span>
	  <br />#<small><?php echo $data["event"][$key]; ?> / <?php $key = "creation_date"; if( isset( $data["event"][$key] ) )echo date("j M Y", doubleval( $data["event"][$key] ) ); ?></small>
	  </p>
   </div>
   <?php } ?>
</div>
<hr>
<div class="row invoice-no-small">
   <div class="col-xs-12 invoice-payment">
	  <div class="" >
		<?php if( isset( $sales_label ) && isset( $serial_number ) ){ ?>
		 <ul class="list-unstyled">
			 <li><strong><?php echo $sales_label; ?> #<?php echo $serial_number; ?></strong></li>
		  </ul>
		<?php } ?>
		
		 <?php if( isset( $show_buttons ) && $show_buttons ){ ?>
		  <div class="btn-group btn-group-justified">
			<a class="btn btn-sm red custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=update_sales_status" href="#">Update Status</a>
			<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=delete_app_sales" href="#"><i class="icon-trash"></i> Delete</a>
		</div>
		<?php } ?>
		
	  </div>
   <div class="col-xs-12 smaller-font">
	  <ul class="list-unstyled">
		 <?php $key = "customer"; if( isset( $customer_name ) && $customer_name ){ ?>
		 <li><?php echo $clabel; ?>: <strong><?php echo $customer_name . $group_text; ?></strong></li>
		 <?php } ?>
		 <?php if( isset( $hmo ) && $hmo ){ ?>
		<li>Paying Institution: 
			<strong><?php 
				echo $hmo . ' (' . number_format( $hmo_percentage, 2 ) . '% covered)';
			?></strong>
		 </li>
		 <?php } ?>
			 
		 <?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "booked" ){ ?><li>status: <strong>booked</strong></li><?php } ?>
		 <?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
		 <li>Comment: <strong><?php echo nl2br( $data["event"][$key] ); ?></strong></li>
		<?php } ?>
		<?php if( isset( $special_comment ) && $special_comment )echo '<li>'.$special_comment.'</li>'; ?>
	  </ul>
   </div>
   </div>
</div>
</div>