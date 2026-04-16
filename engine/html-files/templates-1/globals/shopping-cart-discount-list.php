<option value="0">-Select Discount-</option>
<?php
	if( isset( $data['discount'] ) && is_array( $data['discount'] ) && ! empty( $data['discount'] ) ){
		foreach( $data['discount'] as $key => $val ){
			?>
			<option class=" item-discount-<?php echo isset( $val['item'] )?$val['item']:''; ?> customer-discount-<?php echo isset( $val['customer'] )?$val['customer']:''; ?> customer-category-discount-<?php echo isset( $val["customer_category"] )?$val["customer_category"]:''; ?>" <?php if( isset( $val['minimum_quantity'] ) ){ ?> data-minimum_quantity="<?php echo $val['minimum_quantity']; ?>"<?php } ?> <?php if( isset( $val['line_discount'] ) ){ ?> data-line_discount="<?php echo $val['line_discount']; ?>"<?php } ?> <?php if( $val["value_of_sale"] ){ ?>data-minvalue="<?php echo $val["value_of_sale"]; ?>"<?php } ?> data-type="<?php echo $val["type"]; ?>" id="discount-<?php echo $val["id"]; ?>" value="<?php echo $val["id"]; ?>" data-value="<?php echo $val["value"]; ?>" <?php if( isset( $val["customer_category"] ) ){ ?>data-customer_category="<?php echo $val["customer_category"]; ?>"<?php } ?> <?php if( isset( $val["item_quantity_discounted"] ) ){ ?>data-item_quantity_discounted="<?php echo $val["item_quantity_discounted"]; ?>"<?php } ?>>
				<?php 
					switch( $val["type"] ){
					case 'surcharge_percentage':
					case 'percentage_after_tax':
					case 'percentage':
						echo $val["name"] . ' ( '.format_and_convert_numbers( $val["value"], 4 ).' % )';
					break;
					default:
						echo $val["name"] . ' ( '.format_and_convert_numbers( $val["value"], 4 ).' )';
					break;
					}
				?>
			</option>
			<?php
		}
	}
?>