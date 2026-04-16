<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	// echo '<pre>'; print_r( $data ); echo '</pre>';
	$in_progress = 1;
	$access = get_accessed_functions();
	$super = 0;
	if( ! is_array( $access ) && $access == 1 ){
		$super = 1;
	}
	
	$e = isset( $data["event"] )?$data["event"]:array();
	$actions = isset( $data["actions"] )?$data["actions"]:array();
	
	$container = isset( $data["container"] )?$data["container"]:'';
	$container = isset( $data["html_replacement_selector"] )?$data["html_replacement_selector"]:'';
	
	if( isset( $e["report"] ) && $e["report"] ){
?>
	<div class="row">
		<div class="col-md-12">
			<h4 style="text-align:center;"><a href="#" title="Re-open Window" class="custom-single-selected-record-button" override-selected-record="<?php echo $e["report"]; ?>" action="?action=all_reports&todo=reports_center&html_replacement_selector=<?php echo $container; ?>&report=<?php echo $e["report"]; ?>"><i class="icon-refresh"></i></a> <strong><?php echo $e["report_title"]; ?></strong></h4>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			
			<div class="tabbable tabbable-custom" id="transaction-tabs">
				<ul class="nav nav-tabs">
				   <li class="active"><a data-toggle="tab" href="#tab-1">Query Window</a></li>
				   
				   <li ><a data-toggle="tab" href="#tab-2" id="tab2-handle" >Report Window</a></li>
				   
				   <li ><a data-toggle="tab" href="#tab-3" id="tab3-handle" class="custom-single-selected-record-button" action="?action=files&todo=display_my_report&html_replacement_selector=display-my-report-window&hide_title=1" override-selected-record="-">My Reports</a></li>
				  
				</ul>
				<div class="tab-content resizable-height" style="overflow-y:auto; overflow-x:hidden;">
					
					<div class="tab-pane active" id="tab-1">
						<br />
						<div class="row">
							<div class="col-md-4">
								<div class="well" style="padding:10px;">
								
								<form id="select-report-type-form" class="activate-ajax" action="?action=all_reports&todo=get_report_filter_form&html_replacement_selector=report-filter-form-container&html_replacement_selector2=report-comments-container">
									<input type="hidden" name="report" value="<?php echo $e["report"]; ?>" />
									<h4><strong>Select Report</strong></h4>
									<?php
										foreach( $e["options"] as $k => $v ){
											if( $v ){
											?>
											<label style="display:block; font-weight:normal;"><input type="radio" name="report_type" value="<?php echo $k; ?>" class="on-change-submit"> <?php echo $v; ?></label>
											<?php
											}else{
												echo '<br />';
											}
										}
									?>
								</form>
								
								</div>
							</div>
							
							<div class="col-md-4">
								<div id="report-filter-form-container">
									<h4><strong>Report Options</strong></h4>
									<div class="note note-warning">
										<p>Select a report to see the available options</p>
									</div>
								</div>
							</div>
							
							<div class="col-md-4">
								<div id="report-comments-container">
									
								</div>
							</div>
							
						</div>
						
					</div>
					
					<div class="tab-pane" id="tab-2">
						<br />
						<div id-old="data-table-section" id="display-report-window" style="overflow-y:auto;">
						</div>
						<div id="data-table-section">
						</div>
					</div>
					
					<div class="tab-pane" id="tab-3">
						<br />
						<div id="display-my-report-window">
						</div>
					</div>
					
				</div>
			</div>
			
		</div>
	</div>
	<?php } ?>
	
<script type="text/javascript" class="auto-remove">
	<?php
		if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; 
	?>
</script>
</div>