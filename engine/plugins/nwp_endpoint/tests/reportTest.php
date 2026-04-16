<?php
	use \PHPUnit\Framework\TestCase;


	$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';
	if( ! class_exists("cNwp_app_core") ){
	    require_once $pagepointer . "/plugins/nwp_app_core/cNwp_app_core.php";
	}

	class reportTest extends TestCase{


		public function testLoadReport(){
			$dataFilePath = dirname( __FILE__ ) . '/data/reports/';
        	$dataFile = $dataFilePath . 'loadReport.json';
        	
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

        	$this->assertEqualsCanonicalizing($result, json_decode( $inputData['output'], true ));
		}

		public function testFilterReport(){
			$dataFilePath = dirname( __FILE__ ) . '/data/reports/';
        	$dataFile = $dataFilePath . 'applyReportFilter.json';
        	
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
        	$this->assertArrayHasKey("data", $result);
        	$this->assertEqualsCanonicalizing($result['data'], $inputData['output']['data']);
		}

		public function testExportReportToCSV(){
			$dataFilePath = dirname( __FILE__ ) . '/data/reports/';
        	$dataFile = $dataFilePath . 'exportToCSV.json';
        	
        	$this->assertFileExists( $dataFile, 'DataFile was not found' );
        	$inputData = json_decode( file_get_contents( $dataFile ), 1 );

        	$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
        	$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

        	$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

        	$_GET = $inputData["data"]["get"];

        	if( isset( $inputData["data"]["post"] ) ){
        	    $_POST = $inputData["data"]["post"];
        	}

        	$output = json_decode( $inputData['output'] );

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
        	$this->assertStringContainsString("<h4>Successful Data Export</h4><a class='btn btn-success'", $result['inf']);
		}
	}
?>