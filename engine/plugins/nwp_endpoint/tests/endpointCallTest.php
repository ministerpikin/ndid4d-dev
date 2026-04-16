<?php
	use \PHPUnit\Framework\TestCase;


	$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';
	if( ! class_exists("cNwp_app_core") ){
	    require_once $pagepointer . "/plugins/nwp_app_core/cNwp_app_core.php";
	}

	class endpointCallTest extends TestCase{

		public function testEndpointCall(){
			$dataFilePath = dirname( __FILE__ ) . '/data/endpoint/';
        	$dataFile = $dataFilePath . 'clearEndpointCache.json';

  			$this->assertFileExists( $dataFile, 'DataFile was not found' );
  			$inputData = json_decode( file_get_contents( $dataFile ), 1 );

  			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
  			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

  			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

  			$_GET = $inputData["data"]["get"];

  			if( isset( $inputData["data"]["post"] ) ){
  			    $_POST = $inputData["data"]["post"];
  			}

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

  			$inputData['output'] = json_decode($inputData['output'], true);

  			$this->assertArrayHasKey("nwp_status", $result);
        	$this->assertEquals( "success", $result["nwp_status"]);
        	$this->assertArrayHasKey("inf", $result);

        	$dataFile = $dataFilePath . 'endpointCall.json';
        	$this->assertFileExists( $dataFile, 'DataFile was not found' );
  			$nInputData = json_decode( file_get_contents( $dataFile ), 1 );

  			$this->assertArrayHasKey( "data", $nInputData, 'Missing Data in DataFile' );
  			$this->assertArrayHasKey( "output", $nInputData, 'Missing Output in DataFile' );

  			$this->assertArrayHasKey( "get", $nInputData["data"], 'Missing HTTP GET Parameters' );

  			$_GET = $nInputData["data"]["get"];

  			if( isset( $nInputData["data"]["post"] ) ){
  			    $_POST = $nInputData["data"]["post"];
  			}

  			$result = json_decode( cNwp_app_core::app( [ 
  				'pagepointer' => $pagepointer, 
  				'loggedIn' => true, 
  				'defaultUser' => 'phpunit', 
  				'returnObject' => true, 
  				'openConnection' => true, 
  				'isTesting' => true, 
  				'setTestParams' => true 
  			] ), 1);

  			$this->assertArrayHasKey("data", $result);
  			$this->assertEquals("call_endpoint_bg", $result['data']['nwp_todo']);
  			$this->assertEquals($inputData["data"]["post"]["id"], $result["data"]["reference"]);
		}

		public function testEndpointCallBackground(){
			$dataFilePath = dirname( __FILE__ ) . '/data/endpoint/';
        	$dataFile = $dataFilePath . 'endpointCallBackgroundTask.json';

  			$this->assertFileExists( $dataFile, 'DataFile was not found' );
  			$inputData = json_decode( file_get_contents( $dataFile ), 1 );

  			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
  			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

  			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

  			$_GET = $inputData["data"]["get"];

  			if( isset( $inputData["data"]["post"] ) ){
  			    $_POST = $inputData["data"]["post"];
  			}

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

  			$this->assertArrayHasKey("nwp_status", $result);
        	$this->assertEquals( "success", $result["nwp_status"]);
        	$this->assertArrayHasKey("inf", $result);
        	$this->assertStringContainsString("Endpoint Call Successful", $result['inf']);
		}

		public function testEndpointCallReRerun(){
			$dataFilePath = dirname( __FILE__ ) . '/data/endpoint/';
			$dataFile = $dataFilePath . 'failedEndpointCall.json';
			$this->assertFileExists( $dataFile, 'DataFile was not found' );

			$inputData = json_decode( file_get_contents( $dataFile ), 1 );
			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

			$_GET = $inputData["data"]["get"];

			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			}

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

			$this->assertArrayHasKey("nwp_status", $result);
			$this->assertArrayHasKey("data", $result);
			$this->assertArrayHasKey("log", $result["data"]);
			$this->assertArrayHasKey("required", $result["data"]);

			$log_id = $result["data"]["log"];

			echo "=============== DEPENDENT CALL =====================\n";
			echo "1. ";
			$dataFile = $dataFilePath . 'rerunFailedEndpointLog.json';
			$this->assertFileExists( $dataFile, 'DataFile was not found' );

			$inputData = json_decode( file_get_contents( $dataFile ), 1 );
			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

			$_GET = $inputData["data"]["get"];

			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			    $_POST['id'] = $log_id;
			}

			$result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'loggedIn' => true, 
				'defaultUser' => 'phpunit', 
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("data", $result);
			$this->assertArrayHasKey("nwp_todo", $result['data']);
			$this->assertEquals("call_endpoint_bg", $result['data']["nwp_todo"]);
			

			echo "\n2. ";
			$dataFile = $dataFilePath . 'purgeAndRerunFailedEndpoint.json';
			$this->assertFileExists( $dataFile, 'DataFile was not found' );

			$inputData = json_decode( file_get_contents( $dataFile ), 1 );
			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

			$_GET = $inputData["data"]["get"];

			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			    $_POST['id'] = $log_id;
			}

			$result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'loggedIn' => true, 
				'defaultUser' => 'phpunit', 
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("data", $result);
			$this->assertArrayHasKey("nwp_todo", $result['data']);
			$this->assertEquals("call_endpoint_bg", $result['data']["nwp_todo"]);

			echo "\n3. ";
			$dataFile = $dataFilePath . 'purgeDataOnly.json';
			$this->assertFileExists( $dataFile, 'DataFile was not found' );

			$inputData = json_decode( file_get_contents( $dataFile ), 1 );
			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

			$_GET = $inputData["data"]["get"];

			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			    $_POST['id'] = $log_id;
			}

			$result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'loggedIn' => true, 
				'defaultUser' => 'phpunit', 
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("nwp_status", $result);
        	$this->assertEquals( "success", $result["nwp_status"]);
        	$this->assertArrayHasKey("inf", $result);
        	$this->assertStringContainsString("Data Purged Successfully", $result['inf']);
		}

		public function testMainLoggerPurge(){
			$dataFilePath = dirname( __FILE__ ) . '/data/endpoint/';
        	$dataFile = $dataFilePath . 'mainLoggerPurge.json';

  			$this->assertFileExists( $dataFile, 'DataFile was not found' );
  			$inputData = json_decode( file_get_contents( $dataFile ), 1 );

  			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
  			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

  			$_GET = [];
  			$_POST = [];

  			$_GET['default'] = 'default';
  			$_GET['action'] = 'nwp_logging';
  			$_GET['todo'] = 'execute';
  			$_GET['nwp_action'] = 'log_main_logger';
  			$_GET['nwp_todo'] = 'test_data';
  			
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

  			$this->assertArrayHasKey(0, $result);
  			$this->assertArrayHasKey('id', $result[0]);
  			$_id = $result[0]['id'];

  			$_GET = $inputData["data"]["get"];

  			if( isset( $inputData["data"]["post"] ) ){
  			    $_POST = $inputData["data"]["post"];
  			    $_POST['id'] = $_id;
  			}


  			$result = json_decode( cNwp_app_core::app( [ 
  				'pagepointer' => $pagepointer, 
  				'loggedIn' => true, 
  				'defaultUser' => 'phpunit', 
  				'returnObject' => true, 
  				'openConnection' => true, 
  				'isTesting' => true, 
  				'setTestParams' => true 
  			] ), 1);

  			 $this->assertArrayHasKey("nwp_status", $result);
        	$this->assertEquals( "success", $result["nwp_status"]);
        	$this->assertArrayHasKey("inf", $result);
        	$this->assertStringContainsString("Data Purged Successfully", $result['inf']);
		}

		public function testClearBackgroundLog(){
			$_GET = [];
			$_POST = [];

			$_GET['default'] = 'default';
			$_GET['action'] = 'nwp_endpoint';
			$_GET['todo'] = 'execute';
			$_GET['nwp_action'] = 'endpoint_log';
			$_GET['nwp_todo'] = 'bg_process';
			
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

			// print_r($result);

			// assert_options(ASSERT_CALLBACK, function( $script, $line, $message ) use ($result){
			// 	echo "Assertion failed in $script on line $line: $message";
			// 	print_r($result);
			// });
			$this->assertNotEmpty($result);
			$this->assertArrayHasKey("executed", $result);
		}

		public function testBgMailService(){
			$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';

			$_GET = array(
				'action' => 'nwp_endpoint',
				'todo' => 'execute',
				'nwp_action' => 'endpoint_log',
				'nwp_todo' => 'bg_mail_service'
			);

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

			$this->assertNotEmpty($result);
			$this->assertArrayHasKey("executed", $result);
			$this->assertEquals($result["executed"], 1);
		}
	}

?>