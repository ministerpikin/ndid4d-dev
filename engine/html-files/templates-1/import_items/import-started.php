<!--IMPORT STARTED-->
<?php
if( isset( $data['current_record_details'] ) && $data['current_record_details'] ){
	$r = $data['current_record_details'];
?>
<!--<a href="#" class="pull-right btn btn-success btn-sm">Cancel Operation</a><a href="#" class="pull-right btn btn-success btn-sm">Import Cash Call</a>-->
<small><strong><?php echo get_select_option_value( array( 'id' => $r['budget_code'], 'function_name' => 'get_all_budgets' ) )  .'-'. get_select_option_value( array( 'id' => $r['month'], 'function_name' => 'get_months_of_year' ) ); ?></strong></small>
<h4>Excel File Import Progress &darr;</h4>
<p>&nbsp;</p>
<ol id="excel-file-import-progress-list">
	<li>Started Data Extraction & Loading...</li>
	<li>Excel File Uploaded Successfully</li>
</ol>
<?php	
}
?>