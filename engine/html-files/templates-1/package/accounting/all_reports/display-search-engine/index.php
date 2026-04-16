<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	//$super = 1;
	//print_r( $access );
?>
	
<div style="margin:20px 30px;">
<div class="row">
	<div class="col-md-3">
		<h4><strong>Search Engine</strong></h4>
		<hr />
		<form class="activate-ajax" id="search-engine-form" method="post" action="?action=all_reports&todo=show_search_engine&html_replacement_selector=search-result-container">
			
			<label>Scan Asset Barcode</label>
			<input type="text" class="form-control" name="asset_barcode" placeholder="Scan Barcode" />
			<br />
		
			<label>Search Asset</label>
			<input type="text" class="form-control select2" placeholder="Search Asset" name="asset" action="?action=assets&todo=get_select2" />
			<br />
			<br />
			<label style="font-weight:normal;"><input type="checkbox" checked="checked" name="clear_results" class="form-controlx"> Always Clear Search Results</label>
			<br />
			<br />
			<!--search by staff, contractor, store etc-->
			<!--view type: details view or tabular view-->
			<input type="submit"  class="btn blue btn-block" value="Go &rarr;"/>
			<br />
		
		</form>
	</div>
	<div class="col-md-9">
		<div id="report-preview-container-id">
		<a href="#" class="btn btn-sm btn-default pull-left" onclick="nwSearchEngine.clearResults(); return false;">Clear Search Results</a>
		<?php 
			echo get_export_and_print_popup( ".table" , "#quick-print-container", "", 1 ) . "</div>"; 
		?>
		<div id="quick-print-container">
		
			<div id="search-result-container">
				
			</div>
		
		</div>
		</div>
		
	</div>
</div>
</div>

<script type="text/javascript" class="auto-remove">
	<?php 
		if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; 
	?>
</script>

</div>