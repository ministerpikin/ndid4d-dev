<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	if( isset( $data[ 'function' ] ) && function_exists( $data[ 'function' ] ) ){
		echo '<pre>';print_r( $data[ 'function' ]() );echo '</pre>'; 
	}
?>
<script type="text/javascript" >
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>