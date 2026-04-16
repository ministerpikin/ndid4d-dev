<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	$field = isset( $data[ 'field' ] ) ? $data[ 'field' ] : '';
	$plugin = isset( $data[ 'plugin' ] ) ? $data[ 'plugin' ] : '';
	$tb = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';
	$field_name = isset( $data[ 'field_name' ] ) ? $data[ 'field_name' ] : $field;
	$field_values = isset( $data[ 'field_values' ] ) ? $data[ 'field_values' ] : array();
	 //echo '<pre>';print_r( $data );echo '</pre>'; 
	// echo '<pre>';print_r( $data[ 'data' ] );echo '</pre>'; 

	$datasc = 'type=base:::value='.$tb;
	if( $plugin )$datasc = 'type=plugin:::key='. $plugin .':::value='.$tb;
	
	$bprms = '&field='. $field .'&data_source='.$datasc.'&s_option=datatable';

	if( isset( $data[ 'data' ] ) && $data[ 'data' ] ){ ?>

		<div id="duplicte-vl-container" class="report-table-preview-20">
			<table class="table table-striped table-bordered" cellspacing="0" >
				<thead>
				<tr style="background: #555; color: #fff;">
					<th>S/N</th>
					<th>Count</th>
					<th><?php echo $field_name; ?></th>
				</tr>
				</thead>
				
				<tbody id="codmbo-tab-display">
					<?php  
					foreach( $data[ 'data' ] as $dk => $dd ){
						$vl = '';
						if( isset( $dd[ 'label' ] ) && $dd[ 'label' ] ){
							$vl = $dd[ 'label' ];
						}elseif( isset( $dd[ $field ] ) && isset( $field_values[ $dd[ $field ] ] ) && $field_values[ $dd[ $field ] ] ){
							$vl = $field_values[ $dd[ $field ] ];
						}else if( isset( $dd[ $field ] ) && $dd[ $field ] ){
							$vl = $dd[ $field ];
						}else{
							$vl = '&lt;empty&gt;';
						}

						echo '<tr>';
						echo '<td>'. ( $dk+1 ) .'</td>';
						echo '<td>'. ( number_format( $dd[ 'count' ] ) ) .'</td>';
						echo '<td><a href="#"  class="custom-single-selected-record-button" action="?action=audit&todo=save_display_data_access_view2&html_replacement_selector=duplicte-vl-container&expandable_details=1&tx_details=1'. $bprms .'&menu_title='. rawurlencode( $vl ) .'" override-selected-record="'. ( isset( $dd[ $field ] ) ? $dd[ $field ] : '-' ) .'" target="_blank" new_tab="1" mod="1" title="Bulk Operations">'. $vl .'</a></td>';
						echo '</tr>';
					}
					?>
				</tbody>
				
			</table>
		</div>
		
	<?php }else{
		echo '<div class="note note-warning"><h4><strong>Empty Data</strong></h4>No result was found</div>';
	}
?>
<script type="text/javascript" >
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>