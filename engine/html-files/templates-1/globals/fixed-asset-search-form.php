<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<form id="fixed-asset" class="account-list-form" style="margin-top:-10px;">
<input type="hidden" name="edit_id" class="form-control" value="" />
<input type="hidden" name="item" class="form-control clear-me" value="" />
<?php
	$input_action = '?action=assets_category&todo=get_select2';
	$new_button_action = '?action=assets_category&todo=new_popup_form';
	$new_button_title = 'Add New Asset Category';
	
	$asset_input_action = '?action=assets&todo=get_assets_select2';
	
	$submit_button_caption = 'Add Fixed Asset';
	$fas_key = 'submit_button_caption'; 
	if( ( isset( $fixed_asset_search_form[ $fas_key ] ) && $fixed_asset_search_form[ $fas_key ] ) ){
		$submit_button_caption = $fixed_asset_search_form[ $fas_key ];
	}
	
	$fa_desc_attr = '';
	$fa_desc_class = '';
	$fas_key = 'suggest_fixed_asset'; 
	//echo '<pre>'; print_r( $data ); echo '</pre>';
	if( ( isset( $data[ $fas_key ] ) && $data[ $fas_key ] ) ){
		switch( $data[ $fas_key ] ){
		case 1: case '1':
		case 2: case '2':
			$fa_desc_attr = $data[ $fas_key ];
		break;
		}
		
		if( $fa_desc_attr ){
			$fa_desc_attr = ' action="?action=assets&todo=get_assets_select2&purpose=' . $fa_desc_attr .'" minlength="0" data-params=".asset-cat" ';
			$fa_desc_class = 'select2';
		}
	}
	
	
	$fas_qty_label = 'Quantity';
	
	$fas_key = 'quantity_label'; 
	if( ( isset( $fixed_asset_search_form[ $fas_key ] ) && $fixed_asset_search_form[ $fas_key ] ) ){
		$fas_qty_label = $fixed_asset_search_form[ $fas_key ];
	}
	
?>
<h4 style="margin-top:20px; font-weight:bold !important;">Fixed Asset Details</h4>
<hr style="margin-top:0px;" />
<div class="input-groupX">
 <label classX="input-group-addon" styleX="color:#777;">Category <sup>*</sup></label>
 <input class="form-control select2 retain asset-cat" name="category" type="text" action="<?php echo $input_action; ?>&show_active=1" minlength="0" required="required" <?php $fas_key = 'category_attribute'; if( isset( $fixed_asset_search_form[ $fas_key ] ) )echo $fixed_asset_search_form[ $fas_key ]; ?> />
 <?php $fas_key = 'hide_new_category'; if( ! ( isset( $fixed_asset_search_form[ $fas_key ] ) && $fixed_asset_search_form[ $fas_key ] ) ){ ?>
 <span class="input-group-btn">
 <button class="btn dark custom-single-selected-record-button" override-selected-record="new" action="<?php echo $new_button_action; ?>" type="button" title="<?php echo $new_button_title; ?>"><i class="icon-plus"></i>&nbsp;</button>
 </span>
 <?php } ?>
</div>
<br />

<div class="input-groupX">
 <label classX="input-group-addon" styleX="color:#777;">Description <sup>*</sup></label>
 <input type="text" class="form-control <?php echo $fa_desc_class; ?>" name="description" required="required" <?php echo $fa_desc_attr; ?> <?php $fas_key = 'description_attribute'; if( isset( $fixed_asset_search_form[ $fas_key ] ) )echo $fixed_asset_search_form[ $fas_key ]; ?> />
</div>
<br />

<?php
	$fas_key = 'show_assign_assets'; 
	//$fixed_asset_search_form[ $fas_key ] = 1;
	if( ( isset( $fixed_asset_search_form[ $fas_key ] ) && $fixed_asset_search_form[ $fas_key ] ) ){
	?>
	<div class="input-groupX">
	 <label class="input-groupX-addonX" styleX="color:#777;">Specify Assets <sup>*</sup></label>
	 <input type="text" class="form-control select2" tags="true" action="?action=assets&todo=get_assign_assets_select2<?php echo $af_params; ?>" data-action="?action=assets&todo=get_assign_assets_select2<?php echo $af_params; ?>" minlength="0" name="fixed_asset" <?php $fas_key = 'fixed_asset_attribute'; if( isset( $fixed_asset_search_form[ $fas_key ] ) )echo $fixed_asset_search_form[ $fas_key ]; ?> />
	 <br /><i>NOTE: Only Unassigned Assets can be selected</i>
	</div>
	<br />
	<?php		
	}
