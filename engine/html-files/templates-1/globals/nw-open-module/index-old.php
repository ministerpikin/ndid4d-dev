<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
	$e = isset( $data[ 'item' ] ) ? $data[ 'item' ] : array();
	$table = isset( $data[ 'table' ] ) && $data[ 'table' ] ? $data[ 'table' ] : '';
	//echo '<pre>'; print_r( $data ); echo '</pre>';
	//echo '<pre>'; print_r( $data["locked"] ); echo '</pre>';
	// echo '<pre>'; print_r( $e ); echo '</pre>';
	//echo '<pre>'; print_r( $actions ); echo '</pre>';
	$click_btn = isset( $data[ 'click_btn' ] )?$data[ 'click_btn' ]:'';
	$title_key = isset( $data[ 'title_key' ] )?$data[ 'title_key' ]:'name';
	$title_text = isset( $data[ 'title_text' ] )?$data[ 'title_text' ]:'';
	$pid = isset( $e[ 'id' ] ) ? $e[ 'id' ] : isset( $data[ 'id' ] ) ? $data[ 'id' ] : '' ;
	
	$unique_key = get_new_id();
	
	if( isset( $pid ) && $pid && $table ){
		$container = isset( $data[ 'html_replacement_selector' ] ) && $data[ 'html_replacement_selector' ] ? $data[ 'html_replacement_selector' ] : '';
		$tasks = isset( $data[ 'general_tasks' ] ) ? $data[ 'general_tasks' ] : array();
		$tabs = isset( $data[ 'tabs' ] ) ? $data[ 'tabs' ] : array();
		$details = isset( $data[ 'details' ] ) ? $data[ 'details' ] : '';
		
		$notice = '';
		$close = '';
		
		if( isset( $data["locked"]["locked"] ) && isset( $data["locked"]["flag_action"] ) && $data["locked"]["locked"] ){
			$notice = '<div class="note note-danger"><a href="#" class="btn btn-sm pull-right btn-default custom-single-selected-record-button" override-selected-record="1" action="'. $data["locked"]["flag_action"] .'">Check for Full Access</a>';
			$notice .= isset( $data["locked"]["msg"] )?( ''. $data["locked"]["msg"] .'' ):'';
			$notice .= '</div>';
		}else{
			$close = '<a href="#" class="btn btn-sm pull-right red custom-single-selected-record-button" override-selected-record="1" action="?action=locked_files&todo=close_file&reference_table='. $table .'&reference='. $pid .'&html_replacement_selector='. $container .'" confirm-prompt="close this file & give up your access?" title="Close this file & give up your access"><i class="icon-remove"></i></a>';
		}
?>
	
	<?php if( ! ( isset( $data[ 'hide_title' ] ) && $data[ 'hide_title' ] ) ){ ?>
	<div class="row">
		<div class="col-md-12">
			<?php echo $close; ?>
			<h4 style="text-align:center;">
			<?php 
			if( isset( $data[ 'show_refresh' ] ) && $data[ 'show_refresh' ] && isset( $data[ 'action_to_perform' ] ) && $data[ 'action_to_perform' ] && $container ){ 
				$label = isset( $data[ 'table_label' ] ) ? $data[ 'table_label' ] : ucwords( $table );
			?>
				<a href="#" title="Re-open <?php echo $label ?>" class="custom-single-selected-record-button" override-selected-record="<?php echo $pid; ?>" action="?action=<?php echo $table ?>&todo=<?php echo $data[ 'action_to_perform' ]; ?>&html_replacement_selector=<?php echo $container; ?>"><i class="icon-refresh"></i></a> 
			<?php } ?>
			<strong>
			<?php 
				if( $title_text ){
					echo $title_text;
				}else{ echo isset( $e[ $title_key ] ) ? $e[ $title_key ] : ( isset( $e[ 'name' ] ) ? $e[ 'name' ] : $label ) . ( isset( $e["serial_num"] ) ? '#'.$e["serial_num"] : '' );
				} 
			?></strong></h4>
		</div>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-md-12">
			
			<div class="tabbable tabbable-custom" id="transaction-tabs">
				<ul class="nav nav-tabs">
				   <li class="active"><a data-toggle="tab" href="#tab-1-<?php echo $unique_key; ?>">Basic Info</a></li>
				   
				   <?php if( ! empty( $tasks ) ){ ?>
				   <li ><a data-toggle="tab" href="#tab-2-<?php echo $unique_key; ?>" id="tab2-handle-<?php echo $unique_key; ?>" >Activity Window</a></li>
				   <?php } ?>

				   <?php if( ! empty( $tabs ) ){ 
				   		foreach( $tabs as $ktbs => $tbs ){
				   			$tb_action = ( isset( $tbs[ 'action' ] ) && $tbs[ 'action' ] ) ? $tbs[ 'action' ] : '';
				   			$tb_todo = ( isset( $tbs[ 'todo' ] ) && $tbs[ 'todo' ] ) ? $tbs[ 'todo' ] : '';
				   			$tb_title = ( isset( $tbs[ 'title' ] ) && $tbs[ 'title' ] ) ? $tbs[ 'title' ] : '';
				   			$otr = ( isset( $tbs[ 'one_time_request' ] ) && $tbs[ 'one_time_request' ] ) ? 'one-time-request' : '';

				   			if( $tb_action && $tb_todo && $tb_title ){ ?>
					   			<li ><a data-toggle="tab" href="#tab-2-<?php echo $unique_key; ?>" clickme="<?php echo $ktbs; ?>" class="custom-single-selected-record-button <?php echo $otr ?>" action="?action=<?php echo $tb_action ?>&todo=<?php echo $tb_todo ?>&html_replacement_selector=general-activity-window-<?php echo $unique_key; ?>" override-selected-record="<?php echo $pid; ?>"><?php echo $tb_title ?></a></li>
							<?php } ?>
						<?php } ?>
				   <?php } ?>
				   
				</ul>
				<div class="tab-content resizable-heightx" style="overflow-y:hidden; overflow-x:hidden;">
					
					<div class="tab-pane active" id="tab-1-<?php echo $unique_key; ?>">
						<br />
						<div class="row">
							<?php if( $details || $notice ){ ?>
							<div class="col-md-<?php echo $data[ 'details_column' ]; ?>">
							<?php
								echo $notice;
								echo $details;
							?>
							</div>
							<?php } ?>
							
							<div class="col-md-<?php echo $data[ 'action_column' ]; ?> actions-con">
								<?php
									$h = '';
									if( ( ! empty( $data[ 'general_tasks' ] ) && is_array( $data[ 'general_tasks' ] ) ) || ( ! empty( $data[ 'general_tasks' ] ) && is_array( $data[ 'general_tasks' ] ) ) ){
											
											$h .= '<h5><strong>Actions</strong></h5>';
											$h .= '<div class="dropdown inline clearfix" ><ul class="dropdown-menux" role="menu" style="width:100%;">';
											
											if( ! empty( $data[ 'general_tasks' ] ) && is_array( $data[ 'general_tasks' ] ) ){
												$h .= __perpare_buttons( $data[ 'general_tasks' ], $unique_key, $pid, $container );
											}
											
											if( ! empty( $data[ 'general_tasks2' ] ) && is_array( $data[ 'general_tasks2' ] ) ){
												$h .= '<li class="divider"></li>';
												$h .= __perpare_buttons( $data[ 'general_tasks2' ], $unique_key, $pid, $container );
											}
											
											$h .= '</ul></div>';
									}

									$ags = isset( $data[ 'action_group' ][ 'title' ] ) && isset( $data[ 'action_group' ][ 'actions' ][0] ) ? array( $data[ 'action_group' ] ) : array();
									$ags = isset( $data[ 'action_groups' ] ) ? $data[ 'action_groups' ] : $ags;

									// echo '<pre>'; print_r( $ags ); echo '</pre>';
									if( ! empty( $ags ) ){
										foreach( $ags as $agd ){
											if( isset( $agd[ 'title' ] ) && ! empty( $agd[ 'actions' ] ) ){
												
												$h .= '<br><h5><strong>'. $agd[ 'title' ] .'</strong></h5>';
												$h .= '<div class="dropdown inline clearfix" ><ul class="dropdown-menux" role="menu" style="width:100%;">';
												
												foreach( $agd[ 'actions' ] as $kav => $av ){
													$xid = ( isset( $av["custom_id"] ) && $av["custom_id"] )?$av["custom_id"]:$pid;
													
													$ti = '';
													
													$ak = $av["action"] . '.' . $av["todo"];
													if( isset( $actions[ $ak ]["count"] ) ){
														$ti = ' title="Performed '. $actions[ $ak ]["count"] .' time(s)" ';
													}
													$params2 = ( isset( $av[ "params" ] ) ? $av[ "params" ] : '' );

													$h .= '<li role="presentation"><a clickme='. $kav .' '.$ti.' role="menuitem" tabindex="-1" href="#" action="?action='.$av["action"].'&todo='.$av["todo"].'&html_replacement_selector=general-activity-window-'. $unique_key .'&container='. $container . $params2 .'" override-selected-record="'. $xid .'" class="custom-single-selected-record-button open-activity-window" unique_key="'. $unique_key .'">'. $av["title"] .'</a></li>';
												}
												
												$h .= '</ul></div>';
											}
										}
									}
									
									if( ! empty( $data[ 'general_actions' ] ) && is_array( $data[ 'general_actions' ] ) ){
										$b = $h ? '<br />' : '';
										
										$b .= '<h5><strong>General Actions</strong></h5>';
										$b .= '<div class="dropdown inline clearfix" ><ul class="dropdown-menux" role="menu" style="width:100%;">';
										
										foreach( $data[ 'general_actions' ] as $kav => $av ){
											$xid = ( isset( $av["custom_id"] ) && $av["custom_id"] )?$av["custom_id"]:$pid;
											
											$b .= '<li role="presentation"><a clickme='. $kav .' role="menuitem" tabindex="-1" href="#" action="?action='.$av["action"].'&todo='.$av["todo"].'&container='. $container .'&modal_callback=nwOpenModule.loadLatestComment&table='. $table . ( isset( $av[ 'params' ] ) ? $av[ 'params' ] : '' ) .'" override-selected-record="'. $xid .'" class="custom-single-selected-record-button">'. $av["title"] .'</a></li>';
										}
										
										
										$b .= '</ul></div>';
										$h .= $b;
									}
									
									echo $h;
								?>
							</div>
						
							<div class="col-md-4">
								<a href="#" action="?action=comments&todo=view_details_by_reference&html_replacement_selector=latest-comments-container&table=<?php echo $table; ?>&modal=1&no_error=1" override-selected-record="<?php echo $pid; ?>" class="custom-single-selected-record-button" id="latest-comments-container-link" style="display:none;">View Latest Comments...</a>
							
								<div id="latest-comments-container" style="max-height:350px; overflow-y:auto;  position:relative;">
									<i>View Latest Comments...</i>
								</div>
							</div>
						
						</div>
						
						<hr />

					</div>
					
					<div class="tab-pane" id="tab-2-<?php echo $unique_key; ?>">
						<br />
						<div id="general-activity-window-<?php echo $unique_key; ?>">
						</div>
					</div>
					
				</div>
			</div>
			
		</div>
	</div>
	<?php } ?>	
	
<script type="text/javascript" class="auto-remove">
var click_btn = '<?php echo $click_btn; ?>';
var unique_key = '<?php echo $unique_key; ?>';
<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>

<?php 

function __perpare_buttons( $btns, $unique_key, $pid, $container ){
	$h = '';
	foreach( $btns as $kav => $av ){
		
		$ti = '';
		if( ! isset( $av["action"] ) || ! $av["action"] ){
			$av['action'] = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';
		}
		
		$ak = $av["action"] . '.' . $av["todo"];
		if( isset( $av["html_title"] ) ){
			$ti = ' title="'. $av["html_title"] .'" ';
			
		}
		$params2 = ( isset( $av[ "params" ] ) ? $av[ "params" ] : '' );

		$h .= '<li role="presentation"><a clickme='. $kav .' '.$ti.' role="menuitem" tabindex="-1" href="#" action="?action='.$av["action"].'&todo='.$av["todo"].'&html_replacement_selector=general-activity-window-'. $unique_key .'&container='. $container . $params2 .'" override-selected-record="'. $pid .'" class="custom-single-selected-record-button open-activity-window" unique_key="'. $unique_key .'">'. $av["title"] .'</a></li>';
		
	}
	return $h;
}

?>