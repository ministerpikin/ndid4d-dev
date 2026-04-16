<?php
	$pagepointer = "../";
	require_once $pagepointer."settings/Config.php";
	require_once $pagepointer."settings/Setup.php";
	require_once "html-head-tag.php";
	
	$app = get_app_id();
	
	$result = "";
	if( $app ){
		$result = get_records_from_database( $pagepointer , $app );
	}
	
	$pr = get_project_data();
	
	//active license
	if( doubleval( $result ) == 1 ){
		header("Location: " . $pr['domain_name'] . "?activity=update" );
		exit;
	}
	
	$date = 0;
	if( isset( $_SESSION["release"] ) && $_SESSION["release"] ){
		$date = doubleval( $_SESSION["release"] );
	}
	
	if( isset( $_GET["time"] ) && $_GET["time"] == "unset" ){
		$result = "You Must Set Your System Date & Time<br /><small>Failure to do this will result in loss of data</small>";
	}
	if( isset( $_SESSION['key'] ) )unset( $_SESSION['key'] );
	//echo $result;exit;
?>
<link href="<?php echo $pagepointer; ?>assets/css/pages/coming-soon.css" rel="stylesheet" type="text/css"/>

<!-- BEGIN BODY -->
<body>
   <div class="container">
      <div class="row">
		<?php if( ! $app || $result ){ ?><div class="col-md-3"></div><?php } ?>
         <div class="col-md-6 coming-soon-header">
            <a class="brand" href="<?php if( $app )echo $pr['domain_name']; ?>">
            <img src="assets/img/logo-big.png" alt="logo" />
            </a>
         </div>
      </div>
	  <?php if( ! $app ){ ?>
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6 coming-soon-content">
				   <h1>Expired / Inactive License</h1>
					<blockquote>
					  <p style="font-size:18px">Select Your HYELLA LICENSE
					  </p>
					  <p style="font-size:14px; color:#c55; font-weight:bold; text-shadow:1px 1px 1px #333;">Please make sure your system date & time is correct
					  </p>
				   </blockquote>
				   <br>
				   <form id="fileupload" action="//" method="POST" enctype="multipart/form-data">
					  <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
					  <div class="fileupload-buttonbar">
						 <div class="well">
							<!-- The fileinput-button span is used to style the file input field as button -->
							<span id="upload-box">
							<span>Select License File...</span>
							<input type="file" name="file" class="form-control">
							</span>
							
						 </div>
					  </div>
				   </form>
			   </div>
			</div>
	  <?php }else{ ?>
		  <?php if( $result ){ ?>
			<div class="row">
			 <div class="col-md-3"></div>
			 <div class="col-md-6 coming-soon-content">
			   <?php 
					if( doubleval( $result ) == 1 ){ 
			   ?>
			   <h1>Active License</h1>
				<a href="<?php echo $pr['domain_name']; ?>?activity=update" title="Return to the Application" class="btn btn-lg blue"><span>Click Here to Return to the Application</span> <i class="m-icon-swapright m-icon-white"></i></a>
			   <?php }else{
				   echo "<h1>".$result."</h1>";
				   ?>
				   <a href="<?php echo $pr['domain_name']; ?>html-files/expired-message.php" title="Check License Status" class="btn green"><span>Click Here to Check License Status</span> <i class="m-icon-swapright m-icon-white"></i></a>
				   <?php
				   if( $result == "No Internet Connection" ){
					   ?>
					   <a href="<?php echo $pr['domain_name']; ?>?activity=update" title="Start" class="btn blue"><span>Start</span> <i class="icon-power-off"></i></a>
					   <?php
				   }
			   }
				?>
			</div>
			</div>
			
			<div class="row">
			 <div class="col-md-3"></div>	
				<div class="col-md-6 coming-soon-countdown">
					<h4 style="color:#fff;">Count Down to License Expiry</h4>
					<div id="defaultCountdown"></div>
					 
				 </div>
			</div>
		  
		  <?php }else{ ?>
		  <div class="row">
			 <div class="col-md-6 coming-soon-content">
				<h3 style="color:#fff;"><?php echo $pr['project_title']; ?> - License Check</h3>
				<br>
				<a href="<?php echo $pr['domain_name']; ?>html-files/expired-message.php" title="Check License Status" class="btn btn-lg green"><span>Click Here to Check License Status</span> <i class="m-icon-swapright m-icon-white"></i></a>
				<hr />
				<a href="http://www.northwindproject.com/pay-license-fee?project=<?php echo $app; ?>" target="_blank" title="Renew Annual License Fee Now" class="btn btn-lg red"><span>Click Here to Renew Annual License Fee Now</span> <i class="m-icon-swapright m-icon-white"></i></a>
				<ul class="social-icons margin-top-20">
				   <li><a href="http://www.maybeachtech.com" target="_blank" title="Technical Support Partner" data-original-title="Feed" class="rss"></a></li>
				   <li><a href="https://www.facebook.com/maybeachtech?fref=ts" target="_blank" title="Technical Support Partner" data-original-title="Facebook" class="facebook"></a></li>
				   <li><a href="https://twitter.com/MaybeachTech" target="_blank" title="Technical Support Partner" data-original-title="Twitter" class="twitter"></a></li>
				   <li><a href="https://plus.google.com/u/0/b/118053162038723135944/+Maybeachtechnology" target="_blank" title="Technical Support Partner" data-original-title="Goole Plus" class="googleplus"></a></li>
				   <!--<li><a href="#" data-original-title="Pinterest" class="pintrest"></a></li>
				   <li><a href="#" data-original-title="Linkedin" class="linkedin"></a></li>
				   <li><a href="#" data-original-title="Vimeo" class="vimeo"></a></li>-->
				</ul>
			 </div>
			 <div class="col-md-6 coming-soon-countdown">
				 <h4 style="color:#fff;">Count Down to License Expiry</h4>
					<div id="defaultCountdown"></div>
					 
				 <p style="color:#ddd;">For More Information on Annual License Fees & Other Fees<br />Visit: <a href="http://www.northwindproject.com/license-info?project=<?php echo $app; ?>" target="_blank" title="More Information on Annual License Fee Renewal" style="color:#fff;" >www.northwindproject.com/license-info</a></p>
				<br>
				<p style="color:#ddd;">For Technical Support<br />Visit: <a href="http://www.northwindproject.com/technical-support?project=<?php echo $app; ?>" target="_blank" title="More Information on Technical Support" style="color:#fff;" >www.northwindproject.com/technical-support</a></p>
			 </div>
			</div>
			<?php } ?>
		<?php } ?>
      
      <!--/end row-->
      <div class="row">
		<div class="col-md-3"></div>
         <div class="col-md-6 coming-soon-footer">
			<a href="http://www.northwindproject.com/pay-license-fee?project=<?php echo $app; ?>" class="btn btn-sm red" target="_blank" title="Renew Annual License Fee Now" >Click Here to Renew Annual License Now <i class="m-icon-swapright m-icon-white"></i></a><br /><br />
            2016 &copy; <a style="color:#fff; font-weight:bold;" href="http://www.northwindproject.com" target="_blank">NorthWindProject.com</a> | <a style="color:#fff; font-weight:bold;" href="http://www.maybeachtech.com" target="_blank">Maybeach Tech</a> 
			<br /><br />
			<p style="color:#ddd;">For Technical Support<br />Visit: <a href="http://www.northwindproject.com/technical-support?project=<?php echo $app; ?>" target="_blank" title="More Information on Technical Support" style="color:#fff;" >www.northwindproject.com/technical-support</a></p>
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
   <script src="<?php echo $pagepointer; ?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
   <!-- END CORE PLUGINS -->
   <script src="<?php echo $pagepointer; ?>assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
   <script src="<?php echo $pagepointer; ?>js/fileuploader.js" type="text/javascript"></script>
   <!-- BEGIN PAGE LEVEL PLUGINS -->
   <script src="<?php echo $pagepointer; ?>assets/plugins/countdown/jquery.countdown.js" type="text/javascript"></script>
   <script src="<?php echo $pagepointer; ?>assets/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
   <!-- END PAGE LEVEL PLUGINS -->
   <!-- BEGIN PAGE LEVEL SCRIPTS -->
   <!--<script src="<?php echo $pagepointer; ?>assets/scripts/app.js" type="text/javascript"></script>-->
   <script src="<?php echo $pagepointer; ?>assets/scripts/coming-soon.js" type="text/javascript"></script>      
   <!-- END PAGE LEVEL SCRIPTS --> 
   
   <script>
      jQuery(document).ready(function() {
       // App.init();
        ComingSoon.year = <?php echo date("Y", $date); ?>;
        ComingSoon.month = <?php echo date("n", $date); ?>;
        ComingSoon.day = <?php echo date("j", $date); ?>;
		
		ComingSoon.init();
		<?php if( ! $app ){ ?>
		var uploader = new qq.FileUploader({
			element: document.getElementById("upload-box"),
			listElement: document.getElementById('separate-list'),
			action: '<?php echo $pagepointer; ?>php/upload_1.php',
			params: {
				license:1,
			},
			onComplete: function(id, fileName, responseJSON){
				if( responseJSON.success == 'true' ){
					$('.qq-upload-success')
					.find('.qq-upload-failed-text')
					.text('Success')
					.css('color','#ff6600');
					
					//reload
					document.location = document.location;
				}else{
					alert('Failed to Upload License File. Please Your Hyella License File and Try Again');
				}
			}
		});
		<?php } ?>
      });
	  
   </script>
   <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
