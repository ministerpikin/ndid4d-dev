<div <?php set_hyella_source_path( __FILE__, 1 ); ?> style="width:100%;">
<?php 
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	
	$action_to_perform = isset( $data["action_to_perform"] )?$data["action_to_perform"]:''; 
	
	$current_tab = isset( $data["current_tab"] )?$data["current_tab"]:'inventory';
	$dp = '';
	$dashboard_link = isset( $data["stores"] )?$data["stores"]:'';
	
	$container2 = '';
	if( isset( $data["container"] ) ){
		$container2 = $data["container"] . '-sub';
	}
	
	switch( $action_to_perform ){
	case "display_ssr_menu":
		$current_tab = 'ssr';
	break;
	case "display_nsr_menu":
		$current_tab = 'nsr';
		//$dp = ' class="custom-single-selected-record-button" override-selected-record="-" action="?action=all_reports&todo=display_nsr_dashboard&html_replacement_selector='.$container2.'" ';
	break;
	case "display_survey_menu":
		$current_tab = 'survey';
	break;
	}
	
	if( $dashboard_link ){
		include dirname( dirname( __FILE__ ) ) . "/sidemenu.php";
		include "content-new.php";
	}else{
		?><div class="note note-danger"><h4>No Access to Store</h4><p>Please contact your administrator</p></div><?php
	}
?>

</div>