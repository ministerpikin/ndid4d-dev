<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style>
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	// echo '<pre>';print_r( $data );echo '</pre>'; 
	$project = get_project_data();
	//echo '<pre>';print_r( $project );echo '</pre>'; 
	//echo '<pre>';print_r( $user_info );echo '</pre>'; 
	$container = 'dash-board-main-content-area';
	
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	
	include "main-body.php";
?>
<script type="text/javascript" >
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>