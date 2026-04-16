<?php 
	// $hide_tab = 0;
	$hide_tab = isset( $data[ 'hide_tab' ] ) ? $data[ 'hide_tab' ] : 0;
	$handsontb_container = 'bulk-operation-table-container';

	$data_sources = isset( $data[ 'spreadsheet' ][ 'data_sources' ] ) ? $data[ 'spreadsheet' ][ 'data_sources' ] : array();
	$data_object = isset( $data[ 'spreadsheet' ][ 'data_object' ] ) ? $data[ 'spreadsheet' ][ 'data_object' ] : array();
	$colHeaders = isset( $data[ 'spreadsheet' ][ 'colHeaders' ] ) ? $data[ 'spreadsheet' ][ 'colHeaders' ] : array();
	$columns = isset( $data[ 'spreadsheet' ][ 'columns' ] ) ? $data[ 'spreadsheet' ][ 'columns' ] : array();
	$get = isset( $data[ 'get' ] ) ? http_build_query( $data[ 'get' ] ) : array();
	// echo '<pre>';print_r( $data );echo '</pre>'; 
	
	$unique_key = 1;
	$full_table = isset( $data[ 'full_table' ] ) ? $data[ 'full_table' ] : 1;
	$col_1 = isset( $data[ 'col_1' ] ) ? $data[ 'col_1' ] : 9;
	$col_2 = isset( $data[ 'col_2' ] ) ? $data[ 'col_2' ] : 3;
	$container = isset( $data[ 'container' ] ) ? $data[ 'container' ] : 'ee1';
	
	$before_html = isset( $data[ 'before_html' ] ) ? $data[ 'before_html' ] : '';
	$form_html = isset( $data[ 'form_html' ] ) ? $data[ 'form_html' ] : '';
	$form_preview = isset( $data[ 'form_preview' ] ) ? $data[ 'form_preview' ] : 'Preview Only';
	$form_button = isset( $data[ 'form_button' ] ) ? $data[ 'form_button' ] : 'Save Changes &rarr;';
	$cn_p = isset( $data[ 'confirm_prompt' ] ) ? $data[ 'confirm_prompt' ] : 'confirm-prompt';
	
	$plugin = isset( $data[ 'plugin' ] ) ? $data[ 'plugin' ] : '';
	$table = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';
	$title = isset( $data[ 'title' ] ) ? $data[ 'title' ] : '';
	$type = isset( $data[ 'type' ] ) ? $data[ 'type' ] : '';
	$action = isset( $data[ 'action' ] ) ? $data[ 'action' ] : '';
	$action .= '&html_replacement_selector='. $container;
	$id = isset( $data[ 'id' ] ) ? $data[ 'id' ] : '';
	$store = isset( $data[ 'store' ] ) ? $data[ 'store' ] : '';
	$hidden_fields = isset( $data[ 'hidden_fields' ] ) ? $data[ 'hidden_fields' ] : array();
	$hide_refresh = isset( $data[ 'hide_refresh' ] ) ? $data[ 'hide_refresh' ] : 0;

	$s_btns = isset($data['spreadsheet_btns']) && $data['spreadsheet_btns'] ? $data['spreadsheet_btns'] : [];
	
	$tabs = isset( $data[ 'tabs' ] ) ? $data[ 'tabs' ] : array();
	$h = '';
?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?> id="<?php echo $container; ?>">
<style>
	.htb-con .handsontable td {
	  height: 25px;
	  padding: 0px 4px;
	}
</style>

<?php if( isset( $get ) && $get && ! $hide_refresh ){ ?>
<div class="row">
	<div class="col-md-12" style="text-align: center;">
		<h4 style="text-align:center;display: inline-block;"><a href="#" title="Re-open Window" class="custom-single-selected-record-button" override-selected-record="<?php echo $id; ?>" action="?<?php echo $get; ?>"><i class="icon-refresh"></i></a> <strong><?php echo $title; ?></strong></h4>
	</div>
</div>
<br />
<?php } ?>
	
