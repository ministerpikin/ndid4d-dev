<!--EXCEL IMPOT FORM-->
<?php
	$modal_id = "move-line-items-department";
	$modal_title = "Select the Department to Move the Line Items To &darr;";
	
	if( isset( $data["html"] ) ){
		if( isset( $data['selected_line_items'] ) && is_array( $data['selected_line_items'] ) ){
			$modal_body = "Selected Line Items<hr style='margin:2px;'/><div style='max-height:300px; overflow-y:auto;'><ol>";
			foreach( $data['selected_line_items'] as $sval ){
				$modal_body .= "<li>".$sval."</li>";
			}
			$modal_body .= "</ol></div>";
		}
		
		
		$modal_body = $data["html"] . $modal_body;
	}
	$modal_finish_caption = "Close Me";
	include dirname( __FILE__ ) . "/modal-box.php"; 
?>