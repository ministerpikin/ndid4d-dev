<?php

	function get_audit_trail_tables(){
		return array(
			'plugin:::audit_trail' => 'Audit Trail',
			'class:::logged_in_users' => 'Logged In Users',
			'class:::revision_history' => 'Revision History',
			'plugin:::endpoint' => 'Api Endpoint'
		);
	}
?>