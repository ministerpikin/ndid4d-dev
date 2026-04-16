<?php $budget = ""; if( isset( $data['id'] ) && $data['id'] )$budget = $data['id']; ?>
<?php $stage = "-"; if( isset( $data[ 'stage' ] ) && $data[ 'stage' ] )$stage = $data[ 'stage' ]; ?>
<div id="form-container">
<?php
	if( isset( $data["form"] ) && $data["form"] )echo $data["form"];
	
	$no_all_delete = 0;
	if( isset( $data[ 'no_delete_all' ] ) )
		$no_all_delete = $data[ 'no_delete_all' ];
		
?>
</div>
<hr />

<a href="#" function-id="1" function-class="change_of_name" function-name="add_view_change_of_name" module="" title="Click Here to Add a New Record" class="btn green btn-sm custom-action-button" month-id="<?php echo $budget; ?>" budget-id="<?php echo $stage; ?>" ><i class="icon-plus"></i> Add New Record</a>

		<div class="form-control-table">
		<br />
		
		<?php
			include "form-control-view-table.php";
		?>
		</div>
		