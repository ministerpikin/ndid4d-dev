<?php 
	$package = "";
	if( defined( "HYELLA_PACKAGE" ) ){
		$package = HYELLA_PACKAGE;
	}
?>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	include "expense-function.php";
?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar ">
 <!-- BEGIN SIDEBAR MENU -->        
 <ul class="page-sidebar-menu">
   
	<li>
	   <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
	   <form class="sidebar-search activate-ajax" method="post" id="project" action="?action=project&todo=search_project">
			 <div class="input-box">
				<input class="form-control select2" action="?module=&action=project&todo=get_project_select2" placeholder="Select Project" name="project" />
			 </div>
	   </form>
	   <!-- END RESPONSIVE QUICK SEARCH FORM -->
	</li>
	<li id="tree-view-container">
	   
	</li>
 </ul>
 <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR -->
<!-- BEGIN PAGE -->
<div class="page-content" id="page-sub-content">
</div>
<script type="text/javascript" class="auto-remove">
	var g_site_url = "<?php echo $site_url; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>