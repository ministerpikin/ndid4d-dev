<tr class="<?php echo $class; ?> item-expense" id="<?php echo $sval["id"]; ?>" data-date="<?php echo date( "Y-m-d" , doubleval( $sval["date"] ) ); ?>" data-description="<?php echo $sval["description"]; ?>" data-vendor="<?php echo $sval["vendor"]; ?>" data-category_of_expense="<?php echo $sval["category_of_expense"]; ?>" data-mode_of_payment="<?php echo $sval["mode_of_payment"]; ?>" data-amount_due="<?php echo $sval["amount_due"]; ?>" data-amount_paid="<?php echo $sval["amount_paid"]; ?>" data-receipt_no="<?php echo $sval["receipt_no"]; ?>" data-staff_in_charge="<?php echo $sval["staff_in_charge"]; ?>">
  <td><?php echo date( $date_filter , doubleval( $sval["date"] ) ); ?></td>
  <td>
	<?php 
		if( $type == "year" ){
			echo ( isset( $categories[ $sval['category_of_expense'] ] )?$categories[ $sval['category_of_expense'] ]:$sval['category_of_expense'] );
		}else{
			echo $sval["description"]; ?><br />{<strong><?php echo ( isset( $vendors[ $sval["vendor"] ] )?$vendors[ $sval["vendor"] ]:$sval["vendor"] ); ?></strong>}
	<?php } ?>
  </td>
   
  <td class="r"><?php echo format_and_convert_numbers( $sval["amount_due"], 4 ); ?></td>
  <td class="r"><?php echo format_and_convert_numbers( $sval["amount_due"] - $sval["amount_paid"], 4 ); ?></td>
 
</tr>