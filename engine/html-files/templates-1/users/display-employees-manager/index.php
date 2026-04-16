<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	//echo '<pre>';print_r( $data );echo '</pre>'; 
	$minimal = 0;
	if( isset( $data['frontend'] ) && $data['frontend'] ){
		//$minimal = 1;
		$action = '';
		if( isset( $data[ 'action_to_perform' ] ) && $data[ 'action_to_perform' ] && isset( $data[ 'html_replacement_selector' ] ) && $data[ 'html_replacement_selector' ] ){
			$action = '?action=users&todo='. $data[ 'action_to_perform' ] .'&html_replacement_selector='. $data[ 'html_replacement_selector' ] .'&frontend=1';
		}
?>
<div class="frontend-view">
<h4 style="text-align:center;"><strong><?php if( $action )echo '<a href="#" title="Re-open Window" class="custom-single-selected-record-button" override-selected-record="-" action="'. $action .'"><i class="icon-refresh"></i></a>&nbsp;'; ?>Personnel Manager</strong></h4><br />
<?php }else{ ?>
<div class="row">
<div class="col-md-12">

<div class="portlet grey box">
<div class="portlet-title">
	<div class="caption"><small>Employees Manager</small></div>
</div>
<div class="portlet-body" style="padding-bottom:50px;">
<?php } ?>

<?php include "content.php"; ?>

<?php 
	if( isset( $data['frontend'] ) && $data['frontend'] ){
?>
</div>

<?php }else{ ?>
</div>
</div>
</div>
</div>
<?php } ?>

<script type="text/javascript" class="auto-remove">
	var g_site_url = "<?php echo $site_url; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>