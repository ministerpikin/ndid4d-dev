<div class="list-group" id="dashboard-notification-container">
	<?php
		if( isset( $data[ 'notification' ] ) && is_array( $data[ 'notification' ] ) ){	//start if -1
			foreach( $data[ 'notification' ] as $key => $val ){	//start loop -2
	?>
	<a href="#" class="list-group-item toggle-details notification-<?php echo $val['serial_num']; ?> notification-<?php echo $val['id']; ?>" <?php if( $val['status']=='unread' ){ ?>ajax-request="true"<?php } ?> data-action="notifications" data-todo="mark_as_read" data-record-id="<?php echo $val['serial_num']; ?>">
		<?php
			$title = $val['title'];
			if( $val['status']=='unread' ){
				$title = '<strong class="unread-title">' . $val['title'] . '</strong>';
			}
		?>
		<i class="fa fa-comment fa-fw"></i> <?php echo $title; ?>
		<span class="pull-right text-muted small"><em><?php echo time_passed_since_action_occurred( date("U") - $val['creation_date'] , 2 ); ?></em>
		</span>
	</a>
	<div class="details hide notification-<?php echo $val['serial_num']; ?> notification-<?php echo $val['id']; ?>">
		<?php echo $val['detailed message']; ?>
		<br /><br />
		<button type="button" class="btn btn-mini btn-xs btn-outline btn-info ajax-request" ajax-request="true" data-action="notifications" data-todo="delete_notification" data-record-id="<?php echo $val['id']; ?>"><i class="fa fa-times fa-fw"></i> delete</button>
	</div>
	
	<?php
			}	//end loop -2
		} //end if -1
	?>
</div>
<!-- /.list-group -->
<a href="#" class="btn btn-default btn-block">View All Alerts</a>