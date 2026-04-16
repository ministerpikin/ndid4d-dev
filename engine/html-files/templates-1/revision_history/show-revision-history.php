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

	if( isset( $data[ 'current_record' ] ) && $data[ 'current_record' ] ) {
		$c = count( $data[ 'data' ] );
		$data[ 'data' ][ $c ][ 'data' ] = json_encode( $data[ 'current_record' ] );
		$data[ 'data' ][ $c ][ 'current' ] = 1;
	}

	$fields = isset( $data[ 'other_params' ]['fields'] )?$data[ 'other_params' ]['fields']:array();
	$labels = isset( $data[ 'other_params' ]['labels'] )?$data[ 'other_params' ]['labels']:array();
	$data = isset( $data["data"] )?$data["data"]:"";
	
	$GLOBALS["fields"] = $fields;
	$GLOBALS["labels"] = $labels;

	$transformed = array();

	$c = count( $data );

	for( $i = 0; $i < $c; $i++ ){

		$data[ $i ][ 'data' ] = json_decode( $data[ $i ][ 'data' ], true );

		$key =  'v' . ( $i + 1 );
		$transformed[ 'table_head' ][ $key ] =  ( isset( $data[ $i ][ 'current' ] ) && $data[ $i ][ 'current' ] ) ? 'Current Version' : 'Version ' . ( $i + 1 );
		
		if( is_array( $data[ $i ][ 'data' ] ) && ! empty( $data[ $i ][ 'data' ] ) ){
			foreach( $data[ $i ][ 'data' ] as $k => $v ){
				$tmp = $data;
				unset( $tmp[ $i ] );
				foreach( $tmp as $tk => $tv ){
					$next = $tv[ 'data' ];
					if( ! is_array( $tv[ 'data' ] ) ){
						$next = json_decode( $tv[ 'data' ], true );
					}
					if( is_array( $next ) && ! empty( $next ) ){
						foreach( $next as $sk => $sv ){
							if( $sk == $k && ( $sv != $v || $sk == 'modified_by' ) ){
								$transformed[ 'table_body' ][ $sk ][ $key ] = $v;
							}
						}
					}
				}
			}
		}
	}
// echo "<pre>";print_r( $transformed ); echo "</pre>";

	$html .= '<div class="report-table-preview-20">	';
	$html .= '<div class="table-responsive">	';
	$html .= '<table class="table table-striped table-hover bordered" cellspacing="0">';
	$html .= '<thead><tr>';
		$x = 0;
	foreach( $transformed[ 'table_head' ] as $key => $value ){
		if( ! $x )$html .= '<th>&nbsp;</th>';
		$x = 1;
		$html .= '<th>'. $value .'</th>';
		
	}

	$html .= '</tr></thead><tbody>';
	$skip_all = 0;
	if( isset( $transformed[ 'table_body' ] ) && ! empty( $transformed[ 'table_body' ] ) ){
		foreach( $transformed[ 'table_body' ] as $key => $value ){
			
			switch( $key ){
			case "data":
				continue 2;
			break;
			}
			
			$html .= '<tr>';
				$lbl = __get_value( '', $key, array( "get_label" => 1 ) );
				$lbl = $lbl ? $lbl : ucwords( $key );
				$html .= '<td>' . $lbl .'</td>';
				foreach( $transformed[ 'table_head' ] as $k => $v ){
					if( isset( $value[ $k ] ) ){

						switch( $key ){
						case 'created_by':
						case 'modified_by':
							$vl = get_name_of_referenced_record( array( 'id' => $value[ $k ], 'table' => 'users' ) );
						break;
						default:
							$vl = __get_value( $value[ $k ], $key );
						break;
						}

						// echo "<pre>";strpos( $key, "_date" ); echo "</pre>";
						switch( strpos( $key, "_date" ) ){
						case -1:
						case '':
						case null:
						break;
						default:
							$vl = date( 'd-M-Y H:s', intval( $value[ $k ] ) );
						break;
						}
						$html .= '<td>'. $vl .'</td>';
					}else{
						$html .= '<td><i>NULL</i></td>';
					}
				}
				$skip_all = 0;
			$html .= '</tr>';
		}
	}else{
		$html .= '<div class="note note-warning">No Revision History Found</div>';
	}

	$html .= '</tbody>';
	$html .= '</table>';
	$html .= '</div>';
	$html .= '</div>';

	echo $html; 

}
?>
</div>

