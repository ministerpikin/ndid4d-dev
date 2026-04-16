<!--EXCEL IMPOT FORM-->
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<br />
<div class="row">
	<div class="col-md-9 col-md-offset-1">
		<h4><strong>More Settings</strong></h4>
		<hr />
		<div class="row" >
			<div class="col-md-4">
				<div class="note note-warning">
					<h4 class="block">Show / Hide Print Dialog Box</h4>
					<p>
						<code>STATUS: <strong id="print-dialog-box-status"><?php if( isset( $data["hide_print_dialog_box_caption"] ) )echo 'HIDDEN'; else echo "ENABLED"; ?></strong></code>
					</p>
					<p>
					   If the Print Dialog Box is hidden, the Print Preview window will not be displayed when printing
					</p>
					<a href="#" class="btn blue btn-block custom-action-button" funtion-id="1" function-name="hide_print_dialog_box" function-class="audit">
						<i class="icon-print"></i> <span id="print-dialog-box-caption"><?php if( isset( $data["hide_print_dialog_box_caption"] ) )echo $data["hide_print_dialog_box_caption"]; else echo "Hide Print Dialog Box"; ?></span>
					</a>
				 </div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>