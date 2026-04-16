<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="invoice">
	<div id="extra-info">
		
	</div>
	<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
	<?php //print_r($data['event']); ?>
	
	<?php 
		$support_addr = '2nd floor, Dicon Towers, plot 16 kofo Abayomi, Victoria island, Lagos';
		$st = get_sales_status();
		
		$status = '<span class="label label-info">'.( isset( $st[ $data['event']["sales_status"] ] )?$st[ $data['event']["sales_status"] ]:$data['event']["sales_status"] ).'</span>';
		
		switch( $data['event']["sales_status"] ){
		case "pending":
			$mod = doubleval( $data['event']["modification_date"] );
			$target = mktime( 23, 59, 59, date("n", $mod), date("j", $mod), date("Y", $mod ) );
			if( date("U") > $target ){
				$data['event']["sales_status"] = 'Expired';
				$status = '<span class="label label-danger">'.$data['event']["sales_status"].'</span>';
			}else{
				$t = time_passed_since_action_occurred( $target - date("U") , 0 );
				$status = '<span class="label label-warning">'.( isset( $st[ $data['event']["sales_status"] ] )?$st[ $data['event']["sales_status"] ]:$data['event']["sales_status"] ).' <strong>'.$t.'</strong></span>';
			}
		break;
		}
		
		$unit_type_text = "Quantity";
	?>
	
	<div class="row invoice-logo" style="margin-bottom:0px;">
	   <div class="col-xs-6 invoice-logo-space"><br />
		<?php 
			if( $store_name ){
				if( ! $skip_logo ){
				?><img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo-b.png"; ?>" style="max-height:60px; float:left; margin-right:10px;" align="left" />
				<?php } ?>
				<span class="store-name"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?><?php echo $store_name; ?></span><?php 
			}else{
				$store_name = "Support";
		?>
		<img src="<?php echo $pr["domain_name"]."frontend-assets/img/logo-b.png"; ?>" style="max-height:60px;" />
		<span class="store-name"><?php if( isset( $pr['company_name'] ) )echo $pr['company_name']; ?></span>
		<?php } ?>
	   </div>
	   <div class="col-xs-6">
		  <p style="margin-bottom:0;"><?php $key = "creation_date"; if( isset( $data["event"][$key] ) )echo date("j M Y", doubleval( $data["event"][$key] ) ); ?></small>
		  
		  <span class="muted" style="line-height:16px;"><small><?php echo $support_addr;  ?></small></span>
		  
		  <span style="font-size:10px;"><a href="#"><?php echo $support_line; ?></a>, <a href="#"><?php echo $support_email; ?></a></span>
		  
		  </p>
		  
	   </div>
	</div>
	
	<h4 style="text-align:center;"><strong>Sales <?php echo $sales_label; ?> #<?php echo $serial_number; ?></strong></h4>
	<hr style="margin-top:10px;" />
	
	<div class="row ">
	   <div class="col-xs-5 ">
		  <h4>Client:  <?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "booked" ){ $bookings = 1; ?><span class="pull-right label label-info"><small><strong><i class="icon-pushpin"></i> booked</strong></small></span><?php } ?></h4>
		  <ul class="list-unstyled">
			 <li><strong><?php $key = "customer"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_customers" ) ); ?></strong></li>
			 
		  </ul>
	   </div>
	   <div class="col-xs-7 invoice-payment">
		  <div class="well payment-status-container">
			
			 <?php if( $branch ){ ?><span><?php echo $branch; ?></span><?php } ?>
			 <ul class="list-unstyled">
				 <li><strong><?php echo $sales_label; ?> No.:</strong> #<?php echo $serial_number; ?></li>
				 <li><br /><strong>Status:</strong> <?php echo $status; ?></li>
				 
				 <?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li><strong>Comment:</strong> <?php echo $data["event"][$key]; ?></li>
				<?php } ?>
			  </ul>
			
			 <?php if( 1 == 2 && ( $show_buttons || $bookings ) ){ ?>
			  <div class="btn-group btn-group-justified">
				
				<?php if( $bookings ){ ?>
				<a class="btn btn-sm green custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=deliver_booked_sale" href="#"> Mark As Delivered</a>
				<?php } ?>
				
				<?php if( $show_buttons ){ ?>
				<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=delete_app_sales" href="#"><i class="icon-trash"></i> Delete</a>
				<?php } ?>
				
			</div>
			<?php } ?>
			
		  </div>
	   </div>
	</div>
	
	<div class="row">
	   <div class="col-xs-12">
			
		  <table class="table table-striped table-hover" style="margin-top:5px;">
			 <thead>
				<tr>
				   <th>#</th>
				   <th>Item</th>
				   <th class="hidden-480"><?php echo $unit_type_text; ?></th>
				   <th style="text-align:right;" class="hidden-480"><?php echo $price_label; ?></th>
				   <th style="text-align:right;" class="hidden-480">Discount</th>
				   <th style="text-align:right;">Total</th>
				</tr>
			 </thead>
			 <tbody>
				<?php 
				$total = 0;
				$serial = 0;
				$refund = 0;
				$subtotal = 0;
				$cats = get_items_categories();
				
				if( isset( $data["event_items"] ) && is_array( $data["event_items"] ) && ! empty( $data["event_items"] ) ){
					foreach( $data["event_items"] as $items ){
						++$serial;
						
						$price = $items["cost"];
						$q = $items["quantity"];
						if( isset( $items["quantity_returned"] ) )$q -= $items["quantity_returned"];
						
						$dis = $items["discount"];
						
						$title = "";
						if( $items["item_id"] == "refund" ){
							$title = "Customer Refund";
							$refund = 1;
						}else{
							$item_details = get_items_details( array( "id" => $items["item_id"] ) );
							
							if( isset( $item_details["description"] ) ){
								if( $show_image ){
									$title = '<a href="#" class="custom-single-selected-record-button" override-selected-record="' . $item_details["id"] . '" action="?module=&action=items&todo=view_item_details" title="View Details"><img src="' . $pr["domain_name"] . $item_details["image"] . '" width="'.$image_size.'" align="left" style="margin-right:5px; border:1px solid #aaa; max-height:'.( $image_size * 2 ).'px;" />';
								}
								
								$title .= $item_details["description"] . "<br /><strong><small>#" . $item_details["barcode"] . "</small></strong>";
								
								$title .= '<small><br />' . ( isset( $cats[ $item_details["category"] ] )?$cats[ $item_details["category"] ]:$item_details["category"] ) . '</small>';
								
								if( $show_image ){
									$title .= '</a>';
								}
							}else{
								$title = ucwords( $items["item_id"] );
							}
						}
						$total += ( $price * $q ) - $dis;
						?>
						<tr>
						   <td><?php echo $serial; ?></td>
						   <td><?php echo $title; ?></td>
						   <td class="hidden-480"><?php echo $q; ?></td>
						   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
						   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $dis , 4 ); ?></td>
						   <td align="right"><?php echo format_and_convert_numbers( ( $price * $q ) - $dis, 4 ); ?></td>
						</tr>
						<?php
					}
					
					if( isset( $total_title ) && $total_title ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="4"><strong><?php echo $total_title; ?></strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total, 4 ); ?></strong></td>
					</tr>
					<?php
					}
					
					$subtotal = $total;
					
					$discount = 0;
					$discounted_amount = 0;
					$key = "discount"; 
					if( isset( $data["event"][$key] ) && $data["event"][$key] )
						$discount = doubleval( $data["event"][$key] );
					
					$discount_type = "";
					$key = "discount_type";
					if( isset( $data["event"][$key] ) && $data["event"][$key] )
						$discount_type = $data["event"][$key];
					
					switch( $discount_type ){
					case "fixed_value_after_tax":
					case "percentage_after_tax":
						$g_discount_after_tax = 1;
					break;
					case "fixed_value":
						$g_discount_after_tax = 0;
						$discounted_amount = $discount;
					break;
					case "percentage":
						$g_discount_after_tax = 0;
						$discounted_amount = $total * $discount / 100;
					break;
					}
					
					if( ! $g_discount_after_tax ){
						$total = $total - $discounted_amount;
					}
				}
				
				
				if( $service_tax )$service_tax = round( $service_tax * $total / 100, 2 );
				if( $service_charge )$service_charge = round( $service_charge * $total / 100 , 2);
				if( $vat )$vat = round( $vat * $total / 100 , 2);
				
				$total += $service_charge + $service_tax + $vat + $surcharge;
				
				switch( $discount_type ){
				case "fixed_value_after_tax":
					$g_discount_after_tax = 1;
					$discounted_amount = $discount;
				break;
				case "percentage_after_tax":
					$g_discount_after_tax = 1;
					$discounted_amount = $total * $discount / 100;
				break;
				}
				
				if( $g_discount_after_tax ){
					$subtotal = $total;
					$total = $total - $discounted_amount;
				}
				?>
			 </tbody>
		  </table>
	   </div>
	</div>

	<div class="row">
	  
	   <div class="col-xs-6">
		  <?php if( ( ! ( isset( $refund ) && $refund ) ) && $amount_paid ){ ?>
		  <div class="well">
			 <address>
				<strong>Payment Details </strong><br>
				<span style="font-size: 1em; line-height: 1.6;">
					PAID: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $amount_paid , 4 ); ?></strong></span>
				</span>
				<?php if( ! $reference ){ ?>
				<?php 
					
						if( ! $refund ){
							$ow = $total - $amount_paid;
							
							if( $ow > 0 ){
						?>
						<span style="font-size: 1em; line-height: 1.6; color:#ff0000 !important;">
						<br />
						OWING: <span class="pull-right"><strong style="color:#ff0000 !important;"><?php echo number_format( $ow , 2 ); ?></strong></span>
						</span>
						<?php }
						
						if( $ow < 0 ){ ?>
							<span style="font-size: 1em; line-height: 1.6;">
							<br />
							Customer Bal.: <span class="pull-right"><strong><?php echo number_format( abs( $ow ) , 2 ); ?></strong></span><br class="hidden-print" />
							<?php if( $backend ){ ?>
							<br class="hidden-print" />
							<a class="btn btn-xs red hidden-print btn-block custom-single-selected-record-button" action="?module=&action=sales&todo=refund_customer&customer=<?php echo number_format( abs( $ow ) , 2 ); ?>&store=<?php echo $data["event"][ "store" ]; ?>" override-selected-record="<?php echo round( abs( $ow ) , 2 ); ?>" mod="<?php echo $data["event"][ "id" ]; ?>" title="Click to refund customer <?php echo number_format( abs( $ow ) , 2 ); ?>" >Refund Customer</a>
							<?php } ?>
							</span>
					<?php }
					}
				?>
				<?php } ?>
			 </address>
			 
			 <?php 
				if( $support_msg ){ 
					echo $support_msg; 
				} 
			 ?>
		  </div>
		  <?php }else{ ?>
		  <div class="well">
			 <address>
				<strong>Payment Instructions</strong><br>
			</address>
		</div>
		  <?php } ?>
	   </div>
	  
	   <div class="col-xs-6 invoice-block <?php if( $refund ){ ?> col-md-offset-5 <?php } ?>">
		  <ul class="list-unstyled amounts">
			<?php
				if( ! $reference ){
					
				if( ! $g_discount_after_tax && isset( $discount ) && $discount ){
					?>
					<li><strong>Sub - Total Amount:</strong> <?php echo number_format( $subtotal , 2 ); ?></li>
					<?php
					switch( $discount_type ){
					case "percentage":
						?>
						<li><strong><?php echo $discount."%" ?> Discount:</strong> <?php echo number_format( $discounted_amount , 2 ); ?></li>
						<?php
					break;
					default:
						?>
						<li><strong>Discount:</strong> <?php echo number_format( $discounted_amount, 2 ); ?></li>
						<?php
					break;
					}
				}
				if( $service_charge ){
					?>
					<li><strong>Service Charge <?php echo $iservice_charge; ?>% :</strong> <?php echo number_format( $service_charge , 2 ); ?></li>
					<?php
				}
				if( $service_tax ){
					?>
					<li><strong>Service Tax <?php echo $iservice_tax; ?>% :</strong> <?php echo number_format( $service_tax , 2 ); ?></li>
					<?php
				}
				if( $vat ){
					?>
					<li><strong>VAT <?php echo $ivat; ?>% :</strong> <?php echo number_format( $vat , 2 ); ?></li>
					<?php
				}
				if( $surcharge ){
					?>
					<li><strong>Service Charge :</strong> <?php echo number_format( $surcharge , 2 ); ?></li>
					<?php
				}
				if( $g_discount_after_tax && isset( $discount ) && $discount ){
					?>
					<li><strong>Sub - Total Amount:</strong> <?php echo number_format( $subtotal , 2 ); ?></li>
					<?php
					switch( $discount_type ){
					case "percentage_after_tax":
						?>
						<li><strong><?php echo $discount."%" ?> Discount:</strong> <?php echo number_format( $discounted_amount , 2 ); ?></li>
						<?php
					break;
					default:
						?>
						<li><strong>Discount:</strong> <?php echo number_format( $discounted_amount, 2 ); ?></li>
						<?php
					break;
					}
				}
			?>
			 <li style="font-size:1.3em;"><strong><?php if( $refund )echo "Refund"; else echo "Net Total"; ?>:</strong> <?php echo $currency . format_and_convert_numbers( $total , 4 ); ?></li>
			 <?php } ?>
			 
			 <?php $key = "staff_responsible"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
			 <li style=""><strong>by:</strong> <?php echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></li>
			 <?php } ?>
			 
		  </ul>
		 
	   </div>
	</div>
	
	<div class="row">
		<div class="col-md-offset-5 col-md-7 " style="text-align:right;">
		  <br>
		  <?php if( ! $backend ){ ?>
		  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print Order <i class="icon-print"></i></a>
		  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
		  <?php }else{ ?>
		  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a><br /><br /><br />
		  <?php } ?>
		</div>
	 </div>
	 
	 <?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "awaiting_approval" ){ ?>
	 <div class="row hidden-print">
		<div class="col-md-12">
			<h4>Evidence of Payment</h4>
			<hr />
		</div>
		<div class="col-md-7">
			<?php 
				$p = get_payment_evidence_by_sales_details( array( "id" => $data["event"]["id"] ) );
				if( isset( $p["file"] ) && isset( $p["comment"] ) ){
					?>
					<div class="alert alert-info">
						<p>Confirmation Code: <strong><?php echo $p["comment"]; ?></strong></p>
						<p><a href="<?php echo $pr["domain_name"].$p["file"]; ?>" target="_blank" title="Open in New Window"><img src="<?php echo $pr["domain_name"].$p["file"]; ?>" style="width:100%;" /></a></p>
					</div>
					<?php
				}else{
					?>
					<div class="alert alert-danger">
						<strong>Failed to retrieve Evidence of Payment</strong>
					</div>
					<?php
				}
			?>
		</div>
		<?php if( $show_buttons && isset( $user_info["user_privilege"] ) && trim( $user_info["user_privilege"] ) ){ ?>
		<div class="col-md-5">
			<a href="#" class="btn red custom-single-selected-record-button" override-selected-record="<?php echo $data["event"]["id"]; ?>" action="?module=&action=giftcodes&todo=approve_payment" title="Approve Payment">Approve Payment <i class="icon-check"></i></a>
		</div>
		<?php } ?>
	 </div>
	 <?php } ?>
	 
		
	<?php }else{ ?>
		<div class="alert alert-danger">
			<h3>Cannot Retrieve Receipt</h3>
			<p>Multiple Records Selected, please do select a single sales record to view its receipt</p>
		</div>
	<?php } ?>
</div>
</div>