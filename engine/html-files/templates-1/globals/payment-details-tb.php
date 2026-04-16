<?php 
$pstyll = '';
if( $frm_mobile ){
	$pstyll = 'text-align:center;';
} ?>
<tr>
	<td colspan="2" style="border-right: none;<?php echo $pstyll; ?>">
		<strong>Payment Details</strong>
	</td>
</tr>
<tr>
	<td <?php echo $paidAttr; ?>><strong>PAID:</strong>
	</td>
	<td  style="border-right: none;" align="right"><?php echo format_and_convert_numbers( $amount_paid , 4 ); ?>
	</td>
</tr>
<?php if( ! $reference ){ ?>
<?php 
	if( isset( $data['all_transactions']["amount_due"] ) && isset( $data['all_transactions']["amount_paid"] ) && isset( $data['all_transactions']["discount"] ) ){
	?>
	<tr>
		<td  style="border-right: none;" colspan="2">All Transaction Details
		</td>
	</tr>
	<tr>
		<td>Amount Due:
		</td>
		<td  style="border-right: none;" align="right"><?php echo format_and_convert_numbers( $data['all_transactions']["amount_due"] - $data['all_transactions']["discount"] , 4 ); ?>
		</td>
	</tr>
	<tr>
		<td>Amount Paid:
		</td>
		<td  style="border-right: none;" align="right"><?php echo format_and_convert_numbers( $data['all_transactions']["amount_paid"] , 4 ); ?>
		</td>
	</tr>
	<?php 
		$ow = ( $data['all_transactions']["amount_due"] - $data['all_transactions']["discount"] ) - $data['all_transactions']["amount_paid"];
		if( $ow > 0 ){
	?>
	<tr>
		<td>Total Debt:
		</td>
		<td  style="border-right: none;" align="right"><?php echo format_and_convert_numbers( $ow , 4 ); ?>
		</td>
	</tr>
	<?php
		}
		if( $ow < 0 ){
	?>
	<tr>
		<td>Customer Bal.:
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
			<td>OWING:
			</td>
			<td  style="border-right: none;" align="right"><?php echo number_format( $ow , 2 ); ?>
			</td>
		</tr>
		<?php }
		
		if( $ow < 0 ){ ?>											
			<tr>
				<td>Customer Bal.:
				</td>
				<td  style="border-right: none;" align="right"><?php echo number_format( abs( $ow ) , 2 ); ?>
				</td>
			</tr>
	<?php }
		}
	}
?>
<?php } ?>