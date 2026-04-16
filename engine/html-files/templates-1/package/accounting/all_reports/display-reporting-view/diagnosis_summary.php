<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	if( isset( $GLOBALS[ 'fields' ] ) && $GLOBALS[ 'fields' ] ){
		
		unset( $GLOBALS[ 'fields' ]["reference"] );
		unset( $GLOBALS[ 'fields' ]["reference_table"] );
		unset( $GLOBALS[ 'fields' ]["staff_responsible"] );
		
	}
	include "table_view.php";
?>
</div>