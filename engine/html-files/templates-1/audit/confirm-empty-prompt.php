<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php

	$params = '';
	if( isset( $data["html_replacement_selector"] ) && $data["html_replacement_selector"] ){
		$params = '&html_replacement_selector=' . $data["html_replacement_selector"];
	}
?>

<br />
<br />
<div class="row">
<div class="col-md-6 col-md-offset-3" >
	<div class="auto-scroll resizable-height" >
	<div class="alert alert-danger note note-danger" id="confirm_empty_database">
	<h4><i class="icon-bell"></i> Confirm Empty Database</h4>
		<p>Click "Yes, Empty Database" to Confirm this Operation.</p>
		<p>NOTE: This operation cannot be reversed once it is confirmed</p>
		<br />
		<?php if( isset( $data["keys"] ) && is_array( $data["keys"] ) && ! empty( $data["keys"] ) ){ ?>
		<ol>
		<?php 
			foreach( $data["keys"] as $k => $v ){
				?><li><?php echo $v; ?></li><?php
			} 
		?>
		</ol>
		<?php } ?>
		<a href="#" class="btn red btn-sm custom-single-selected-record-button" override-selected-record="1" action="?action=audit&todo=confirm_empty_database<?php echo $params; ?>" confirm-prompt="Empty Database" title="Empty Database">Yes, Empty Database</a>
		
	</div>
	</div>
	</div>
</div>
</div>