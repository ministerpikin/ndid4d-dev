<?php
	if( ! ( isset( $data['hide_title'] ) && $data[ 'hide_title' ] ) ){
?>
<div class="portlet box grey" >
	<div class="portlet-title">
		<div class="caption">
			<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
				<small>
				<span id="datatable-title"><?php if( isset( $data['title'] ) && $data['title'] )echo $data['title']; ?></span>
				</small>
			</div>
		</div>
		<?php include "quick-select-view.php"; ?>
		<small style="float:right; font-size:11px; max-width:550px;">
			<small id="search-title">
				<?php if( defined( "SEARCH_QUERY" ) )echo SEARCH_QUERY; ?>
			</small>
		</small>
	</div>
<?php
	}else{
		?>
		<div class="portlet">
		<?php
	}
	?>
	<div class="portlet-body" style="/* padding-bottom:50px; */" id="data-table-section">
	<?php
	if( isset( $data['html'] ) && $data['html'] ){
		echo $data['html'];
	}
	include "datatable-record-details-popup.php";
	?>
	</div>
</div>

