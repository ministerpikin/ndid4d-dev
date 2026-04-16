<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
	// echo '<pre>'; print_r( $data ); echo '</pre>';exit;
	$d2 = array();
	
	$has_default_values = 0;
	
	$tab_content1 = '';
	$tab_content2 = isset( $data["comment_form"] )?$data["comment_form"]:'';
	
	$tab_title1 = 'Set Default Values';
	$tab_title2 = 'Create Message';
	
	if( isset( $data["default_values_form"] ) && $data["default_values_form"] ){
		$has_default_values = 1;
		
		$tab_title1 = 'Set Default Values';
		$tab_content1 = $data["default_values_form"];
		
		$tab_title2 = 'Finish & ' . $tab_title2;
	}else{
		$tab_title1 = $tab_title2;
		$tab_title2 = '';
		
		$tab_content1 = $tab_content2;
		$tab_content2 = '';
	}
?>
<div class="row">
	<div class="col-md-12">
		<h4 style="text-align:center;" class="card-title-1"><strong> <?php echo isset( $data['title'] )?$data['title']:''; ?></strong></h4><br />
	</div>
</div>
<?php 
	//$notice = '<div class="note note-danger">There are no pending records</div>';
	$notice = '';
?>
<div class="row">
	<div class="col-md-3">
		<?php echo $notice; ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6 col-md-offset-3" id="new_comment_form-container">
		
		<div class="tabbable tabbable-custom" id="transaction-tabs">
			<ul class="nav nav-tabs">
			   <li class="active"><a data-toggle="tab" href="#tab-1">1. <?php echo $tab_title1; ?></a></li>
			   
			   <?php if( $tab_title2 ){ ?>
			   <li><a data-toggle="tab" href="#tab-2" id="tab2-handle">2. <?php echo $tab_title2; ?></a></li>
			   <?php } ?>
			   
			</ul>
			<div class="tab-content resizable-height" style="overflow-y:auto; overflow-x:hidden;">
				
				<div class="tab-pane active" id="tab-1">
					<br />
					<?php echo $tab_content1; ?>
				</div>
				
				<div class="tab-pane" id="tab-2">
					<br />
					<?php echo $tab_content2; ?>
				</div>
				
			</div>
		</div>
	</div>
</div>
	
</div>
<script type="text/javascript" >
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>