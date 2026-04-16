<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
if( isset( $_GET["hyella_no_header"] ) && $_GET["hyella_no_header"] ){
	$no_header = 1;
}
if( isset( $_GET["nwp_request"] ) && $_GET["nwp_request"] ){
	$no_header = 1;
}
if( ! ( isset( $no_header ) && $no_header ) ){ 

		// echo '<pre>';print_r( $data );echo '</pre>';
	?>
<div class="row1 invoice-logo" style="margin-bottom:0px;">
<table class="table" style="position: relative; width: 100%; margin:0;" border="0" cellpadding="0" cellspacing="0">
   <tr>
	<td style="border:none; padding:0; width:50%;">
	<?php 
		$imgsrc = $pr["domain_name"] . get_logo_file_settings();
		
		if( isset( $data["encode_image"] ) && $data["encode_image"] ){
			$imgsrc = get_base64_image( $pr["domain_name"] . get_logo_file_settings() );
		}
		
		$serial_number_label = '';
		if( $serial_number ){
			$serial_number_label = '#' . $serial_number . ' / ';
		}
		
		if( function_exists("get_do_not_show_reference_in_receipt_heading_settings") ){
			if( get_do_not_show_reference_in_receipt_heading_settings() ){
				$serial_number_label = '';
			}
		}
		
		if( $show_logo_only > 0 ){
			?><img src="<?php echo $imgsrc; ?>" style="max-width:90%;" />
			<?php
		}else{
			
			if( $show_logo_only < 0 ){
				$skip_logo = 1;
			}
			
			?><br /><?php
			if( $store_name ){
				if( ! ( isset( $skip_logo ) && $skip_logo ) ){
				?><div class="pull-left no-pdf"><img src="<?php echo $pr["domain_name"] . get_logo_file_settings(); ?>" style="max-height:60px; margin-right:10px;" align="left" /></div>
				<?php } ?>
				<span class="store-name"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php //echo $store_name; ?></span><?php 
			}else{
				$store_name = "Support";
				if( ! ( isset( $skip_logo ) && $skip_logo ) ){
		?>
				<div class="pull-left no-pdf"><img src="<?php echo $imgsrc; ?>" style="max-height:50px; margin-right:10px;" /></div>
				<?php } ?>
		<span class="store-name"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?></span>
		<?php } ?>
	<?php } ?>
   </td>
   <td style="border:none;padding:0; width:50%;">
	  <p style="margin-bottom:0; font-size:20px;" class="serial-num-date"><?php echo $serial_number_label; ?><?php 
		$key = "date"; 
		if( isset( $data["event"][$key] ) && doubleval( $data["event"][$key] ) ){
			echo date("j M Y H:i", doubleval( $data["event"][$key] ) ); 
		}else{
			$key = "creation_date"; if( isset( $data["event"][$key] ) && doubleval( $data["event"][$key] ) )echo date("j M Y H:i", doubleval( $data["event"][$key] ) ); 
		}
	?><span class="muted" style="line-height:1.3;"><small><?php if( isset( $support_addr ) )echo $support_addr;  ?></small></span>
	  
	  <span style="font-sizeX:10px; line-height: 1.5; diplay:block; margin-top:5px;"><a href="#"><?php if( isset( $support_line ) )echo $support_line; ?></a>, <a href="#"><?php if( isset( $support_email ) )echo $support_email; ?></a></span>
	  
	  </p>
	  
   </td>
   </tr>
   </table>
</div>
<?php } ?>
<?php 
if( ! ( isset( $frm_mobile ) && $frm_mobile ) ){ 
	if( $serial_number && $sales_label ){ ?>
	<h4 style="text-align:center;"><strong><?php echo $sales_label; ?> #<?php echo $serial_number; ?></strong></h4>
<?php }
}else{
	echo '<br><br>';
} ?>
<?php /* if( ! isset( $hide_line ) && $hide_line ){ ?>
<hr style="margin-top:10px; margin-bottom:0px;" />
<?php } */ ?>
</div>