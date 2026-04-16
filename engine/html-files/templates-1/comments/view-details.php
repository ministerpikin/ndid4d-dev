<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	.title{
		text-align:center;
		font-size:1.1em;
	}
</style>
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
	
	$action_to_perform = '';
	if( isset( $data["action_to_perform"] ) && $data["action_to_perform"] ){
		$action_to_perform = $data["action_to_perform"];
	}
	
	$start_survey = isset( $data["start_survey"] )?$data["start_survey"]:0;
	
	$todo = 'save_using_temporary_id';
	
	//$params = '&html_replacement_selector=modal-replacement-handle';
	foreach( $data["items"] as $item ){
		?>
		<div id="<?php $container = $table . '-window-'; echo $container; ?>">
		
	<div class="row">			
	<div class="col-md-12">
	<div class="report-table-preview-20">
		
		<table class="table table-bordered table-hover" cellspacing="0">
			
			<tbody>
				<?php 
					$key = "date";
					$value = isset( $item[ $key ] )?$item[ $key ]:'';
				?>
				<tr>
					<td class="col-md-4"><strong><?php echo __get_value('', $key, array( 'get_label' => 1 ) ); ?></strong></td>
					<td><?php echo __get_value( $value , $key ); ?></td>
				</tr>
				
				<?php 
					$key = "department";
					$value = isset( $item[ $key ] )?$item[ $key ]:'';
				?>
				<tr>
					<td class="col-md-4"><strong><?php echo __get_value('', $key, array( 'get_label' => 1 ) ); ?></strong></td>
					<td><?php echo __get_value( $value , $key ); ?></td>
				</tr>
				
				<?php 
					$key = "priority";
					$value = isset( $item[ $key ] )?$item[ $key ]:'';
				?>
				<tr>
					<td class="col-md-4"><strong><?php echo __get_value('', $key, array( 'get_label' => 1 ) ); ?></strong></td>
					<td><?php echo __get_value( $value , $key ); ?></td>
				</tr>
				
				<?php 
					$key = "problem";
					$value = isset( $item[ $key ] )?$item[ $key ]:'';
				?>
				<tr>
					<td class="col-md-4"><strong><?php echo __get_value('', $key, array( 'get_label' => 1 ) ); ?></strong></td>
					<td><?php echo __get_value( $value , $key ); ?></td>
				</tr>
				
				<?php 
					$key = "supporting_document";
					$value = isset( $item[ $key ] )?$item[ $key ]:'';
				?>
				<tr>
					<td class="col-md-4"><strong><?php echo __get_value('', $key, array( 'get_label' => 1 ) ); ?></strong></td>
					<td><?php echo __get_value( $value , $key ); ?></td>
				</tr>
				
			</tbody>
				
		</table>
		
	</div>	
	<div class="shopping-cart-table">	
		<?php
			$k1 = 'data';
			$html = '';
			
			if( isset( $item[ $k1 ] ) && $item[ $k1 ] ){ 
				$ijson = json_decode( $item[ $k1 ], true );
				
				if( isset( $ijson["form_data"] ) && is_array( $ijson["form_data"] ) && ! empty( $ijson["form_data"] ) ){
					
					$html .= '<h4><strong>Default Values</strong></h4><p><i><strong>NB:</strong> Default values will be automatically linked to data captured by the users assigned to this survey.</i></p>';
					
					$html .= '<table class="table table-striped table-bordered table-hover" cellspacing="0">';
					$html .= '<tbody>';
					
					foreach( $ijson["form_data"] as $sv ){
						
						$html .= '<tr>';
							$html .= '<td class="col-md-4"><strong>'. $sv["label"] .'</strong></td>';
							$html .= '<td>'. $sv["value_text"] .'</td>';
						$html .= '</tr>';
					}
					
					$html .= '</tbody>';
					$html .= '</table>';
				}
				
				echo $html;
				
			}
		?>
	</div>
	</div>
	</div>
	</div>
		<?php
	}
	
}
?>
</div>