<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="invoice">
	<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
	
	<?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "i_menu_order" ){ ?>
		<div class="note note-danger alert alert-danger">
			<h3>Pending Confirmation</h3>
			<p>This transaction is not recorded in the Financial Accounting Ledger until it is marked as delivered</p>
		</div>
	<?php } ?>
	
	<?php //print_r($data['event']); ?>
	<?php 
		if( isset( $data["more_actions"] ) && strip_tags($data["more_actions"]) ){
			echo $data["more_actions"] . '<br /><br />';
		}
		
		if( isset( $show_header ) && $show_header ){
			include dirname( dirname( __FILE__ ) ) . "/invoice-header.php";
		}
		
		$tr_oc = '';
		$c_cols = 'col-xs-6';
		$cw2 = '';
		$cw1 = '';
		$show_costing = 0;
		$item_label = 'Services / Description';
	?>
	<table class="table" style="position: relative; width: 100%; margin:0; background:#f7f7f7;" border="1" cellpadding="5" cellspacing="0">
		<tr>
		<td><strong><?php echo $customer_label; ?></strong></td>
		<td><?php echo $cw1 . $customer_name . $group_text . $cw2; ?>
		   
		   <?php if( $extra_customer_data )echo '<br />'.$extra_customer_data; ?>
		</td>
		</tr>
		<tr>
		<td><?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
			<strong><?php echo $comment_text; ?>:</strong></td><td><?php echo nl2br( $data["event"][$key] ); ?>
			<?php } ?>
			<?php if( $special_comment )echo '<br />'.$special_comment; ?>
	   </td>
	   </tr>
		<?php if( $show_current_store && $branch_name ){ ?>
		<tr>
		<td colspan="2">
		 <span><?php echo $branch_name; ?></span>
		 </td>
		 </tr>
		 <?php } ?>
		 <tr>
			<td>
				<strong><?php echo $sales_label; ?> No.:</strong>
			</td>
			<td>
				 #<?php echo $serial_number_link; ?>
			</td>
		 </tr>
		<?php if( $reference_label ){ ?>
		 <tr>
			<td>
				<strong><?php echo $reference_label; ?> No.:</strong>
			</td>
			<td>
				 #<?php echo $reference_serial_number; ?>
			</td>
		 </tr>
		 <?php } ?>
		 
		<?php if( $more_reference ){ echo $more_reference; } ?>
		 
		  <?php if( $hmo ){ ?>
		<tr>
			<td>
				<strong>Paying Institution:</strong>
			</td>
			<td>
				<?php 
					echo $hmo;// . ' (' . number_format( $hmo_percentage, 2 ) . '% covered)';
				?>
			</td>
		 </tr>
		 <?php } ?>
			 
		</table>
	<br />
	<div class1="row">
	   <div class1="col-xs-12">
			
		  <table class="table table-striped table-hover" style="position: relative; width: 100%; margin:0;" border="1" cellpadding="5" cellspacing="0" >
			 
			 <tbody>
				<tr class="thead">
				   <td style="text-align:center; font-weight:bold; <?php echo $table_header_style; ?>"><?php echo $item_label; ?></td>
				   
				   <td style="text-align:center; font-weight:bold; <?php echo $table_header_style; ?>">Amount Due</td>
				</tr>
				<?php 
				$total = 0;
				$serial = 0;
				$refund = 0;
				$subtotal = 0;
				$total_sub_quantity = 0;
				$cspan = 4;
				
				$line_discount = 0;
				$discount = 0;
				$discounted_amount = 0;
				$discount_type = "";
					
				//remove later
				$total_title = 'GRAND TOTAL';
				
				if( $show_sub_quantity )++$cspan;
				
				if( isset( $data["event_items"] ) && is_array( $data["event_items"] ) && ! empty( $data["event_items"] ) ){
					foreach( $data["event_items"] as $items ){
						++$serial;
						
						if( isset( $items["parent_item"] ) && $items["parent_item"] ){
							continue;
						}
						
						$price = $items["cost"];
						$q = $items["quantity"];
						if( isset( $items["quantity_returned"] ) )$q -= $items["quantity_returned"];
						
						$sub_quantity = 1;
						if( $show_sub_quantity ){
							if( isset( $items["sub_quantity"] ) )$sub_quantity = $items["sub_quantity"];
						}
						
						$dis = $items["discount"];
						$line_discount += $dis;
						
						$code_text = "";
						$title = "";
						if( $items[ $item_key ] == "refund" ){
							$title = "Customer Refund";
							$refund = 1;
						}else{
							
							$item_title = '';
							if( isset( $items["data"] ) && $items["data"] ){
								$items_data = ( isset( $items[ 'data' ] ) && is_array( $items[ 'data' ] ) ) ? $items[ 'data' ] : ( isset( $items[ 'data' ] ) ? json_decode( $items[ 'data' ], true ) : array() );
								
								//print_r($items_data);
								
								if( isset( $items_data["type"] ) ){
									switch( $items_data["type"] ){
									case "service_open":
										$item_title = isset( $items_data["desc"] )?$items_data["desc"]:'';
									break;
									case "fixed-asset":
										$item_title = isset( $items_data["desc"] )?$items_data["desc"]:'';
										if( isset( $items_data["comment"] ) && $items_data["comment"] )$item_title .= "<br /><small>" . $items_data["comment"] . "</small>";
									break;
									}
								}
								
								if( isset( $items_data["approval_code"] ) && $items_data["approval_code"] ){
									$code_text = "<br /><small>CODE: " . $items_data["approval_code"] . "</small>";
								}
								
								if( $no_cap_rev ){
									if( isset( $items_data["percentage_coverage"] ) && isset( $items_data["coverage_category"] ) && $items_data["coverage_category"] == "his_capitation" ){
										$cap_p = doubleval( $items_data["percentage_coverage"] );
										if( $cap_p ){
											$cap_price = ( $price * $cap_p / 100 );
											$price = $price - $cap_price;
											
											if( $show_cap_note ){
												$code_text = "<br /><small>LESS OF ".round($cap_p,2)."% CAP.: " . number_format($cap_price , 2) . "</small>";
											}
										}
									}
								}
							}
							
							if( ! $item_title ){
								$item_details = get_record_details( array( "id" => $items[ $item_key ], "table" => $record_details_table ) );
								$item_title = isset( $item_details["description"] )?$item_details["description"]:'';
								
								if( isset( $item_details["barcode"] ) && $item_details["barcode"] ){
									$item_title .= "<br /><strong><small>#" . $item_details["barcode"] . "</small></strong>";
								}
							}
							
							if( $item_title ){
								if( $show_image ){
									$title = '<a href="#" class="custom-single-selected-record-button" override-selected-record="' . $item_details["id"] . '" action="?module=&action=items&todo=view_item_details" title="View Details"><img src="' . $pr["domain_name"] . $item_details["image"] . '" width="'.$image_size.'" style="margin-right:5px; border:1px solid #aaa; '.( ( $image_size > 60 )?'clear:both; float:none; display:block;':'float:left;' ).' max-height:'.( $image_size * 2 ).'px;" />';
								}
								
								$title .= $item_title;
								
								if( $show_image ){
									$title .= '</a>';
								}
							}else{
								if( isset( $items["desc"] ) )$title = $items["desc"];
								else $title = ucwords( isset( $items["item_id"] ) ? $items["item_id"] : $items["item"] );
								
								if( isset( $items["barcode"] ) && $items["barcode"] ){
									$title .= "<br /><strong><small>#" . $items["barcode"] . "</small></strong>";
								}
							}
						}
						
						$title .= $code_text;
						
						$total_sub_quantity += ( $q * $sub_quantity );
						$total += ( $price * $q * $sub_quantity );
						?>
						<tr>
						   <td><?php echo $title; ?></td>
						   
						   <td align="right"><?php echo format_and_convert_numbers( ( ( $price * $q * $sub_quantity ) ), 4 ); ?></td>
						</tr>
						<?php
					}
					
					/*
					if( isset( $total_title ) && $total_title ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="<?php echo $cspan; ?>"><strong><?php echo $total_title; ?></strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total, 4 ); ?></strong></td>
					</tr>
					<?php
					}
					*/
					
					$subtotal = $total;
					
					$key = "discount"; 
					if( isset( $data["event"][$key] ) && $data["event"][$key] )
						$discount = doubleval( $data["event"][$key] );
					
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
					
					if( $line_discount ){
						if( ! $discount )$discount = $line_discount;
						$discounted_amount += $line_discount;
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
		  
		  <table class="table" style="position: relative; width: 100%; margin:0;" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
				   <td valign="bottom" style="margin:0; padding:0; border:none;" width="40%">
						<br />_____________________________<br />
						<strong>Sign: <?php echo $raised_by; ?></strong>
				   </td>
				   <?php echo $tr_oc; ?>
				   <td  style="margin:0; padding:0; border:none;"  width="60%">
					<?php
						if( ! $reference ){
					?>
					<table class="table" style="position: relative; width: 100%; margin:0;" border="1" cellpadding="5" cellspacing="0" >
						<?php if( ( ! $g_discount_after_tax && isset( $discount ) && $discount ) || $loyalty_points ){ ?>
						<tr>
							<td align="right"><strong>Sub-Total Amount:</strong>
							</td>
							<td align="right"><?php echo number_format( $subtotal , 2 ); ?>
							</td>
						</tr>
						<?php if( $loyalty_points ){ ?>
						<tr>
							<td align="right"><strong>Loyalty Points:</strong>
							</td>
							<td align="right"><?php echo number_format( $loyalty_points , 2 ); ?>
							</td>
						</tr>
						<?php } ?>
						<?php
						if( isset( $discount ) && $discount ){
							switch( $discount_type ){
							case "percentage":
								?>
								<tr>
									<td align="right"><strong><?php echo $discount."%" ?> Discount:</strong>
									</td>
									<td align="right"><?php echo number_format( $discounted_amount , 2 ); ?>
									</td>
								</tr>
								<?php
							break;
							default:
								?>
								<tr>
									<td align="right"><strong>Discount:</strong>
									</td>
									<td align="right"><?php echo number_format( $discounted_amount , 2 ); ?>
									</td>
								</tr>
								<?php
							break;
							}
						}
						?>
						<?php } ?>
						<?php if( $service_charge ){ ?>
							<tr>
								<td align="right"><strong>Service Charge <?php echo $iservice_charge; ?>%:</strong>
								</td>
								<td align="right"><?php echo number_format( $service_charge , 2 ); ?>
								</td>
							</tr>
						<?php
						}
						if( $service_tax ){
							?>
							<tr>
								<td align="right"><strong>Service Tax <?php echo $iservice_tax; ?>%:</strong>
								</td>
								<td align="right"><?php echo number_format( $service_tax , 2 ); ?>
								</td>
							</tr>
							<?php
						}
						if( $vat ){
							?>
							<tr>
								<td align="right"><strong>VAT <?php echo $ivat; ?>%:</strong>
								</td>
								<td align="right"><?php echo number_format( $vat , 2 ); ?>
								</td>
							</tr>
							<?php
						}
						if( $surcharge ){
							?>
							<tr>
								<td align="right"><strong>Service Charge:</strong>
								</td>
								<td align="right"><?php echo number_format( $surcharge , 2 ); ?>
								</td>
							</tr>
							<?php
						}
						if( $g_discount_after_tax && isset( $discount ) && $discount ){
						?>
							<tr>
								<td align="right"><strong>Sub-Total Amount:</strong>
								</td>
								<td align="right"><?php echo number_format( $subtotal , 2 ); ?>
								</td>
							</tr>
							<?php
							switch( $discount_type ){
							case "percentage_after_tax":
								?>
								<tr>
									<td align="right"><strong><?php echo $discount."%" ?> Discount:</strong>
									</td>
									<td align="right"><?php echo number_format( $discounted_amount , 2 ); ?>
									</td>
								</tr>
								<?php
							break;
							default:
								?>
								<tr>
									<td align="right"><strong>Discount:</strong>
									</td>
									<td align="right"><?php echo number_format( $discounted_amount, 2 ); ?>
									</td>
								</tr>
								<?php
							break;
							}
						}
						?>
						
						<tr>
							<td align="right"><strong><?php if( $refund )echo "Refund"; else echo "Net Total"; ?>:</strong>
							</td>
							<td align="right"><strong><?php echo $currency . format_and_convert_numbers( $total , 4 ); ?></strong></td>
						</tr>
						<tr>
							<td align="right" colspan="2"><?php echo strtoupper( convert_number_to_word( $total ) ) . ' NAIRA ONLY.'; ?></td>
						</tr>
						<?php if(  ( ! ( isset( $refund ) && $refund ) ) && $amount_paid ){ ?>
						<tr>
							<td align="right"><strong>PAID:</strong>
							</td>
							<td  style="border-right: none;" align="right"><?php echo format_and_convert_numbers( $amount_paid , 4 ); ?>
							</td>
						</tr>
						<?php if( ! $reference ){ ?>
						<?php 
							if( isset( $data['all_transactions']["amount_due"] ) && isset( $data['all_transactions']["amount_paid"] ) && isset( $data['all_transactions']["discount"] ) ){
						?>
							<tr>
								<td  align="right" style="border-right: none;" colspan="2">All Transaction Details
								</td>
							</tr>
							<tr>
								<td>Amount Due:
								</td>
								<td  style="border-right: none;" align="right"><?php echo format_and_convert_numbers( $data['all_transactions']["amount_due"] - $data['all_transactions']["discount"] , 4 ); ?>
								</td>
							</tr>
							<tr>
								<td align="right">Amount Paid:
								</td>
								<td  style="border-right: none;" align="right"><?php echo format_and_convert_numbers( $data['all_transactions']["amount_paid"] , 4 ); ?>
								</td>
							</tr>
							<?php 
								$ow = ( $data['all_transactions']["amount_due"] - $data['all_transactions']["discount"] ) - $data['all_transactions']["amount_paid"];
								if( $ow > 0 ){
							?>
							<tr>
								<td align="right">Total Debt:
								</td>
								<td  style="border-right: none;" align="right"><?php echo format_and_convert_numbers( $ow , 4 ); ?>
								</td>
							</tr>
							<?php
								}
								if( $ow < 0 ){
							?>
							<tr>
								<td align="right">Customer Bal.:
								</td>
								<td  style="border-right: none;" align="right"><?php $m = format_and_convert_numbers( $ow * -1 , 4 ); echo $m; ?>
								</td>
							</tr>
							<?php } ?>
							<?php
							}else{
								if( ! $refund ){
									$ow = $total - $amount_paid;
									
									if( $ow > 0 ){
								?>
								<tr>
									<td align="right">OWING:
									</td>
									<td  style="border-right: none;" align="right"><?php echo number_format( $ow , 2 ); ?>
									</td>
								</tr>
								<?php }
								
								if( $ow < 0 ){ ?>											
									<tr>
										<td align="right">Customer Bal.:
										</td>
										<td  style="border-right: none;" align="right"><?php echo number_format( abs( $ow ) , 2 ); ?>
										</td>
									</tr>
							<?php }
								}
							}
						?>
						<?php } ?>
						<?php }  ?>
						
					</table>
					<?php
						}
					?>
				   </td>
				</tr>
				
				<?php 
					$msg = get_sales_receipt_message_settings();
					if( $msg ){
				?>
				<tr>
					<td colspan="8" style="<?php echo get_sales_receipt_message_style_settings(); ?> border:none;"><?php echo $msg; ?></td>
				</tr>
				<?php } ?>
				
		  </table>
	   </div>
	</div>
	<?php if( isset( $show_printer ) && $show_printer ){ ?>
	<div class="row">
		<div class="col-md-12" style="text-align:right;">
		  <br>
		  <?php if( ! $backend ){ ?>
		  <a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
		 
		  <?php if( ! ( isset( $data["skip_timeout_script"] ) && $data["skip_timeout_script"] ) ){ ?>
		  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
		  <?php }?>
		  
		  <?php }else{ 
			if( isset( $data["event"]["id"] ) && $data["event"]["id"] ){
		  ?>
		  <!--<a href="../?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&pos=1" class="btn dark hidden-print"><i class="icon-print"></i></a>-->
		  <a href="#" override-selected-record="<?php echo $data["event"]["id"]; ?>" class="custom-single-selected-record-button btn btn-default hidden-print" action="?action=sales&todo=send_email">Send Email</a>
		  <?php if( ! ( isset( $hide_pos_print ) && $hide_pos_print ) ){ ?>
		  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&pos=1&type=<?php echo $print_type; ?>" target="_blank" class="btn dark hidden-print">POS Print Preview </a>
		  <?php } ?>
		  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&type=<?php echo $print_type; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a>
			<?php } ?>
		  <br /><br /><br />
		 <?php } ?>
		</div>
	</div>
	<?php } ?>
	
		<?php if( $amount_paid  || $show_owing ){ ?>
		<style type="text/css">
			.payment-status-container{
				background-image:url(<?php
					if( ( isset( $ow ) && $ow <= 0 ) || ( $total - $amount_paid ) <= 0 ){
						$stamp = "paid-in-full.png";
					}
					if( ! ( isset( $refund ) && $refund ) ){
						echo $pr["domain_name"]."images/" . $stamp;
					}
				?>);
				background-position:right top;  background-repeat:no-repeat;  background-size:100px;
			}
		  </style>
		<?php } ?>
	<?php }else{ ?>
		<div class="alert alert-danger">
			<h3>Cannot Retrieve Receipt</h3>
			<p>Multiple Records Selected, please do select a single sales record to view its receipt</p>
		</div>
	<?php } ?>
 </div>
</div>