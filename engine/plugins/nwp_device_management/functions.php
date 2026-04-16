<?php  

function get_device_online_status(){
	return [
		'online' => 'Online',
		'offline' => 'Offline',
	];
}

function get_device_otype(){
	return [
		'migrated' => 'Migrated',
		'created' => 'Created',
		'self-onboarded' => 'Self-Onboarded',
	];
}

function get_device_onboard_notification_option(){
	return [
		'all' => 'All',
		'per_fep' => 'Per FEP',
	];
}

?>