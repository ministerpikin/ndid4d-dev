<?php

	use \PHPUnit\Framework\TestCase;
	$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';

	if( ! class_exists("cNwp_app_core") ){
	    require_once $pagepointer . "/plugins/nwp_app_core/cNwp_app_core.php";
	}

	class userAuthTest extends TestCase{
		
		public function testLoginWithOTP(){

			$dataFilePath = dirname( __FILE__ ) . '/data/authentication/';
			$dataFile = $dataFilePath . 'loginOTP.json';
			
			$this->assertFileExists( $dataFile, 'Login DataFile was not found' );
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
				'defaultUser' => 'phpunit', 
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("html_replacement", $result, " == No response Body === ");
			$this->assertStringContainsString("Please check your email to input otp below", $result['html_replacement']);
			
			$_GET = array(
				'action' => 'users',
				'todo'=> 'test_get_otp'
			);
			
			$result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'defaultUser' => 'phpunit', 
				'loggedIn' => true,
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("otp", $result);
			$this->assertIsArray($result['otp']);
			$this->assertArrayHasKey("value", $result["otp"]);
			$this->assertArrayHasKey("status", $result["otp"]);
			$this->assertEquals("unused", $result["otp"]["status"]);

			$dataFile = $dataFilePath . 'verifyOTP.json';
			
			$this->assertFileExists( $dataFile, 'OTP Verification DataFile was not found' );
			$inputData = json_decode( file_get_contents( $dataFile ), 1 );
			
			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );
			
			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

			$_GET = $inputData["data"]["get"];

			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			}

			$_POST['otp'] = $result["otp"]["value"];

			$result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'defaultUser' => 'phpunit', 
				// 'loggedIn' => true,
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("status", $result);
			$this->assertEquals("new-status", $result["status"]);
			$this->assertArrayHasKey("redirect_url", $result);
			$this->assertEquals("main.php", $result["redirect_url"]);
			$this->_destroy_session();
		}

		/**
	    * @runInSeparateProcess
	    */
		public function testGlobalSettings(){
			//tesing singly passess
			//Testing as a whole fails
			$_SESSION = [];

			$dataFilePath = dirname( __FILE__ ) . '/data/authentication/';
			$dataFile = $dataFilePath . 'loginOTP.json';
			
			$this->assertFileExists( $dataFile, 'Login DataFile was not found' );
			$inputData = json_decode( file_get_contents( $dataFile ), 1 );
			
			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );
			
			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

			$_GET = $inputData["data"]["get"];

			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			}

			$_vars = [ 
				'OTP_ON_SIGNIN' => 0,			
				// '' => 1
			];

			$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';
			
			foreach ($_vars as $var => $value) {
				$GLOBALS["env"] = [];

				$_GET = $inputData["data"]["get"];

				if( isset( $inputData["data"]["post"] ) ){
				    $_POST = $inputData["data"]["post"];
				}

				echo "\nTesting '". $var ."': ". $value ." \n";
				echo "-----------------------------------------------";
				$GLOBALS["env"][ $var ] = $value;

				$result = json_decode( cNwp_app_core::app([
					'pagepointer' => $pagepointer, 
					'defaultUser' => 'phpunit', 
					'returnObject' => true,
					'openConnection' => true, 
					'isTesting' => true, 
					'setTestParams' => true 
				] ), 1);

				$this->assertArrayHasKey("status", $result);
				$this->assertEquals("new-status", $result["status"]);
				$this->assertArrayHasKey("redirect_url", $result);
				$this->assertEquals("main.php", $result["redirect_url"]);
			}

			$this->_destroy_session();
		}


		/**
	    * @runInSeparateProcess
	    */
		public function testOtpFingerprint(){
			
			$dataFilePath = dirname( __FILE__ ) . '/data/authentication/';
			$dataFile = $dataFilePath . 'loginOTP.json';
			
			$this->assertFileExists( $dataFile, 'Login DataFile was not found' );
			$inputData = json_decode( file_get_contents( $dataFile ), 1 );
			
			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );
			
			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

			$_GET = $inputData["data"]["get"];

			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			}

			$fparams = json_decode( $_POST['formParams'], true );
			unset( $fparams['d1'] );
			unset( $fparams['d2'] );
			$_POST['formParams'] = json_encode( $fparams );

			$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';

			$result = json_decode( cNwp_app_core::app([
				'pagepointer' => $pagepointer, 
				'defaultUser' => 'phpunit', 
				'returnObject' => true,
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("typ", $result);
			$this->assertArrayHasKey("inf", $result);
			$this->assertEquals("<h4><strong>Missing Authentication Parameter</strong></h4>Please contact your system administrator", $result['inf']);
			$this->_destroy_session();
		}



		/**
	    * @runInSeparateProcess
	    */
		public function testOtpExpiry(){
			
			$var = 'OTP_EXPIRATION_VALIDITY';
			$GLOBALS["env"][ $var  ] = 1.5; // 1min
			$value = $GLOBALS['env']['OTP_EXPIRATION_VALIDITY'];

			//LOGIN 
			$dataFilePath = dirname( __FILE__ ) . '/data/authentication/';
			$dataFile = $dataFilePath . 'loginOTP.json';
			
			$this->assertFileExists( $dataFile, 'Login DataFile was not found' );
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
				'defaultUser' => 'phpunit', 
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("html_replacement", $result);
			$this->assertStringContainsString("Please check your email to input otp below", $result['html_replacement']);
			$this->_otp_validity_handler( $input, $result, $var, $value);
		}


		/**
	    * @runInSeparateProcess
	    */
		public function testOtpValidityOnSignIn(){

			$GLOBALS["env"][ "OTP_ON_SIGNIN_VALIDITY" ] = 0.016666666667; // 1min
			$value = $GLOBALS['env']['OTP_ON_SIGNIN_VALIDITY'];

			//LOGIN 
			$dataFilePath = dirname( __FILE__ ) . '/data/authentication/';
			$dataFile = $dataFilePath . 'loginOTP.json';
			
			$this->assertFileExists( $dataFile, 'Login DataFile was not found' );
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
				'defaultUser' => 'phpunit', 
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("html_replacement", $result, " == No response Body === ");
			$this->assertStringContainsString("Please check your email to input otp below", $result['html_replacement']);
			
			$_GET = array(
				'action' => 'users',
				'todo'=> 'test_get_otp'
			);
			
			$result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'defaultUser' => 'phpunit', 
				'loggedIn' => true,
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("otp", $result);
			$this->assertIsArray($result['otp']);
			$this->assertArrayHasKey("value", $result["otp"]);
			$this->assertArrayHasKey("status", $result["otp"]);
			$this->assertEquals("unused", $result["otp"]["status"]);

			//VERIFY OTP
			$dataFile = $dataFilePath . 'verifyOTP.json';
			
			$this->assertFileExists( $dataFile, 'OTP Verification DataFile was not found' );
			$inputData = json_decode( file_get_contents( $dataFile ), 1 );
			
			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );
			
			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

			$_GET = $inputData["data"]["get"];

			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			}

			$_POST['otp'] = $result["otp"]["value"];

			$result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'defaultUser' => 'phpunit', 
				// 'loggedIn' => true,
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("status", $result);
			$this->assertEquals("new-status", $result["status"]);
			$this->assertArrayHasKey("redirect_url", $result);
			$this->assertEquals("main.php", $result["redirect_url"]);

			//SIGNOUT
			$this->_destroy_session();

			//TRY LOGGING
			$sleep_time = $value * 3600;
			echo "\n Waiting On OTP SIGNIN Time Expiry: ". ($sleep_time) ."s\n ";
			for($x = 1; $x <= $sleep_time; $x++){
			    $this->show_status( $x, $sleep_time );
			    sleep(1);
			}
			echo "\nAdditional Time Wasting: 10s\n";
			sleep(10);

			$dataFilePath = dirname( __FILE__ ) . '/data/authentication/';
			$dataFile = $dataFilePath . 'loginOTP.json';
			
			$this->assertFileExists( $dataFile, 'Login DataFile was not found' );
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
				'defaultUser' => 'phpunit', 
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("html_replacement", $result, " == No response Body === ");
			$this->assertStringContainsString("Please check your email to input otp below", $result['html_replacement']);
			$this->_destroy_session();
		}

		public function show_status($done, $total, $size=30) {
 
		    static $start_time;
		 
		    if($done > $total) return;
		 
		    if(empty($start_time)) $start_time=time();
		    $now = time();
		 
		    $perc=(double)($done/$total);
		 
		    $bar=floor($perc*$size);
		 
		    $status_bar="\r[";
		    $status_bar.=str_repeat("=", $bar);
		    if($bar<$size){
		        $status_bar.=">";
		        $status_bar.=str_repeat(" ", $size-$bar);
		    } else {
		        $status_bar.="=";
		    }
		 
		    $disp=number_format($perc*100, 0);
		 
		    $status_bar.="] $disp%  $done/$total";
		 
		    $rate = ($now-$start_time)/$done;
		    $left = $total - $done;
		    $eta = round($rate * $left, 2);
		 
		    $elapsed = $now - $start_time;
		 
		    $status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";
		 
		    echo "$status_bar  ";
		    flush();
		 
		    if($done == $total) {
		        echo "\n";
		    }
		}

		public function _otp_validity_handler($input, $result, $var, $value ){

			$pagepointer = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ). '/';
			$dataFilePath = dirname( __FILE__ ) . '/data/authentication/';

			$_GET = $inputData["data"]["get"];
			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			}

			$_GET = array(
				'action' => 'users',
				'todo'=> 'test_get_otp'
			);
			
			$otp_result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'defaultUser' => 'phpunit', 
				'loggedIn' => true,
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("otp", $otp_result);
			$this->assertIsArray($otp_result['otp']);
			$this->assertArrayHasKey("value", $otp_result["otp"]);
			$this->assertArrayHasKey("status", $otp_result["otp"]);
			$this->assertEquals("unused", $otp_result["otp"]["status"]);

			$sleep_time = $value * 60;
			
			echo "\n Waiting On Time Expiry: ". ($sleep_time) ."s ";
			for($x = 1; $x <= $sleep_time; $x++){
			    $this->show_status($x, $sleep_time);
			    sleep(1);
			}
			echo "\nOTP: Expiry Concluded";
			echo "\nAdditional Time Wasting: 30s\n";
			sleep(30);

			$dataFile = $dataFilePath . 'verifyOTP.json';
			
			$this->assertFileExists( $dataFile, 'OTP Verification DataFile was not found' );
			$inputData = json_decode( file_get_contents( $dataFile ), 1 );
			
			$this->assertArrayHasKey( "data", $inputData, 'Missing Data in DataFile' );
			$this->assertArrayHasKey( "output", $inputData, 'Missing Output in DataFile' );
			
			$this->assertArrayHasKey( "get", $inputData["data"], 'Missing HTTP GET Parameters' );

			$_GET = $inputData["data"]["get"];

			if( isset( $inputData["data"]["post"] ) ){
			    $_POST = $inputData["data"]["post"];
			}

			$_POST['otp'] = $otp_result["otp"]["value"];

			$result = json_decode( cNwp_app_core::app( [ 
				'pagepointer' => $pagepointer, 
				'defaultUser' => 'phpunit', 
				// 'loggedIn' => true,
				'returnObject' => true, 
				'openConnection' => true, 
				'isTesting' => true, 
				'setTestParams' => true 
			] ), 1);

			$this->assertArrayHasKey("typ", $result);
			$this->assertArrayHasKey("inf", $result);
			$this->assertEquals("<h4><strong>OTP Expired</strong></h4>Please resend a new otp", $result['inf']);

			$this->_destroy_session();
		}


		public function _destroy_session(){
			$_GET = array( 'action' => 'signout' );
			$_SESSION = array();
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			error_reporting (~E_ALL);
			session_destroy();	
		}
	}
	
?>