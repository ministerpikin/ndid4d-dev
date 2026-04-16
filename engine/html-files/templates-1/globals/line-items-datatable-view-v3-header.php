<?php
	if( ! ( isset( $data['hide_title'] ) && $data[ 'hide_title' ] ) ){
		
		$bc = isset( $more_data["breadcrum"] )?$more_data["breadcrum"]:'';
		
?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between" <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<h4 id="datatable-title" class="mb-sm-0"><?php echo $bc; ?><strong classX="text-success"><?php if( isset( $data['title'] ) && $data['title'] )echo $data['title']; ?></strong></h4>
	<div class="page-title-actions page-title-right">
		<?php include "quick-select-view.php"; ?>
		<?php /*
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
			<li class="breadcrumb-item active">Datatables</li>
		</ol>
		*/ ?>
	</div>
</div>
<?php } ?>