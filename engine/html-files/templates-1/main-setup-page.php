<div id="generate-report-progress-bar">
</div>
<input type="hidden" id="rollover-for-auth" value="<?php if( isset( $_GET['t'] ) && $_GET['t'] )echo $_GET['t']; ?>" />
<body class="login">
<div id="page-body-wrapper">
	<!-- BEGIN LOGO -->
	<p>&nbsp;</p>
    <div class="logo">
		<img src="assets/img/logo-big.png" alt="" /> 
	</div>
	<!-- END LOGO -->
    <p>&nbsp;</p>
	<!-- BEGIN LOGIN -->
	<div class="content" id="form-content-area">
		<!-- BEGIN LOGIN FORM -->
		<form class="login-form" action="index.html" method="post">
			<h3 class="form-title">Initializing...</h3>
            <p>please wait</p>
			<a href="" class="btn btn-block red" title="Login">Login</a>
		</form>
	</div>
	<!-- END LOGIN -->
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		<p style="font-size:1.2em;">ver <span id="project-version"><?php echo get_app_version(); ?></span></p>
	</div>
    <div id="notification-container" style="max-width:300px;">

    </div>
    <div id="generate-report-progress-bar">
    
    </div>
</div>
</body> 