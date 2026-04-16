<?php 
	if( isset( $data["query"] ) && is_array( $data["query"] ) ){
		?>
		<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
		<?php if( isset( $data["query"]["previous_id"] ) && $data["query"]["previous_id"] && $data["query"]["previous_id"] < $previous_id ){ ?>
		<form class="activate-ajax" method="post" action="?action=<?php echo $data["table"]; ?>&todo=search_pagination_previous">
			<input type="hidden" name="id" value="1"  />
			<textarea style="display:none;" type="hidden" name="mod" ><?php echo json_encode( $data["query"] ); ?></textarea>
			<input type="submit" class="btn btn-sm green pull-left" value="Go to First Page" />
		</form>
		<?php } ?>
		
		<?php if( $last_id ){ ?>
		<form class="activate-ajax" method="post" action="?action=<?php echo $data["table"]; ?>&todo=search_pagination_next">
			<input type="hidden" name="id" value="<?php echo $last_id; ?>"  />
			<?php 
				$data["query"]["previous_id"] = $previous_id;
			?>
			<textarea style="display:none;" type="hidden" name="mod" ><?php echo json_encode( $data["query"] ); ?></textarea>
			<input type="submit" class="btn btn-sm green pull-left" value="Next Page >>" />
		</form>
		<?php } ?>
		<br /><br /><br />
		</div>
		<?php
	}
?>