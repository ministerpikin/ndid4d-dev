<?php
/**
 * Main Plugin Class
 * @created  Hyella Nathan | 18:58 | 22-Aug-2023
 */
class cNwp_device_management extends cPlugin_class{
	
	public $class_settings = array();
	
	private $current_record_id = '';
	
	public $table_name = 'nwp_device_management';
	
	public $label = 'Device Management';
	public $dir_path = __FILE__;

	public $refresh_cache_after_save_line_items = 1;

	protected function __after_construct(){
		if( isset(cNwp_app_core::$def_cs['package_config']['exclude_plugin_classes'][ $this->table_name ]) && cNwp_app_core::$def_cs['package_config']['exclude_plugin_classes'][ $this->table_name ] && is_array( cNwp_app_core::$def_cs['package_config']['exclude_plugin_classes'][ $this->table_name ] ) ){
			foreach (array_keys( cNwp_app_core::$def_cs['package_config']['exclude_plugin_classes'][ $this->table_name ] ) as $table ) {
				if( isset($this->table_classes[ $table ]) && $this->table_classes[ $table ] ){
					unset( $this->table_classes[ $table ] );
				}
			}
		}
	}

	
	function nwp_device_management(){
		
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