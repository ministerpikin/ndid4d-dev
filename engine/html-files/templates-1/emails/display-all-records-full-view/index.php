<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>

<?php 
	$col_1 = 6;
	$col_2 = 6;
	if( isset( $data['col_1'] ) && isset( $data['col_2'] ) ){		
		$col_1 = $data['col_1'];
		$col_2 = $data['col_2'];
	}
	
	include dirname( dirname( dirname( __FILE__ ) ) ) . "/globals/all-records-datatable-full-view.php";
?>
<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>