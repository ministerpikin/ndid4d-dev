<span <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="btn-group">
	<a type="button"  class="btn btn-sm dark dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
	More Actions <i class="icon-angle-down"></i>
	</a><div class="dropdown-backdrop"></div>
	 
	<ul class="dropdown-menu" role="menu">
		<?php if( isset( $data["update_general_settings"] ) && $data["update_general_settings"] ){ ?>
		<li><a href="#" class="custom-single-selected-record-button" override-selected-record="-" action="?module=&action=general_settings&todo=update_general_settings" title="Update General Settings">Update General Settings</a></li>
		<?php } ?>
		
		<?php /* if( isset( $data["update_general_settings"] ) && $data["update_general_settings"] ){ ?>
		<li><a href="#" class="custom-single-selected-record-button-old" override-selected-record="-" action="?module=&action=general_settings&todo=show_project_settings" title="Project Settings">Project Settings</a></li>
		<?php } */ ?>
		
	</ul>
</div>
</span>