<header id="page-topbar">
<div class="layout-width">
	<div class="navbar-header">
		<div class="d-flex">
			<!-- LOGO -->
			<div class="navbar-brand-box horizontal-logo">
				<a href="index.html" class="logo logo-dark">
					<span class="logo-sm">
						<img src="assets/images/logo-sm.png" alt="" height="22">
					</span>
					<span class="logo-lg">
						<img src="assets/images/logo-dark.png" alt="" height="17">
					</span>
				</a>

				<a href="index.html" class="logo logo-light">
					<span class="logo-sm">
						<img src="assets/images/logo-sm.png" alt="" height="22">
					</span>
					<span class="logo-lg">
						<img src="assets/images/logo-light.png" alt="" height="17">
					</span>
				</a>
			</div>

			<button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger shadow-none" id="topnav-hamburger-icon">
				<span class="hamburger-icon">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>
			<?php if( isset( $data["search"] ) && $data["search"] )include "main-header-search.php"; ?>
		</div>

		<div class="d-flex align-items-center">
			<?php if( isset( $data["apps"] ) && $data["apps"] )include "main-header-web-apps.php"; ?>
			<?php if( isset( $data["cart"] ) && $data["cart"] )include "main-header-cart.php"; ?>

			<div class="ms-1 header-item d-none d-sm-flex">
				<button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle shadow-none" data-toggle="fullscreen">
					<i class='bx bx-fullscreen fs-22'></i>
				</button>
			</div>

			<!-- <div class="ms-1 header-item d-none d-sm-flex">
				<button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode shadow-none">
					<i class='bx bx-moon fs-22'></i>
				</button>
			</div> -->
			
			<?php if( isset( $data["notifications"] ) && $data["notifications"] )include "main-header-notifications.php"; ?>
			<?php if( isset( $user_info["user_id"] ) && $user_info["user_id"] )include "main-header-user.php"; ?>
		</div>
	</div>
</div>
</header>
