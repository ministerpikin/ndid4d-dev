	<style type="text/css">
	.goog-te-gadget-simple{
		    height: 28px;
    line-height: 24px;
    border: 1px solid #d7d7d7;
    background: #ddd;
	}
	.top-bar-link a.btn:hover{
			color:#fff !important;
		}
	</style>
	<!-- BEGIN COPYRIGHT -->
    <div class="copyright" style="padding:3px 0; border-bottom:3px solid #4a5866;/*#35AA47*/ background:#4a5866;">
        <div class="container" >
            <div class="row">
                <div class="col-md-6 col-sm-6" style="color:#ddd;">
                    <p>
                        <a href="tel:+2348065984558" style="color:#ddd;">+234 (080) 6598 4558</a> | <a href="tel:+2348029964630" style="color:#ddd;">+234 (080) 2996 4630</a> | 
                        <a href="?page=help" style="color:#ddd;">Help?</a>
                    </p>
                </div>
				
                <div class="col-md-6 col-sm-6 top-bar-link">
					
					<div class="pull-right btn-group btn-group-solid">
						<a href="?page=track-invoice" style="color:#fff;" class="btn blue btn-sm"> Track My Invoice</a>
						<a href="?page=book-event" class="btn default btn-sm"> Book Now</a>
					</div>
					
                </div>
				
            </div>
        </div>
    </div>
    <!-- END COPYRIGHT -->
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-default navbar-static-top" style="/*border-bottom:3px solid #db3a1b;*/">
		<div class="container">
			<div class="navbar-header">
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<button class="navbar-toggle btn navbar-btn" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- END RESPONSIVE MENU TOGGLER -->
				<!-- BEGIN LOGO (you can use logo image instead of text)-->
				<a class="navbar-brand logo-v1" href="?page=homepage">
					<img src="engine/frontend-assets/img/logo_blue.png" id="logoimg" alt="" style="position: absolute;    background: #fff;    padding: 12px;    top: -10px;    box-shadow: -1px 2px 2px 0px #ddd;"/>
				</a>
				<!-- END LOGO -->
			</div>
		
			<!-- BEGIN TOP NAVIGATION MENU -->
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<?php
						$menu = array(
							"about-us" => "About Us",
							"news-and-events" => "News & Events",
							"photo-gallery" => "Photo Gallery",
							"book-event" => "Bookings",
							"contact-us" => "Contact Us",
						);
						$active = "";
						if( isset( $_GET["page"] ) && $_GET["page"] )
							$active = $_GET["page"];
						
						foreach( $menu as $key => $val ){
							if( $key == $active ){
								?>
								<li class="active"><a href="?page=<?php echo $key ?>"><?php echo $val ?></a></li>
								<?php
							}else{
								?>
								<li><a href="?page=<?php echo $key ?>"><?php echo $val ?></a></li>
								<?php
							}
						}
					?>
				</ul>                           
			</div>
			<!-- BEGIN TOP NAVIGATION MENU -->
		</div>
    </div>
    <!-- END HEADER -->
