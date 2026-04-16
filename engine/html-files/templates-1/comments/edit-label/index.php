<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<br />
<?php
$label_data = "''";
$comment_id = "''";
if( isset( $data ) && $data ){
	// print_r( $data );exit;
	$label_data = isset( $data[ 'labels' ] ) ? $data[ 'labels' ] : '';
	$comment_id = isset( $data[ 'comment_id' ] ) ? $data[ 'comment_id' ] : '';
}
?>
<div class="row">
	<div class="col-md-12">

		<div class="row">
			<div class="col-md-offset-3 col-md-6">
				<form id="labels" class="client-form">
					<label>Labels</label>
					<div style="display:grid;">
						<input class="form-control select2" type="text" placeholder="" action="?module=&action=labels&todo=get_select2" name="assigned_to" required/>

						<input class="btn btn-sm dark " value="Add" type="submit" />
					</div>
				</form>	
				
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<br />
				<div class="shopping-cart-table">
					<div class="table-responsive">
						<table class="table table-striped table-hover bordered">
							<thead>
							   <tr>
								  <th>Serial No.</th>
								  <th>Name</th>
								  <th>Description</th>
								  <th>Color</th>
								  <th>Type</th>
								  <th class="r"></th>
							   </tr>
							</thead>
							<tbody id="display-labels">
							   
							</tbody>
							<tfoot>
							   
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<form action="?action=comments&todo=update_comment_label" id="submit" class="activate-ajax">
					<textarea value="" class="form-control" type="hidden" name="labels"></textarea>
					<input value="<?php echo $comment_id; ?>" class="form-control" type="hidden" name="id" />

					<input class="btn btn-lg blue" value="Save" type="submit" />
				</form>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
	var lbl = <?php echo json_encode( $label_data ); ?>;
	var coment = "<?php echo $comment_id; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>