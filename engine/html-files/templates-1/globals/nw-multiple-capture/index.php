<div <?php set_hyella_source_path( __FILE__, 1 ); ?> id="">
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
$table = isset( $data[ 'table' ] ) && $data[ 'table' ] ? $data[ 'table' ] : array();
$child_table = isset( $data[ 'child_table' ] ) && $data[ 'child_table' ] ? $data[ 'child_table' ] : array();

$form = isset( $data[ 'form' ] ) && $data[ 'form' ] ? $data[ 'form' ] : '';
$child_forms = isset( $data[ 'child_forms' ] ) && $data[ 'child_forms' ] ? $data[ 'child_forms' ] : array();
$default_values = isset( $data[ 'default_values' ] ) && $data[ 'default_values' ] ? $data[ 'default_values' ] : array();
// echo '<pre>';print_r($data);echo '</pre>';  
$html_replacement_selector = 'nw-custom-caprute-form';

if( $form && ! empty( $child_forms ) && $table && ! empty( $child_table ) ){
?>
<div class="row" id="<?php echo $html_replacement_selector; ?>">
	<div class="col-md-12" style="display: grid;">
		<div class="tabbable tabbable-custom" id="transaction-tabs">
			<ul class="nav nav-tabs" id="nw-tabs">

			   <li class="tabbs active"><a data-toggle="tab" href="#table-parent" onclick="">Create New</a></li>

			</ul>
			<div id="nw-tabs-body" class="tab-content resizable-height" style="overflow-y:auto; overflow-x:hidden;">
				<div class="tab-pane active" id="table-parent">
					<div id="custom-form-contaner">
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	foreach( $child_forms as &$cfs ){
		$cfs = urlencode( $cfs );
	}
?>

<script type="text/javascript" >
	var default_values = '<?php echo json_encode( $default_values ); ?>';

	var child_forms = '<?php echo json_encode( $child_forms ); ?>';
	var form = '<?php echo urlencode( $form ); ?>';

	var child_table = '<?php echo json_encode( $child_table ); ?>';
	var table = '<?php echo $table; ?>';

	var html_replacement_selector = '<?php echo $html_replacement_selector; ?>';

<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
<?php } ?>
</div>