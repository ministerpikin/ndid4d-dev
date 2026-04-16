<div <?php set_hyella_source_path( __FILE__, 1 ); ?> style="width:100%;">
<?php 
	// echo '<pre>';print_r( $data );echo '</pre>';
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	
	$action_to_perform = isset( $data["action_to_perform"] )?$data["action_to_perform"]:''; 
	$current_tab = isset( $data["current_tab"] )?$data["current_tab"]:'report'; 
	
	$dp = '';
	
	$container2 = '';
	if( isset( $data["container"] ) ){
		$container2 = $data["container"] . '-sub';
	}

	include dirname( dirname( __FILE__ ) ) . "/sidemenu.php"; 
	
	include "content-new.php";
?>
</div>