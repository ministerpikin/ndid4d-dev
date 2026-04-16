<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	.title{
		text-align:center;
		font-size:1.1em;
	}
</style>
<?php
$html = '';
// echo "<pre>";print_r( $data ); echo "</pre>";
if( isset( $data[ 'data' ] ) && ! empty( $data[ 'data' ] ) ){

	$fields = isset( $data[ 'other_params' ]['fields'] )?$data[ 'other_params' ]['fields']:array();
	$labels = isset( $data[ 'other_params' ]['labels'] )?$data[ 'other_params' ]['labels']:array();
	$id = isset( $data[ 'other_params' ]['id'] )?$data[ 'other_params' ]['id']:'';
	$table = isset( $data[ 'other_params' ]['table'] )?$data[ 'other_params' ]['table']:'';
	$data = isset( $data["data"] )?$data["data"]:"";
	
	$GLOBALS["fields"] = $fields;
	$GLOBALS["labels"] = $labels;

	$html .= '<a href="#" class="btn btn-default custom-single-selected-record-button" override-selected-record="'. $id .'" action="?module=&action=revision_history&todo=show_revision_history&table='. $table .'"  title="View Full Details of Revision History">Open</a><br /><br />';

	$html .= '<div class="report-table-preview-20">	';
	$html .= '<div class="table-responsive">';
	$html .= '<table class="table table-striped table-hover bordered" cellspacing="0">';
	$html .= '<thead><tr>';
		$html .= '<th>S/N</th>';
		$html .= '<th>Revised By</th>';
		$html .= '<th>Date</th>';
	$html .= '</tr></thead><tbody>';
	$serial = 0;
	foreach( $data as $value ){
		$html .= '<tr>';
			$html .= '<td>'. ++$serial .'</td>';
			$html .= '<td>'. __get_value( $value[ 'created_by' ], 'created_by' ) .'</td>';
			$html .= '<td>'. __get_value( $value[ 'creation_date' ], 'creation_date' ) .'</td>';
		$html .= '</tr>';
		
	}
	$html .= '</tbody>';
	$html .= '</table>';
	$html .= '</div>';
	$html .= '</div>';

	echo $html; 

}
?>
</div>

