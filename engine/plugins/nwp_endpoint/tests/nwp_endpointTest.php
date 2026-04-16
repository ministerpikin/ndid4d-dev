<?php

$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';
if( ! class_exists("cNwp_app_core") ){
    require_once $pagepointer . "/plugins/nwp_app_core/cNwp_app_core.php";
}

class nwp_endpointTest extends \PHPUnit\Framework\TestCase{
    
    public function testdoNotLoadCSVWithUmatchedFields() {
        $dataFilePath = dirname( __FILE__ );
        $dataFile = $dataFilePath . '/data/doNotLoadCSVWithUmatchedFields.json';
        
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
        $result = json_decode( cNwp_app_core::app( [ 'pagepointer' => $pagepointer, 'loggedIn' => true, 'defaultUser' => 'phpunit', 'returnObject' => true, 'openConnection' => true, 'isTesting' => true ] ), 1);

        $output = json_decode( $inputData["output"], 1 );
        unset( $output["development"] );
        unset( $output["nwp_status"] );
        unset( $output["audit_params"] );
        unset( $output["tok"] );

        unset( $result["development"] );
        unset( $result["nwp_status"] );
        unset( $result["audit_params"] );
        unset( $result["tok"] );
        
        // print_r( $output ); exit;
        //print_r( $result ); exit;

        $this->assertEquals($result, $output);
    }

    public function testLoadCSVWithoutSaving() {
        $dataFilePath = dirname( __FILE__ );
        $dataFile = $dataFilePath . '/data/LoadCSVWithoutSaving.json';
        
        $this->assertFileExists( $dataFile, 'DataFile was not found' );
        $inputData = json_decode( file_get_contents( $dataFile ), 1 );
        
        $this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
        $this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

        $this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );
        $_GET = $inputData["data"]["get"];

        if( isset( $inputData["data"]["post"] ) ){
            $_POST = $inputData["data"]["post"];
        }

        //get background process ref: when form is submitted
        $pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';
        $result = json_decode( cNwp_app_core::app( [ 'pagepointer' => $pagepointer, 'loggedIn' => true, 'defaultUser' => 'phpunit', 'returnObject' => true, 'openConnection' => true, 'isTesting' => true, 'setTestParams' => true ] ), 1);

        $this->assertArrayHasKey( "id", $result, 'Background Process Cache Ref was not received' );

        //trigger background process
        $_GET['nwp_todo'] = 'save_data_to_table_bg';
        $_POST = [ 'id' => $result['id'] ];
        $result = json_decode( cNwp_app_core::app( [ 'pagepointer' => $pagepointer, 'loggedIn' => true, 'defaultUser' => 'phpunit', 'returnObject' => true, 'openConnection' => true, 'isTesting' => true, 'setTestParams' => true ] ), 1);

        $this->assertArrayHasKey( "nwp_status", $result, 'Failed to Return Status in Error Message' );
        
        // print_r( $result ); exit;
        $this->assertEquals( "success", $result["nwp_status"]);

    }
    
    public function testLoadCSVBatchesWithoutSaving() {
        $dataFilePath = dirname( __FILE__ );
        $dataFile = $dataFilePath . '/data/LoadCSVBatchesWithoutSaving.json';
        
        $this->assertFileExists( $dataFile, 'DataFile was not found' );
        $inputData = json_decode( file_get_contents( $dataFile ), 1 );
        
        $this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
        $this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );

        $this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );
        $_GET = $inputData["data"]["get"];

        if( isset( $inputData["data"]["post"] ) ){
            $_POST = $inputData["data"]["post"];
        }

        //get background process ref: when form is submitted
        $pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';
        $result = json_decode( cNwp_app_core::app( [ 'pagepointer' => $pagepointer, 'loggedIn' => true, 'defaultUser' => 'phpunit', 'returnObject' => true, 'openConnection' => true, 'isTesting' => true, 'setTestParams' => true ] ), 1);

        $this->assertArrayHasKey( "id", $result, 'Background Process Cache Ref was not received' );

        //trigger background process
        $_GET['nwp_todo'] = 'save_data_to_table_bg';
        $_POST = [ 'id' => $result['id'] ];
        $result = json_decode( cNwp_app_core::app( [ 'pagepointer' => $pagepointer, 'loggedIn' => true, 'defaultUser' => 'phpunit', 'returnObject' => true, 'openConnection' => true, 'isTesting' => true, 'setTestParams' => true ] ), 1);

        $this->assertArrayHasKey( "nwp_status", $result, 'Failed to Return Status in Error Message' );
        $this->assertEquals( "success", $result["nwp_status"]);

    }
    
    public function testLoadingFileUpload() {
        $result = 1 + 2;
        $this->assertEquals( $result, 3);
    }
    
}