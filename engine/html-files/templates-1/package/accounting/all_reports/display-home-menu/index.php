<div <?php set_hyella_source_path( __FILE__, 1 ); ?> style="width:100%;" id="my-dashboard">
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
	// echo '<pre>'; print_r( $data ); echo '</pre>';

	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	
	$xtype = isset( $data["type"] )?$data["type"]:''; 
	$action_to_perform = isset( $data["action_to_perform"] )?$data["action_to_perform"]:''; 
	$pr = get_project_data();
	$site_url2 = isset( $pr["domain_name"] )?$pr["domain_name"]:'';
	$app_url = isset( $pr["app_url"] )?$pr["app_url"]:'';
	
	$current_tab = '';
	$dp = '';
	
	$container2 = '';
	if( isset( $data["container"] ) ){
		$container2 = $data["container"] . '-sub';
	}
	
	switch( $action_to_perform ){
	case "display_home_menu":
		$current_tab = 'home';
	break;
	}
	
	$hs2 = '';
	$hs1 = array();
	if( isset( $user_info[ 'user_dept' ] ) && $user_info[ 'user_dept' ] ){
		$hs1[] = get_name_of_referenced_record( array( "id" => $user_info[ 'user_dept' ], "table" => "departments" ) );
	}
	if( isset( $user_info[ 'user_store' ] ) && $user_info[ 'user_store' ] ){
		$hs1[] = get_name_of_referenced_record( array( "id" => $user_info[ 'user_store' ], "table" => "stores" ) );
	}
	if( ! empty( $hs1 ) ){
		$hs2 = '<div style="margin-top:10px;">'. implode(" - ", $hs1 ) .'</div>';
	}
	if( $hs2 ){
		$hs2 .= '<div style="font-size:85%; margin-top:5px;">'. ( isset( $user_info[ 'user_role' ] ) ? $user_info[ 'user_role' ] : '' ) .'</div>';
	}else{
		$hs2 = ( isset( $user_info[ 'user_role' ] ) ? $user_info[ 'user_role' ] : '' );
	}
	
	$fullname = ( isset( $user_info[ 'user_full_name' ] ) ? $user_info[ 'user_full_name' ] : '' );
	
	//$pic = ( isset( $user_info[ 'photograph' ] ) && $user_info[ 'photograph' ] && $user_info[ 'photograph' ] !== 'none' ? ( $site_url2 . $user_info[ 'photograph' ] ) : ( $app_url . 'files/resource_library/images/avatars/1.jpg' ) );
	
	$pp = isset( $pagepointer2 )?$pagepointer2:$pagepointer;
	
	if( isset( $user_info[ 'photograph' ] ) && $user_info[ 'photograph' ] && $user_info[ 'photograph' ] !== 'none' && file_exists( $pp . $user_info[ 'photograph' ] ) ){
		$pic1 = $user_info[ 'photograph' ];
	}else if( defined("IPIN_APP_DEFAULT_AVATAR") && IPIN_APP_DEFAULT_AVATAR ){
		$pic1 = IPIN_APP_DEFAULT_AVATAR;
	}else{
		$pic1 = 'files/resource_library/images/avatars/1.jpg';
	}
	//echo (isset( $pagepointer2 )?$pagepointer2:$pagepointer) . $pic1;
	
	$pic = get_uploaded_files( $pp , $pic1, '', '', array( "return_link" => 1 ) );
	//if( $dp ){
		$dashboard_link = '<li style="margin:-10px; overflow:hidden;">
			<div class="dropdown-menu-header">
				<div class="dropdown-menu-header-inner bg-dark">
					<div class="menu-header-content">
						<div class="avatar-icon-wrapper mb-3 avatar-icon-xl">
							<div class="avatar-icon"><img src="'. $pic .'" alt="'. $fullname .'"></div>
						</div>
						<div><h5 class="menu-header-title">'. $fullname .'</h5><h6 class="menu-header-subtitle">'. $hs2 . '</h6></div>
						<div class="menu-header-btn-pane pt-1">
							<button class="btn-icon btn default btn-sm custom-single-selected-record-button" override-selected-record="-" action="?action=users&todo=display_my_profile_manager&html_replacement_selector='. $container2 .'">My Profile</button>
							<a href="'.$site_url2.'sign_out?action=signout" class="btn default btn-sm"><i class="icon-power-off fa fa-power-off"> </i></a>
						</div>
					</div>
				</div>
			</div>
		</li>';
		
		$key = 'my';
		$dashboard_link .= '<li><div id="'.$key.'-tree-con" class="white-tree" style="margin-top:10px;"><a href="#" id="'.$key.'-tree" data-action="dashboard" data-todo="display_my_tree_view" data-container="'. $container2 .'" data-table="'. $key .'" class="btn btn-sm blue pull-rightx " style="display:none;">Explore</a></div></li>';
	//}
	$sidemenu_theme  = 'background:#f8f9fa;#f1f4f6; color:#222; font-weight:600; '; 
	
	include dirname( dirname( __FILE__ ) ) . "/sidemenu.php"; 
	
	if( file_exists( dirname( __FILE__ ) . "/" . $xtype.'.php' ) ){
		include $xtype.'.php';
	}else{
		include "content-new.php";
	}
	// if( defined("HYELLA_UI") && HYELLA_UI ){
		// include "content-new.php";
	// }else{
	// 	include "content-default.php";
	// }
?>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>