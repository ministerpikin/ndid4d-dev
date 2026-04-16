<?php
	if( ! ( isset( $data['hide_title'] ) && $data[ 'hide_title' ] ) ){
		
		$bc = isset( $more_data["breadcrum"] )?$more_data["breadcrum"]:'';
		
?>
<div class="page-table-wrapper" style=" background:#fff; border: 1px solid #ddd; ">
<div class="page-title-wrapperX " style="background: #efefef;  padding: 1px 15px;  /*  border-radius: 10px 10px 0 0 !important; */   box-shadow: 0px 2px 1px 1px #d8d8d8;">
	<div class="page-title-headingX ">
		<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
			<h4 id="datatable-title" style="font-size:17px;"><?php echo $bc; ?><strong classX="text-success"><?php if( isset( $data['title'] ) && $data['title'] )echo $data['title']; ?></strong></h4>
			<div class="page-title-subheading"><small id="search-title">
				<?php if( defined( "SEARCH_QUERY" ) )echo SEARCH_QUERY; ?>
			</small>
			</div>
		</div>
	</div>
	<div class="page-title-actions" style="float: right; margin-top: -36px;">
		<?php include "quick-select-view.php"; ?>
	</div>
</div>
<?php }else{
	?><div><?php
} ?>
	<div style="padding:15px;" id="data-table-section" class=" shopping-cart-tableX">
	<?php
	if( isset( $data['html'] ) && $data['html'] ){
		echo $data['html'];
	}
	include "datatable-record-details-popup.php";
	?>
	</div>
</div>