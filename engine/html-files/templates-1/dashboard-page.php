<?php
	$super = 0;
	
	$access = array();
	
	if( get_disable_access_control_settings() ){
		$super = 1;	//new addition
	}else{
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
	
	if( empty( $access ) && ! $super ){
		?>
		<div class="alert alert-danger">
			<h1>Access Roles Not Set</h1>
			<p>Please Refresh Access Roles Cache</p>
		</div>

		<?php
	}
	
	//print_r( $access );
	$key = "11297443254";	//backend
	$key2 = "aec780d3f8240cd4a3c7255669ba5dab";	//backend
	if( isset( $access[ $key ] ) ||  isset( $access[ $key2 ] ) || $super ){
	$pr = get_project_data();
?>
 <style type="text/css">
	.datepicker-days th.dow,
	.datepicker-days td.day{
		font-size:10px !important;
	}
	.header-inner .nav-tabs > li > a{
		line-height:1;
	}
	.header-inner .portlet-tabs > .nav-tabs > li{
		float:left;
	}
	.header-inner .nav-tabs > li{
		float:left;
		margin-right:4px;
	}
	.header-inner .nav-tabs {
		margin-top:0px;
		margin-bottom:0px;
		margin-right:100px;
		margin-left:0px;
	}
	.header-inner .nav > li > a{
		padding:7px 15px;
		text-transform:uppercase;
		color:#000;
	}
	.header-inner .portlet.box > .portlet-title{
		padding:0;
		background:#fff;
		border-bottom-color:#ddd;
	}
	.header-inner .portlet > .portlet-title > .caption{
		font-size:12px;
		margin-bottom:0;
	}
	.header-inner .text-white{
		color:#fff;
	}
	/*.header-inner .btn-default:not(.btn-bordered){*/
	.header-inner .btn-default{
		border-width: 0px;
	}
	.header-inner .nav-tabs{
		border-bottom-color:transparent;
	}
	.header-inner .nav-tabs > li > a{
		border-bottom-color:transparent;
		border-bottom-width: 2px;
		margin-bottom: -1px;
	}
	.header-inner .nav-tabs > li.active > a, .header-inner .nav-tabs > li.active > a:hover, .header-inner .nav-tabs > li.active > a:focus{
		border-color:#ddd;
		color:#000;
		font-weight:bold;
		border-bottom-color:#fff;
		border-bottom-width: 2px;
		margin-bottom: -1px;
	}
	.header-inner .nav-tabs > li.active > a:focus,
	.header-inner .nav-tabs > li.active > a{
		/*
		background: #4d90fe;
		border-bottom-color: #4d90fe;
		color: #fff;
		*/
		background: #d1e2ff;
		border-color: #4d90fe;
		border-bottom-color: #d1e2ff;
	}
	body.login{
		background-color:#f1f1f1 !important;
		overflow:hidden;
	}
	.header .hor-menu ul.nav li a{
		padding:5px 15px;
		font-size:12px;
	}
	
	.header .hor-menu ul.nav li a:hover,
	.header .hor-menu ul.nav li.active a{
		color:#fff;
	}
	#horizontal-nav{
		margin:10px;
	}
	a.top-bar-icon.icon-btn div{
		margin-top: 0px;
		font-weight:400;
	}
	a.top-bar-icon.icon-btn i{
		font-size: 10px;
	}
	a.top-bar-icon{
		background-color: #fff !important;
		border: 1px solid #fff;
		margin-top: 0;
		padding:8px;
		padding-top: 5px;
		height:62px;
	}
	.backend-menu a.active-clicked-menu{
		border: 1px solid #4d90fe !important;
		background:#d1e2ff !important;
	}
	.backend-menu .btn-group > .btn:hover,
	a.top-bar-icon:hover{
		background-color: #FaFeFF !important;
		border: 1px solid #47CCEA !important;
	}
	
	.border-right{
		border-right:1px solid #ddd;
	}
	.border-left{
		border-left:1px solid #ddd;
	}
	.align-left{
		text-align:left;
	}
	#dash-board-main-content-area .tile{
		height:185px;
	}
 </style>
 
 <!-- BEGIN HEADER -->   
   <div class="header navbar navbar-fixed-top1" style="background:#fff !important; height:auto;">
      <!-- BEGIN TOP NAVIGATION BAR -->
      <div class="header-inner" id="dashboard-menus">
	   <div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
		<div class="row">
			<div class="col-md-3">
				<div class="btn-group">
					<button type="button" class="btn btn-default"><i class="icon-cogs"></i></button>
				</div>
				<span style="font-size:0.8em;">ver <span id="project-version"><?php echo get_app_version( $pagepointer ); ?></span></span>
			</div>
			<div class="col-md-6 text-white" style="background:#4D90FE !important; /*c:#4D90FE !important; b:#e02222 !important;*/">
				<p class="text-center" style="margin: 5px 0 6px 0;" id="secondary-display-title"><?php echo $pr["company_name"] . " - " . $pr["app_title"]; ?></p>
			</div>
			<div class="col-md-3" style="text-align:right;">
				<div class="btn-group" style="margin-right:5px;">
					<button class="btn-sm btn blue custom-single-selected-record-button" style="display:none;" id="current-store-button" action="?module=&action=stores&todo=change_store_backend" title="Go">Go</button>
					
					<!--<a href="#" class="btn btn-default custom-action-button" function-id="79799510" function-class="help" function-name="display_help_library" module-id="12" module-name="Help" title="Help"><i class="icon-question"></i></a>-->
					<?php if( ! ( defined("HYELLA_NO_APP") && HYELLA_NO_APP ) ){ ?>
					<a href="../sign-in/" class="btn btn-sm dark" title="App Dashboard">FRONTEND <i class="icon-external-link"></i>&nbsp;</a>
					<?php } ?>
					
					<?php if( defined("HYELLA_WEB_COPY") && HYELLA_WEB_COPY ){ ?>
					<a href="?activity=update" class="btn btn-sm blue" title="License & Billing Info"><i class="icon-upload-alt"></i> License & Billing Info</a>
					<?php }else{ ?>
					<a href="?activity=update" class="btn btn-sm blue" title="Update Application"><i class="icon-upload-alt"></i> Update Application</a>
					<?php } ?>
					<!--
					<button type="button" class="btn btn-default" title="Minimize"><i class="icon-minus"></i></button>
					<button type="button" class="btn btn-default" title="Restore Down / Maximize"><i class="icon-fullscreen"></i></button>
					<button type="button" class="btn btn-default" title="Close"><i class="icon-remove"></i></button>
					-->
				</div>
			</div>
		</div>
		</div>
		<?php 
			include "dashboard-menu.php";
		?>
	  </div>
      <!-- END TOP NAVIGATION BAR -->
   </div>
   <!-- END HEADER -->
   <div class="clearfix"></div>
   
  <div class="clearfix"></div> 
  <!-- BEGIN PAGE -->
  <div class="page-content1" id="dash-board-main-content-area" style="margin-right:11px;">  
	
  </div>
</div>
  <!-- END PAGE -->
  <div id="notification-container"></div>
 <script type="text/javascript">
	
</script>
<?php 
	}else{
		?>
		<div class="alert alert-danger">
			<h1>Access Denied</h1>
			<p>You do not have access to the Backend</p>
		</div>

		<?php
	}
?>