<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>

<div class="row">
	<div class="col-md-10  col-md-offset-2">
		<h4>Select import type below:</h4>
		<hr />
	</div>
</div>

<br />
<div class="row">
	
	<div class="col-md-2 col-md-offset-2">
		<a href="#" class="custom-single-selected-record-button btn-block btn dark btn-lg" action="?action=import_items&todo=excel_import_form_options" override-selected-record="map">Import with Mapping</a>
	</div>
	
	<div class="col-md-2">
		<a href="#" class="custom-single-selected-record-button btn-block btn dark btn-lg" action="?action=import_items&todo=excel_import_form_options" override-selected-record="no-map">Import without Mapping</a>
	</div>
	
	<div class="col-md-2">
		<a href="#" class="custom-single-selected-record-button btn-block btn dark btn-lg" action="?action=import_items&todo=excel_import_form_options" override-selected-record="no-map-update">Update from Excel</a>
	</div>
</div>
<br />

</div>