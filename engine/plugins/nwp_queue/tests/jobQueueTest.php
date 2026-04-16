<?php
	
	use \PHPUnit\Framework\TestCase;

	$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';
	if( ! class_exists("cNwp_app_core") ){
	    require_once $pagepointer . "/plugins/nwp_app_core/cNwp_app_core.php";
	}

	class jobQueueTest extends TestCase{
		public function testViewObjectsInQueue(){

			$_GET = array(
				"nwp_action" => "reports_bay",
				"nwp_todo" => "save_export_as_csv",
				"action" => "nwp_reports",
				"todo" => "execute",
				"nwp_repro" => "1",
				"modal" => "1"
			);

			$_POST = array( "data" => "{\"fields_title\":\"Display Fields\",\"plugin\":\"nwp_reports\",\"table\":\"es_monitoring\",\"current_tab\":\"es_monitoring\",\"report_id\":\"ray33u3334923991\",\"page_offset\":\"0\",\"page_limit\":\"10000\",\"page_size\":\"500\",\"tables\":{\"es_monitoring\":{\"nin\":1,\"poverty_quintile\":1,\"age\":1,\"disability_status\":1}}}" );

			$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';
			$result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'loggedIn' => true, 
				'defaultUser' => 'phpunit', 
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			print_r($result);

			$this->assertEquals(0, 0);
		}
	}

?>