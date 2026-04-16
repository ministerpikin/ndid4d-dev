<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="invoice">
	<div id="extra-info">
	
	</div>
	<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
	<?php //print_r($data['event']); ?>
	<?php 
		//$support_addr = '2nd floor, Dicon Towers, plot 16 kofo Abayomi, Victoria island, Lagos';
		$st = get_sales_status();
		
		$discount_type = '';
		$discounted_amount = 0;
		
		$status = '<span class="label label-info">'.( isset( $st[ $data['event']["sales_status"] ] )?$st[ $data['event']["sales_status"] ]:$data['event']["sales_status"] ).'</span>';
		$occuppied = 1;
		
		$show_rent = 1;
		$client_label = "Tenant: ";
		$sales_label_prefix = "Rent ";
		
		switch( $data['event']["sales_status"] ){
		case "vacated":
			$occuppied = 0;
		break;
		case "occuppied":
			$status = '<span class="label label-danger">'.( isset( $st[ $data['event']["sales_status"] ] )?$st[ $data['event']["sales_status"] ]:$data['event']["sales_status"] ).'</span>';
		break;
		case "purchase":
			$client_label = "Client: ";
			//get purchase details
			$p = get_record_details( array( "id" => $data['event']["id"], "table" => "purchase" ) );
			
			if( isset( $p['id'] ) && $p['id'] ){
				$pplan = get_record_details( array( "id" => $p['payment_plan'], "table" => "banks" ) );
				$ptype = get_record_details( array( "id" => $p['payment_type'], "table" => "banks" ) );
			}
			$show_rent = 0;
			$sales_label_prefix = "";
		break;
		}
		
		$store_address = '';
		if( isset( $data["event"]["store"] ) && $data["event"]["store"] ){ 
		   $s1 = get_record_details( array( "id" => $data["event"]["store"], "table" => "property_location" ) );
		   if( isset( $s1["id"] ) )$store_address = $s1["estate"] . ', ' . $s1["district"];
		   
		   $s2 = get_store_details( array( "id" => get_main_store() ) );
		   if( isset( $s2["id"] ) ){
			   $store_name = $s2["name"];
				$support_line = $s2["phone"];
				$support_addr = $s2["address"];
				$support_email = $s2["email"];
				$support_msg = '';
		   }
		}
		
		$tenure = '';
		$billing_cycle = 0;
		if( isset( $data["event"]["term1"] ) ){
			$tenure = get_billing_cycle_text( $data["event"]["term1"] ) . '(s)';
			$billing_cycle = get_billing_cycle_seconds( $data["event"]["term1"] ) * $data["event"]["quantity"];
		}
		
		$unit_type_text = "No. of " . $tenure;
	?>
	
	<?php include "invoice-header.php"; ?>
	
	<div class="row ">
	   <div class="col-xs-5 ">
		  <h4><?php echo $client_label; ?></h4>
		  <ul class="list-unstyled">
			 <li><strong><?php $key = "customer"; if( isset( $data["event"][$key] ) ){
				 $c = get_customers_details( array( "id" => $data["event"][$key] ) );
				 if( isset( $c["name"] ) )echo $c["name"] . '<br />' . $c["phone"];
			 }; ?></strong></li>
			 
		  </ul>
	   </div>
	   <div class="col-xs-7 invoice-payment">
		  <div class="well payment-status-container">
			
			 <?php if( $branch ){ ?><span><?php echo $branch; ?></span><?php } ?>
			 <ul class="list-unstyled">
				<li><strong>Status:</strong> <?php echo $status; ?><br /><br /></li>
				
				<?php  if( $show_rent ){ ?>
				 <li><strong>Entry Date:</strong> <?php $key = "date"; if( isset( $data["event"][$key] ) )echo date("d-M-Y", doubleval( $data["event"][$key] ) ); ?></li>
				 
				<li><strong>Tenure:</strong> <?php $key = "quantity"; if( isset( $data["event"][$key] ) )echo intval( $data["event"][$key] ) . ' ' . $tenure; ?></li>
				
				<?php if( $billing_cycle ){ ?>
				<li><strong>Vacation Date:</strong> <?php $key = "date"; if( isset( $data["event"][$key] ) )echo date("d-M-Y", doubleval( $data["event"][$key] ) + $billing_cycle ); ?></li>
				<?php } ?>
				 <?php if( ! $occuppied ){ ?>
				<li><strong>Actual Vacation Date:</strong> <?php $key = "modification_date"; if( isset( $data["event"][$key] ) )echo date("d-M-Y", doubleval( $data["event"][$key] ) ); ?></li>
				<?php } ?>
				
				<?php  }else{ ?>
				 <?php if( isset( $ptype["name"] ) ){ ?>
					<li><strong>Payment Type:</strong> <?php echo $ptype["name"]; ?><br /></li>
				 <?php } ?>
				 <?php if( isset( $pplan["name"] ) ){ ?>
					<li><strong>Payment Plan:</strong> <?php echo $pplan["name"]; ?><br /></li>
				 <?php } ?>
				<?php } ?>
				
				 <?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
				 <li><strong>Comment:</strong> <?php echo $data["event"][$key]; ?></li>
				<?php } ?>
			  </ul>
			
			 
		  </div>
	   </div>
	</div>
	
	<div class="row">
	   <div class="col-xs-12">
		<?php if( isset( $store_address ) ){ ?>
		  <p style="text-align:center;"><strong><?php echo $store_address; ?></strong></p>
		<?php } ?>
		  <table class="table table-striped table-hover table-bordered" style="margin-top:5px;">
			 <thead>
				<tr>
				   <th>#</th>
				   <th>Item</th>
				   <th class="hidden-480"><?php echo $unit_type_text; ?></th>
				   <th style="text-align:right;" class="hidden-480"><?php echo $price_label; ?></th>
				   <th style="text-align:right;">Total</th>
				</tr>
			 </thead>
			 <tbody>
				<?php 
				$total = 0;
				$serial = 0;
				$refund = 0;
				$subtotal = 0;
				$cats = get_property_type();
				
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
							$item_details = get_record_details( array( "id" => $items["item_id"], "table" => "property" ) );
							
							if( isset( $item_details["address"] ) ){
								
								$title .= $item_details["address"];
								
								$title .= '<small><br />' . ( isset( $cats[ $item_details["property_type"] ] )?$cats[ $item_details["property_type"] ]:$item_details["property_type"] ) . '</small>';
								
								if( ! $show_rent ){
									$title .= '<small><br />' . $item_details["description"] . '<br />' . $item_details["property_size"] . ' SQ.M</small>';
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
					if( isset( $data['all_transactions']["amount_due"] ) && isset( $data['all_transactions']["amount_paid"] ) && isset( $data['all_transactions']["discount"] ) ){
						?>
						<br /><br />
						<strong><small>All Transaction Details</small></strong><br>
						<span style="font-size: 0.9em; line-height: 1.6;">
							Amount Due: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $data['all_transactions']["amount_due"] - $data['all_transactions']["discount"] , 4 ); ?></strong></span><br />
							Amount Paid: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $data['all_transactions']["amount_paid"] , 4 ); ?></strong></span><br />
							<?php 
								$ow = ( $data['all_transactions']["amount_due"] - $data['all_transactions']["discount"] ) - $data['all_transactions']["amount_paid"];
								if( $ow > 0 ){
									?>
									Total Debt: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $ow , 4 ); ?></strong></span>
									<?php
								}
								if( $ow < 0 ){
									?>
									Customer Bal.: <span class="pull-right"><strong><?php $m = format_and_convert_numbers( $ow * -1 , 4 ); echo $m; ?></strong></span><br class="hidden-print" /><br class="hidden-print" />
									 <a class="btn btn-xs red hidden-print btn-block custom-single-selected-record-button" action="?module=&action=sales&todo=refund_customer&customer=<?php echo $data["event"][ "customer" ]; ?>&store=<?php echo $data["event"][ "store" ]; ?>" override-selected-record="<?php echo $ow * -1; ?>" mod="<?php echo $data["event"][ "id" ]; ?>" title="Click to refund customer <?php echo $m; ?>" >Refund Customer</a>
									<?php
								}
							?>
						</span>
						<?php
					}else{
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
							
							</span>
					<?php }
						}
					}
				?>
				<?php } ?>
			 </address>
			 
			 <?php 
				$msg = get_sales_receipt_message_settings();
				if( $msg ){
			?>
			<div style="<?php echo get_sales_receipt_message_style_settings(); ?> border:none;"><?php echo $msg; ?></div>
			<?php } ?>
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
	
	<?php if( $show_signature ){ ?>
	<div class="row ">
	   <div class="col-xs-6">
		  <ul class="list-unstyled">
			 <li><?php echo $client_label; ?></li>
			 <li><strong><?php $key = "customer"; if( isset( $data["event"][$key] ) ){
				 $c = get_customers_details( array( "id" => $data["event"][$key] ) );
				 if( isset( $c["name"] ) )echo $c["name"] . '<br />' . $c["phone"];
			 } ?></strong></li>
			 
			 <li><br />Signature: ____________________</li>
		  </ul>
	   </div>
	   <div class="col-xs-6">
			<ul class="list-unstyled">
				 
				 <li>Raised By:</li>
				 <li><strong><?php $key = "modified_by"; if( isset( $data["event"][$key] ) )echo get_select_option_value( array( "id" => $data["event"][$key], "function_name" => "get_employees" ) ); ?></strong><br />[for Property Owner]</li>
				 <li><br />Signature: ____________________</li>
			  </ul>
			
	   </div>
	</div>
	<?php } ?>
	
	<div class="row">
		<div class="col-md-offset-5 col-md-7 " style="text-align:right;">
		  <br>
		  <?php if( ! $backend ){ ?>
		  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print <i class="icon-print"></i></a>
		  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
		  <?php }else{ ?>
		  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a><br /><br /><br />
		  <?php } ?>
		</div>
	 </div>
	 
		
	<?php }else{ ?>
		<div class="alert alert-danger">
			<h3>Cannot Retrieve Receipt</h3>
			<p>Multiple Records Selected, please do select a single sales record to view its receipt</p>
		</div>
	<?php } ?>
</div>
</div>