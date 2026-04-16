<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	if( ! isset( $super ) ){
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
	}
?>
	<div class="tabbable-custom " id="main-tabs">
		<ul class="nav nav-tabs ">
		   <li class="active"><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=teams&todo=display_new_team_form" data-toggle="tab">Create Team(s)</a></li>
		   
		   <li ><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=customers&todo=view_version2_report2" data-toggle="tab">List of Employees</a></li>
		   <!--<li><a href="#search-for-patient" data-toggle="tab" id="search-for-patient-handle" onclick="$.fn.cCallBack.showSearchPatientForm();">Member File</a></li>-->
		   
		   <!--<li><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=all_reports&todo=display_report_selection" data-toggle="tab">Reports</a></li>-->
		</ul>
		<div class="tab-content">
		   <div class="tab-pane active" id="dash-board-main-content-area">
				<div style="text-align:center;">
					<br />
					<br />
					<br />
					<img src="hospital-assets/img/ajax-loading.gif" />
					<br />
					<br />
					<br />
				</div>
		   </div>
		   
		</div>
	 </div>
	 
	 
</div>