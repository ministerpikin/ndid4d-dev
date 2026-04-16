<?php
/**
 * Main Plugin Class
 * @created  @timeofcreation@
 */
class cDemo_class extends cPlugin_class{
	
	public $class_settings = array();
	
	private $current_record_id = '';
	
	public $table_name = 'demo_class';
	
	public $label = 'Demo Class';
	public $dir_path = __FILE__;
	
	function demo_class(){
		
		$returned_value = array();
		
		$this->class_settings['current_module'] = '';
		
		switch ( $this->class_settings['action_to_perform'] ){
		case "display_plugin_details":
		case "get_plugin_details":
			$returned_value = $this->_get_plugin_details();
		break;
		case "execute":
			$returned_value = $this->_execute();
		break;
		}
		
		return $returned_value;
	}
	
}
if( file_exists( dirname( __FILE__ ) . '/functions.php' ) )include dirname( __FILE__ ) . '/functions.php';
?>