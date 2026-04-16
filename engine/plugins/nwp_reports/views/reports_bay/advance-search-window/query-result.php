<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
// echo '<pre>'; print_r( $data ); echo '</pre>';
if( isset( $data['data_query']['data'][ 'stats_data' ] ) &&  isset( $data[ 'stats' ][0] ) && is_array( $data['data_query']['data'][ 'stats_data' ] ) && ! empty( $data['data_query']['data'][ 'stats_data' ] ) ){
	$key = isset( $data[ 'mine_key' ] )?$data[ 'mine_key' ]:'';
	$data_source = isset( $data[ 'data_source' ] )?$data[ 'data_source' ]:'';
	$mine_source = isset( $data[ 'mine_source' ] )?$data[ 'mine_source' ]:'';
	$plugin = isset( $data[ 'plugin' ] )?$data[ 'plugin' ]:'';
?>
	<br />
	<div class="row">
		<div class="col-md-12">
			<div class="report-table-preview-20">
				<table class="table table-bordered" cellspacing="0">
					<tbody>
						<tr>
							<td colspan="3" class="title" style="background-color: #53729e !important;">Query Result Statistics</td>
							<?php 
							
							foreach( $data['data_query']['data'][ 'stats_data' ] as $k => $title ){
								if( isset( $data[ 'stats' ][0][ $k ] ) ){
									$value = $data[ 'stats' ][0][ $k ];
									?>
									<td><a role="menuitem" tabindex="-1" title="Click to View <?php echo $value; ?>  Records" href="#" class="custom-single-selected-record-button" override-selected-record="<?php echo $k; ?>" action="?action=search&todo=get_search_query&html_replacement_selector=view-records&key=<?php echo $key; ?>&data_source=<?php echo $data_source; ?>&mine_source=<?php echo $mine_source . ( $plugin ? '&plugin=' . $plugin : '' ); ?>"><?php echo ucwords( $title ); ?> (<?php echo number_format( $value ); ?>)</a></td>
									</td>
									<?php	
								}
							}
							?>
						</tr>

					</tbody>
						
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12" id="view-records">
		</div>
	</div>
	<?php
}else{
?>
<div class="note note-warning"><h4><strong>No Result Found</strong></h4>Check your filter criteria</div>
<?php
}
?>
</div>