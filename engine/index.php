<?php
	// echo 'No-go area!';
	exit;
	//CONFIGURATION
	/* echo '2echo';
	exit; */
	$pagepointer = './';
	require_once $pagepointer."settings/Config.php";
	
	$_SESSION["admin_page"] = 1;
	$_SESSION["admin_try"] = 1;
	require_once $pagepointer."settings/Setup.php";
	
	//check for update
	if( file_exists( "update/update.php" ) ){
		if( ! isset( $_SESSION["update_in_progress"] ) )
			$_SESSION["update"] = 1;
	}
	if( isset( $_GET["reset"] ) )
		unset( $_SESSION["update_in_progress"] );
	
	$page_id = "myschool-admin";
?>
<!--START PAGE HEAD-->
<?php
	require_once $pagepointer."html-files/html-head-tag.php"; 
	
	if( isset( $_GET["activity"] ) && $_GET["activity"] ){
		switch( $_GET["activity"] ){
		case "update":
			require_once $pagepointer."html-files/update.php";
		break;
		}
	}else{
		require_once $pagepointer."html-files/templates-1/main-admin.php";
		
		require_once $pagepointer."html-files/html-jquery-files.php";
	}
?>
<!--END PAGE HEAD-->
</html>