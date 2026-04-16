<?php
	//Logout Function
	if(isset($_GET['action']) && $_GET['action']=='signout'){
		if( ! isset( $pagepointer ) )$pagepointer = '../';
		if( file_exists( $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php" ) ){
			require_once $pagepointer . "plugins/nwp_app_core/cNwp_app_core.php";
			cNwp_app_core::startSession();
		}
		
		// require_once $pagepointer."settings/Config.php";
		require_once $pagepointer."settings/Setup.php";
	
		require_once $pagepointer . "classes/cAudit.php";
		//Auditor
		//auditor($pagepointer,'',$_SESSION['ucert']['id'],$_SESSION['ucert']['fname'].' '.$_SESSION['ucert']['lname'],$database_connection,$database,'log out','USERS','logged out');
		
		$url = '../';
		if( defined("HYELLA_DEFAULT_LOCATION") && HYELLA_DEFAULT_LOCATION ){
			$url = '../../sign-in/';
		}
		// echo $url;exit;
		
		$uname = '';
		if( isset( $_SESSION['key'] ) && $_SESSION['key'] ){
			$d = md5( 'ucert' . $_SESSION['key'] );
			if( isset( $_SESSION[ $d ]['fname'] ) )$uname = $_SESSION[ $d ]['fname'].' '.$_SESSION[ $d ]['lname'];
		}
		auditor("", "login", "signout_logout", array( "comment" => "User: " . $uname ) );
		
		$url = '../';
		if( isset( $_GET[ 'redirect_url' ] ) && $_GET[ 'redirect_url' ] ){
			$url = rawurldecode( $_GET[ 'redirect_url' ] );
		}else if( isset( $_GET[ 'r' ] ) && $_GET[ 'r' ] ){
			$url = rawurldecode( $_GET[ 'r' ] );
		}else if( defined( 'NWP_SIGNOUT_REDIRECT_URL' ) && NWP_SIGNOUT_REDIRECT_URL ){
			$url = NWP_SIGNOUT_REDIRECT_URL;
		}
		
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
		//$_SESSION["out"] = 1;
		//echo print_r( $_SESSION ); exit;
		//echo $_SESSION["out"]; exit;
		
		header('location: '.$url);
		exit;
	}
?>