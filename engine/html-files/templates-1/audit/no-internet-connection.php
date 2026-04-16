
<br style="line-height:0.5;"/>
<div class="alert alert-danger note note-danger">
	<h4><i class="icon-bell"></i> No Internet Access</h4>
	<div>
	Please ensure you have internet access and then try again
	</div>
	<br /><br />
	<a href="#" function-name="app_update" function-class="audit" function-id="a-1" title="Try Again" class="custom-action-button btn btn-lg red"><i class="icon-reload"></i> Try Again</a>
	<?php 
		$back_url = "./";
		if( file_exists( "../" . $pagepointer . "sign-in/index.html" ) ){
			$back_url = "../sign-in/";
		}
	?>
	<a href="<?php echo $back_url; ?>" title="Update Later" class="custom-action-button1 btn btn-lg dark"><i class="icon-reload"></i> Update Later</a>
</div>
