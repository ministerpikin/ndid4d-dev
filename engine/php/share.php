<?php 
/*
use Office365\SharePoint\ClientContext;
use Office365\Runtime\Auth\NetworkCredentialContext;
use Office365\PHP\Client\SharePoint\FileCreationInformation;
require_once __DIR__."/../vendor/vgrem-phpso/autoload.php";
//require_once './init.php';

$authCtx = new NetworkCredentialContext( 'fea', '#Y4u.7k*rm2J' );
$authCtx->AuthType = CURLAUTH_NTLM;
$ctx = new ClientContext( 'https://elibrary.nape.org.ng' ,$authCtx);

$targetFolderUrl = "/sites/elib/Shared Documents/NAPE EXECUTIVE COMMITTEE";
$file_to_download = "NAPE Fellows.pdf";
$files = $ctx->getWeb()->getFolderByServerRelativeUrl($targetFolderUrl)->getFiles();
$ctx->load($files);
$ctx->executeQuery();

foreach ($files as $file) {
	if ($file_to_download == $file->getName()){
		try {
			$fileName = join(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), $file->getName()]);
			$fh = fopen($fileName, 'w+');
			$file->download($fh);
			$ctx->executeQuery();
			fclose($fh);
			print "File {$fileName} has been downloaded successfully\r\n";
		
		} catch (Exception $e) {
			print "File download failed:\r\n";
		}
	}else{
		//print "File not found:\r\n";
	}
}
exit;
*/

include "..\classes\sharepoint\api\SharePointAPI.php"; date_default_timezone_set('Africa/Lagos'); //phpinfo(); 
use Thybag\SharePointAPI;

use Office365\SharePoint\ClientContext;
use Office365\Runtime\Auth\NetworkCredentialContext;
use Office365\Runtime\Auth\FileCreationInformation;


// $sp = new SharePointAPI('fea', '#Y4u.7k*rm2J', 'https://elibrary.nape.org.ng/sites/elib/_vti_bin/Lists.asmx?WSDL', 'NTLM');
// $a = $sp->read( 'NAPE Documents', NULL, NULL, NULL, NULL, NULL );
// $a = $sp->read( 'NAPE Documents', NULL, NULL, NULL, NULL, '<Folder>Shared Documents/NAPE NEWS</Folder>' );
// $a = $sp->read( 'NAPE Documents', 5, NULL, NULL, NULL, '<ViewAttributes Scope="Recursive"/>' ); // pulls all documents at once

include 'global_variables.php';
// echo '<pre>';
// print_r($_GET);exit;

$error = "";

$sharepoint_type = isset( $_GET[ 'sharepoint_type' ] ) ? $_GET[ 'sharepoint_type' ] : '';
$operation = isset( $_GET[ 'operation' ] ) ? $_GET[ 'operation' ] : '';
$username = isset( $_GET[ 'username' ] ) ? $_GET[ 'username' ] : '';
$password = isset( $_GET[ 'password' ] ) ? $_GET[ 'password' ] : '';
$url = isset( $_GET[ 'url' ] ) ? $_GET[ 'url' ] : '';
$parent_url = isset( $_GET[ 'parent_url' ] ) ? $_GET[ 'parent_url' ] : '';
$escape_string = isset( $_GET[ 'escape_string' ] ) ? $_GET[ 'escape_string' ] : '';
$target_path = isset( $_GET[ 'target_path' ] ) ? $_GET[ 'target_path' ] : '';

foreach( array( 'username', 'password', 'url', 'operation' ) as $test_var ){
	if( ! ( isset( $$test_var ) && $$test_var ) ){
		$error = "The parameter '". $test_var ."' is missing. This is a required parameter";
	}
}

