<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<style type="text/css"><?php /* if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; */ ?></style>
	<?php 
		//echo '<pre>';print_r( $data ); echo '</pre>'; 
		$title = isset( $data["title"] )?$data["title"]:'';
		$name = isset( $data["name"] )?$data["name"]:'';
		
		if( $title ){
			echo '<h2>'. $title . ' <small>['.$name.']</small></h2><br>';
		}
		$t1='';
		$t2='';
		
		if( isset( $data['tech_info'] ) && is_array( $data['tech_info'] ) && ! empty( $data['tech_info'] ) ){
		
			foreach( $data['tech_info'] as $key => $val ){
				switch( $key ){
				case "database connection":
					$body_content = '';
					
					foreach($val as $v => $k){
						$body_content .= '<tr><td>'. ucwords($v) .' : </td><td>'. ucwords($k) .'</td></tr>';
					}
					
					$t1 .= '<h3>'. ucwords( $key ) .'</h3><table class="table table-striped ">'. $body_content .'</table><br />';
				break;
				case "classes":
					$body_content = '';
					
					foreach($val as $v => $k ){
						$body_content .= '<li>'. $k["table_label"] .'</li>';
					}
					$t2 .= '<h3>'. ucwords( $key ) .'</h3><ol>'. $body_content .'</ol><br />';
				break;
				}
				
			}
		}
		
		// if( isset( $data["tech_info"] ) && is_array( $data["tech_info"] ) && ! empty( $data["tech_info"] ) ){
		// 	foreach( $data["tech_info"] as $tk => $tv ){
		// 		echo '<h3>'. ucwords( $tk ) .'</h3>';
		// 		echo '<pre style="white-space:pre-wrap; word-break: break-all; word-wrap: break-word; background-color: #f5f5f5; border: 1px solid #cccccc; padding:10px 20px;">';
		// 			print_r( $tv );
		// 		echo '</pre>'; 
		// 	}
		// }
	?>
	   <div class="tabbable tabbable-custom boxless">
	      <ul class="nav nav-tabs">
	      	<li class="active"><a href="#tab_1" data-toggle="tab">Plugin Details</a></li>
	      	<li><a href="#tab_3" data-toggle="tab" class="custom-single-selected-record-button one-time-request" override-selected-record="<?php echo $name; ?>" action="?action=audit&todo=display_plugin_erd&html_replacement_selector=erd_display">ERD</a></li>
	      </ul>
	      <div class="tab-content">
	         <div class="tab-pane active" id="tab_1">
				<div class="row">
					<div class="col-md-5 col-md-offset-1">
						<?php echo $t1; ?>
					</div>
					<div class="col-md-5">
						<?php echo $t2; ?>
					</div>
				</div>
			 </div>
	         <div class="tab-pane" id="tab_3">
	         	<div class="margin-top-10">
	           		<div id="erd_display" class=""></div>
	           </div>
	         </div>
	      </div>
	   </div>
	</div>
</div>