<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="invoice edit-invoice">
	<?php 
	//echo '<pre>'; print_r( $data ); echo '</pre>'; 
	// echo '<pre>'; print_r( $data["expenditure_ids"] ); echo '</pre>'; 
	$prevent_advance_edit = 0;
	$prevent_qty_edit = (isset( $data[ 'prevent_qty_edit' ] ) )? $data[ 'prevent_qty_edit' ] : 0;
	$extra_refs = '';
	if( isset( $data[ 'expenditure_ids' ] ) && $data[ 'expenditure_ids' ] ){
		foreach( $data[ 'expenditure_ids' ] as $ei ){
			$prfx = 'P';
			switch( $ei[ 'status' ] ){
			case "stock":
				$prfx = 'GR';
			break;
			case "draft-purchase-order":
			case "draft-purchase-ordered":
				$prfx = 'DP';
			break;
			case "returned":
				$prfx = 'CN';
			break;
			
			}
			$extra_refs .= ', #<a href="#" class="custom-single-selected-record-button" action="?action=expenditure&todo=view_invoice" target="_blank" override-selected-record="'.$ei["id"].'">'.mask_serial_number( $ei[ 'serial_num' ], $prfx ).'</a>';
			// We dont want the hmo percentage coverage to be changed due to the HMO claims report for bills with credit note
			$prevent_advance_edit = 1;
		}
	}
	// echo '<pre>'; print_r( $extra_refs ); echo '</pre>'; 

	//$prevent_qty_edit = 1;
	$item_label = (isset( $data[ 'item_label' ] ) && $data[ 'item_label' ])? $data[ 'item_label' ] : 'Add New Item';
	
	$allow_edit_qty_category = isset( $data[ 'allow_edit_qty_category' ] ) ? $data[ 'allow_edit_qty_category' ] : array();
	$prevent_edit_qty_category = isset( $data[ 'prevent_edit_qty_category' ] ) ? $data[ 'prevent_edit_qty_category' ] : array();
	
	$allow_edit_qty_type = isset( $data[ 'allow_edit_qty_type' ] ) ? $data[ 'allow_edit_qty_type' ] : array();
	
	$itm_params = isset( $data[ 'item_params' ] ) ? $data[ 'item_params' ] : '';
	$hide_item = isset( $data[ 'hide_item' ] ) ? $data[ 'hide_item' ] : '';
	$ex_v4 = get_match_expiry_dates_settings();
	?>
	<?php 
	if( isset( $data['event'] ) && $data['event'] ){ 
		$customer_id = isset( $data["event"]["customer"] )?$data["event"]["customer"]:'';
	?>
	<h4 style="text-align:center;"><strong>Edit #<?php echo $serial_number; ?></strong></h4><hr />
	
	<div id="basic-edit-con">
	<form class="activate-ajax form-inlinex" method="post" action="?action=sales&todo=update_edited_bill&show_dc=1">
	<input type="hidden" name="id" value="<?php echo $data["event"]["id"]; ?>" />
	
	<table class="table" style="position: relative; width: 100%; margin:0;" border="0" cellpadding="5" cellspacing="0">
	
	<tr>
		<td width="40%" style="padding:0; border:none;">
		  <?php echo  $customer_name . $group_text; ?>
		   
			<br /><?php $key = "comment"; $cval = ""; if( isset( $data["event"][$key] ) && $data["event"][$key] ){ $cval = $data["event"][$key]; } ?>
			<br /><strong><?php echo $comment_text; ?>:</strong><br />
			<textarea name="comment" class="form-control"><?php echo $cval;  ?></textarea><br />
			<?php if( $special_comment )echo '<br />'.$special_comment; ?>
			
	   </td>
	   <td style="padding:0; border:none;">
			<table class="table" style="position: relative; width: 100%; margin:0; background:#f7f7f7;" border="1" cellpadding="5" cellspacing="0">
			
			 <tr>
				<td>
					<strong><?php echo $sales_label; ?> No.:</strong>
				</td>
				<td>
					<?php //echo '<a href="#" class="custom-single-selected-record-button" action="?action=sales&todo=view_invoice" target="_blank" override-selected-record="'.$data["event"]["id"].'">'. $serial_number . '</a>'; ?>
					#<?php echo '<a href="#" class="custom-single-selected-record-button" action="?action=transactions&todo=view_details" target="_blank" override-selected-record="'.$data["event"]["id"].'">'. $serial_number . '</a>' . $extra_refs; ?>
				</td>
			 </tr>
			<?php if( $reference_label ){ ?>
			 <tr>
				<td>
					<strong><?php echo $reference_label; ?> No.:</strong>
				</td>
				<td>
					 #<?php echo $reference_serial_number . $extra_refs; ?>
				</td>
			 </tr>
			 <?php } ?>
			 
			 <tr>
				<td>
					<strong>Raised By:</strong>
				</td>
				<td>
					<?php echo $raised_by; ?>
				</td>
			 </tr>
			 <tr>
				<td>
					<strong>Staff Responsible:</strong>
				</td>
				<td>
					<?php 
						$key = "staff_responsible"; 
						if( isset( $data["event"][$key] ) && $data["event"][$key] ){
					?>
					<input type="text" name="staff_responsible" value="<?php echo $data["event"][$key];  ?>" label="<?php echo $staff_responsible; ?>" placeholder="<?php echo $staff_responsible; ?>" class="form-control select2" action="?action=users&todo=get_users_select2" />
					<?php } ?>
				</td>
			 </tr>
			 <tr>
				<td>
					<strong>Date:</strong>
				</td>
				<td>
					<?php 
						$key = "date"; 
						if( isset( $data["event"][$key] ) && doubleval( $data["event"][$key] ) ){
					?>
					<input type="date" name="date" value="<?php echo date("Y-m-d", doubleval( $data["event"][$key] ) );  ?>" class="form-control" placeholder="YY-MM-DD" />
					<?php } ?>
				</td>
			 </tr>
			 
			 <?php /* if( $hmo ){ ?>
			<tr>
				<td>
					<strong>Paying Institution:</strong>
				</td>
				<td>
					<?php 
						echo $hmo;
					?>
				</td>
			 </tr>
			<tr>
				<td>
					<strong>Modify Percentage Coverage:</strong>
				</td>
				<td>
					<input type="number" name="hmo_percentage" value="<?php echo $hmo_percentage;  ?>" class="form-control" />
				</td>
			 </tr>
			 <?php }  */ ?>
			 
			</table>
		  </td>
	   </tr>
	   <tr>
		  <td colspan="2" style="border:none;">&nbsp; </td>
	   </tr>
		
	</table>
	<?php  
	if( $hide_item ){ ?>
		<div class="note note-info"><?php echo $hide_item; ?></div>
	<?php }else{ ?>
	<div class="row">
	   <div class="col-md-4">
		<div class="input-groupx">
			<label><?php echo $item_label; ?></label><br />
			<input type="text" name="new_item" placeholder="<?php echo $item_label; ?>" class="form-control select2" action="?action=items&todo=get_items_select2&fields=selling_price<?php echo $itm_params; ?>" />
		</div>
	   </div>
	</div>
	<br />
	<?php } ?>
	
	<div class="row">
	   <div class="col-md-12">
			
		  <table class="table table-striped table-hover" style="position: relative; width: 100%; margin:0;" border="1" cellpadding="5" cellspacing="0" >
			 
			 <thead>
				<tr class="thead">
				   <td style="text-align:center; font-weight:bold;">#</td>
				   <td style="text-align:center; font-weight:bold;">Item</td>
				   <td style="text-align:center; font-weight:bold;" class="hidden-480"><?php echo $unit_type_text; ?></td>
				   <?php if( $show_sub_quantity ){ ?>
				   <td style="text-align:center; font-weight:bold;" class="hidden-480">C.O</td>
				   <?php } ?>
				   <td style="text-align:center; font-weight:bold;" class="hidden-480"><?php echo $price_label; ?></td>
				   <td style="text-align:center; font-weight:bold;" class="hidden-480 line-discount">Discount</td>
				   <td style="text-align:center; font-weight:bold;">Total</td>
				   <td style="text-align:center; font-weight:bold;"></td>
				</tr>
			 </thead>
			 <tbody id="line-items-body">
				<?php 
				$total = 0;
				$serial = 0;
				$refund = 0;
				$subtotal = 0;
				$total_sub_quantity = 0;
				$cspan = 4;
				
				//remove later
				$total_title = 'GRAND TOTAL';
				$dx = array();
				$ah = array(
					"composite" => 1,
					"end_date" => 1,
					"start_date" => 1,
					"coverage_category" => 1,
					"coverage_class" => 1,
					"percentage_coverage" => 1,
					"hmo_org" => 1,
					"hmo_id" => 1,
					"hmo_flag" => 1,
					"requires_code" => 1,
					"hmo_price" => 1,
					"approval_code" => 1,
					"package" => 1,
					"package_reference" => 1,
					"open_category" => 1,
					"category" => 1,
				);
				
		
				if( $show_sub_quantity )++$cspan;
				
				if( isset( $data["event_items"] ) && is_array( $data["event_items"] ) && ! empty( $data["event_items"] ) ){
					foreach( $data["event_items"] as $items ){
						++$serial;
						
						if( isset( $items["parent_item"] ) && $items["parent_item"] ){
							continue;
						}
						$pedit = $prevent_qty_edit;
						
						$components = 0;
						$open_service = 0;
						$price = $items["cost"];
						$cost_price = isset( $items["cost_price"] )?$items["cost_price"]:0;
						
						$q = $items["quantity"];
						if( isset( $items["quantity_returned"] ) )$q -= $items["quantity_returned"];
						
						$sub_quantity = 1;
						if( $show_sub_quantity ){
							if( isset( $items["sub_quantity"] ) )$sub_quantity = $items["sub_quantity"];
						}
						
						$dis = $items["discount"];
						
						$item_title_only = '';
						$title = "";
						$itype = '';
						$icat = '';
						
						if( $items[ $item_key ] == "refund" ){
							$title = "Customer Refund";
							$refund = 1;
						}else{
							
							$item_title = '';
							if( isset( $items["data"] ) && $items["data"] ){
								$items_data = json_decode( $items["data"], true );
								
								if( isset( $items_data["type"] ) ){
									$itype = $items_data["type"];
									
									switch( $items_data["type"] ){
									case "service_open":
										$item_title = isset( $items_data["desc"] )?$items_data["desc"]:'';
										$open_service = $item_title;
										$item_title_only = $item_title;
										$icat = $itype;
									break;
									case "fixed-asset":
										$item_title = isset( $items_data["desc"] )?$items_data["desc"]:'';
										$item_title_only = $item_title;
										if( isset( $items_data["comment"] ) && $items_data["comment"] )$item_title .= "<br /><small>" . $items_data["comment"] . "</small>";
									break;
									}
								}
							
								if( isset( $items_data["components"] ) && is_array( $items_data["components"] ) && ! empty( $items_data["components"] ) ){
									foreach( $items_data["components"] as $cmpt ){
										if( doubleval( $cmpt["csp"] ) ){
											$components = 1;
											break;
										}
									}
								}
							}
							
							if( ! $item_title ){
								$item_details = get_record_details( array( "id" => $items[ $item_key ], "table" => $record_details_table ) );
								$item_title = isset( $item_details["description"] )?$item_details["description"]:'';
								$item_title_only = $item_title;
								
								if( isset( $item_details["barcode"] ) && $item_details["barcode"] ){
									$item_title .= "<br /><strong><small>#" . $item_details["barcode"] . "</small></strong>";
								}
								
								if( isset( $item_details["category"] ) ){
									$icat = $item_details["category"];
								}
								if( isset( $item_details["type"] ) && $item_details["type"] ){
									$itype = $item_details["type"];
								}
								//print_r($item_details);
							}
							
							if( ! $pedit ){
								//print_r($allow_edit_qty_type);
								if( $icat && isset( $prevent_edit_qty_category[ $icat ] ) ){
									$pedit = 1;
								}else if( $icat && $allow_edit_qty_category && ! isset( $allow_edit_qty_category[ $icat ] ) ){
									$pedit = 1;
								}else if( $itype && $allow_edit_qty_type && ! isset( $allow_edit_qty_type[ $itype ] ) ){
									$pedit = 1;
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
								else $title = ucwords( $items["item_id"] );
								
								$item_title_only = $title;
								
								if( isset( $items["barcode"] ) && $items["barcode"] ){
									$title .= "<br /><strong><small>#" . $items["barcode"] . "</small></strong>";
								}
							}
						}
						
						$total_sub_quantity += ( $q * $sub_quantity );
						$total += ( $price * $q * $sub_quantity ) - $dis;
						
						$dx[ $items["id"] ] = array(
							"id" => $items[ $item_key ],
							"price_old" => $price,
							"price" => $price,
							"cost_price" => $cost_price,
							"quantity" => $q,
							"quantity_old" => $q,
							"total" => ( $price * $q * $sub_quantity ) - $dis,
							"discount" => $dis,
							"discount_old" => $dis,
							"desc" => $item_title_only,
							"description" => $item_title_only,
							"type" => $itype,
							"open_service" => $open_service,
							"old" => 1,
							"components" => $components,
							"prevent_qty_edit" => $pedit,
						);
						
						foreach( $ah as $ak => $av ){
							if( isset( $items_data[ $ak ] ) ){
								
								switch( $ak ){
								case 'hmo_id':
									$dx[ $items["id"] ]["hmo_id_text"] = get_name_of_referenced_record( array( "id" => $items_data[ $ak ], "table" => "hmo" ) );
									$dx[ $items["id"] ]["hmo_id_old"] = $items_data[ $ak ];
								break;
								case 'package':
									$dx[ $items["id"] ]["package_old"] = $items_data[ $ak ];
									
									$dx[ $items["id"] ]["package_text"] = get_name_of_referenced_record( array( "id" => $items_data[ $ak ], "table" => "packaged_services" ) );
								break;
								}
								
								$dx[ $items["id"] ][ $ak ] = $items_data[ $ak ];
							}
						}
						
						/*
						?>
						<tr>
						   <td><?php echo $serial; ?></td>
						   <td><?php if( $open_service ){
							   ?>
							   <input type="text" class="form-control data-change" value="<?php echo $open_service; ?>" id="desc" key="<?php echo $item_key; ?>" />
							   <?php
						   }else{
							 echo $title;  
						   } ?></td>
						   <td align="right" class="hidden-480"><input type="number" class="form-control data-change" step="any" min="0" value="<?php echo $q; ?>" id="quantity" key="<?php echo $item_key; ?>" /></td>
						   <?php if( $show_sub_quantity ){ ?>
						   <td align="right" class="hidden-480"><input type="number" class="form-control data-change" step="any" min="0" value="<?php echo $sub_quantity; ?>" id="sub_quantity" key="<?php echo $item_key; ?>" /></td>
						   <?php } ?>
						   <td align="right" class="hidden-480"><input type="number" class="form-control data-change" step="any" min="0" value="<?php echo $price; ?>" id="cost" key="<?php echo $item_key; ?>" /></td>
						   <td align="right" class="hidden-480 line-discount"><input class="form-control data-change" type="number" step="any" min="0" value="<?php echo $dis; ?>" id="discount" key="<?php echo $item_key; ?>" /></td>
						   <td align="right" class="total-<?php echo $item_key; ?>" ><?php echo format_and_convert_numbers( ( ( $price * $q * $sub_quantity ) - $dis ), 4 ); ?></td>
						</tr>
						<?php
						*/
					}
				}
				?>
			 </tbody>
			 
		  </table>
		
		</div>
	</div>
	
	<input type="hidden" name="quantity" value="" />
	<input type="hidden" name="amount_due" value="" />
	<textarea style="display:none;" name="items_data"><?php echo json_encode( $dx ); ?></textarea>
	<br />
	<input type="submit" value="Save Changes & Regenerate Bill" class="btn pull-leftx blue" />
	</form>
	</div>
	
	<div id="advance-edit-con" style="display:none;">
	<h4 style="text-align:center;"><strong><span class="advance-desc"></span> - Advance Options</strong></h4>
	<a href="#" class="btn red" onclick="nwInvoiceEdit.showAdvanceEdit(0); return false;" >Cancel & Close Advance Window</a><br /><br />
	<form class="activate-ajax1 client-form" method="post" id="advance-edit-form">
		<input type="hidden" name="service_id" value="" class="form-control package-info" />
		<input type="hidden" name="service_qty" value="" class="form-control package-info" />
		<input type="hidden" name="new_idx" value="" class="form-control" />
		<input type="hidden" name="mode" value="hmo-form" class="form-control" />
		
		<?php
			$transaction_options = array( 
				0 => array( "label" => "HMO Details", "value" => "ap-hmo", "onchange" => 'nwInvoiceEdit.changeSearchMode(this)', "checked" => ' checked="checked" ' ),
				1 => array( "label" => "Package Details", "value" => "ap-package", "onchange" => 'nwInvoiceEdit.changeSearchMode(this)' ),
			);
		?>
		<div class="form-group" style="background: #fff; margin-top: -10px;  padding-top: 5px; clear: both; border: 1px solid #ddd; display: block; padding-left: 15px;">
		  <div class="radio-listx">
			<?php 
				foreach( $transaction_options as $opt ){
			?>
				<label class="radio-inlinex" style="margin-right:15px;">
				 <input type="radio" name="param_type" id="radio-<?php echo $opt["value"]; ?>" value="<?php echo $opt["value"]; ?>" <?php if( isset( $opt["checked"] ) )echo $opt["checked"]; ?> onchange="<?php echo $opt["onchange"]; ?>"> <small><?php echo $opt["label"]; ?></small>
				 </label>
					<?php
				}
			?>
		  </div>
	   </div>
	   <div class="advance-param-form" id="ap-hmo">
	   
			<h4>HMO PARAMETERS</h4><hr />
			<div class="row">
			   <div class="col-md-4">
					<div class="calculated-item-con">
					<label>Payer</label><br />
					<div>
					<input type="text" name="hmo_id" placeholder="Payer" class="form-control select2 allow-clear data-form optional-field" id="hmo_id_field" action="?action=hmo&todo=get_select2" />
					</div>
					<i>Leave this field blank to convert the bill to private paying</i>
					</div>
			   </div>
			   <div class="col-md-4">
					<label>Coverage Class</label><br />
					<select name="coverage_class" class="form-control data-form optional-field">
						<option value=""></option>
					<?php 
						$ss = get_hospital_hmo_services_classification();
						foreach( $ss as $sk => $sv ){
							echo '<option value="'. $sk .'">'.$sv.'</option>';
						}
					?>
					</select>
			   </div>
			   <div class="col-md-4">
					<label>Coverage Category</label><br />
					<select name="coverage_category" class="form-control data-form optional-field">
						<option value=""></option>
					<?php 
						$ss = get_hospital_payment_category_for_his_settings();
						foreach( $ss as $sk => $sv ){
							echo '<option value="'. $sk .'">'.$sv.'</option>';
						}
					?>
					</select>
			   </div>
			</div>
			<br />
			
			<div class="row">
			   <div class="col-md-4">
					<label>Percentage Coverage</label><br />
					<input type="number" name="percentage_coverage" class="form-control data-form optional-field" step="any" max="100" min="0" />
			   </div>
			   <div class="col-md-4">
					<label>Auth Code</label><br />
					<input type="text" name="approval_code" class="form-control data-form optional-field" />
			   </div>
			   <div class="col-md-4">
					<label>Remark</label><br />
					<input type="text" name="hmo_comment" class="form-control data-form optional-field" />
			   </div>
			</div>
			<br />
		</div>
		<div class="advance-param-form" id="ap-package" style="display:none;">
			<h4>PACKAGE PARAMETERS</h4><hr />
			<div class="note note-warning">HMO Parameters will not take effect when package is selected</div>
			<div class="row">
			   <div class="col-md-4">
					<div class="calculated-item-con use-service-id">
						<label>Active Package</label><br />
						<div>
						<input type="text" name="package" placeholder="Active Package" class="form-control select2 allow-clear data-form optional-field" id="b-package" action="?action=assigned_packaged_services&todo=get_select2&type=active&customer=<?php echo $customer_id; ?>" minlength="0" data-params=".package-info" />
						</div>
					</div>
			   </div>
			</div>
			<br />
		</div>
		
		<input type="submit" value="Save Options & Close Advance Window" class="btn dark" />
	</form>
	</div>
	<script type="text/javascript" class="auto-remove">
		<?php 
			echo 'var ex_v4 = '. $ex_v4 .';';
			if( file_exists( dirname( __FILE__ ).'/invoice-script.js' ) )include "invoice-script.js"; 
		?>
	</script>
	
	<?php }else{ ?>
		<div class="alert alert-danger">
			<h3>Cannot Retrieve Receipt</h3>
			<p>Multiple Records Selected, please do select a single sales record to view its receipt</p>
		</div>
	<?php } ?>
 </div>
</div>