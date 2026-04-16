<?php
	$pr = get_project_data();
	
	$date = 0;
	if( isset( $_SESSION["release"] ) && $_SESSION["release"] ){
		$date = doubleval( $_SESSION["release"] );
	}else{
		//$pr = get_project_data();
		//session_destroy();
		//header("Location: " . $pr['domain_name'] );
		//exit;
	}
	//echo $date;
	$back_url = $pagepointer;
	
	if( defined("HYELLA_NO_APP") && HYELLA_NO_APP ){
	}else{
		if( file_exists( "../sign-in/index.html" ) ){
			//$pr = get_project_data();
			$back_url = "../sign-in/";
		}
	}
	
	$update_now = 0;
	if( isset( $_GET["automatic"] ) && intval( $_GET["automatic"] ) ){
		$update_now = 1;
	}
	
	$platform = '';
	/*
	if( defined("PLATFORM") && PLATFORM ){
		$platform = 'mac';
	}
	*/
	
	$show_web_copy = 0;
	if( defined("HYELLA_WEB_COPY") && HYELLA_WEB_COPY && $platform != 'mac' ){
		$show_web_copy = 1;
	}
	if( defined("HYELLA_WEB_COPY_UPLOAD") && HYELLA_WEB_COPY_UPLOAD ){
		$show_web_copy = 0;
	}
?>
<link href="<?php echo $pagepointer; ?>assets/css/pages/coming-soon.css" rel="stylesheet" type="text/css"/>

