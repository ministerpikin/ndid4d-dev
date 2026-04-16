<?php
	$date = 0;
	if( isset( $_GET["record_id"] ) && doubleval( $_GET["record_id"] ) ){
		$date = doubleval($_GET["record_id"]);
	}
?>
<!-- BEGIN PAGE CONTAINER -->  
    <div class="page-container" style="background:url(<?php echo $site_url; ?>/engine/frontend-assets/img/bg.jpg) center center no-repeat;">
		<style type="text/css">
		.desc{
			display:block;
			font-size:0.8em;
			white-space: normal;
			font-style:italic;
			color:#eee;
			margin-top:10px;
		}
		 </style>
		<!-- BEGIN BREADCRUMBS -->   
		<div class="row breadcrumbs margin-bottom-40a">
			<div class="container">
				<div class="col-md-4 col-sm-4">
					<h1>&nbsp;</h1>
				</div>
				<div class="col-md-8 col-sm-8">
					<ul class="pull-right breadcrumb">
						<li><a href="">Home</a></li>
						<li class="active">Book Event</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- END BREADCRUMBS -->

		<!-- BEGIN CONTAINER -->   
		<div class="container min-hight">
			<!-- BEGIN ABOUT INFO -->   
			<div class="row margin-bottom-30">
				<!-- BEGIN INFO BLOCK -->               
				<div class="col-md-2">
				</div>
				<!-- END INFO BLOCK -->   

				<!-- BEGIN CAROUSEL -->            
				<div class="col-md-8">
					<div style="box-shadow: 1px 11px 11px 3px #848181; margin-top:0px; margin-bottom:30px; padding:20px; background:#FdFdFF;">
						<h4>Track Your Invoice</h4>
						<hr />
						<div>
							<div class="input-group input-large">
                                 <input type="text" id="track-invoice-field" class="form-control col-md-12" placeholder="Invoice No. / Tracking No.">
                                 <span class="input-group-btn">
                                 <button class="btn blue custom-single-selected-record-button" override-selected-record="" mod="edit-<?php echo md5("event"); ?>" action="?module=&action=events&todo=track_invoice" type="button" id="track-invoice-button">Track Your Invoice</button>
                                 </span>
                              </div>
						</div>
						<br />
						<div id="dash-board-main-content-area">
							<div class="note note-warning">
								<h4 class="block">Track Your Invoice</h4>
								<p>
								   Enter your invoice number to view its current status
								</p>
							 </div>
						</div>
					</div>
				</div>
				<!-- END CAROUSEL -->             
			</div>
			<!-- END ABOUT INFO -->   
            
		</div>
		<!-- END CONTAINER -->

	</div>
    <!-- END PAGE CONTAINER -->  
	<script type="text/javascript">
		window.onload = function(){
			
			$("#track-invoice-button")
			.on( "click", function(){
				$("#track-invoice-button").attr( "override-selected-record", $("#track-invoice-field").val() );
			});
		};
	</script>