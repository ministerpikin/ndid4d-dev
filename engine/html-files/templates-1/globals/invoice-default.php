<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="invoice">
	<?php if( isset( $data['event'] ) && $data['event'] ){ ?>
	
	<?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "i_menu_order" ){ ?>
		<div class="note note-danger alert alert-danger">
			<h3>Pending Confirmation</h3>
			<p>This transaction is not recorded in the Financial Accounting Ledger until it is marked as delivered</p>
		</div>
	<?php } ?>
	
	<?php 
	//echo '<pre>';print_r(json_encode($data)); echo '</pre>'; ?>
	<?php
		if( isset( $data["more_actions"] ) && strip_tags($data["more_actions"]) ){
			echo '<div id="nwp-i-mbtn-'. $data["event"]["id"] .'">' . $data["more_actions"] . '<br /><br /></div>';
		}
		
		if( isset( $show_header ) && $show_header ){
			include "invoice-header.php";
		}
		
		$tr_oc = '';
		$c_cols = 'col-xs-6';
		$cw2 = '';
		$cw1 = '';
		if( isset( $no_column ) && $no_column ){
			$c_cols = 'col-xs-12';
			$tr_oc = '</tr><tr>';
			$cw2 = '</h4>';
			$cw1 = '<h4>';
			
		}

		if( $frm_mobile ){
			$show_costing = 0;
			$show_sn = 0;
			$unit_type_text = 0;
		}
	?>
	
	<table class="table" style="position: relative; width: 100%; margin:0;" border="0" cellpadding="5" cellspacing="0">
	<?php 
		if( $show_signature ){
	?>
		<tr>
		<td style="border: none;" width="40%">
		<?php $key = "comment"; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ ?>
		<strong><?php echo $comment_text; ?>:</strong><br /><?php echo nl2br( $data["event"][$key] ); ?>
	   <?php } ?>
	   <?php if( $special_comment )echo '<br />'.$special_comment; ?>
	   </td>
	   <?php if( $hmo ){ ?>
		<td style="border: none;">
			<strong>Paying Institution:</strong><br />
			<?php 
				echo $hmo;// . ' (' . number_format( $hmo_percentage, 2 ) . '% covered)';
			?>
		</td>
	 <?php } ?>
	<?php 
		}else{
	?>
	<?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "unvalidated_sales_order" ){ ?>
	 <tr>
	  <td colspan="2" style="border:none;">
		<?php if( isset( $data["validate_invoice"] ) && $data["validate_invoice"] ){ ?>
		<div id="validate_prompt_container">
		<div class="alert alert-danger note note-danger">
			<h4>Validate Invoice</h4>
			<p>NOTE: This process cannot be reversed. Please ensure that everything is ok before validating the invoice</p><br />
			<a href="#" class="btn btn-sm red custom-single-selected-record-button" override-selected-record="<?php echo $data["event"]["id"]; ?>" action="?action=sales&todo=validate_sales_invoice">Validate Invoice Now</a>
			<a href="#" class="btn btn-sm dark custom-single-selected-record-button-old" override-selected-record="<?php echo $data["event"]["id"]; ?>" action="?action=void_transactions&todo=return_sales_invoice&html_replacement_selector=validate_prompt_container" mod="sales">Return This Invoice</a>
		</div>
		</div>
		<?php }else{ ?>
		<div class="alert alert-danger note note-danger">
			<h4>Unvalidated Invoice</h4>
			<p>The Invoice has not yet been validated by the accounting / internal audit team</p>
			<p>The Invoice must be validated before any further actions can be performed</p>
		</div>
		<?php } ?>
	  </td>
   </tr>
	<?php } ?>
	
	<tr>
		<?php 
		$cval1 = '';
		$key = "sales_status"; 
		if( isset( $data["event"][$key] ) && $data["event"][$key] == "booked" ){
			$bookings = 1; 
			$cval1 = '<span class="pull-right label label-info"><small><strong><i class="icon-pushpin"></i> booked</strong></small></span> <br /> '.$cw1 . $customer_name . $group_text . $cw2;
		}
		$cval1 .= $cw1 . $customer_name . $group_text . $cw2;
		if( $extra_customer_data ){
			$cval1 .= '<br />'.$extra_customer_data;
		}

		$ccomment = '';
		$key = "comment"; 
		if( isset( $data["event"][$key] ) && $data["event"][$key] ){
			$ccomment .= nl2br( $data["event"][$key] );
		}
		if( $special_comment ){
			$ccomment .= '<br />'.$special_comment;
		}

		if( ! $frm_mobile ){ ?>
		<td width="40%" style="padding:0; border:none;">
		  <strong><?php echo $customer_label; ?></strong> <?php echo $cval1 ?>
		   
			<?php if( $ccomment ){ ?>
		 	<br /><strong><?php echo $comment_text; ?>:</strong> <?php echo '<br />'. $ccomment ?>
			<?php } ?>
			
			<?php $key = "sales_status"; if( ( isset( $data["i_menu_delivered"] ) && $data["i_menu_delivered"] ) && isset( $data["event"][$key] ) && $data["event"][$key] == "i_menu_order" ){ ?>
			<br /><br /><a class="btn red custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=deliver_e_menu_order" href="#"> Mark As Delivered</a>
			<?php } ?>
				
	   </td>
		<?php } ?>

	   <?php echo $tr_oc; ?>
	   <?php if( $show_right_column ){ ?>
	   <td style="padding:0; border:none;">
			<table class="table" style="position: relative; width: 100%; margin:0; background:#f7f7f7;" border="1" cellpadding="5" cellspacing="0">
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
				<td class="nwp-serial-num">
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
			 
			 <?php if( $show_raised_by ){ ?>
			 <tr class="tr-raised-by">
				<td>
					<strong>Raised By:</strong>
				</td>
				<td>
					<?php echo $raised_by; ?>
				</td>
			 </tr>
			 <?php } ?>
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
			<?php if( $even_more_reference ){ echo $even_more_reference; } ?>
				 
			 <?php if( $show_buttons || $bookings ){ ?>
			  <tr>
			  <td colspan="2" style="border: none;">
				
				<?php if( $bookings ){ ?>
				<a class="btn btn-sm green custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=deliver_booked_sale" href="#"> Mark As Delivered</a>
				<?php } ?>
				
				<?php if( $show_buttons ){ ?>
				<a class="btn btn-sm dark default custom-single-selected-record-button" override-selected-record="<?php $key = "id"; if( isset( $data["event"][$key] ) )echo $data["event"][$key]; ?>" action="?module=&action=sales&todo=delete_app_sales" href="#"><i class="icon-trash"></i> Delete</a>
				<?php } ?>
				
				</td>
			
			 </tr>
			<?php } ?>

			<?php if( function_exists( 'get_extra_invoice_header_rows' ) ){ 
				$apms = array();
				if( $frm_mobile ){
					$apms[] = array(
						'key' => $customer_label,
						'value' => $cval1,
					);
					if( $ccomment ){
						$apms[] = array(
							'key' => $comment_text,
							'value' => $ccomment,
						);
					}
				}

				$noh = 0;
				$dtt = 0;
				$key = "date"; 
				if( isset( $_GET["hyella_no_header"] ) && $_GET["hyella_no_header"] ){
					$noh = 1;
				}
				if( isset( $_GET["nwp_request"] ) && $_GET["nwp_request"] ){
					$noh = 1;
				}
				if( $noh ){
					if( isset( $data["event"][ 'date' ] ) && doubleval( $data["event"][ 'date' ] ) ){
						$dtt = doubleval( $data["event"][ 'date' ] );
					}else{
						if( isset( $data["event"][ 'creation_date' ] ) && doubleval( $data["event"][ 'creation_date' ] ) ){
							$dtt = doubleval( $data["event"][ 'creation_date' ] );
						}
					}
					if( $dtt ){
						$apms[] = array(
							'key' => 'Date',
							'value' => date("j M Y H:i", doubleval( $dtt ) ),
						);
					}
				}

					// echo '<pre>';print_r( $apms ); echo '</pre>';
				if( ! empty( $apms ) ){
					echo get_extra_invoice_header_rows( $apms );
				}
			} ?>
			
			</table>
		  </td>
		  <?php } ?>
	   </tr>
	   <tr>
		  <td colspan="2" style="border:none;">&nbsp; </td>
	   </tr>
		
	<?php 
		}
	?>
	</table>
	
	<div class1="row">
	   <div class1="col-xs-12">
			
		  <table class="table table-striped table-hover" style="position: relative; width: 100%; margin:0;" border="1" cellpadding="5" cellspacing="0" >
			 
			 <tbody>
				<tr class="thead">
				   <?php if( $show_sn ){ ?>
				   <td style="text-align:center; font-weight:bold; <?php echo $table_header_style; ?>">#</td>
				   <?php } ?>
				   <td style="text-align:center; font-weight:bold; <?php echo $table_header_style; ?>"><?php echo $item_label; ?></td>
				   <?php if( $unit_type_text ){ ?>
				   <td style="text-align:center; font-weight:bold; <?php echo $table_header_style; ?>" class="hidden-480 td-unit_type_text"><?php echo $unit_type_text; ?></td>
				   <?php } ?>
				    
				   <?php if( $show_costing ){ ?>
				   <?php if( $show_sub_quantity ){ ?>
				   <td style="text-align:center; font-weight:bold; <?php echo $table_header_style; ?>" class="hidden-480">C.O</td>
				   <?php } ?>
				   <td style="text-align:center; font-weight:bold; <?php echo $table_header_style; ?>" class="hidden-480"><?php echo $price_label; ?></td>
				   <td style="text-align:center; font-weight:bold; <?php echo $table_header_style; ?>" class="hidden-480 line-discount">Discount</td>
				   <?php } ?>
				   <?php if( $show_total_column ){ ?>
				   <td style="text-align:center; font-weight:bold; <?php echo $table_header_style; ?>">Total</td>
				   <?php } ?>
				</tr>
				<?php 
				$total = 0;
				$serial = 0;
				$refund = 0;
				$subtotal = 0;
				$total_sub_quantity = 0;
				$cspan = 4;
				
				$discount = 0;
				$discounted_amount = 0;
				$discount_type = "";
					
				//remove later
				$total_title = 'GRAND TOTAL';
				
				if( $show_sub_quantity )++$cspan;
				
				$icat_cstore = get_current_store();
				if( isset( $_GET[ 'imenu_store' ] ) ){
					$icat_cstore = $_GET[ 'imenu_store' ];
				}
				
				if( isset( $data["event_items"] ) && is_array( $data["event_items"] ) && ! empty( $data["event_items"] ) ){
					$sen = 0;
					$edv = array();
					$exist = array();
					foreach( $data["event_items"] as $items ){
						++$sen;
						if( ! isset( $items[ 'expiry_date' ] ) )$items[ 'expiry_date' ] = '';
						if( ! isset( $items[ 'reduction_type' ] ) )$items[ 'reduction_type' ] = '';
						if( ! isset( $items[ 'reference_table' ] ) )$items[ 'reference_table' ] = '';
						if( ! isset( $items[ 'reference' ] ) )$items[ 'reference' ] = '';
						if( ! isset( $items[ 'child_reference' ] ) )$items[ 'child_reference' ] = '';
						if( ! isset( $items[ 'child_reference_table' ] ) )$items[ 'child_reference_table' ] = '';
						if( ! isset( $items[ 'batch_number' ] ) )$items[ 'batch_number' ] = '';
						
						$kkey = $items[ 'item' ].$items[ 'reduction_type' ].$items[ 'expiry_date' ].$items[ 'batch_number' ].$items[ 'reference_table' ].$items[ 'reference' ].$items[ 'child_reference' ].$items[ 'child_reference_table' ];
						if( isset( $exist[ $kkey ] ) && $exist[ $kkey ] ){
							// code...
							$items[ 'reduction' ] = doubleval( $items[ 'reduction' ] ) + $exist[ $kkey ][ 'reduction' ];
							$items[ 'amount_due' ] = doubleval( $items[ 'amount_due' ] ) + $exist[ $kkey ][ 'amount_due' ];
							$items[ 'quantity' ] = doubleval( $items[ 'quantity' ] ) + $exist[ $kkey ][ 'quantity' ];
							$items[ 'quantity_left' ] = doubleval( $items[ 'quantity_left' ] ) + $exist[ $kkey ][ 'quantity_left' ];
							$items[ 'amount_due' ] = doubleval( $items[ 'amount_due' ] ) + $exist[ $kkey ][ 'amount_due' ];
							$exist[ $kkey ] = $items;
							--$sen;
						}else{
							$exist[ $kkey ] = $items;
						}

						
						$add_item2 = 1;
						if( $seperate_items_bcat && $icat_cstore ){
							$item_details2 = get_record_details( array( "id" => $items[ $item_key ], "table" => $record_details_table ) );
							if( isset( $item_details2["category"] ) && $item_details2["category"] ){
								$icat = get_record_details( array( "id" => $item_details2["category"], "table" => 'category' ) );
								if( isset( $icat["store"] ) && $icat["store"] != $icat_cstore ){
									$add_item2 = 0;
								}
							}
						}
						if( $add_item2 ){
							$edv[ $sen ] = $items;
						}
						
						$q = $items["quantity"];
						if( isset( $items["quantity_returned"] ) )$q -= $items["quantity_returned"];
						
						if( isset( $items["data"] ) && $items["data"] ){
							$items_data = ( isset( $items[ 'data' ] ) && is_array( $items[ 'data' ] ) ) ? $items[ 'data' ] : ( isset( $items[ 'data' ] ) ? json_decode( $items[ 'data' ], true ) : array() );
							
							if( isset( $items_data["components"] ) && is_array( $items_data["components"] ) && ! empty( $items_data["components"] ) ){
								$ken0 = $items["cost"];
								$ken1 = $items["cost"] - $items["discount"];
								
								if( isset( $items_data["standard_price"] ) && $items_data["standard_price"] && isset( $items_data["hmo_price"] ) && $items_data["hmo_price"] && $items["cost"] == $items_data["hmo_price"] ){
									$ken0 = ( $items_data["standard_price"] );
									$ken1 = $items["cost"];
								}
								
								$sen1 = 0;

								// echo '<pre>'; print_r($items_data["components"]); echo '</pre>';
								foreach( $items_data["components"] as $cmpt ){
									if( doubleval( $cmpt["csp"] ) ){
										if( $ken0 > 0 ){
											$cdis = $cmpt["csp"] - ( $cmpt["csp"] * $ken1 / $ken0 );
										}else{
											$cdis = $cmpt["csp"];
										}
										$edv[] = array(
											"component" => $sen .'.'. ++$sen1,
											"item_id" => $cmpt["cid"],
											"quantity" => $q,
											"cost" => $cmpt["csp"],
											"discount" => $cdis,
											"desc" => isset( $cmpt[ 'cde' ] ) ? $cmpt[ 'cde' ] : '',
										);
									}
								}
							}else if( isset( $items_data["component"] ) && $items_data["component"] ){
								unset( $edv[ $sen ] );
							}
						}
					}
					
					if( ! empty( $edv ) ){
					foreach( $edv as $items ){
						$continue = 0;
						
						// echo '<pre>'; print_r($items); echo '</pre>';
						if( isset( $items["parent_item"] ) && $items["parent_item"] ){
							
							$ptype = isset( $items["product_type"] ) ? $items["product_type"] : '';
							switch( $ptype ){
							case 'sub_item':
							case 'admission2':
							break;
							default:
								$continue = 1;
							break;
							}
						}
						
						if( $continue ){
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
						
						$plain_text = "";
						$code_text = "";
						$title = "";
						if( $items[ $item_key ] == "refund" ){
							$title = "Customer Refund";
							$plain_text = "Customer Refund";
							$refund = 1;
						}else{
							
							$item_title = '';
							if( isset( $items["data"] ) && $items["data"] ){
								$items_data = ( isset( $items[ 'data' ] ) && is_array( $items[ 'data' ] ) ) ? $items[ 'data' ] : ( isset( $items[ 'data' ] ) ? json_decode( $items[ 'data' ], true ) : array() );
								//echo '<pre>';print_r($items_data);echo '</pre>';
								
								$sub_item = 0;
								if( isset( $items_data["type"] ) ){
									switch( $items_data["type"] ){
									case "service_open":
										$plain_text = isset( $items_data["desc"] )?$items_data["desc"]:'';
										$item_title = $plain_text;
									break;
									case "fixed-asset":
										$plain_text = isset( $items_data["desc"] )?$items_data["desc"]:'';
										$item_title = $plain_text;
									break;
									case 'sub_item':
										$sub_item = 1;
									break;
									}
								}

								if( isset( $items_data["comment"] ) && $items_data["comment"] ){
									$items["comment"] = $items_data["comment"];
								}
								if( isset( $items_data[ 'product_type' ] ) ){
									switch( $items_data[ 'product_type' ] ){
									case 'sub_item':
										// This means the bill has already been edited so sub_item calculation had already occured unpon initial save
										// No need to recalculate quantity and price again
										// $sub_item = 1; 
										$plain_text = isset( $items_data["desc"] )?$items_data["desc"]:$plain_text;
										$item_title = $plain_text;
									break;
									}
								}

								if( $sub_item ){

									if( isset( $items_data[ 'sub_unit' ] ) && doubleval( $items_data[ 'sub_unit' ] ) ){

										$item_title = isset( $items_data["desc"] )?$items_data["desc"]:'';
										$q = doubleval( $q ) / doubleval( $items_data[ 'sub_unit' ] );
										$price = doubleval( $price ) * doubleval( $items_data[ 'sub_unit' ] );
										
										// $record_details_table = 'sub_items';
										// $items[ $item_key ] = $items_data[ 'id' ];

									}
								}
								
								if( isset( $items_data["approval_code"] ) && $items_data["approval_code"] ){
									$code_text = "<br /><small>CODE: " . $items_data["approval_code"] . "</small>";
								}
								
								if( $no_cap_rev ){
									$a_no_cap_rev = 1;
									if( isset( $items_data["requires_code"] ) && $items_data["requires_code"] ){
										$a_no_cap_rev = 0;
										if( isset( $items_data["approval_code"] ) && $items_data["approval_code"] ){
											$a_no_cap_rev = 1;
										}
									}
									
									if( $a_no_cap_rev && isset( $items_data["percentage_coverage"] ) && isset( $items_data["coverage_category"] ) && $items_data["coverage_category"] == "his_capitation" ){
										$cap_p = doubleval( $items_data["percentage_coverage"] );
										if( $cap_p ){
											$cap_price = ( $price * $cap_p / 100 );
											$price = $price - $cap_price;
											
											if( $show_cap_note ){
												$code_text .= "<br /><small>Less of ".  number_format($cap_price , 2) ." being ".round($cap_p,2)."% CAP.</small>";
											}
										}
									}
								}
							}
							
							if( ! $item_title ){
								$item_details = get_record_details( array( "id" => $items[ $item_key ], "table" => $record_details_table ) );
								
								$plain_text = isset( $item_details["description"] )?$item_details["description"]:'';
								$item_title = $plain_text;
								
								if( isset( $item_details["barcode"] ) && $item_details["barcode"] ){
									$item_title .= "<br /><strong><small>#" . $item_details["barcode"] . "</small></strong>";
								}
							}
							
							if( $item_title ){
								if( $show_image ){
									$title = '<a href="#" class="custom-single-selected-record-button" override-selected-record="' . $item_details["id"] . '" action="?module=&action=items&todo=view_item_details" title="View Details"><img src="' . $pr["domain_name"] . $item_details["image"] . '" width="'.$image_size.'" style="margin-right:5px; border:1px solid #aaa; '.( ( $image_size > 60 )?'clear:both; float:none; display:block;':'float:left;' ).' max-height:'.( $image_size * 2 ).'px;" />';
								}
								
								$set_title = 1;
								if( isset( $items[ 'reduction_type' ] ) ){
									switch( $items[ 'reduction_type' ] ){
									case 'admission2':
										if( isset( $items[ 'item_desc' ] ) && $items[ 'item_desc' ] ){
											$set_title = 0;
											$title .= $items[ 'item_desc' ];
										}
									break;
									}
								}

								if( $set_title ){
									$title .= $item_title;
								}
								
								if( $show_image ){
									$title .= '</a>';
								}
							}else{
								if( isset( $items["desc"] ) ){
									$plain_text = $items["desc"];
								}else{
									$plain_text = isset( $items[ 'item_desc' ] ) && $items[ 'item_desc' ] ? $items[ 'item_desc' ] : ( ucwords( isset( $items["item_id"] ) ? $items["item_id"] : $items["item"] ) );
								}
								// echo '<pre>'; print_r($items); echo '</pre>';
								
								$title = $plain_text;
								if( isset( $items["barcode"] ) && $items["barcode"] ){
									$title .= "<br /><strong><small>#" . $items["barcode"] . "</small></strong>";
								}
							}
						}
						
						if( isset( $items["comment"] ) && $items["comment"] ){
							$title .= "<br /><small>" . $items["comment"] . "</small>";
						}
						$title .= $code_text;
						
						$sid2 = isset( $items[ "id" ] )?$items[ "id" ]:'';
						$sty = '';
						if( isset( $items["component"] ) && $items["component"] ){
							$sid2 = $items["component"];
							$sn = $items["component"];
							$sty = 'font-weight:bold; font-style:italic; text-decoration:line-through; ';
						}else{
							++$serial;
							$sn = $serial;
							$total_sub_quantity += ( $q * $sub_quantity );
							$total += ( $price * $q * $sub_quantity ) - $dis;
						}
						
						$ltv = ( ( $price * $q * $sub_quantity ) - $dis );
						$returned_data["items"][ $sid2 ] = array(
							"id" => $sid2,
							"key" => $items[ $item_key ],
							"title" => $plain_text,
							"full_title" => $title,
							"quantity" => $q,
							"sub_quantity" => $sub_quantity,
							"price" => $price,
							"discount" => $dis,
							"total" => $ltv,
						);
						?>
						<tr style="<?php echo $sty; ?>">
				   		   <?php if( $show_sn ){ ?>
						   <td><?php echo $sn; ?></td>
						   <?php } ?>
						   <td><?php echo $title; ?></td>
						   <?php if( $unit_type_text ){ ?>
						   <td align="right" class="hidden-480 td-unit_type_text"><?php echo number_format( $q, 2 ); ?></td>
						   <?php } ?>
						   
						   <?php if( $show_costing ){ ?>
							   <?php if( $show_sub_quantity ){ ?>
							   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $sub_quantity , 4 ); ?></td>
							   <?php } ?>
							   <td align="right" class="hidden-480"><?php echo format_and_convert_numbers( $price, 4 ); ?></td>
							   <td align="right" class="hidden-480 line-discount"><?php echo format_and_convert_numbers( $dis , 4 ); ?></td>
						   <?php } ?>
						   <?php if( $show_total_column ){ ?>
						   <td align="right"><?php echo format_and_convert_numbers( $ltv, 4 ); ?></td>
						   <?php } ?>
						</tr>
						<?php
					}
					}else{
						if( $seperate_items_bcat && $icat_cstore ){
							echo '<style>#nwp-i-minv-'. $data["event"]["id"] .', #nwp-i-mbtn-'. $data["event"]["id"] .'{display:none ! important;}</style>';
						}
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
					if( $show_sub_quantity ){
					?>
					<tr>
					   <td></td>
					   <td align="right" colspan="<?php echo $cspan; ?>"><strong>TOTAL CUTTING ORDER</strong></td>
					   <td align="right"><strong><?php echo format_and_convert_numbers( $total_sub_quantity, 4 ); ?></strong></td>
					</tr>
					<?php
					}
					
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
		  
		  <?php if( isset( $show_bottom_section ) && $show_bottom_section ){ ?>
		  <table class="table" style="position: relative; width: 100%; margin:0;" border="0" cellpadding="5" cellspacing="0">
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<?php 
					$estyll = 'position: relative; width: 100%; margin:0; border-right: none;';
					$paidAttr = '';
					if( ! $frm_mobile ){ ?>
				   	<td valign="top" style="margin:0; padding:0; border:none;" width="40%">
					<?php }else{
						$estyll = 'position: relative; width: 100%; margin:0;';
						$paidAttr = 'align="right"';
					}
					?>
					<?php if(  ( ! ( isset( $refund ) && $refund ) ) && $amount_paid ){ ?>
					<?php if( ! $frm_mobile ){ ?>
					<table class="table" style="<?php echo $estyll ?>" border="1" cellpadding="5" cellspacing="0" >
						
					<?php include "payment-details-tb.php"; ?>
					</table>
					<?php }  ?>
					<?php }  ?>
					<?php if( ! $frm_mobile ){ ?>
				   	</td>
					<?php } ?>
				   	<?php echo $tr_oc; ?>
					<?php if( ! $frm_mobile ){ ?>
					<td  style="margin:0; padding:0; border:none;"  width="60%">
					<?php }  ?>
					<?php
				// echo '<pre>'; print_r($loyalty_points); echo '</pre>';
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
						<?php 
						if( $loyalty_points ){ ?>
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
							<td align="right"><?php echo $currency . format_and_convert_numbers( $total , 4 ); ?>
							</td>
						</tr>
						
						<?php if( $show_raised_by ){ ?>
							<tr class="tr-raised-by">
								<td align="right"><strong>By:</strong>
								</td>
								<td align="right">
								<?php 
								if( $staff_responsible ){ 
									echo $staff_responsible;
								}else{ 
									echo $raised_by; 
								}
								?>
								</td>
							</tr>
							<?php $key = "validated_by"; if( isset( $edata[$key] ) && $edata[$key] ){ ?>
							<tr>
								<td align="right"><strong>Validated By:</strong>
								</td>
								<td align="right">
								<?php echo get_name_of_referenced_record( array( "id" => $edata[$key], "table" => "users" ) ); ?>
								</td>
							</tr>
							<?php } ?>
						<?php } ?>

						<?php if(  ( ! ( isset( $refund ) && $refund ) ) && $amount_paid ){ ?>
							<?php if( $frm_mobile ){ ?>
								<?php include "payment-details-tb.php"; ?>
							<?php }  ?>
						<?php }  ?>
					</table>
					<?php
						}
					?>
					<?php if( ! $frm_mobile ){ ?>
				   	</td>
					<?php }  ?>
				</tr>
				
				<?php 
					$msg = '';
					if( $get_receipt_message ){
						$msg = get_sales_receipt_message_settings();
					}
					if( $msg ){
				?>
				<tr>
					<td colspan="8" style="<?php echo get_sales_receipt_message_style_settings(); ?> border:none;"><?php echo $msg; ?></td>
				</tr>
				<?php } ?>
				
				<?php if( $show_signature ){ ?>
				 <tr>
				 <td colspan="8" style="margin:0; padding:0; border:none;">
				<table class="table" style="position: relative; width: 100%; margin:0;" border="0" cellpadding="5" cellspacing="0">
				<?php $key = "sales_status"; if( isset( $data["event"][$key] ) && $data["event"][$key] == "booked" ){ $bookings = 1; ?>
				<tr>
					<td colspan="2"><strong><i class="icon-pushpin"></i> booked</strong></td>
				</tr>
				<?php } ?>
				<tr>
					<td style="border: none;">
					<?php if( $staff ){ ?>
						Compliment to Staff<br />
						<strong><?php echo get_select_option_value( array( "id" => $staff, "function_name" => "get_employees" ) ); ?></strong>
					<?php }else{ ?>
						<?php echo $customer_label; ?><br /><strong><?php echo $customer_name; ?></strong>
					<?php } ?>
					<br /><br />
					<strong>Signature:</strong>____________________
					</td>
					
					<td style="border: none;">Raised By:<br /><strong><?php echo $raised_by; ?></strong>
					<br /><br />
					<strong>Signature:</strong>____________________
					</td>
				</tr>
				</table>
				</td>
				</tr>
				<?php } ?>
				
		  </table>
		  <?php } ?>
	   </div>
	</div>
	<?php if( isset( $show_printer ) && $show_printer ){ ?>
	<div class="row">
		<div class="col-md-12" style="text-align:right;">
		  <br>
		  <?php if( ! $backend ){ ?>
		  <a class="<?php if( isset( $print_btn_cls ) )echo $print_btn_cls; ?> hidden-print" onclick="javascript:window.print();">Print Invoice <i class="icon-print"></i></a>
		 
		  <?php if( ! ( isset( $data["skip_timeout_script"] ) && $data["skip_timeout_script"] ) ){ ?>
		  <script type="text/javascript">setTimeout( function(){ window.print(); } , 800 );</script>
		  <?php }?>
		  
		  <?php }else{ 
			if( isset( $data["event"]["id"] ) && $data["event"]["id"] ){
		  ?>
		  <!--<a href="../?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&pos=1" class="btn dark hidden-print"><i class="icon-print"></i></a>-->
		  <a href="#" override-selected-record="<?php echo $data["event"]["id"]; ?>" class="custom-single-selected-record-button btn btn-default hidden-print" action="?action=sales&todo=send_email">Send Email</a>
		  <?php 
		  $hide_pr = 0;
		  if( $frm_mobile && defined( 'HYELLA_V3_HIDE_PRINT_ON_MOBILE_RECEIPT' ) && HYELLA_V3_HIDE_PRINT_ON_MOBILE_RECEIPT ){
			  $hide_pr = 1;
		  }
		  	if( ! $hide_pr ){ 
			  	if( ! ( isset( $hide_pos_print ) && $hide_pos_print ) ){ 
				  ?>
				  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&pos=1&type=<?php echo $print_type; ?>" target="_blank" class="btn dark hidden-print">POS Print Preview </a>
				  <?php } ?>
				  <a href="<?php echo $pr["domain_name"]; ?>print.php?page=print-invoice&record_id=<?php echo $data["event"]["id"]; ?>&type=<?php echo $print_type; ?>" target="_blank" class="btn blue hidden-print">Print Preview <i class="icon-print"></i></a>
				<?php 
				} 
			} 
			?>
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