<?php 
	$pic = isset( $user_info["photograph"] )?$user_info["photograph"]:'';
	
	if( $pic && file_exists( $pagepointer . $pic ) ){
		$pic = $project["domain_name"] . $pic;
	}else{
		$app_url = isset( $project["app_url"] )?$project["app_url"]:'';
		$pic = $app_url . 'files/resource_library/images/avatars/1.jpg';
	}
?>
<div class="dropdown ms-sm-3 header-item topbar-user">
	<button type="button" class="btn shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<span class="d-flex align-items-center">
			<img class="rounded-circle header-profile-user" src="<?php echo $pic; ?>" width="36" alt="<?php if( isset( $user_info["user_initials"] ) )echo $user_info["user_initials"]; ?> Picture">
			<span class="text-start ms-xl-2">
				<span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text"><?php if( isset( $user_info["user_initials"] ) )echo $user_info["user_initials"]; ?></span>
				<span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text"><?php echo ( isset( $user_info[ 'user_role' ] ) ? $user_info[ 'user_role' ] : '' ); ?></span>
			</span>
			&nbsp;&nbsp;<i class="mdi mdi-arrow-down"></i>
		</span>
	</button>
	<div class="dropdown-menu dropdown-menu-end">
		<!-- item-->
		<h6 class="dropdown-header">Welcome <?php if( isset( $user_info["user_initials"] ) )echo $user_info["user_initials"]; ?>!</h6>
		<a class="dropdown-item custom-single-selected-record-button" override-selected-record="-" action="?action=users&todo=display_my_profile_manager&html_replacement_selector=<?php echo $container; ?>" href="javascript:;"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
		
		<?php /*
		<a class="dropdown-item" href="apps-chat.html"><i class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Messages</span></a>
		<a class="dropdown-item" href="apps-tasks-kanban.html"><i class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Taskboard</span></a>
		<a class="dropdown-item" href="pages-faqs.html"><i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Help</span></a>
		*/ ?>
		<div class="dropdown-divider"></div>
		<?php /*
		<a class="dropdown-item" href="pages-profile.html"><i class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance : <b>$5971.67</b></span></a>
		<a class="dropdown-item" href="pages-profile-settings.html"><span class="badge bg-soft-success text-success mt-1 float-end">New</span><i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Settings</span></a>
		<a class="dropdown-item" href="auth-lockscreen-basic.html"><i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock screen</span></a>
		*/ ?>
		<a class="dropdown-item" href="<?php echo $project["domain_name"].'sign_out?action=signout'; ?>"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
	</div>
</div>
