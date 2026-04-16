<div id="list-view-object" <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
$list_function = isset( $data["other_params"][ 'list_function' ] )?$data["other_params"][ 'list_function' ]:"";
$button_array = function_exists( $list_function ) ? $list_function() : array();

if( ! empty( $button_array ) ){
	if( isset( $data["items"] ) && is_array( $data["items"] ) && !empty( $data["items"] ) ){

		$capture = isset( $data["other_params"]["capture"] )?$data["other_params"]["capture"]:0;

		$action_to_perform = isset( $data["action_to_perform"] )?$data["action_to_perform"]:"";
		$pid = isset( $data["other_params"]["id"] )?$data["other_params"]["id"]:"";
		$item = isset( $data[ 'items' ][ $pid ] ) ? $data[ 'items' ][ $pid ] : array();	
		$op_id = isset( $item[ 'op_id' ] ) ? $item[ 'op_id' ] : '';	
		$h1 = '';
		$h2 = '';
		$h = '';
		$hall = array();
		
		// echo '<pre>';print_r( $action_to_perform );echo '</pre>';
		$params = '';
		if( $op_id ){
			$params .= '&op_id=' . $op_id;
		}
		
		$view2 = isset( $data["other_params"][ 'view2' ] ) ? $data["other_params"][ 'view2' ] : '';

		$container = 'db-obj-data-view-container';
		$container = 'db-obj-'.$action_to_perform;
		$show_report = 1;
		$bg2 = 'btn-primary';
		$icon = '<i class="icon-book"></i> ';
		
		if( $capture ){
			$show_report = 0;
			$bg2 = 'btn-success';
			$container = 'db-obj-data-capture-container';
			$icon = '<i class="icon-pencil"></i> ';
		}
		$bg = 'btn btn-block ' . $bg2;

		foreach( $button_array as $vxc => &$bx ){
			if( isset( $bx[ 'accepted_status' ] ) && $bx[ 'accepted_status' ] ){
				$ex = explode( ',', $bx[ 'accepted_status' ] );

				if( ! in_array( $item[ 'status' ], $ex ) ){
					unset( $button_array[ $vxc ] );
				}
			}
		}
		
		if( ! empty( $button_array ) ){
			foreach( $button_array as $tbx => $tv ){
				$alx = explode( ':::', $tbx );
				$plugin = '';
				$plugin_action = 'execute';
				$bgf = $bg;

				$pr2 = '';

				if( isset( $alx[1] ) && $alx[1] ){
					$plugin = $alx[0];
					$tb = $alx[1];
					if( isset( $alx[2] ) && $alx[2] ){
						$plugin_action = $alx[2];
					}
				}else{
					$tb = $alx[0];
				}

				switch( $tb ){
				case 'ivf_icsi_record2':
					$pr2 .= '&type=ovum_recipient';
					$tb = 'ivf_icsi_record';
				break;
				case 'ivf_icsi_record':
					$pr2 .= '&type=ivf_icsi';
				break;
				}

				switch( $plugin_action ){
				case 'x':
				case 'y':
					$plugin_action = 'execute';
				break;
				}
				// echo '<pre>';print_r( $alx );echo '</pre>';

				if( isset( $data[ 'other_params' ][ 'captured' ][ $tb ] ) && $data[ 'other_params' ][ 'captured' ][ $tb ] ){
					$bgf .= ' btn-success ';
				}
				
				if( isset( $tv["sub_menu"] ) && is_array( $tv["sub_menu"] ) && ! empty( $tv["sub_menu"] ) ){
					
					$h2 .= '<div class="btn-group hidden-print">';
					$h2 .= '<a type="button"  class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">'.$tv["view_caption"].' <i class="icon-angle-down"></i></a><div class="dropdown-backdrop"></div>';
					
					$h2 .= '<ul class="dropdown-menu" role="menu">';
					
					
					// $h .= '';
					$hsub = '';
					$hsub .= '<div class="panel-group accordion" id="db-obj-acc1'. $tbx .'">';
					$hsub .= '<div class="panel panel-default" style="margin-top:10px; ">';
					   //$hsub .= '<div class="panel-heading">';
						  //$hsub .= '<h4 class="panel-title">';
							 $hsub .= '<a class="accordion-toggle btn btn-block '.$bg2.' collapsed" data-toggle="collapse" data-parent="#db-obj-acc1'. $tbx .'" href="#db-obj-acc-child-1'. $capture . $tbx .'" style="padding-left:10px; text-align:left;">' .$icon . $tv["data_capture_caption"];
							 $hsub .= '</a>';
						// $hsub .= ' </h4>';
					  // $hsub .= '</div>';
					   $hsub .= '<div id="db-obj-acc-child-1'. $capture . $tbx .'" class="panel-collapse '. ( $view2 ? ' out collapse ' : ' in ' ) .'" style="position: fixed;width: 18%;z-index: 9999;background-color:white;">';
						  $hsub .= '<div class="panel-body">';
					
					/* $hsub .= '<div   class="btn-group hidden-print dropup" style="margin-top:5px;">';
					
					$hsub .= '<a type="button" href="#" style="text-align:left; padding:10px;" class="'.$bg.' dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">'.$tv["data_capture_caption"].' </a><div class="dropdown-backdrop"></div>';
					
					$hsub .= '<ul class="dropdown-menu" role="menu">'; */
						
					foreach( $tv["sub_menu"] as $tk => $v ){
						
						if( $tk == "seperator" ){
							$hsub .= $v;
							$h2 .= $v;
						}else{
							if( $show_report ){
								$tv["data_capture_todo"] = $tv["view_todo"];
							}

							$tact = 'action='. $tb .'&todo='. $tv["view_todo"];
							if( $plugin ){
								$tact = 'action='. $plugin .'&todo='. $plugin_action .'&nwp_action='. $tb . '&nwp_todo='. $tv["view_todo"];
							}
							
							$h2 .= '<li><a href="#" class="custom-single-selected-record-button" override-selected-record="' . $item["id"] . '" action="?module=&'. $tact .'&view_option='.$v["option"].'&html_replacement_selector='. $tb .'-view" title="'. $v["text"] .'">'. $v["text"] .'</a></li>';
							
							$hsub .= '<li><a href="#" class="custom-single-selected-record-button sub-list-buttons " override-selected-record="' . $item["id"] . '" action="?module=&action='. $tb .'&todo='. $tv["data_capture_todo"] .'&view_option='.$v["option"].'&html_replacement_selector='. $container . $params . $pr2 .'" title='. $v["text"] .'>'. $v["text"] .'</a> <a href="#" class="pull-right custom-single-selected-record-button" override-selected-record="' . $item["id"] . '" action="?module=&action='. $tb .'&todo='. $tv["data_capture_todo"] .'&view_option='.$v["option"].'&html_replacement_selector='. $container . $params. $pr2 .'" target="_blank" new_tab="1" title='. $v["text"] .'><i class="icon-external-link"></i></a></li>';
						}
						
					}
					
					$hsub .= '</div></div></div></div>';
					$hall[] = $hsub;
					//$h .= '</ul></div>';
					$h2 .= '</ul></div><div id="'. $tb .'-view"></div><br /><br />';
				}else{
					$tact = 'action='. $tb .'&todo='. $tv["view_todo"];
					if( $plugin ){
						$tact = 'action='. $plugin .'&todo='. $plugin_action .'&nwp_action='. $tb . '&nwp_todo='. $tv["view_todo"];
					}
					
					$h2 .= '<div id="'. $tb .'-view"><a href="#" class="custom-single-selected-record-button btn btn-default hidden-print" override-selected-record="' . $item["id"] . '" action="?module=&'. $tact .'&html_replacement_selector='. $tb .'-view" title="'. $tv["view_title"] .'">'. $tv["view_caption"] .'</a><br /><br /></div>';
					
					
					if( $show_report ){
						$tv["data_capture_todo"] = $tv["view_todo"];
						$tv["data_capture_caption"] = $tv["view_title"];
					}
					
					$tact = 'action='. $tb .'&todo='. $tv["data_capture_todo"];
					if( $plugin ){
						$tact = 'action='. $plugin .'&todo='. $plugin_action .'&nwp_action='. $tb . '&nwp_todo='. $tv["data_capture_todo"];
					}

					$hall[] = '<a href="#" style="text-align:left; padding:10px;" class="custom-single-selected-record-button list-group-itemX '.$bgf.'" override-selected-record="' . $item["id"] . '" action="?module=&'. $tact .'&html_replacement_selector='. $container . $params . $pr2 .'" title="'. $tv["data_capture_title"] .'">' . $icon . $tv["data_capture_caption"] .'</a>';
				}
			}
			

			if( $view2 ){
				$h = '
				<div class="row">
					<div class="col-md-12">
						<div class="list-group">
							<div class="row">
								<div class="col-md-3">' . implode( '</div><div class="col-md-3">', $hall ) . '</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="'. $container .'"></div>
						<br /><br /><br /><br />
					</div>
				</div><br /><br />';

				$h .= ' <script>
					$( "a.sub-list-buttons" ).on( "click", function(){
						$("div#list-view-object a.accordion-toggle").each(function() {
						   if (!$(this).hasClass("collapsed")) {
						   	$(this).click();
						   }
						 });
					});
					</script>
				';

			}else{
				$h = '<div class="row"><div class="col-md-3"><div class="list-group">' . implode( '', $hall ) . '</div></div>';
				
				$h .= '<div class="col-md-9"><div id="'. $container .'"></div><br /><br /><br /><br /></div>';
				$h .= '</div><br /><br />';
			}
			echo $h;
		}else{
			echo '<div class="note note-warning"><h4><strong>Access Denied</strong></h4>This Procedure is not in the right status to perform this action</div>';
		}
		
	}else{
		echo '<div class="note note-warning"><h4><strong>Invalid Reference Item</strong></h4>Please contact your administrator</div>';
	}
}else{
	echo '<div class="note note-warning"><h4><strong>Invalid Button List</strong></h4>Please contact your administrator.<br>This could be due to the absence of the IVF_CONSENTS plugin</div>';
}
?>
</div>