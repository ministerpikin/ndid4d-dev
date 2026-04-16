<?php

	function get_job_queue_status(){
		return array(
			'in_queue' => 'In Queue',
			'running' => 'Running',
			'stopped' => 'Stopped',
			'complete' => 'Complete',
			'cancelled' => 'Cancelled'
		);
	}
?>