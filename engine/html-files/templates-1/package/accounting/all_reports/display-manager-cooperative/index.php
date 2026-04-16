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
	
	$deposit_label = get_cooperative_label_for_deposits_settings();
	
	$show_home_tab = 0;
	$home_tab_class = 'active';
	if( function_exists("get_cooperative_show_home_icon_with_tabs_settings") ){
		$show_home_tab = get_cooperative_show_home_icon_with_tabs_settings();
	}
?>
	<div class="tabbable-custom " id="main-tabs">
		<ul class="nav nav-tabs ">
		   <?php if( $show_home_tab ){ $home_tab_class = ''; ?>
		   <li class="active"><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=customers&todo=display_manage_home_icon" data-toggle="tab"><i class="icon-home">&nbsp;</i></a></li>
		   <?php } ?>
		   
		   <li class="<?php echo $home_tab_class; ?>"><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=customers&todo=display_manage_appointment" data-toggle="tab">Members Centre</a></li>
		   
		   <li ><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=customers&todo=display_manage_contribution" data-toggle="tab"><?php echo $deposit_label; ?>(s)</a></li>
		   
		   
		   <li ><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=customers&todo=display_manage_transfers" data-toggle="tab">Transfer(s)</a></li>
		   
		   <li ><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=customers&todo=display_manage_loan_application" data-toggle="tab">Loan / Withdrawal</a></li>
		   
		   <li ><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=customers&todo=display_requisition_view" data-toggle="tab">Consumables Request</a></li>
		   
		   <li ><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=customers&todo=view_version2_report2" data-toggle="tab">List of Members</a></li>
		   
		   <!--<li><a href="#search-for-patient" data-toggle="tab" id="search-for-patient-handle" onclick="$.fn.cCallBack.showSearchPatientForm();">Member File</a></li>-->
		   
		   <li><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=all_reports&todo=display_report_selection" data-toggle="tab">Reports</a></li>
		   
		   <li><a href="#search-for-patient" class="custom-single-selected-record-button" override-selected-record="1" action="?action=bulk_message&todo=display_compose_message_options" data-toggle="tab">Bulk Messaging</a></li>
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