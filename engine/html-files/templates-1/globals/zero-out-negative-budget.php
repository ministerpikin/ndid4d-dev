<!--EXCEL IMPOT FORM-->
<?php
	$modal_id = "zero-out-negative-budget";
	$modal_title = "Zero Out Line Items with <strong>NEGATIVE BUDGET BALANCE</strong> &darr;";
	if( isset( $data["html_title"] ) )$modal_title = $data["html_title"];
	$modal_body = "";
	
	if( isset( $data["html"] ) ){
		if( isset( $data['selected_line_items'] ) && is_array( $data['selected_line_items'] ) ){
			$modal_body = "Are you sure you want to zero out this line items with <strong>NEGATIVE BUDGET BALANCE</strong><hr style='margin:2px;'/><div style='max-height:300px; overflow-y:auto;'><table class='table table-striped' id='zero-out-table'><tbody>";
			foreach( $data['selected_line_items'] as $sval ){
				$modal_body .= "<tr>".$sval."</tr>";
			}
			$modal_body .= "</tbody></table></div>";
		}
		
		
		$modal_body = $data["html"] . $modal_body;
	}
	$modal_finish_caption = "Close Me";
	$modal_finish_caption = defined("HYELLA_MODAL_CLOSE_CAPTION") && HYELLA_MODAL_CLOSE_CAPTION ? HYELLA_MODAL_CLOSE_CAPTION : $modal_finish_caption;
	include dirname( __FILE__ ) . "/modal-box-1.php"; 
?>