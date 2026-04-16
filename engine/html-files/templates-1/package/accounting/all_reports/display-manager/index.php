<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	
	$action_to_perform = isset( $data["action_to_perform"] )?$data["action_to_perform"]:''; 
	
	$current_tab = '';
	$dp = '';
	
	$container2 = '';
	if( isset( $data["container"] ) ){
		$container2 = $data["container"] . '-sub';
	}
	
	include dirname( dirname( __FILE__ ) ) . "/sidemenu.php"; 
?>
<div class="app-main__outer">
	<!--content-->
	<div class="app-main__inner">
	   <div id="<?php echo $container2; ?>">
			
	   </div>
	</div>
	<!--content-->
</div>

</div>