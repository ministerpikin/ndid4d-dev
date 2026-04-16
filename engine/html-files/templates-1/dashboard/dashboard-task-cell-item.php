<div class="list-group">
<?php
	if( isset( $data[ 'pending_task' ] ) && is_array( $data[ 'pending_task' ] ) ){	//start if -1
		foreach( $data[ 'pending_task' ] as $key => $val ){	//start loop -2
		
			$pending_task = true;
			if( $val['status'] == 'complete' ){
				$pending_task = false;
			}
?>
<a href="<?php if( $pending_task )echo $val['trigger function']; else echo '#'; ?>" class="list-group-item" <?php if( $pending_task ){ ?>title="Click to <?php echo $val['title']; ?>" <?php } ?> >
	<i class="fa fa-comment fa-fw"></i> <?php echo $val['title']; ?>
	
	<div class="pull-right">
		<div class="progress progress-striped active">
			<div class="progress-bar <?php if( $pending_task )echo 'progress-bar-danger'; else echo 'progress-bar-success'; ?>" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
				<div class="pending-task"><?php echo $val['status']; ?></div>
			</div>
		</div>
	</div>
	
	<div class="text-muted small one-line"><?php echo strip_tags( $val['description'] ); ?></div>
	
	<div class="text-muted small"><em><?php echo time_passed_since_action_occurred( date("U") - $val['creation_date'] , 2 ); ?></em></div>
</a>
<?php
		}	//end loop -2
	} //end if -1
?>
</div>
<!-- /.list-group -->
<a href="#" class="btn btn-default btn-block">View All Tasks</a>