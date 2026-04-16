<span <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$development = isset( $data["development"] )?$data["development"]:0;
	
	$params = '';
	if( isset( $data["html_replacement_selector"] ) && $data["html_replacement_selector"] ){
		$params = '&html_replacement_selector=' . $data["html_replacement_selector"];
	}
?>
<a href="#" class="btn dark btn-sm custom-single-selected-record-button-old" action="?module=&action=access_roles&todo=manage_access_roles<?php echo $params; ?>">Manage Access Role(s)</a>
<!--
<div class="btn-group">
	<a type="button"  class="btn btn-sm dark dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
	More Actions <i class="icon-angle-down"></i>
	</a><div class="dropdown-backdrop"></div>
	 
	<ul class="dropdown-menu" role="menu">
		<li><a href="#" class="custom-single-selected-record-button-old" action="?module=&action=database_table&todo=upgrade_table_structure" title="Upgrade Table Structure">Upgrade Table Structure</a></li>
		
		<li><a href="#" class="custom-multi-selected-record-button" action="?module=&action=database_table&todo=refresh_all_fields" title="Refresh All Fields">Refresh All Table Fields</a></li>
		
	</ul>
</div>
-->
</span>