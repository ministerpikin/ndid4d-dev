<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>

	<?php 
		//print_r($data);
		if( isset( $data["status_text"] ) && is_array( $data["status_text"] ) && ! empty( $data["status_text"] ) ){
			krsort( $data["status_text"] );
			$class = 'note-info alert-info';
			$title = 'Progress Update';
			
			if( isset( $data["status"] ) && $data["status"] ){
				$class = 'note-success alert-success';
				$title = 'Process Completed Successfully';
				
				if( $data["status"] == 2 ){
					$class = 'note-danger alert-danger';
					$title = 'Process Failed';
				}
			}
			
			?>
			<div class="note alert <?php echo $class; ?>">
			<h3><?php echo $title; ?></h3>
			<?php 
				if( isset( $data["link"] ) )echo $data["link"];
			?>
			<ol style="max-height:400px; overflow-y:auto;">
			<?php
			$html = '';
			foreach( $data["status_text"] as $k => $v ){
				$html .= '<li>' . $v . '<br /><br /></li>';
			}
			echo $html;
			?>
			<ol>
			</div>
			<?php
		}else{
	?>
		<div class="alert alert-danger">
			<h3>Cannot Retrieve Progress Update</h3>
			<p>Please try again</p>
		</div>
	<?php } ?>
</div>