?>	

<div class="row">
<?php $fas_key = 'hide_purchase_state'; if( ! ( isset( $fixed_asset_search_form[ $fas_key ] ) && $fixed_asset_search_form[ $fas_key ] ) ){ ?>
<div class="col-md-6 input-groupX">
 <label classX="input-group-addon" styleX="color:#777;">Purchase State</label>
  <select class="form-control" name="purchase_state" type="select">
	<?php
		$pm = get_purchase_state();
		
		if( isset( $pm ) && is_array( $pm ) ){
			foreach( $pm as $key => $val ){
				?>
				<option value="<?php echo $key; ?>">
					<?php echo $val; ?>
				</option>
				<?php
			}
		}
	?>
 </select>
</div>
<?php } ?>

<?php $fas_key = 'hide_condition'; if( ! ( isset( $fixed_asset_search_form[ $fas_key ] ) && $fixed_asset_search_form[ $fas_key ] ) ){ ?>
<div class="col-md-6  input-groupX">
 <label classX="input-group-addon" styleX="color:#777;">Condition</label>
  <select class="form-control" name="condition" type="select">
	<?php
		$pm = get_inventory_status();
		
		if( isset( $pm ) && is_array( $pm ) ){
			foreach( $pm as $key => $val ){
				?>
				<option value="<?php echo $key; ?>">
					<?php echo $val; ?>
				</option>
				<?php
			}
		}
	?>
 </select>
</div>
<?php } ?>
</div>
<br />

<?php $fas_key = 'hide_cost_price'; if( ! ( isset( $fixed_asset_search_form[ $fas_key ] ) && $fixed_asset_search_form[ $fas_key ] ) ){ 

$csup = '<sup>*</sup>';
$csup1 = ' required="required" min="0.01" ';
$fas_key = 'optional_cost_price'; if( ( isset( $fixed_asset_search_form[ $fas_key ] ) && $fixed_asset_search_form[ $fas_key ] ) ){
	$csup = '';
	$csup1 = '';
}
?>
<div class="input-groupX">
 <label classX="input-group-addon" styleX="color:#777;">Unit Price <?php echo $csup; ?></label>
 <input type="number" step="any" <?php echo $csup1; ?> class="form-control" name="cost_price" value="0.00" />
</div>
<br />
<?php } ?>

<div class="input-groupX">
 <label classX="input-group-addon" styleX="color:#777;"><?php echo $fas_qty_label; ?> <sup>*</sup></label>
 <input type="number" step="1" required="required" class="form-control" name="quantity" value="1" min="1" />
</div>


<br />

<div class="input-groupX">
 <label classX="input-group-addon" styleX="color:#777;">Comment</label>
 <input type="text" class="form-control" name="comment" />
</div>

<div id="fixed-asset-last-vendor" style="display:none;">
	<br />
	<div class="input-groupX">
	 <label classX="input-group-addon" styleX="color:#777;">Last Purchase Price</label>
	 <input type="number" step="any" readonly="true" class="form-control clear-me" name="last_cost_price" value="" />
	 <input type="hidden" class="form-control clear-me" name="price1" value="" />
	 <input type="hidden" class="form-control clear-me" name="dateprice1" value="" />
	 <input type="hidden" class="form-control clear-me" name="quantity_orderedprice1" value="" />
	</div>

	<br />
	<div class="input-groupX">
	 <label classX="input-group-addon" styleX="color:#777;">Last Vendor</label>
	 <input type="text" class="form-control clear-me" readonly="true" name="last_vendor_text" value="" />
	 <input type="hidden" class="form-control clear-me" name="last_vendor" value="" />
	</div>
</div>

<br />
<button type="submit" id="save-account-info" class="btn dark btn-block"><?php echo $submit_button_caption; ?></button>
</form>

<?php 
	if( isset( $fixed_asset_search_form ) )unset( $fixed_asset_search_form );
?>
</div>