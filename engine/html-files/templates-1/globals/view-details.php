<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
	// echo '<pre>'; print_r( $data[ 'addition_button_params' ] ); echo '</pre>';
	if( isset( $data[ "items" ] ) && is_array( $data[ "items" ] ) && isset( $data[ "fields" ] ) && is_array( $data[ "fields" ] ) && isset( $data[ "labels" ] ) && is_array( $data[ "labels" ] ) ){
		$pr = get_project_data();
		
		$plugin = isset( $data["plugin"] )?$data["plugin"]:"";
		$table = isset( $data["table"] )?$data["table"]:"customer_call_log";
		$reference = isset( $data[ "reference" ] )?$data[ "reference" ]:"customer";
		$mobile = isset( $more_data["mobile"] )?$more_data["mobile"]:"";
		
		$mobile_framework = $mobile;
		
		$GLOBALS["fields"] = $data[ "fields" ];
		$GLOBALS["labels"] = $data[ "labels" ];
		
	
		if( ! isset( $params ) ){
			$params = '';
			if( isset( $data[ "html_replacement_selector" ] ) && $data[ "html_replacement_selector" ] ){
				$params = '&html_replacement_selector=' . $data[ "html_replacement_selector" ];
			}
		}

		if( isset( $data[ 'addition_button_params' ] ) && $data[ 'addition_button_params' ] ){
			$params .= $data[ 'addition_button_params' ];
		}
		
		$r_action = 'action';
		$r_todo = 'todo';
		if( $plugin ){
			$r_action = 'nwp_action';
			$r_todo = 'nwp_todo';
			//29-mar-23
			$params .= '&todo='. ( isset( $data[ 'crud_todo' ] ) ? $data[ 'crud_todo' ] : 'execute' ) .'&action=' . $plugin;
		}
		
		$print_preview = 0;
		if( isset( $data[ "print_preview" ] ) && $data[ "print_preview" ] ){
			$print_preview = 1;
		}
		
		if( $print_preview ){
			
			$template_file = "invoice-header-small.php";
			$cs = "invoice-small";
			//$cs = "invoice";
			$sales_label = isset( $user_info["user_full_name"] )?( 'By: ' . $user_info["user_full_name"] ):'';
			$serial_number = date("d-M-Y H:i");
			
			?>
			<div id="invoice-container-wrapper" class="page-container <?php echo $cs; ?>">
			<div class="container <?php echo $cs; ?>" id="invoice-container" <?php //echo $print_style; ?>>

			<div class="invoice">

			<?php
				include "invoice-css.php";
				include $template_file;
		}
		
		foreach( $data[ "items" ] as $item => $item_data ){
			if( ! isset( $item_data["id"] ) )
				$dass = get_record_details( array( "id" => $item, "table" => $table ) );
			else
				$dass = $item_data;
		
			include "view-details-header.php";
			
			if( ! ( isset( $skip_body ) && $skip_body ) ){
				include "view-details-body.php";
			}
		}
		
		if( $print_preview ){
			?>
			<a class="btn btn-lg blue hidden-print" onclick="javascript:window.print();">Print <i class="icon-print"></i></a>
			</div>
			</div>
			</div>
			<?php
		}
		
		//if( ! ( isset( $g_no_unset_gfields_and_labels ) && $g_no_unset_gfields_and_labels ) ){
			unset( $GLOBALS["labels"] );
			unset( $GLOBALS["fields"] );
		//}
	} 
?>
</div>