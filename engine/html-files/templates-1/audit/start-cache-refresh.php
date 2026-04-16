<style type="text/css">
#update-progress-container li{
	margin:5px 0;
	text-align:left;
}
#update-progress-container li.task{
	/*color:#ccc;*/
}
</style>
<div class="alert alert-danger">
<h4><i class="icon-bell"></i> Refreshing...</h4>

<?php if( isset( $data["show_check_button"] ) && $data["show_check_button"] ){ ?>
<a href="#" class="btn btn-sm dark custom-single-selected-record-button" override-selected-record="1" action="?module=&action=audit&todo=refresh_cache_via_background_check">Check Refresh Status</a>
<?php } ?>

<ol id="update-progress-container">
	<li><i class="icon-check"></i> Starting Cache Refresh</li>
	<li class="task">Preparing Users Cache...</li>
</ol>
</div>