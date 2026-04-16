<div id="container-<?php echo $dass["id"]; ?>">
	<?php
	
	if( isset( $data[ "delete_prompt" ] ) && $data[ "delete_prompt" ] ){
		?>
		<div class="note note-warning">
			<h4>Confirm Delete Operation</h4>
			<p>Are you sure that you want to delete the below record</p><br />
			<a href="#" class="custom-single-selected-record-button btn blue" override-selected-record="<?php echo $dass["id"]; ?>" action="?module=&action=<?php echo $table; ?>&todo=delete_from_popup<?php echo $params; ?>" mod="delete-<?php echo md5( $table ); ?>" title="Yes, Delete Record">Yes, Delete Record</a>
		</div>
		<hr />
		<?php
	}
	
	if( isset( $data[ "validate_override" ] ) && $data[ "validate_override" ] ){
		$data[ "validate_prompt" ] = 1;
		$data[ "validate_time" ] = 0;
	}
	
	if( isset( $data[ "validate_header" ] ) && $data[ "validate_header" ] ){ 
		echo $data[ "validate_header" ]; 
	}
	
	if( isset( $data[ "validate_prompt" ] ) && $data[ "validate_prompt" ] ){
		
		$show_delete_validate = 1;
		if( isset( $data[ "validate_prompt_hide_delete" ] ) && $data[ "validate_prompt_hide_delete" ] ){
			$show_delete_validate = 0;
		}
		
		$show_edit_validate = 1;
		if( isset( $data[ "validate_prompt_hide_edit" ] ) && $data[ "validate_prompt_hide_edit" ] ){
			$show_edit_validate = 0;
		}
		
		$data[ "view" ] = 1;
		?>
		<div class="note note-warning" id="validate-<?php echo $dass["id"]; ?>">
			<?php 
				if( isset( $data[ "validate_text" ] ) ){ 
					echo $data[ "validate_text" ]; 
				}else{ 
			?>
			<h4>Verify Your Input?</h4>
			<p>Please confirm & verify the entries you have made</p><br />
			<?php } ?>
			
			<?php 
				if( isset( $data[ "validate_before_html" ] ) ){ 
					echo $data[ "validate_before_html" ]; 
				}
			?>
			
			<p class="segmented segmented-raised">
			<?php if( $show_edit_validate ){ ?>
			<button class="btn btn-default button custom-single-selected-record-button" action="?<?php echo $r_action .'='. $table . '&' . $r_todo .'=edit_popup_form_in_popup' . $params; ?>" mod="edit-<?php echo md5( $table ); ?>" override-selected-record="<?php echo isset( $dass[ "id" ] )?$dass[ "id" ]:'-'; ?>">Modify Entry</button>
			<?php } ?>
			
			<?php if( $show_delete_validate ){ ?>
			<a href="#" class="custom-single-selected-record-button button btn btn-default" override-selected-record="<?php echo $dass["id"]; ?>" action="?<?php echo $r_action .'='. $table . '&' . $r_todo .'=delete_from_popup2' . $params; ?>" mod="delete-<?php echo md5( $table ); ?>" title="Yes, Delete Record">Delete Entry</a>
			<?php } ?>
			</p>
			
			<?php 
				if( isset( $data[ "validate_after_html" ] ) ){ 
					echo $data[ "validate_after_html" ]; 
				}
			?>
			
			<?php 
				if( isset( $data[ "validate_time" ] ) && $data[ "validate_time" ] ){
					$count_down = $data[ "validate_time" ];
			?>
			<br /><br /><p style="color:#dd0000; font-size:16px">This window will automatically close in <strong id="count-down-<?php echo $dass["id"]; ?>" class="count-down-time" data-time="<?php echo $count_down; ?>" data-time-count="<?php echo $count_down / 1000; ?>" data-container="#container-<?php echo $dass["id"]; ?>"></strong></p>
			<script type="text/javascript">
				
				var count_down = <?php echo $count_down; ?>;
				
				if( count_down_id_<?php echo $dass["id"]; ?> ){
					clearTimeout( count_down_id_<?php echo $dass["id"]; ?> );
				}
				var count_down_id_<?php echo $dass["id"]; ?> = setTimeout( function(){ 
					if( $( "#container-<?php echo $dass["id"]; ?>" ).find( "#validate-<?php echo $dass["id"]; ?>" ) ){
						$( "#container-<?php echo $dass["id"]; ?>" ).remove();
					}
				}, count_down );
				
				function timer_<?php echo $dass["id"]; ?>(){
					
					if( $("#container-<?php echo $dass["id"]; ?>" ).find( "#validate-<?php echo $dass["id"]; ?>" ) ){
						var count_down_<?php echo $dass["id"]; ?> = $("#count-down-<?php echo $dass["id"]; ?>").attr("data-time-count");
						--count_down_<?php echo $dass["id"]; ?>;
						$("#count-down-<?php echo $dass["id"]; ?>").html( count_down_<?php echo $dass["id"]; ?> + " seconds" );
						if( count_down_<?php echo $dass["id"]; ?> > 0 ){
							$("#count-down-<?php echo $dass["id"]; ?>").attr("data-time-count", count_down_<?php echo $dass["id"]; ?> );
							setTimeout( function(){ timer_<?php echo $dass["id"]; ?>(); } , 1000 );
						}
					}
				};
				timer_<?php echo $dass["id"]; ?>();
			</script>
			<?php } ?>
		</div>
		<hr />
		<?php
	}
	
	if( isset( $data[ "validate_footer" ] ) && $data[ "validate_footer" ] ){ 
		echo $data[ "validate_footer" ]; 
	}
?>