$params1 = isset( $_GET[ 'params1' ] ) && $_GET[ 'params1' ] ? urldecode( str_replace( $escape_string, '%', $_GET[ 'params1' ] ) ) : NULL;
$params2 = isset( $_GET[ 'params2' ] ) && $_GET[ 'params2' ] ? urldecode( str_replace( $escape_string, '%', $_GET[ 'params2' ] ) ) : NULL;
$params3 = isset( $_GET[ 'params3' ] ) && $_GET[ 'params3' ] ? urldecode( str_replace( $escape_string, '%', $_GET[ 'params3' ] ) ) : NULL;
$params4 = isset( $_GET[ 'params4' ] ) && $_GET[ 'params4' ] ? urldecode( str_replace( $escape_string, '%', $_GET[ 'params4' ] ) ) : NULL;
$params5 = isset( $_GET[ 'params5' ] ) && $_GET[ 'params5' ] ? urldecode( str_replace( $escape_string, '%', $_GET[ 'params5' ] ) ) : NULL;
$params6 = isset( $_GET[ 'params6' ] ) && $_GET[ 'params6' ] ? urldecode( str_replace( $escape_string, '%', $_GET[ 'params6' ] ) ) : NULL;
$parent_url = $parent_url ? urldecode( str_replace( $escape_string, '%', $parent_url ) ) : NULL;
$url = $url ? urldecode( str_replace( $escape_string, '%', $url ) ) : NULL;

$sp = new SharePointAPI( $username, $password, $url, 'NTLM'); 

$returned_sharepoint_data = array();

if( isset( $_GET[ 'mike' ] ) ){
	// print_r($target_path);
	// print_r($params1);echo "\n\n";
	// print_r($params6);echo "\n\n";
	// print_r($url);echo "\n\n";
	// print_r($parent_url);echo "\n\n";
	// print_r($target_path);echo "\n\n";
}
if( ! $error ){
	switch( $operation ){
	case 'read':

		switch( $sharepoint_type ){
		case 'get_documents':
		    require_once __DIR__."/../vendor/vgrem-phpso/autoload.php";
	        $pass = 1;
	        $file_to_download = json_decode( $params1, true );
	        // if( ! empty( $file_to_download ) ){
	        // 	$pass = 0;
	        // }
	        $sfilename = $params2;
	        $json = array(
        		'nape_bot.pdf' => 'files/files/732.pdf',
	        );

		    $authCtx = new NetworkCredentialContext($username, $password);
		    $authCtx->AuthType = CURLAUTH_NTLM;
		    $ctx = new ClientContext($parent_url,$authCtx);
			
			if( ! file_exists( $target_path ) ){
				mkdir( $target_path, 0777, true );
			}

		    $files = $ctx->getWeb()->getFolderByServerRelativeUrl($url)->getFiles();
		    $ctx->load($files);
		    $ctx->executeQuery();

			// print_r($target_path);exit;
		    // $json[ $file->getName() ]
			if( ! empty( $files ) ){
			    foreach ($files as $file) {
			    	$rfilename = $file->getName();
			        if( isset( $file_to_download[ $rfilename ] ) && $file_to_download[ $rfilename ] ){
			        // if( $pass || ( $file_to_download && $file_to_download == $rfilename ) ){
			            try {
			                $fileName = $target_path . $file_to_download[ $rfilename ];
			                // $fileName = join(DIRECTORY_SEPARATOR, [$target_path, $file_to_download[ $rfilename ]]);
			                // $fileName = join(DIRECTORY_SEPARATOR, [$target_path, $sfilename]);
			                // $fileName = join(DIRECTORY_SEPARATOR, [dirname( __FILE__ ), $file->getName()]);
			                $fh = fopen($fileName, 'w+');
			                $file->download($fh);
			                $ctx->executeQuery();
			                fclose($fh);
			                // print "File {$fileName} has been downloaded successfully\r\n";
			            	// if( $file_to_download && $file_to_download == $rfilename )break;
			            } catch (Exception $e) {
			                // print "File download failed:\r\n";
			            }
			        }else{
			            // print "File not found:\r\n";
			        }
			    }
			}
		break;
		default:
			$returned_sharepoint_data = $sp->read( $params1, $params2, $params3, $params4, $params5, $params6 );
		break;
		}
	break;
	default:
		$error = "Undefined Variable Type";
	break;
	}
}

// echo '<pre>';
// print_r($returned_sharepoint_data);exit;

if( $error ){
	echo $error;
}else{
	$file_name = time().rand(0,10).rand(0,100);
	$path = dirname( dirname( __FILE__ ) ).'\files\sharepoint\\'.$file_name.'.json';
	file_put_contents( $path, json_encode( $returned_sharepoint_data ) );
	$_POST['json_path'] = $path;
	$_POST['filename'] = $file_name;

	include 'ajax_request_processing_script.php';
}

// print_r($_GET);

//https://docs.microsoft.com/en-us/sharepoint/dev/sp-add-ins/upload-a-file-by-using-the-rest-api-and-jquery
?>