<!-- BEGIN BODY -->
<body>
   <div class="container">
      
    <div id="generate-report-progress-bar">
    
    </div>
		<input type="hidden" value="update" id="update-app" />
      <div class="row">
         <div class="col-md-1" style="text-align:center;">
			<img src="assets/img/logo.png" alt="logo" style="width:100%; max-width:150px; margin-top:35px;" />
		 </div>
         <div class="col-md-6 col-md-offset-2 coming-soon-content" style="text-align:center;">
            <h4 style="color:#fff;">Version <?php echo get_application_version( $pagepointer ); ?></h4>
			<?php if( $show_web_copy ){ ?>
				<h5 style="color:#fff;">Master Copy of <strong><?php echo $pr['app_title']; ?></strong></h5>
				<hr />
				<div id="updates-container">
					<a href="<?php echo $back_url; ?>" title="Click here to Return Back to the Application" class="btn btn-lg dark"><span>Go Back to Application</span> </a>
					<br />
					<br />
					<div class="row">
					<div class="col-md-6 " style="text-align:left;">
						<h4><i class="icon-gear"></i> Other Available Modules</h4>
						<ol>
						<?php 
							$am = __list_of_available_modules();
							foreach( $am as $aa ){
								?>
								<li style="font-size:1.1em;"><?php echo $aa; ?></li>
								<?php
							}
						?>
						</ol>
					</div>
					<div class="col-md-5 col-md-offset-1" style="color:#eee; text-align:left;">
						<h4><strong><i class="icon-gear"></i> Installed Modules</strong></h4>
						<ol>
						<?php 
							$am = __list_of_installed_modules();
							foreach( $am as $aa ){
								?>
								<li style="font-size:1.1em;"><?php echo $aa; ?></li>
								<?php
							}
						?>
						</ol>
					</div>
					</div>

				</div>
			<?php }else{ ?>
				<?php 
					if( isset( $pr["auto_backup"] ) && $pr["auto_backup"] == "none" ){
				?>
				<h5 style="color:#fff;"><strong>Your License is Limited & does not permit you to use our Auto Back-up Service</strong></h5>
				<?php }else{
					$update = check_for_update_manifest();
					$update1 = check_queue_email_notification();
				?>
				<h3 style="color:#fff;">You have <strong><?php echo $update["count"]; ?></strong> pending update(s) of <strong><?php echo $update["size"]; ?></strong></h3>
					<?php if( isset( $update1["count"] ) && isset( $update1["size"] ) ){ ?>
					<p style="color:#fff;">You have <strong><?php echo $update1["count"]; ?></strong> pending batch email(s) of <strong><?php echo $update1["size"]; ?></strong></p>
					<?php } ?>
				<?php } ?>
				
				<?php 
					$update_label = 'Sign In';
					
					$key = md5( 'ucert' . $_SESSION['key'] );
					if( isset( $_SESSION[ $key ] ) ){
						$update_label = 'Enter Application';
					}
				?>
				<hr />
				<div id="updates-container">
					<a href="#" id="btn-update" function-name="app_update_automatic" style="display:none;" function-class="audit" function-id="a-1" title="Click here to Start Update" class="custom-action-button btn btn-lg red"><span>Auto</span> </a>
					
					<a href="#" function-name="background_app_update" function-name1="app_update" function-class="audit" function-id="a-1" title="Click here to Start Update" class="custom-action-button btn btn-lg red"><span>Click Here to Start Update</span> </a>
					<a href="<?php echo $back_url; ?>" title="Click here to Return Back to the Application" class="btn btn-lg dark"><span><?php echo $update_label; ?></span> </a>
				</div>
			<?php } ?>
			
			
         </div>
        </div>
      <div class="row">
		 <div class="col-md-5  col-md-offset-7 coming-soon-countdown" style="position: absolute; bottom: 0; right: 0px;">
			<?php 
			if( ! ( defined("HYELLA_LIFETIME") && HYELLA_LIFETIME ) ){
		   ?>
			<h4 style="color:#fff;">Count Down to License Expiry </h4>
			<?php } ?>
			<?php if( isset( $pr["company_name"] ) ){ ?>
			<p style="color:#ddd;">
				Registered Client Name: <strong><?php echo $pr["company_name"]; ?></strong>
			</p>
			<?php } ?>
			<div id="defaultCountdown"></div>
			 <p style="color:#aaa;"><small><br /><?php if( isset( $_SESSION[ "app" ] ) )echo $_SESSION[ "app" ]; ?>-<?php echo md5( rand(0,100) ); ?></small></p>
		 </div>
      </div>
      <!--/end row-->
      <div class="row" style="position:absolute; bottom:0;">
         <div class="col-md-6 coming-soon-footer">
             <br>
            
            <ul class="social-icons margin-top-20">
               <li><a href="http://www.maybeachtech.com" target="_blank" title="Technical Support Partner" data-original-title="Feed" class="rss"></a></li>
               <li><a href="https://www.facebook.com/maybeachtech?fref=ts" target="_blank" title="Technical Support Partner" data-original-title="Facebook" class="facebook"></a></li>
               <li><a href="https://twitter.com/MaybeachTech" target="_blank" title="Technical Support Partner" data-original-title="Twitter" class="twitter"></a></li>
               <li><a href="https://plus.google.com/u/0/b/118053162038723135944/+Maybeachtechnology" target="_blank" title="Technical Support Partner" data-original-title="Google Plus" class="googleplus"></a></li>
            </ul>
			2016 &copy; <a style="color:#fff; font-weight:bold;" href="http://www.northwindproject.com" target="_blank">NorthWindProject.com</a>
         </div>
      </div>
   </div>
   <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
   <!-- BEGIN CORE PLUGINS -->   
   <!--[if lt IE 9]>
   <script src="<?php echo $pagepointer; ?>assets/plugins/respond.min.js"></script>
   <script src="<?php echo $pagepointer; ?>assets/plugins/excanvas.min.js"></script> 
   <![endif]-->   
   <?php require_once "html-jquery-files.php"; ?>
   <!--
   <script src="<?php echo $pagepointer; ?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
   <script src="<?php echo $pagepointer; ?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
   <script src="<?php echo $pagepointer; ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
   <script src="<?php echo $pagepointer; ?>assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
   <script src="<?php echo $pagepointer; ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
   <script src="<?php echo $pagepointer; ?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>  
   <script src="<?php echo $pagepointer; ?>assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
   <script src="<?php echo $pagepointer; ?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
   <!-- END CORE PLUGINS -->
   <!-- BEGIN PAGE LEVEL PLUGINS -->
   <script src="<?php echo $pagepointer; ?>assets/plugins/countdown/jquery.countdown.js" type="text/javascript"></script>
   <script src="<?php echo $pagepointer; ?>assets/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
   <!-- END PAGE LEVEL PLUGINS -->
   <!-- BEGIN PAGE LEVEL SCRIPTS -->
   <!--<script src="<?php echo $pagepointer; ?>assets/scripts/app.js" type="text/javascript"></script>-->
   <script src="<?php echo $pagepointer; ?>assets/scripts/coming-soon.js" type="text/javascript"></script>      
   <!-- END PAGE LEVEL SCRIPTS --> 
   <?php 
	if( ! ( defined("HYELLA_LIFETIME") && HYELLA_LIFETIME ) ){
   ?>
   <script>
      jQuery(document).ready(function() {
		//App.init();
		ComingSoon.year = <?php echo date("Y", $date); ?>;
        ComingSoon.month = <?php echo date("n", $date); ?>;
        ComingSoon.day = <?php echo date("j", $date); ?>;
		
        ComingSoon.init();
      });
       
   </script>
	<?php } ?>
	
	<script>
	<?php if( $update_now ){ ?>
		setTimeout( function(){ if( $("#updates-container").is(":visible") )$("#btn-update").click(); }, 1000 );
	<?php } ?>
	</script>
   <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
