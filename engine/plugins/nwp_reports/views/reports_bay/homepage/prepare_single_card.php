<!-- <div <?php //set_hyella_source_path( __FILE__, 1 ); ?>> -->
	<?php 
		// echo '<pre>';print_r( $data );echo '</pre>';
		if( isset($data['rdata']) && $data['rdata'] ){
			include 'prepard_card.php';
			echo $__prepareCard( $data['rdata']);
		}else{ ?>

		<?php }
	?>
<!-- </div> -->