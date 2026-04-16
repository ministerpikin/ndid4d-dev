<?php 
	$super = 0;
	
	$access = array();
	if( isset( $user_info["user_privilege"] ) && $user_info["user_privilege"] ){
		if( $user_info["user_privilege"] == "1300130013" ){
			$super = 1;
		}else{
			$functions = get_access_roles_details( array( "id" => $user_info["user_privilege"] ) );
			if( isset( $functions[ $user_info["user_privilege"] ]["accessible_functions"] ) ){
				$a = explode( ":::" , $functions[ $user_info["user_privilege"] ]["accessible_functions"] );
				if( is_array( $a ) && $a ){
					foreach( $a as $k => $v ){
						$access[ $v ] = $v;
					}
				}
			}
		}
	}
	$pr = get_project_data();
	
	$params = '';
	if( isset( $data["html_replacement_selector"] ) && $data["html_replacement_selector"] ){
		$params = '&html_replacement_selector=' . $data["html_replacement_selector"];
	}
?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<br />
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<h4 class="card-title-1"><strong><?php if( isset( $data["title"] ) )echo $data["title"]; ?></strong></h4>
		<hr />
		<div class="row" >
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Updates</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=display_application_update_view&action=audit<?php echo $params; ?>">
					Application Update</a>
				</div>
			</div>
			
			<?php
				$key = "10859091476";
				if( isset( $access[ $key ] ) || $super ){
			?>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Export Database</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=export_db_prompt&action=audit<?php echo $params; ?>">
					Export Db</a>
				 </div>
			</div>
			<?php } ?>
			
			<?php
				if( $super ){
			?>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>System Debugger</p>
					<a href="#" class="btn dark btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=system_debug_start&action=audit<?php echo $params; ?>">System Debug</a>
				 </div>
			</div>
			<?php } ?>
			
			<?php
				$key = "10859089764";
				if( isset( $access[ $key ] ) || $super ){
			?>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Import Database</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=import_db&action=audit<?php echo $params; ?>">
					Import DB</a>
				 </div>
			</div>
			<?php } ?>
			
			<?php
				$key = "10859013761";
				if( isset( $access[ $key ] ) || $super ){
			?>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Drop Views</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=drop_views&action=audit<?php echo $params; ?>">
					Drop Views</a>
				 </div>
			</div>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Empty Database</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=empty_database&action=audit<?php echo $params; ?>">
					Empty Database</a>
				 </div>
			</div>
			<?php } ?>
			
			<?php /* if( $super ){ ?>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>System Functions</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=display_all_records_full_view&action=functions<?php echo $params; ?>">Functions</a>
				 </div>
			</div>
			
			<div class="col-md-3">
				<div class="note note-warning">
					<p>System Modules</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=display_all_records_full_view&action=modules<?php echo $params; ?>">Modules</a>
				 </div>
			</div>
			<?php } */ ?>
			
			<?php
				$key = "10859016228";
				if( isset( $access[ $key ] ) || $super ){
					$refresh_app_function = 'refresh_cache';
					if( get_refresh_cache_in_background_settings() ){
						$refresh_app_function = 'refresh_cache_via_background';
					}
			?>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Rebuild App Cache</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=<?php echo $refresh_app_function; ?>&action=audit<?php echo $params; ?>">Rebuild App Cache</a>
				 </div>
			</div>
			<?php } ?>
			
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Clear Server Cache</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=clear_cache&action=audit<?php echo $params; ?>">
					Clear Cache</a>
				 </div>
			</div>
			<!--
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Upgrade Database</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=upgrade_database_from_database&action=audit<?php echo $params; ?>">Upgrade DB</a>
				 </div>
			</div>
			-->
			<div class="col-md-3">
				<div class="note note-warning">
					<p>More Settings</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=display_more_settings&action=audit<?php echo $params; ?>">More Settings</a>
				 </div>
			</div>
			<?php /*
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Manage Countries</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=display_all_records_full_view&action=country_list<?php echo $params; ?>">Manage Countries</a>
				 </div>
			</div> */ ?>
			
			<?php if( get_hyella_development_mode() ){ ?>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Export Updated Files</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=display_export_updated_files&action=audit<?php echo $params; ?>">Export Files</a>
				 </div>
			</div>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>ERD</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=display_erd&action=audit<?php echo $params; ?>">Show ERD</a>
				 </div>
			</div>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Migrate Data</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=move_data_from_old_fields_to_new_prompt&action=audit<?php echo $params; ?>" title="Move Data From Old to New Class">Migrate Data</a>
				 </div>
			</div>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Clean App</p>
					<a href="<?php echo $pr["domain_name"]; ?>remove-unused-classes.php" class="btn dark btn-block" target="_blank">Clean App</a>
				 </div>
			</div>
			<?php if( class_exists("cItems") ){ ?>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Make All Inventory Items become Zero(0)</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=zero_out_inventory_prompt&action=items<?php echo $params; ?>" title="Make All Inventory Items become Zero(0)">Zero Stock Level</a>
				 </div>
			</div>
			<?php } ?>
			<?php if( class_exists("cAssets") ){ ?>
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Refresh Journal Posting of Fixed Assets</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" confirm-prompt="Please take an offsite back-up before proceeding with this action" action="?todo=recreate_new_assets_posting&action=assets<?php echo $params; ?>" title="Refresh Journal Posting of Fixed Assets">Refresh Fixed Assets</a>
				 </div>
			</div>
			<?php } ?>
			<?php } ?>
			<!--
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Charts of Accounts Configuration</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button-old" override-selected-record="-" action="?todo=display_all_records_full_view&action=chart_of_accounts<?php //echo $params; ?>">Chart of Accounts Config</a>
				 </div>
			</div>
			
			<div class="col-md-3">
				<div class="note note-warning">
					<p>Automatically Assign Codes</p>
					<a href="#" class="btn blue btn-block custom-single-selected-record-button" override-selected-record="-" action="?todo=generate_codes_for_accounts&action=chart_of_accounts<?php //echo $params; ?>" title="Automatically Assign Codes to Chart of Accounts">Auto Assign Codes to COA</a>
				 </div>
			</div>
			-->
		</div>
	</div>
</div>
</div>