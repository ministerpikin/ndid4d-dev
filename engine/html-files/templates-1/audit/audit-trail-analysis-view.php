<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$index = array();
	if( isset( $data["index"] ) && $data["index"] ){
		$index = $data["index"];
		unset( $data["index"] );
	}
	//print_r($index);
	$format = '';
	if( isset( $data["format"] ) && $data["format"] ){
		$format = $data["format"];
	}
	
	$count = 0;
	if( isset( $data["count"] ) && $data["count"] ){
		$count = $data["count"];
	}
	
	$title = '';
	if( isset( $data["title"] ) && $data["title"] ){
		$title = $data["title"];
	}
	
	$report_type = '';
	if( isset( $data["report_type"] ) && $data["report_type"] ){
		$report_type = $data["report_type"];
	}
	
	$hasTable = 0;
	$html1 = '';
	if( file_exists( dirname( __FILE__ ).'/audit-trail-analysis-view-'. $report_type .'.php' ) ){
		include 'audit-trail-analysis-view-'. $report_type .'.php'; 
	}
	
	$container = 'report-preview-container-id';
	$container1 = 'quick-print-container';
	
	echo '<div id="'. $container .'">';
	if( $hasTable ){
		$opts = array( "saveX" => 1, "shareX" => 1, "copyX" => 1, "subject" => $title );
	
		$opts["dcsv"] = 1;
		echo get_export_and_print_popup( ".table" , "#".$container1, "", 1, $opts ) . "</div>";
	}
	
	
	echo '<div id="'.$container1.'">';
		echo '<h3>' . $title . '</h3>';
		echo '<div class=" report-table-preview-20" ><div class="table-responsive">' . $html1 . '</div></div>';
	echo '</div>';
	
	echo '</div>';
?>
</div>