<?php
	if( ! empty( $data_object ) && ! empty( $columns ) && ! empty( $colHeaders ) ){
		$title = isset( $data[ 'main_tab_title' ] ) ? $data[ 'main_tab_title' ] : $title;
		
		$h1 = '';
		if( $before_html )$h1 = '<h4>'. $before_html .'</h4>';

		$h1 .= '<div id="'. $handsontb_container .'" class="htb-con" style1="min-height:420px; width:100%;"></div>';
		
		$xact = $action;
		if( isset( $data[ 'params' ] ) )$xact .= $data[ 'params' ];
		$h2 = '<form id="'. $handsontb_container .'-form" class="activate-ajax '.$cn_p.'" method="post" action="'. $xact . '">';
			$h2 .= get_form_headers( array(
				'action' => $xact,
				'table' => $table,
				'nw_more_data' => isset( $hidden_fields["form_more_data"] )?$hidden_fields["form_more_data"]:'',
			) );
			
			if( isset( $hidden_fields["form_more_data"] ) ){
				unset( $hidden_fields["form_more_data"] );
			}
			
			$h2 .= '<input type="hidden" value="'. $id .'" name="id" />';
			$h2 .= '<input type="hidden" value="'. $type .'" name="s_type" />';
			$h2 .= '<input type="hidden" value="'. $plugin .'" name="s_plugin" />';
			$h2 .= '<input type="hidden" value="'. $table .'" name="s_table" />';
			$h2 .= '<input type="hidden" value="'. $store .'" name="s_store" />';
			
			if( ! empty( $hidden_fields ) ){
				foreach( $hidden_fields as $hk => $hv ){
					$h2 .= '<input type="hidden" value="'. $hv .'" name="'.$hk.'" />';
				}
			}
			
			$h2 .= $form_html;
			
			if( $form_preview ){
				$h2 .= '<label><input type="checkbox" value="1" name="s_preview" /> Preview Only</label><br />';
			}
			
			$h2 .= '<input type="submit" value="'. $form_button .'" class="btn blue" />';
			$h2 .= '<textarea name="data" style="display:none;"></textarea>';
		$h2 .= '</form>';
		$h2 .= '<br /><i>* denotes required fields</i>';
		
		if( $full_table ){
			$h .= $h1 . '<hr />' . $h2;
		}else{
			$h .= '<div class="row"><div class="col-md-'. $col_1 .'">' . $h1 . '</div><div class="col-md-'. $col_2 .'">' . $h2 . '</div></div>';
		}
?>
	<div class="row" id="<?php echo $handsontb_container . '-con'; ?>">
		<div class="col-md-12">
			
			<?php if( ! $hide_tab ){ ?>
			<div class="tabbable tabbable-custom" id="transaction-tabs">
				<ul class="nav nav-tabs">
				
				   <?php
					$th1 = '';
					$hh1 = '';
					$th2 = '';
					$d_active = 'active';
					$d_active2 = '';
					if( ! empty( $tabs ) ){ 
						foreach( $tabs as $ktbs => $tbs ){
							$tb_action = ( isset( $tbs[ 'action' ] ) && $tbs[ 'action' ] ) ? $tbs[ 'action' ] : '';
							$tb_todo = ( isset( $tbs[ 'todo' ] ) && $tbs[ 'todo' ] ) ? $tbs[ 'todo' ] : '';
							$tb_title = ( isset( $tbs[ 'title' ] ) && $tbs[ 'title' ] ) ? $tbs[ 'title' ] : '';
							$otr = ( isset( $tbs[ 'one_time_request' ] ) && $tbs[ 'one_time_request' ] ) ? 'one-time-request' : '';
							
							$xcon1 = 'tabd-'.$ktbs.'-' .  $unique_key;

							if( isset( $tbs[ 'html' ] ) && $tbs[ 'html' ] ){
								
								if( ! $d_active2 && isset( $tbs[ 'active' ] ) && $tbs[ 'active' ] ){
									$d_active = '';
									$d_active2 = ' active ';
								}
								
								$th1 .= '<li class="'.$d_active2.'"><a data-toggle="tab" href="#'.$xcon1.'" class=" m-link">' .  $tb_title . '</a></li>';
								$hh1 .= '<div class="'.$d_active2.' tab-pane" id="'. $xcon1 . '">'. $tbs[ 'html' ] .'</div>';
								
								if( $d_active2 ){
									$d_active2 = ' second-place ';
								}
							}else if( $tb_action && $tb_todo && $tb_title ){ 
								$xcon = $xcon1;
								if( $otr ){
									$hh1 .= '<div class="tab-pane" id="'. $xcon1 . '"></div>';
								}else{
									$xcon1 = 'tab-2-' .  $unique_key;
									$xcon = 'general-activity-window-' .  $unique_key;
								}
								
								$th2 .= '<li ><a data-toggle="tab" href="#'.$xcon1.'" clickme="' .  $ktbs . '" class="custom-single-selected-record-button ' .  $otr .'" action="?action=' .  $tb_action .'&todo=' .  $tb_todo .'&html_replacement_selector='.$xcon.'" override-selected-record="' .  $id . '" class="m-link">' .  $tb_title . '</a></li>';
							}  
						} 
					} 
				   ?>
				   
				   <li class="<?php echo $d_active; ?>"><a data-toggle="tab" href="#tab-1-<?php echo $unique_key; ?>"><?php echo $title; ?></a></li>
				   
				   <?php echo $th1.$th2; ?>
				   <?php if( isset( $data_sources ) && ! empty( $data_sources ) ){ ?>
				   <li ><a data-toggle="tab" href="#tab-3-<?php echo $unique_key; ?>" id="tab3-handle-<?php echo $unique_key; ?>">Calculated Values</a></li>
				   <?php } ?>


				</ul>
				<div class="tab-content resizable-heightx" styleX="overflow-y:hidden; overflow-x:hidden;">
					<?php echo $hh1; ?>
					<div class="tab-pane <?php echo $d_active; ?>" id="tab-1-<?php echo $unique_key; ?>"><?php } ?>
						<div class="row">
							<div class="col-md-3">
								<div class="note note-danger the-notice-container" style="display:none;"></div>
							</div>
							<?php
								if( $s_btns ){ ?>
									<div class="col-md-9">
										<div class="pull-right">
											<?php
												foreach( $s_btns as $_btn ){ ?>
													<a href="#" class="custom-single-selected-record-button btn btn-default" action="?action=<?php echo $_btn[ 'action' ]; ?>&todo=<?php echo $_btn[ 'todo' ].( isset($_btn['html_replacement_selector']) && $_btn['html_replacement_selector'] ? '&html_replacement_selector='.$_btn['html_replacement_selector'] : '' ).(isset($_btn['params']) && $_btn['params'] ? '&'.$_btn['params'] : '');?>" override-selected-record="<?php echo isset($_btn['record']) ? $_btn['record'] : '-' ?>"><?php echo $_btn['title']; ?></a>
												<?php }
											?>
										</div>
									</div>
								<?php }
							?>
						</div>
						<?php echo $h;
						if( ! $hide_tab ){ ?>
					</div>
					<div class="tab-pane" id="tab-2-<?php echo $unique_key; ?>">
						<br />
						<div id="general-activity-window-<?php echo $unique_key; ?>">
							<div class="note note-info"><h4><strong>No Action/Task</strong></h4>Some activities in 'Basic Info' will appear here</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-3-<?php echo $unique_key; ?>">
						<br />
						<?php print_r( $data_sources ); ?>
					</div>
				</div>
			</div>
			<?php } ?>
			
		</div>
	</div>

<script type="text/javascript" class="auto-remove">
	nwBulkDataCapture.global_data = <?php echo json_encode( $data_object ); ?>;
	nwBulkDataCapture.global_colHeaders = [<?php echo implode( ",", $colHeaders ); ?>];
	nwBulkDataCapture.global_columns = <?php echo json_encode( $columns ); ?>;
	nwBulkDataCapture.tbcontainer = '<?php echo $handsontb_container; ?>';
	nwBulkDataCapture.activateData = {};
	
	<?php //if( file_exists( dirname( __FILE__ ).'/bulk-data-capture.js' ) )include "bulk-data-capture.js"; ?>

</script>
	<?php }else{ ?>
		<div class="note note-warning"><h4><strong>No Data Found</strong></h4>Try adjusting your search filter</div>
	<?php } ?>
</div>