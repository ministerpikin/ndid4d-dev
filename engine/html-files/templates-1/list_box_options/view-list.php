<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php
if( isset( $data["items"] ) && is_array( $data["items"] ) && !empty( $data["items"] ) ){
	
	$fields = isset( $data['fields'] )?$data['fields']:array();
	$labels = isset( $data['labels'] )?$data['labels']:array();
	$table = isset( $data["table"] )?$data["table"]:"";
	
	$GLOBALS["fields"] = $fields;
	$GLOBALS["labels"] = $labels;
	
	$preview = 0;
	
	$params = '';
	if( isset( $data[ "html_replacement_selector" ] ) && $data[ "html_replacement_selector" ] ){
		$params = '&html_replacement_selector=' . $data[ "html_replacement_selector" ];
	}
	
	foreach( $data["items"] as $item ){
		?>
		
	<div class="row">			
	<div class="col-md-12">
	<div class="report-table-preview-20">
		
		<table class="table table-bordered table-hover" cellspacing="0">
			
			<tbody>
				<?php 
					$key = "key";
					$value = isset( $item[ $key ] )?$item[ $key ]:'';
				?>
				<tr>
					<td class="col-md-4"><strong><?php echo __get_value('', $key, array( 'get_label' => 1 ) ); ?></strong></td>
					<td><?php echo __get_value( $value , $key ); ?></td>
				</tr>
				
				<?php 
					$key = "name";
					$value = isset( $item[ $key ] )?$item[ $key ]:'';
				?>
				<tr>
					<td class="col-md-4"><strong><?php echo __get_value('', $key, array( 'get_label' => 1 ) ); ?></strong></td>
					<td><?php echo __get_value( $value , $key ); ?></td>
				</tr>
				
				<?php 
					$key = "data";
					$value = json_decode( isset( $item[ $key ] )?$item[ $key ]:'', true );
				?>
				<tr>
					<td colspan="2"><pre><?php print_r( $value ); ?></pre></td>
				</tr>
				
			</tbody>
				
		</table>
			
		</div>
		</div>
		</div>
		<?php
	}
	
}
?>
</div>