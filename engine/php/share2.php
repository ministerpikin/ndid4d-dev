<?php

use Office365\SharePoint\ClientContext;
use Office365\Runtime\Auth\NetworkCredentialContext;
use Office365\PHP\Client\SharePoint\FileCreationInformation;
require_once __DIR__."/../vendor/vgrem-phpso/autoload.php";
//require_once './init.php';


$authCtx = new NetworkCredentialContext( 'fea', '#Y4u.7k*rm2J' );
$authCtx->AuthType = CURLAUTH_NTLM;
$ctx = new ClientContext( 'https://elibrary.nape.org.ng', $authCtx );

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
		print "File not found:\r\n";
	}
}
// echo 'mike';
exit;

?>