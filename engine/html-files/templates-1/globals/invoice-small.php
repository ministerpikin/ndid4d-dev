<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="invoice invoice-small" style="padding:1px;">
	<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
	<?php //print_r($data['event']); ?>
	
	<?php 
		$unit_type_text = "Qty.";
		include "invoice-header-small.php";
	?>
	
	<div class="row">
	   <div class="col-xs-12">
		  <table class="table table-striped table-hover">
			 <tbody>
				<tr>
				   <th class="hidden-480">#</th>
				   <th>Item</th>
				   <th ><?php echo $unit_type_text; ?></th>
				   <th style="text-align:right;" class="hidden-480">S. Price</th>
				   
				   <th style="text-align:right;" class="hidden-480">Discount</th>
				   
				   <th style="text-align:right;">Total</th>
				</tr>
				<?php 
				$total = 0;
				$serial = 0;
				$refund = 0;
				$subtotal = 0;
				$total_discount = 0;
				
				$colspan = 4;
				if( $g_discount_after_tax ){
					$colspan = 3;
				}
				
				// echo '<pre>'; print_r($data["event_items"]); echo '</pre>';
				if( isset( $data["event_items"] ) && is_array( $data["event_items"] ) && ! empty( $data["event_items"] ) ){
					foreach( $data["event_items"] as $items ){
						if( isset( $items["item_type"] ) && $items["item_type"] == 'raw_materials' ){
							continue;
						}
						++$serial;
						
						$price = $items["cost"];
						$q = $items["quantity"] - ( isset( $items["quantity_returned"] )?$items["quantity_returned"]:0 );
						$dis = $items["discount"];
						
						$code_text = "";
						$title = "";
						if( isset( $items["item_id"] ) && $items["item_id"] == "refund" ){
							$title = "Customer Refund";
							$refund = 1;
						}else{
							
							$item_title = '';
							if( isset( $items["data"] ) && $items["data"] ){
								$items_data = json_decode( $items["data"], true );
								if( isset( $items_data["desc"] ) && isset( $items_data["type"] ) && $items_data["type"] == "service_open" ){
									$item_title = $items_data["desc"];
								}
								
								if( isset( $items_data["approval_code"] ) && $items_data["approval_code"] ){
									$code_text = "<br /><small>CODE: " . $items_data["approval_code"] . "</small>";
								}
							}
							
						if( ! $item_title ){
								$item_details = get_record_details( array( "id" => $items[ $item_key ], "table" => $record_details_table ) );
								$item_title = isset( $item_details["description"] )?$item_details["description"]:'';
								
								if( isset( $item_details["barcode"] ) && $item_details["barcode"] ){
									$item_title .= "<br /><strong><small>#" . $item_details["barcode"] . "</small></strong>";
								}
							}
							
							if( isset( $items_data["type"] ) ){
								switch( $items_data["type"] ){
								case "service_open":
									$item_title = isset( $items_data["desc"] )?$items_data["desc"]:'';
								break;
								case "fixed-asset":
									$item_title = isset( $items_data["desc"] )?$items_data["desc"]:'';
									if( isset( $items_data["comment"] ) && $items_data["comment"] )$item_title .= "<br /><small>" . $items_data["comment"] . "</small>";
								break;
								case 'sub_item':
									if( isset( $items_data[ 'sub_unit' ] ) && doubleval( $items_data[ 'sub_unit' ] ) ){

										$item_title = isset( $items_data["desc"] )?$items_data["desc"]:'';
										$q = doubleval( $q ) / doubleval( $items_data[ 'sub_unit' ] );
										$price = doubleval( $price ) * doubleval( $items_data[ 'sub_unit' ] );

									}
								break;
								}
							}
							
							$set_title = 1;
							switch( $items[ 'reduction_type' ] ){
							case 'admission2':
								if( isset( $items[ 'item_desc' ] ) && $items[ 'item_desc' ] ){
									$set_title = 0;
									$title = $items[ 'item_desc' ];
								}
							break;
							}

							if( $set_title ){
								$title = $item_title;
							}

							$title .= $code_text;
						}
						
						$total += ( $price * $q ) - $dis;
						
						?>
						<tr>
						   <td class="hidden-480"><?php echo $serial; ?></td>
						   <td><?php echo $title; ?></td>
						   <td ><?php echo $q; ?></td>
						   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
						   
						   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $dis , 4 ); ?></td>
						   <td align="right"><?php echo format_and_convert_numbers( ( $price * $q ) - $dis, 4 ); ?></td>
						   
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
					
					if( $loyalty_points ){
						$total = $total - $loyalty_points;
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
	<div class="row payment-status-container">
	   <div class="col-xs-12 invoice-block"  style="padding-left:18px; padding-right:18px;">
		  <ul class="list-unstyled amounts smaller-font">
			<?php
				if( ( ! $g_discount_after_tax && isset( $discount ) && $discount ) || $loyalty_points ){
				//if( $loyalty_points || $discount ){
					?>
					<li><strong>Sub - Total Amount:</strong> <?php echo format_and_convert_numbers( $subtotal, 4 ); ?></li>
					<?php if( $loyalty_points ){ ?>
					<li><strong>Loyalty Points:</strong> <?php echo number_format( $loyalty_points , 2 ); ?></li>
					<?php } ?>
					<?php
					if( isset( $discount ) && $discount ){
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
				}
				if( $service_charge ){
					?>
					<li><strong>Service Charge <?php echo $iservice_charge; ?>% :</strong> <?php echo format_and_convert_numbers( $service_charge , 4 ); ?></li>
					<?php
				}
				if( $service_tax ){
					?>
					<li><strong>Service Tax <?php echo $iservice_tax; ?>% :</strong> <?php echo format_and_convert_numbers( $service_tax , 4 ); ?></li>
					<?php
				}
				if( $vat ){
					?>
					<li><strong>VAT <?php echo $ivat; ?>% :</strong> <?php echo format_and_convert_numbers( $vat , 4 ); ?></li>
					<?php
				}
				if( $surcharge ){
					?>
					<li><strong>Service Charge :</strong> <?php echo number_format( $surcharge , 2 ); ?></li>
					<?php
				}
				if( $discount ){
					if( $g_discount_after_tax ){
						?>
						<li><strong>Discount:</strong> <?php echo number_format( $discounted_amount, 2 ); ?></li>
						<?php
					}
				}
			?>
			<!--
			 <li><strong>Sub - Total amount:</strong> $9265</li>
			 <li><strong>Discount:</strong> 12.9%</li>
			 <li><strong>VAT:</strong> -----</li>
			 -->
			 <li><strong><small ><?php if( $refund )echo "Refund"; else echo "Net Total"; ?>:</small></strong> <?php echo $currency . format_and_convert_numbers( $total , 4 ); ?></li>
			 
			 
			 <?php if( isset( $raised_by ) && $raised_by ){ ?>
			 <li>Raised by: <strong><?php echo $raised_by; ?></strong></li>
			 <?php } ?>
			 
		  </ul>
	   </div>
	   <?php if( ( ! ( isset( $refund ) && $refund ) )  && $amount_paid ){ ?>
	   <div class="col-xs-12 smaller-font" style="padding-left:18px; padding-right:18px;">
			 <address>
				<strong>Payment Details</strong><br>
				<span >
					PAID: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $amount_paid , 4 ); ?></strong></span><br />
					<?php if( ! $refund ){ ?>
					OWING: <span class="pull-right"><strong><?php echo format_and_convert_numbers( $total - $amount_paid , 4 ); ?></strong></span>
					<?php } ?>
				</span>
				
			 </address>
			 
	   </div>
	   <?php } ?>
	   
		 <?php 
			$msg = get_sales_receipt_message_settings();
			if( $msg ){
		?>
		<div style="text-align:center;"><?php echo $msg; ?></div>
		<?php } ?>
			 
	</div>
	<?php if( ! $backend ){ ?>
		  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
		  
		  <?php if( ! ( isset( $data["skip_timeout_script"] ) && $data["skip_timeout_script"] ) ){ ?>
		  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
		  <?php }?>
		  
		  <?php }else{ ?>
		  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a>
		  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&pos=1" target="_blank" class="btn dark hidden-print">POS Print Preview <i class="icon-print"></i></a>
		  
		  <br /><br /><br /><br />
		  <?php } ?>
	<style type="text/css">
		.payment-status-container{
			background-image:url(<?php
				if( ( $total - $amount_paid ) <= 0 ){
					$stamp = "paid-in-full.png";
				}
				
				if( ! ( isset( $refund ) && $refund ) ){
					echo $pr["domain_name"]."images/" . $stamp;
				}
			?>);
			background-position:center top;  background-repeat:no-repeat;  background-size:100px;
		}
	  </style>
  
	<?php }else{ ?>
		<div class="alert alert-danger">
			<h3>Cannot Retrieve Receipt</h3>
			<p>Multiple Records Selected, please do select a single sales record to view its receipt</p>
		</div>
	<?php } ?>
</div>
</div>