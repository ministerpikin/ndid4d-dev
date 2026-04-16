<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
	$ph = '';
	$ph1 = '';
	if( isset( $online_payment["online_payment"] ) && $online_payment["online_payment"] ){
		$ph = '<form class="activate-ajax" action="?action=online_payment&todo=save_payment_request" method="post">';
		$ph1 = '<br /><input type="submit" class="btn btn-primary blue" value="'. $online_payment["title"] .'" /></form>';
	}
	
	if( isset( $online_payment["payment_method"] ) && $online_payment["payment_method"] ){
		$pms = get_online_payment_methods();
		$ph .= '<strong>PAYMENT METHOD:</strong><br />' . ( isset( $pms[ $online_payment["payment_method"] ] )?$pms[ $online_payment["payment_method"] ]:$online_payment["payment_method"] ) . '<br />';
	}
	
	if( isset( $online_payment["payment_instructions"] ) && $online_payment["payment_instructions"] ){
		$ph .= '<strong>PAYMENT INSTRUCTIONS:</strong><br />' . $online_payment["payment_instructions"] . '<br />';
	}
	
	foreach( $online_payment as $ok => $ov ){
		$ph .= '<input type="hidden" name="'. $ok .'" value="'. $ov .'" />';
	}
	echo '<hr /><div style="text-align:right;">' . $ph . $ph1 . '</div>';
?>
</div>