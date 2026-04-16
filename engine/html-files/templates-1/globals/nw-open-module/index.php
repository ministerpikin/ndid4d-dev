<?php
	$e = isset( $data[ 'item' ] ) ? $data[ 'item' ] : array();
	$pid = isset( $e[ 'id' ] ) ? $e[ 'id' ] :( isset( $data[ 'id' ] ) ? $data[ 'id' ] : '' );
	
	$table_real = isset( $data[ 'table_real' ] ) && $data[ 'table_real' ] ? $data[ 'table_real' ] : '';
	$table = isset( $data[ 'table' ] ) && $data[ 'table' ] ? $data[ 'table' ] : '';
	$plugin = isset( $data[ 'plugin' ] ) && $data[ 'plugin' ] ? $data[ 'plugin' ] : '';
	$no_reolading_tab = isset( $data[ 'no_reolading_tab' ] ) && $data[ 'no_reolading_tab' ] ? $data[ 'no_reolading_tab' ] : 0;
	//echo '<pre>'; print_r( $data["general_tasks"] ); echo '</pre>';
	// echo '<pre>'; print_r( $data["show_close"] ); echo '</pre>';
	// echo '<pre>'; print_r( $e ); echo '</pre>';
	$static_comments = isset( $data[ 'static_comments' ] )?$data[ 'static_comments' ]:'';
	$click_btn = isset( $data[ 'click_btn' ] )?$data[ 'click_btn' ]:'';
	$title_key = isset( $data[ 'title_key' ] )?$data[ 'title_key' ]:'name';
	$title_text = isset( $data[ 'title_text' ] )?$data[ 'title_text' ]:'';
	$note = isset( $data[ 'note' ] )?$data[ 'note' ]:'';

	$pre_basic_info = isset( $data[ 'pre_basic_info' ] )?$data[ 'pre_basic_info' ]:'';
	
	$percentage = isset( $data[ 'percentage' ]['percentage'] )?$data[ 'percentage' ]['percentage']:0;
	$refresh_params = isset( $data[ 'refresh_params' ] )?$data[ 'refresh_params' ]:'';
	
	$tb = $table;
	if( $table_real ){
		$tb = $table_real;
	}
	//echo '<pre>'; print_r( $data ); echo '</pre>';
	$unique_key = get_new_id();
	$basic_info = 'Basic Info';
	$styx = '';
	
	if( isset( $pid ) && $pid && $table ){
		$container = isset( $data[ 'html_replacement_selector' ] ) && $data[ 'html_replacement_selector' ] ? $data[ 'html_replacement_selector' ] : 'dash-board-main-content-area';
		$tasks = isset( $data[ 'general_tasks' ] ) ? $data[ 'general_tasks' ] : array();
		$tabs = isset( $data[ 'tabs' ] ) ? $data[ 'tabs' ] : array();
		
		$single_view = isset( $data[ 'single_view' ] ) ? $data[ 'single_view' ] : '';
		if( $single_view ){
			$styx = 'background: #f1f4f6; margin: -10px;  padding: 10px;';
			$basic_info = 'Review';
		}
		
		$details = isset( $data[ 'details' ] ) ? $data[ 'details' ] : '';
		$override_refresh = isset( $data[ 'override_refresh' ] ) ? $data[ 'override_refresh' ] : '';
		
		$notice = '';
		$close = '';
		
		if( isset( $data["locked"]["locked"] ) && isset( $data["locked"]["flag_action"] ) && $data["locked"]["locked"] ){
			$notice = '<div class="note note-danger"><a href="#" class="btn btn-sm pull-right btn-default custom-single-selected-record-button" override-selected-record="1" action="'. $data["locked"]["flag_action"] .'">Check for Full Access</a>';
			$notice .= isset( $data["locked"]["msg"] )?( ''. $data["locked"]["msg"] .'' ):'';
			$notice .= '</div>';
		}else{
			$close = '<a href="#" class="btn btn-sm pull-right red custom-single-selected-record-button" override-selected-record="1" action="?action=locked_files&todo=close_file&reference_table='. $table .'&reference='. $pid .'&html_replacement_selector='. $container .'" confirm-prompt="close this file & give up your access?" title="Close this file & give up your access"><i class="icon-remove"></i></a>';
		}
		
		
		$ags = isset( $data[ 'action_group' ][ 'title' ] ) && isset( $data[ 'action_group' ][ 'actions' ][0] ) ? array( $data[ 'action_group' ] ) : array();
		$ags = isset( $data[ 'action_groups' ] ) ? $data[ 'action_groups' ] : $ags;
?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?> id="control-panel-<?php echo $unique_key; ?>">
<div id="control-panel">
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
	
	<?php if( ! ( isset( $data[ 'hide_title' ] ) && $data[ 'hide_title' ] ) ){ ?>
	<div class="row">
		<div class="col-md-12">
			<?php if( isset( $data[ 'show_close' ] ) && $data[ 'show_close' ] ){
				 echo $close;
			}
			if( isset( $data[ 'show_full_screen' ] ) && $data[ 'show_full_screen' ] ){
				 echo '<button class="btn btn-sm default pull-left" onclick="$.fn.cProcessForm.fullScreen( '. "'control-panel-" . $unique_key ."'" .' );" title="Open/Close Full Screen" ><i class=" icon-fullscreen"></i></button>';
			} ?>
			<h4 style="text-align:center;">
			<?php 
			if( isset( $data[ 'show_top_footer' ] ) && $data[ 'show_top_footer' ] ){
				$tfoot = 1;
				include "footer.php";
				unset( $tfoot );
			}
			
			if( isset( $data[ 'show_refresh' ] ) && $data[ 'show_refresh' ] && isset( $data[ 'action_to_perform' ] ) && $data[ 'action_to_perform' ] && $container ){ 
				$label = isset( $data[ 'table_label' ] ) ? $data[ 'table_label' ] : ucwords( $table );
				$refresh_table = $table;
				if( $plugin )$refresh_table = $plugin;

				$rsa = "?action=". $refresh_table ."&todo=". $data[ 'action_to_perform' ];
				if( $override_refresh )$rsa = $override_refresh;

				$rsa .= $refresh_params;
			?>
				<a href="#" title="Re-open <?php echo $label ?>" class="custom-single-selected-record-button m-link" override-selected-record="<?php echo $pid; ?>" id="reopen-<?php echo $unique_key; ?>" action="<?php echo $rsa ?>&html_replacement_selector=<?php echo $container; ?>"><i class="icon-refresh"></i></a> 
			<?php } ?>
			<strong>
			<?php 
				if( $title_text ){
					echo $title_text;
				}else{ 
					echo isset( $e[ $title_key ] ) ? $e[ $title_key ] : ( isset( $e[ 'name' ] ) ? $e[ 'name' ] : $label ) . ( isset( $e["serial_num"] ) ? '#'.$e["serial_num"] : '' );
				} 
			?></strong></h4>
		</div>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-md-12">
			
			<div class="tabbable tabbable-custom" id="transaction-tabs">
				<ul class="nav nav-tabs">
				   
				   <?php 
					$th1 = '';
					$hh1 = '';
					$th2 = '';
					$d_active = 'active';
					$d_active2 = '';
					$tab_params = ( isset( $data[ 'tab_params' ] ) && $data[ 'tab_params' ] ) ? $data[ 'tab_params' ] : '';
					// echo '<pre>'; print_r( $tabs ); echo '</pre>';
					if( ! empty( $tabs ) && ! $notice ){ 
						foreach( $tabs as $ktbs => $tbs ){
							$tb_action = ( isset( $tbs[ 'action' ] ) && $tbs[ 'action' ] ) ? $tbs[ 'action' ] : '';
							$tb_todo = ( isset( $tbs[ 'todo' ] ) && $tbs[ 'todo' ] ) ? $tbs[ 'todo' ] : '';
							$tb_title = ( isset( $tbs[ 'title' ] ) && $tbs[ 'title' ] ) ? $tbs[ 'title' ] : '';
							$otr = ( isset( $tbs[ 'one_time_request' ] ) && $tbs[ 'one_time_request' ] ) ? 'one-time-request' : '';
							$xid = ( isset( $tbs["custom_id"] ) && $tbs["custom_id"] )?$tbs["custom_id"]:$pid;

							$tab_params .= ( isset( $tbs[ 'params' ] ) && $tbs[ 'params' ] ? $tbs[ 'params' ] : '' );

							if( isset( $tbs[ 'html' ] ) && $tbs[ 'html' ] ){
								$xhref = 'tabd-'.$ktbs.'-' .  $unique_key;
								if( isset( $tbs[ 'href' ] ) && $tbs[ 'href' ] ){
									$xhref = $tbs[ 'href' ];
								}
								
								if( ! $d_active2 && isset( $tbs[ 'active' ] ) && $tbs[ 'active' ] ){
									$d_active = '';
									$d_active2 = ' active ';
								}
								
								$th1 .= '<li class="'.$d_active2.'"><a data-toggle="tab" href="#'. $xhref .'" class=" m-link">' .  $tb_title . '</a></li>';
								$hh1 .= '<div class="'.$d_active2.' tab-pane" id="'. $xhref .'">'. $tbs[ 'html' ] .'</div>';
								
								if( $d_active2 ){
									$d_active2 = ' second-place ';
								}
							}else if( $tb_action && $tb_todo && $tb_title ){ 
								$tbs_con1 = 'tab-2-' .  $unique_key;
								$tbs_con = 'general-activity-window-' .  $unique_key;
								if( isset( $tbs[ 'has_container' ] ) && $tbs[ 'has_container' ] ){
									$tbs_con1 = 'tabd2-'.$ktbs.'-' .  $unique_key;
									$tbs_con = $tbs_con1;
									
									$hh1 .= '<div class="tab-pane" id="'.$tbs_con1.'">Processing, Please wait...</div>';
								}
								
								$parek = '<li ><a data-toggle="tab" href="#'.$tbs_con1.'" clickme="' .  $ktbs . '" class="custom-single-selected-record-button ' .  $otr .'" action="?action=' .  $tb_action .'&todo=' .  $tb_todo .'&html_replacement_selector=' . $tbs_con . $tab_params.'" override-selected-record="' .  $xid . '" class="m-link">' .  $tb_title . '</a></li>';
								
								
								if( isset( $tbs[ 'position' ] ) && $tbs[ 'position' ] == 1 ){
									$th1 .= $parek;
								}else{
									$th2 .= $parek;
								}
							}  
						} 
					}
					
					?>
					
				   <li class="<?php echo $d_active; ?>"><a data-toggle="tab" class="m-link" href="#tab-1-<?php echo $unique_key; ?>"><?php echo $basic_info; ?></a></li>
				   <?php echo $th1; ?>
				   
				   <?php 
					if( ! empty( $ags ) || ! empty( $data[ 'general_tasks' ] ) || ! empty( $data[ 'general_tasks2' ] ) ){
				   ?>
				   <li ><a data-toggle="tab" href="#tab-2-<?php echo $unique_key; ?>" class="m-link" id="tab2-handle-<?php echo $unique_key; ?>" >Activity Window</a></li>
					<?php } ?>
				   <?php echo $th2; ?>

				   
				   
				</ul>
				<div class="tab-content resizable-heightx" style="overflow-y:hidden; overflow-x:hidden;">
					<?php echo $hh1; 
						
					?>
					<div class="tab-pane <?php echo $d_active; ?>" id="tab-1-<?php echo $unique_key; ?>">
						<?php echo $pre_basic_info; ?>
						<div class="nw-open-module-main" style="<?php echo $styx; ?>">
						<br />
						<div class="row">
							<?php
								$show_footer = 1;
								if( $single_view ){
									//print_r( $details );
										
									$show_footer = 0;
									echo '<div class="col-md-'. $data[ 'action_column' ] .' action-column">';
										include "footer.php";
										echo "<hr />";
										if( $percentage ){
											echo "<code>".$percentage."% complete</code><br />";
										}
										echo $notice;
										if( ! $notice ){
											include "tasks.php";
										}
										echo $details;
									echo '</div>';
									echo '<div class="col-md-'. ( 12 - $data[ 'action_column' ] ) .'">';
										echo $single_view;
									echo '</div>';
								}else{
									echo '<div class="col-md-'. $data[ 'details_column' ] .'">';
										echo $notice;
										echo $details;
									echo '</div>';
									
									if( ! $notice ){
										echo '<div class="col-md-'. $data[ 'action_column' ] .'  action-column">';
											include "tasks.php";
										echo '</div>';
									}
									?>
									<div class="col-md-4">
										<?php if( $static_comments ){ echo $static_comments; } ?>
										<?php if( isset( $data["show_comments"] ) && $data["show_comments"] ){ ?>
										<a href="#" action="?action=comments&todo=view_details_by_reference&html_replacement_selector=latest-comments-container&table=<?php echo $tb; ?>&modal=1&no_error=1" override-selected-record="<?php echo $pid; ?>" class="custom-single-selected-record-button" id="latest-comments-container-link" style="display:none;">View Latest Comments...</a>
									
										<div id="latest-comments-container" style="max-height:350px; overflow-y:auto;  position:relative;">
											<i>View Latest Comments...</i>
										</div>
										<?php } ?>
									</div>
									<?php
								}
							?>
							
						</div>
						<?php if( $show_footer ){ ?>
						<hr />
						<div class="row">
							<div class="col-md-12">
								<?php include "footer.php"; ?>
							</div>
						</div>
						<?php } ?>

						</div>
					</div>
					
					<div class="tab-pane" id="tab-2-<?php echo $unique_key; ?>">
						<br />
						<div id="general-activity-window-<?php echo $unique_key; ?>">
							<div class="note note-info"><h4><strong>No Action/Task</strong></h4>Some activities in 'Basic Info' will appear here</div>
						</div>
					</div>
				</div>
			</div>
			<?php  
			if( isset( $data[ 'show_notice' ][ 'title' ] ) && $data[ 'show_notice' ][ 'title' ] ){
				$h1 = '<div class="consent-before-action" id="open-module-notice">';
					$h1 .= '<div style="margin:5% 25%;"><div class="note note-danger"><h2><b>'. $data[ 'show_notice' ][ 'title' ] .'</b></h2>';
					$h1 .= isset( $data[ 'show_notice' ][ 'body' ] ) ? $data[ 'show_notice' ][ 'body' ] : '';
					$h1 .= '<br /><br /><button class="btn btn-lg blue" onclick="nwWorkflow.removeNotice();">'. ( isset( $data[ 'button_text' ] ) ? $data[ 'button_text' ] : 'I agree and wish to proceed' ) .' &rarr;</button><br />';
					$h1 .= '</div></div>';
				$h1 .= '</div>';
				
				echo $h1;
			}
			?>
		</div>
	</div>
	<?php } 

	
	?>	
	
<script type="text/javascript" class="auto-remove">
var click_btn = '<?php echo $click_btn; ?>';
var unique_key = '<?php echo $unique_key; ?>';
var no_reolading_tab = <?php echo $no_reolading_tab; ?>;
<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>
</div>

<?php 

function __perpare_buttons( $btns, $unique_key, $pid, $container ){
	$h = '';
	foreach( $btns as $kav => $av ){
		if( ! isset( $av["todo"] ) ){
			continue;
		}
		$class = 'open-activity-window';
		if( isset( $av[ 'do_not_open_in_new_window' ] ) && $av[ 'do_not_open_in_new_window' ] )$class = '';

		$attrx = '';

		if( isset( $av[ 'new_window' ] ) && $av[ 'new_window' ] ){
			$attrx .= ' target="_blank" ';
			
			if( $av[ 'new_window' ] == 2 ){
				$attrx .= ' new_tab="2" onclick="nwWorkflow.newWindowOpen('."'".$unique_key."'".');" ';
			}else{
				$attrx .= ' new_tab="1" ';	//open new browser tab
			}
			
			$class = '';
		}

		$pid2 = $pid;
		if( isset( $av[ 'selected_record' ] ) && $av[ 'selected_record' ] ){
			$pid2 = $av[ 'selected_record' ];
		}
		if( isset( $av[ 'custom_id' ] ) && $av[ 'custom_id' ] ){
			$pid2 = $av[ 'custom_id' ];
		}
		
		if( isset( $av[ 'confirm_prompt' ] ) && $av[ 'confirm_prompt' ] ){
			$attrx .= ' confirm-prompt="'. $av[ 'confirm_prompt' ] .'" ';
		}

		$cont = '';
		if( isset( $av[ 'html_replacement_key' ] ) && $av[ 'html_replacement_key' ] ){
			if( $av[ 'html_replacement_key' ] == 'phtml_replacement_selector' ){
				$cont = $container;
			}else{
				$cont = $av[ 'html_replacement_key' ];
			}
		}else{
			$cont = 'general-activity-window-'. $unique_key;
		}

		$ti = '';
		if( ! isset( $av["action"] ) || ! $av["action"] ){
			$av['action'] = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';
		}
		
		$ak = $av["action"] . '.' . $av["todo"];
		if( isset( $av["html_title"] ) && $av["html_title"] ){
			$ti = ' title="'. strip_tags( $av["html_title"] ) .'" ';
			
		}
		$params2 = ( isset( $av[ "params" ] ) ? $av[ "params" ] : '' );

		$add = '';
		if( isset( $av[ 'done' ] ) ){
			if( $av[ 'done' ] )$add = '<span class="label label-success"><i class="icon-ok"></i></span> ';
			else $add = '<span class="label label-danger "><i class="icon-exclamation-sign"></i></span>  ';
		}

		$h .= '<li role="presentation"><a clickme="'. $kav .'" '.$ti.' role="menuitem" tabindex="-1" href="#" action="?action='.$av["action"].'&todo='.$av["todo"].'&html_replacement_selector='. $cont .'&container='. $container . $params2 .'" override-selected-record="'. $pid2 .'" '. $attrx .'class="skip-processing custom-single-selected-record-button '. $class .'" unique_key="'. $unique_key .'">'. $add.$av["title"] .'</a></li>';
		
	}
	return $h;
}

?>