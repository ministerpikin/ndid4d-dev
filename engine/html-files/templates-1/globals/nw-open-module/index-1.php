<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
	$e = isset( $data[ 'item' ] ) ? $data[ 'item' ] : array();
	$table = isset( $data[ 'table' ] ) && $data[ 'table' ] ? $data[ 'table' ] : '';
	// echo '<pre>'; print_r( $data ); echo '</pre>';
	//echo '<pre>'; print_r( $data["locked"] ); echo '</pre>';
	// echo '<pre>'; print_r( $e ); echo '</pre>';
	//echo '<pre>'; print_r( $actions ); echo '</pre>';
	$title_key = isset( $data[ 'title_key' ] )?$data[ 'title_key' ]:'name';
	$pid = isset( $e[ 'id' ] ) ? $e[ 'id' ] : isset( $data[ 'id' ] ) ? $data[ 'id' ] : '' ;
	
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
			<strong><?php echo isset( $e[ $title_key ] ) ? $e[ $title_key ] : ( isset( $e[ 'name' ] ) ? $e[ 'name' ] : $label ) . ( isset( $e["serial_num"] ) ? '#'.$e["serial_num"] : '' ); ?></strong></h4>
		</div>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-md-12">
			
			<div class="tabbable tabbable-custom" id="transaction-tabs">
				<ul class="nav nav-tabs">
				   <li class="active"><a data-toggle="tab" href="#tab-1">Basic Info</a></li>
				   
				   <?php if( ! empty( $tasks ) ){ ?>
				   <li ><a data-toggle="tab" href="#tab-2" id="tab2-handle" >Activity Window</a></li>
				   <?php } ?>

				   <?php if( ! empty( $tabs ) ){ 
				   		foreach( $tabs as $tbs ){ 
				   			$tb_action = ( isset( $tbs[ 'action' ] ) && $tbs[ 'action' ] ) ? $tbs[ 'action' ] : '';
				   			$tb_todo = ( isset( $tbs[ 'todo' ] ) && $tbs[ 'todo' ] ) ? $tbs[ 'todo' ] : '';
				   			$tb_title = ( isset( $tbs[ 'title' ] ) && $tbs[ 'title' ] ) ? $tbs[ 'title' ] : '';
				   			$otr = ( isset( $tbs[ 'one_time_request' ] ) && $tbs[ 'one_time_request' ] ) ? 'one-time-request' : '';

				   			if( $tb_action && $tb_todo && $tb_title ){ ?>
					   			<li ><a data-toggle="tab" href="#tab-2" class="custom-single-selected-record-button <?php echo $otr ?>" action="?action=<?php echo $tb_action ?>&todo=<?php echo $tb_todo ?>&html_replacement_selector=general-activity-window" override-selected-record="<?php echo $pid; ?>"><?php echo $tb_title ?></a></li>
							<?php } ?>
						<?php } ?>
				   <?php } ?>
				   
				</ul>
				<div class="tab-content resizable-heightx" style="overflow-y:hidden; overflow-x:hidden;">
					
					<div class="tab-pane active" id="tab-1">
						<br />
						<div class="row">
							<div class="col-md-<?php echo $data[ 'details_column' ]; ?>">
							<?php
								echo $notice;
								echo $details;
							?>
							</div>
							<div class="col-md-<?php echo $data[ 'action_column' ]; ?>">
								<?php
									$h = '';
									if( ! empty( $data[ 'general_tasks' ] ) && is_array( $data[ 'general_tasks' ] ) ){
										
										$h .= '<h5><strong>Tasks</strong></h5>';
										$h .= '<div class="dropdown inline clearfix" ><ul class="dropdown-menu" role="menu" style="width:100%;">';
										
										foreach( $data[ 'general_tasks' ] as $av ){
											
											$ti = '';
											
											
											$ak = $av["action"] . '.' . $av["todo"];
											if( isset( $actions[ $ak ]["count"] ) ){
												$ti = ' title="Performed '. $actions[ $ak ]["count"] .' time(s)" ';
												
											}
											$params2 = ( isset( $av[ "params" ] ) ? $av[ "params" ] : '' );

											$h .= '<li role="presentation"><a '.$ti.' role="menuitem" tabindex="-1" href="#" action="?action='.$av["action"].'&todo='.$av["todo"].'&html_replacement_selector=general-activity-window&container='. $container . $params2 .'" override-selected-record="'. $pid .'" class="custom-single-selected-record-button open-activity-window">'. $av["title"] .'</a></li>';
										}
										
										$h .= '</ul></div>';
									}

									if( isset( $data[ 'action_group' ][ 'title' ] ) && isset( $data[ 'action_group' ][ 'actions' ][0] ) ){
										
										$h .= '<br><h5><strong>'. $data[ 'action_group' ][ 'title' ] .'</strong></h5>';
										$h .= '<div class="dropdown inline clearfix" ><ul class="dropdown-menu" role="menu" style="width:100%;">';
										
										foreach( $data[ 'action_group' ][ 'actions' ] as $av ){
											
											$ti = '';
											
											
											$ak = $av["action"] . '.' . $av["todo"];
											if( isset( $actions[ $ak ]["count"] ) ){
												$ti = ' title="Performed '. $actions[ $ak ]["count"] .' time(s)" ';
												
											}
											$params2 = ( isset( $av[ "params" ] ) ? $av[ "params" ] : '' );

											$h .= '<li role="presentation"><a '.$ti.' role="menuitem" tabindex="-1" href="#" action="?action='.$av["action"].'&todo='.$av["todo"].'&html_replacement_selector=general-activity-window&container='. $container . $params2 .'" override-selected-record="'. $pid .'" class="custom-single-selected-record-button open-activity-window">'. $av["title"] .'</a></li>';
										}
										
										$h .= '</ul></div>';
									}
									
									if( ! empty( $data[ 'general_actions' ] ) && is_array( $data[ 'general_actions' ] ) ){
										$b = $h ? '<br />' : '';
										
										$b .= '<h5><strong>General Actions</strong></h5>';
										$b .= '<div class="dropdown inline clearfix" ><ul class="dropdown-menu" role="menu" style="width:100%;">';
										
										foreach( $data[ 'general_actions' ] as $av ){
											$b .= '<li role="presentation"><a role="menuitem" tabindex="-1" href="#" action="?action='.$av["action"].'&todo='.$av["todo"].'&container='. $container .'&modal_callback=nwOpenModule.loadLatestComment&table='. $table .'" override-selected-record="'. $pid .'" class="custom-single-selected-record-button">'. $av["title"] .'</a></li>';
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
					
					<div class="tab-pane" id="tab-2">
						<br />
						<div id="general-activity-window">
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