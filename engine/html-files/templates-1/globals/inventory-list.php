<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
								// echo '<pre>';print_r( $data );echo '</pre>'; 
	$q = $sval["quantity"];
	if( isset( $sval["quantity_used"] ) )$q -= $sval["quantity_used"];
	if( isset( $sval["quantity_sold"] ) )$q -= $sval["quantity_sold"];
	if( isset( $sval["quantity_picked"] ) )$q -= $sval["quantity_picked"];
	if( isset( $sval["quantity_damaged"] ) )$q -= $sval["quantity_damaged"];
	
if( isset( $sval["avg_cost_price"] ) ){
	$sval["cost_price"] = $sval["avg_cost_price"];
}
	$price = $sval["selling_price"];
	if( $sval["type"] == "raw_materials" || ( isset( $use_cost_price ) && $use_cost_price ) ){
		$price = $sval["cost_price"];
	}
?>
<a class="item <?php if( isset( $active ) && $active )echo "active"; ?> cart-item-select <?php echo $sval["category"]." barcode-".$sval["barcode"]; ?>" href="#" id="<?php echo $sval["item"]; ?>-container" data-id="<?php echo $sval["item"]; ?>" data-max="<?php echo $q; ?>" data-price="<?php echo $sval["selling_price"]; ?>" data-cost="<?php echo $sval["cost_price"]; ?>" style="margin-left:0; padding-left:0;" <?php foreach( $sval as $ck => $cv ){ echo ' data-' . $ck . '="'.$cv.'" '; } ?> data-unit_of_measure_text="<?php if( isset( $sval["unit_of_measure"] ) && isset( $unit_of_measure[ $sval["unit_of_measure"] ] ) )echo $unit_of_measure[ $sval["unit_of_measure"] ]; else echo "-"; ?>" data-color_of_gold-text="<?php if( isset( $sval["color_of_gold"] ) && isset( $color[ $sval["color_of_gold"] ] ) )echo $color[ $sval["color_of_gold"] ]; ?>" data-category-text="<?php if( isset( $cat[ $sval["category"] ] ) )echo $cat[ $sval["category"] ]; ?>" title="<?php echo $sval["description"]; ?>">

<div class="col-md-12" style="margin-left:0; padding-left:0;">
<div class="row" style="margin-left:0; padding-left:0;">
	<div class="col-xs-3" style="margin-left:0; padding-left:0;">
		<div class="item-image-1">
			<img src="<?php echo $site_url . $sval["image"]; ?>" id="<?php echo $sval["item"]; ?>-image" >
		</div>
	</div>
	<div class="col-xs-9">
	  <span class="b-c"><span class="badge quantity-select badge-roundless badge-success"></span></span>
		
	  <p class="item-title" id="<?php echo $sval["item"]; ?>-title">
		<?php 
		echo $sval["description"];

		if( get_match_expiry_dates_settings() == 2 ){
			echo '&nbsp;<button href="#" class="custom-single-selected-record-button" style="border: none;" action="?action=inventory_ent&todo=select_expiry_batch'. ( isset( $data[ 'store' ] ) ? '&current_store='.$data[ 'store' ] : '' ) .'" override-selected-record="'. $sval["item"] .'" title="Select Expiry Date/Batch Number for this Item" ><i class="icon-external-link"></i></button>';
		}
		?>
	  </p>
	  
	  <code class="pull-right">
	  <?php switch( $sval["type"] ){
			case "service":
	  ?>
	  <strong>SERVICE
	  <?php break;
			case "composite":
				$q = 0;
			default:
	  ?>IN-STOCK: <strong class="stock-levels"><?php echo format_and_convert_numbers( $q , 1 ); 
			break;
	  }
	  ?></strong></code>
	  
	  <?php if( isset($sval["weight_in_grams"]) ){ ?><code class="pull-right" style="color:#2725c7;"><?php echo number_format( $sval["weight_in_grams"], 2 ); ?>g</code><?php } ?>
	  
	  
	  <?php if( isset( $sval["unit"] ) && $sval["unit"] && isset( $sval["unit_of_measure"] ) && $sval["unit_of_measure"] ){ ?>
	  <code class="pull-right"><?php echo $sval["unit"] . $sval["unit_of_measure"]; ?></code>
	  <?php } ?>
	  
	  
	  <?php if( $sval["barcode"] ){ ?> <code class="pull-right" style="color:#2725c7;"><?php echo $sval["barcode"]; ?></code> <?php } ?>
	  
	  <p class="item-price">
		<span class="badge badge-primary pull-left" ><strong><?php echo format_and_convert_numbers( $price, 4 ); ?></strong></span>
	  </p>
	</div>
</div>
</div>

</a>
</div>