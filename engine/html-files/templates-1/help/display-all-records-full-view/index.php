<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<!--EXCEL IMPOT FORM-->
<div class="row">
	<div class="col-md-12" > 
		
		<div class="portlet box purple">
		<div class="portlet-title">
			<div class="caption">
				<small>
				<i class="icon-bell"></i> <span id="datatable-title"><?php if( isset( $data['title'] ) && $data['title'] )echo $data['title']; ?></span>
				</small>
			</div>
		</div>
		<div class="portlet-body" style="padding-bottom:40px; padding-left:0; padding-top:0;" id="data-table-section">
			<iframe id="excel-import-form-container" style="border:none; margin-left:0px;" src="<?php echo $site_url; ?>help/<?php if( isset( $data['file'] ) && $data['file'] )echo $data['file']; ?>" width="100%" name="help"></iframe>
		</div>
	</div>
	</div>
</div>

<script type="text/javascript" class="auto-remove">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>